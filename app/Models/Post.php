<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Post extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        "user_id",
        "title",
        "caption",
        "content_type",
        "status",
        "slug",
        "hashtag",
        "post_at",
    ];

    protected $casts = [
        "hashtag" => "array",
        "content_type" => "integer",
        "post_at" => "datetime",
    ];

    public function getHashtagAttribute($value)
    {
        // pastikan hasilnya selalu array unik
        return collect(json_decode($value, true) ?: [])
            ->flatten()
            ->unique()
            ->values()
            ->toArray();
    }



    public function getMediaCollectionName($contentType = null)
    {
        $contentType = $contentType ?? $this->content_type;

        return match ((int) $contentType) {
            1 => 'feed',
            2 => 'carousel',
            3 => 'story',
            4 => 'reel',
            default => 'feed',
        };
    }
    

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection("reel")->singleFile();
        $this->addMediaCollection("carousel");
        $this->addMediaCollection("story");
        $this->addMediaCollection("feed")->singleFile();
    }

    public function attachMedia(Request $request): void
    {
        if (!$request->hasFile("media")) {
            return;
        }

        $titleSlug = Str::slug($this->title);
        $timestamp = now()->timestamp;

        switch ($this->content_type) {
            case 1: // feed
                $file = $request->file("media");
                $fileName =
                    $timestamp .
                    "_" .
                    $titleSlug .
                    "." .
                    $file->getClientOriginalExtension();

                $this->addMediaFromRequest("media")
                    ->usingFileName($fileName)
                    ->toMediaCollection("feed");
                break;

            case 4: // reel
                $file = $request->file("media");
                $fileName =
                    $timestamp .
                    "_" .
                    $titleSlug .
                    "." .
                    $file->getClientOriginalExtension();

                $this->addMediaFromRequest("media")
                    ->usingFileName($fileName)
                    ->toMediaCollection("reel");
                break;

            case 2: // carousel
                foreach ($request->file("media", []) as $index => $file) {
                    $fileName =
                        $timestamp .
                        "_" .
                        $titleSlug .
                        "_" .
                        ($index + 1) .
                        "." .
                        $file->getClientOriginalExtension();

                    $this->addMedia($file)
                        ->usingFileName($fileName)
                        ->toMediaCollection("carousel");
                }
                break;

            case 3: // story
                foreach ($request->file("media", []) as $index => $file) {
                    $fileName =
                        $timestamp .
                        "_" .
                        $titleSlug .
                        "_" .
                        ($index + 1) .
                        "." .
                        $file->getClientOriginalExtension();

                    $this->addMedia($file)
                        ->usingFileName($fileName)
                        ->toMediaCollection("story");
                }
                break;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function revision()
    {
        return $this->hasMany(Revision::class, 'post_id') ;
    }
    public function oldRevision() // revisi yang menunjuk ke post ini (kebalikannya)
    {
        return $this->hasMany(Revision::class, 'new_post_id');
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function createRevision(string $comment = null): Revision
    {
        // Hitung nomor revisi terakhir
        $lastRevision = $this->revision()->latest('rev_number')->first();
        $revNumber = $lastRevision ? $lastRevision->rev_number + 1 : 1;

        // Buat revisi baru
        return $this->revision()->create([
            'user_id' => auth()->id(),
            'comment' => $comment ?? '',
            'rev_at' => now(),
            'rev_number' => $revNumber,
        ]);
    }
}
