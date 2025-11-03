<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Tampilkan daftar postingan untuk halaman utama (feed dan story).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $posts = Post::with("user")->latest()->get();

        return view("home.page", [
            "posts" => $posts,
        ]);
    }
}
