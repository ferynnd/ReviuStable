<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class HashtagController extends Controller
{
     public function index()
    {
        $tags = Tag::latest()->get();
        return view('hashtag.page', compact('tags'));
    }

    public function store(Request $request)
    {
        if (Tag::count() >= 10) {
            return redirect()->route('tags.index')->with('error', 'Maksimal 10 hashtag yang diperbolehkan!');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:tags,name',
        ]);

        Tag::create($validated);

        return redirect()->route('tags.index')->with('success', 'Hashtag berhasil ditambahkan!');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('tags.index')->with('success', 'Hashtag berhasil dihapus!');
    }
}
