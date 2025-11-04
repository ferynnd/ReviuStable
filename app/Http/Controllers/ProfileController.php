<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class ProfileController extends Controller
{
    //

    public function index($username)
    {
        // Ambil user berdasarkan username
        $user = User::where("username", $username)->firstOrFail();

        // Ambil semua post milik user
        $posts = Post::where("user_id", $user->id)
            ->whereIn("status", ["published", "revision"])
            ->latest()
            ->get();

        // Filter berdasarkan content_type
        $feeds = $posts->where("content_type", 1);
        $carousels = $posts->where("content_type", 2);
        $stories = $posts->where("content_type", 3);
        $reels = $posts->where("content_type", 4);

        $draftCounts = [
            "feeds" => Post::where("user_id", $user->id)
                ->where("status", "draft")
                ->where("content_type", 1)
                ->count(),
            "reels" => Post::where("user_id", $user->id)
                ->where("status", "draft")
                ->where("content_type", 4)
                ->count(),
            "carousels" => Post::where("user_id", $user->id)
                ->where("status", "draft")
                ->where("content_type", 2)
                ->count(),
            "stories" => Post::where("user_id", $user->id)
                ->where("status", "draft")
                ->where("content_type", 3)
                ->count(),
        ];

        return view("profile.page", [
            "user" => $user,
            "feeds" => $feeds,
            "carousels" => $carousels,
            "stories" => $stories,
            "reels" => $reels,
            "draftCounts" => $draftCounts,
        ]);
    }

    // Form edit profile
    public function edit()
    {
        $user = Auth::user();
        return view("profile.edit", compact("user"));
    }

    // Update profile
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            "fullname" => "required|string|max:255",
            "username" =>
                "required|string|max:100|unique:users,username," . $user->id,
            "email" => "required|email|max:150|unique:users,email," . $user->id,
            "image" => "nullable|image|max:2048",
            "bio" => "nullable|max:500",
        ]);

        if ($request->hasFile("image")) {
            if ($user->image && Storage::exists($user->image)) {
                Storage::delete($user->image);
            }

            // Simpan image baru
            $path = $request->file("image")->store("profile", "public");
            $validated["image"] = $path;
        }

        $user->update($validated);

        return redirect()
            ->route("profile.edit", $user->username)
            ->with("success", "Profile berhasil diperbarui!");
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate(
            [
                "old_password" => "required",
                "new_password" => [
                    "required",
                    "string",
                    "min:8",
                    "different:old_password", // tidak boleh sama dengan password lama
                    "regex:/[a-z]/", // minimal 1 huruf kecil
                    "regex:/[A-Z]/", // minimal 1 huruf besar
                ],
                "confirm_password" => "required|same:new_password",
            ],
            [
                "old_password.required" => "Current password is required.",
                "new_password.required" => "New password is required.",
                "new_password.min" =>
                    "New password must be at least 8 characters.",
                "new_password.different" =>
                    "New password cannot be the same as your old password.",
                "new_password.regex" =>
                    "New password must contain uppercase, lowercase.",
                "confirm_password.required" =>
                    "Please confirm your new password.",
                "confirm_password.same" =>
                    "Password confirmation does not match.",
            ],
        );

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return back()
                ->withInput()
                ->with(
                    "error",
                    "The current password you entered is incorrect.",
                );
        }

        $user->update([
            "password" => Hash::make($request->new_password),
        ]);

        return redirect()
            ->route("login")
            ->with(
                "success",
                "Password changed successfully. Please log in again.",
            );
    }

    public function draftList($username, $type)
    {
        $user = User::where("username", $username)->firstOrFail();

        $typeMap = [
            "feed" => 1,
            "carousel" => 2,
            "story" => 3,
            "reel" => 4,
        ];

        if (!isset($typeMap[$type])) {
            abort(404, "Tipe konten tidak ditemukan");
        }

        $posts = Post::where("user_id", $user->id)
            ->where("status", "draft")
            ->where("content_type", $typeMap[$type])
            ->latest()
            ->get();

        return view("profile.list", [
            "user" => $user,
            "posts" => $posts,
            "type" => $type,
        ]);
    }
}
