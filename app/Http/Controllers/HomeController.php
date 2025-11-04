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

    public function index(Request $request)
    {
        $query = Post::with("user")->whereIn("status", [
            "published",
            "revision",
        ]);

        // Jika ada search
        if ($request->has("search") && $request->search != "") {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where("title", "like", "%{$keyword}%")->orWhereHas(
                    "user",
                    function ($q2) use ($keyword) {
                        $q2->where("username", "like", "%{$keyword}%")->orWhere(
                            "fullname",
                            "like",
                            "%{$keyword}%",
                        );
                    },
                );
            });
        }

        $posts = $query->latest()->get();

        return view("home.page", [
            "posts" => $posts,
            "request" => $request,
        ]);
    }
}
