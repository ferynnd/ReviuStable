<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;
use App\Models\Tag;
use App\Models\Comment;
use App\Models\Revision;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function index(Request $request)
    {
        // 1 = feed, 2 = carousel, 3 = story, 4 = reel
        $postType = [
            "feed" => "Feed",
            "carousel" => "Carousel",
            "story" => "Story",
            "reel" => "Reel",
        ];

        $status = [
            "draft" => "Draft",
            "published" => "Published",
            "revision" => "Revision",
        ];

        $type = $request->query("type", "feed");

        $tags = Tag::latest()->get();

        return view("post.page", compact("postType", "status", "type", "tags"));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "title" => "required|string|max:150",
            "caption" => "nullable|string",
            "content_type" => "required|integer|in:1,2,3,4", // 1 = feed, 2 = carousel, 3 = story, 4 = reel
            "status" => "required|in:draft,published,revision",
            "hashtag" => "nullable",
            "post_at" => "nullable|date",
        ]);

        try {
            if (in_array($validated["content_type"], [1, 4])) {
                $request->validate([
                    "media" => "required|file|mimes:jpg,jpeg,png,mp4|max:20480",
                ]);
                $files = [$request->file("media")];
            } else {
                $request->validate([
                    "media" => "required|array",
                    "media.*" => "file|mimes:jpg,jpeg,png,mp4|max:20480",
                ]);
                $files = $request->file("media");
            }
        } catch (\Throwable $e) {
            \Log::error("💥 Error di blok upload: " . $e->getMessage(), [
                "trace" => $e->getTraceAsString(),
            ]);
        }

        $hashtags = $request->input("hashtag", []);

        // Generate unique slug
        $slug = Str::slug($validated["title"]);
        $originalSlug = $slug;
        $counter = 1;

        while (Post::where("slug", $slug)->exists()) {
            $slug = $originalSlug . "-" . $counter;
            $counter++;
        }

        $post = Post::create([
            "user_id" => auth()->id(),
            "title" => $validated["title"],
            "caption" => $validated["caption"] ?? "",
            "content_type" => $validated["content_type"],
            "status" => $validated["status"],
            "slug" => $slug,
            "hashtag" => $hashtags,
            "post_at" => $validated["post_at"] ?? now(),
        ]);

        $post->attachMedia($request);

        return redirect()->route("home")->with("success", "Post berhasil 😊");
    }

    public function edit(Request $request, $slug)
    {
        $post = Post::with("user", "media")
            ->where("slug", $slug)
            ->firstOrFail();

        // Authorization check
        if (
            $post->user_id !== auth()->id() &&
            !auth()
                ->user()
                ->hasRole(["staff", "superadmin"])
        ) {
            abort(403, "Unauthorized action.");
        }

        // Mapping untuk dropdown
        $postType = [
            "feed" => "Feed",
            "carousel" => "Carousel",
            "story" => "Story",
            "reel" => "Reel",
        ];

        $status = [
            "draft" => "Draft",
            "published" => "Published",
            "revision" => "Revision",
        ];

        // Reverse mapping content_type → key
        $typeMap = [
            1 => "feed",
            2 => "carousel",
            3 => "story",
            4 => "reel",
        ];

        $type = $typeMap[$post->content_type] ?? "feed";

        $tags = Tag::latest()->get();

        return view(
            "post.edit",
            compact("postType", "status", "type", "post", "tags"),
        );
    }

    public function update(Request $request, $slug)
    {
        $post = Post::where("slug", $slug)->firstOrFail();

        if (
            $post->user_id !== auth()->id() &&
            !auth()
                ->user()
                ->hasRole(["staff", "superadmin"])
        ) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Unauthorized action.",
                ],
                403,
            );
        }

        // Validasi data utama
        $validated = $request->validate([
            "title" => "required|string|max:150",
            "caption" => "nullable|string",
            "status" => "required|in:draft,published,revision",
            "hashtag" => "nullable",
            "post_at" => "nullable|date",
        ]);

        try {
            // Deteksi file upload baru
            if (in_array($post->content_type, [1, 4])) {
                // Feed/Reel → 1 file
                $files = $request->hasFile("media")
                    ? [$request->file("media")]
                    : [];
            } else {
                // Carousel/Story → multiple
                $files = $request->hasFile("media")
                    ? $request->file("media")
                    : [];
            }

            // Slug generator
            if ($post->title !== $validated["title"]) {
                $newSlug = Str::slug($validated["title"]);
                $originalSlug = $newSlug;
                $counter = 1;

                while (
                    Post::where("slug", $newSlug)
                        ->where("id", "!=", $post->id)
                        ->exists()
                ) {
                    $newSlug = $originalSlug . "-" . $counter;
                    $counter++;
                }

                $validated["slug"] = $newSlug;
            }

            $hashtags = array_unique($request->input("hashtag", []));
            $validated["hashtag"] = array_values($hashtags);
            $validated["content_type"] = $post->content_type;
            $post->update($validated);

            $oldPreviews = $request->input("old_previews", []);
            $mediaCollection = $post->getMediaCollectionName(
                $post->content_type,
            );

            $existingMedia = $post->getMedia($mediaCollection);
            if (!empty($oldPreviews)) {
                foreach ($existingMedia as $media) {
                    if (!in_array($media->getUrl(), $oldPreviews)) {
                        $media->delete();
                    }
                }
            }

            foreach ($files as $file) {
                $post->addMedia($file)->toMediaCollection($mediaCollection);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    "success" => true,
                    "message" => "Post berhasil diperbarui!",
                    "data" => $post->load("media", "user"),
                ]);
            }

            return redirect()
                ->route("post.detail", $post->slug)
                ->with("success", "Post berhasil diperbarui! 🎉");
        } catch (\Throwable $e) {
            \Log::error("💥 Update Post Error: " . $e->getMessage(), [
                "trace" => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson()) {
                return response()->json(
                    [
                        "success" => false,
                        "message" =>
                            "Gagal memperbarui post: " . $e->getMessage(),
                    ],
                    500,
                );
            }

            return back()
                ->withInput()
                ->with("error", "Gagal memperbarui post!");
        }
    }

    public function detail($slug)
    {
        $post = Post::with("user", "media")
            ->where("slug", $slug)
            ->firstOrFail();

        $typeMap = [
            1 => "feed",
            2 => "carousel",
            3 => "story",
            4 => "reel",
        ];

        $type = $typeMap[$post->content_type] ?? "feed";

        switch ($type) {
            case "feed":
            case "carousel":
                $aspect_class = "aspect-[4/5]";
                $badge_color = "bg-blue-500";
                break;

            case "story":
                $aspect_class = "aspect-[9/16]";
                $badge_color = "bg-pink-500";
                break;

            case "reel":
            default:
                $aspect_class = "aspect-[9/16]";
                $badge_color = "bg-purple-500";
                break;
        }

        if (in_array($type, ["carousel", "story"])) {
            // Jika carousel atau story, ambil semua file
            $media_urls = $post->getMedia($type)->map(fn($m) => $m->getUrl());
        } else {
            $media_urls = collect([$post->getFirstMediaUrl($type)]);
        }

        $username = $post->user->name ?? "Pengguna Tidak Dikenal";

        $tags = Tag::latest()->get();

        return view(
            "post.detail",
            compact(
                "post",
                "type",
                "aspect_class",
                "badge_color",
                "media_urls",
                "username",
                "tags",
            ),
        );
    }

    public function initialStatus($slug)
    {
        $post = Post::where("slug", $slug)->first();

        if (!$post) {
            return response()->json(["error" => "Post not found"], 404);
        }

        $user = Auth::user();
        $isLiked = false;

        if ($user) {
            $isLiked = $post->likes()->where("user_id", $user->id)->exists();
        }

        $likeCount = $post->likes()->count();
        $commentsCount = $post->comments()->count();
        $likes = $post
            ->likes()
            ->with("user:id,username,fullname,image")
            ->latest()
            ->get();

        return response()->json([
            "liked" => $isLiked,
            "likes" => $likes,
            "like_count" => $likeCount,
            "comment_count" => $commentsCount,
        ]);
    }

    public function like($slug)
    {
        $post = Post::where("slug", $slug)->first();

        if (!$post) {
            return response()->json(["error" => "Post not found"], 404);
        }

        $user = Auth::user();

        if (!$user) {
            return response()->json(["message" => "Unauthenticated."], 401);
        }

        // Toggle like
        $existing = $post->likes()->where("user_id", $user->id)->first();
        $liked = false;

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            $post->likes()->create([
                "user_id" => $user->id,
                "post_id" => $post->id,
                "like_at" => now(),
            ]);
            $liked = true;
        }

        // Mengembalikan hitungan yang diperbarui
        $likeCount = $post->likes()->count();

        return response()->json([
            "liked" => $liked,
            "like_count" => $likeCount,
        ]);
    }

    /**
     * Menyimpan komentar baru
     */
    public function comment(Request $request, $slug)
    {
        $post = Post::where("slug", $slug)->first();

        if (!$post) {
            return response()->json(["error" => "Post not found"], 404);
        }

        $request->validate(["comment" => "required|string"]);
        $user = Auth::user();

        if (!$user) {
            return response()->json(["message" => "Unauthenticated."], 401);
        }

        $comment = $post->comments()->create([
            "user_id" => $user->id,
            "comment" => $request->comment,
        ]);

        $comment->load("user");

        return response()->json($comment);
    }

    /**
     * Memuat semua komentar
     */
    public function comments($slug)
    {
        $post = Post::where("slug", $slug)->first();

        if (!$post) {
            return response()->json(["error" => "Post not found"], 404);
        }

        $comments = $post
            ->comments()
            ->with("user:id,username,fullname")
            ->latest()
            ->get();

        return response()->json($comments);
    }

    public function share(Request $request, $slug)
    {
        // Simple version tanpa try-catch
        $post = Post::where("slug", $slug)->first();

        if (!$post) {
            return response()->json(["error" => "Post not found"], 404);
        }

        return response()->json([
            "success" => true,
            "share_url" => url("/post/detail/{$post->slug}"),
            "message" => "Link berhasil dibuat",
        ]);
    }

    /**
     * Menghapus postingan secara permanen
     */
    public function destroy(Request $request, $slug)
    {
        try {
            $post = Post::where("slug", $slug)->firstOrFail();

            // Authorization check
            if (
                $post->user_id !== auth()->id() &&
                !auth()
                    ->user()
                    ->hasRole(["staff", "superadmin"])
            ) {
                if ($request->expectsJson()) {
                    return response()->json(
                        [
                            "success" => false,
                            "message" => "Unauthorized action.",
                        ],
                        403,
                    );
                }
                abort(403, "Unauthorized action.");
            }

            // Hapus semua relasi terlebih dahulu
            $post->likes()->delete();
            $post->comments()->delete();

            // Hapus media files
            $mediaCollection = $post->getMediaCollectionName(
                $post->content_type,
            );
            $post->clearMediaCollection($mediaCollection);

            // Hapus post secara permanen
            $post->forceDelete();

            if ($request->expectsJson()) {
                return response()->json([
                    "success" => true,
                    "message" => "Post berhasil dihapus permanen!",
                ]);
            }
            return redirect()->route("home");
        } catch (\Throwable $e) {
            \Log::error("💥 Delete Post Error: " . $e->getMessage(), [
                "trace" => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson()) {
                return response()->json(
                    [
                        "success" => false,
                        "message" =>
                            "Gagal menghapus post: " . $e->getMessage(),
                    ],
                    500,
                );
            }

            return back();
        }
    }

    public function revision(Request $request, $slug)
    {
        $post = Post::where("slug", $slug)->firstOrFail();

        if ($post->user_id !== Auth::id()) {
            return redirect()
                ->back()
                ->with("error", "You are not allowed to revise this post.");
        }

        $postType = [
            "feed" => "Feed",
            "carousel" => "Carousel",
            "story" => "Story",
            "reel" => "Reel",
        ];

        $status = [
            "draft" => "Draft",
            "published" => "Published",
            "revision" => "Revision",
        ];

        $type = $request->query("type", "feed");

        $tags = Tag::latest()->get();

        return view("post.revision", [
            "postType" => $postType,
            "status" => $status,
            "oldPost" => $post,
            "type" => $type,
            "tags" => $tags,
        ]);
    }

    public function storeRevision(Request $request, $slug)
    {
        $oldPost = Post::where("slug", $slug)->firstOrFail();

        // Ubah status post lama menjadi 'revision'
        $oldPost->update(["status" => "revision"]);

        // Validasi sesuai content type
        try {
            if (in_array($oldPost->content_type, [1, 4])) {
                // Feed / Reel → single media
                $request->validate([
                    "media" => "required|file|mimes:jpg,jpeg,png,mp4|max:20480",
                ]);
                $files = [$request->file("media")];
            } else {
                // Carousel / Story → multiple media
                $request->validate([
                    "media" => "required|array",
                    "media.*" => "file|mimes:jpg,jpeg,png,mp4|max:20480",
                ]);
                $files = $request->file("media");
            }
        } catch (\Throwable $e) {
            \Log::error("💥 Error di blok upload revisi: " . $e->getMessage(), [
                "trace" => $e->getTraceAsString(),
            ]);
        }

        // Buat post baru hasil revisi
        $newPost = Post::create([
            "user_id" => Auth::id(),
            "title" => $request->input("title", $oldPost->title),
            "caption" => $request->input("caption", $oldPost->caption),
            "content_type" => $oldPost->content_type,
            "status" => $request->input("status", $oldPost->status), // tetap sama seperti sebelum direvisi
            "hashtag" => $oldPost->hashtag,
            "post_at" => now(),
            "slug" => Str::slug($oldPost->title) . "-rev-" . time(),
        ]);

        // Simpan komentar revisi ke tabel revisions
        Revision::create([
            "user_id" => Auth::id(),
            "post_id" => $oldPost->id,
            "new_post_id" => $newPost->id,
            "comment" => $request->input(
                "revision_comment",
                "Perbaikan konten",
            ),
            "rev_at" => now(),
            "rev_number" =>
                Revision::where("post_id", $oldPost->id)->count() + 1,
        ]);

        // Upload media baru ke post hasil revisi
        $newPost->attachMedia($request);

        return redirect()
            ->route("home")
            ->with("success", "Revisi berhasil dibuat!");
    }
}
