<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $postType = [
            1 => 'reels',
            2 => 'carousel',
            3 => 'feed',
            4 => 'story',
        ];

        $status = [
            'draft' => 'Draft',
            'published' => 'Published',
            'revision' => 'Revision',
        ];

        return view('post.page', compact('postType', 'status'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'caption' => 'nullable|string',
            'content_type' => 'required|integer|in:1,2,3,4',
            'status' => 'required|in:draft,published,revision',
            'hashtag' => 'nullable|array',
            'post_at' => 'nullable|date',
            'media.*' => 'file|mimes:jpg,jpeg,png,mp4|max:20480',
        ]);

        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'caption' => $validated['caption'] ?? '',
            'content_type' => $validated['content_type'],
            'status' => $validated['status'],
            'hashtag' => $validated['hashtag'] ?? [],
            'post_at' => $validated['post_at'] ?? now(),
        ]);

        $post->attachMedia($request);

        return redirect()->route('posts.index');
    }

}
