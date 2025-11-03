<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    //

    public function index($username)
    {
        // Ambil user berdasarkan username
        $user = User::where('username', $username)->firstOrFail();

        // Ambil semua post milik user
        $posts = Post::where('user_id', $user->id)
            ->latest()
            ->get();

        // Filter berdasarkan content_type
        $feeds = $posts->where('content_type', 1);
        $carousels = $posts->where('content_type', 2);
        $stories = $posts->where('content_type', 3);
        $reels = $posts->where('content_type', 4);

        return view('profile.page', [
            'user' => $user,
            'feeds' => $feeds,
            'carousels' => $carousels,
            'stories' => $stories,
            'reels' => $reels,
        ]);
    }

     // Form edit profile
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // Update profile
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username,' . $user->id,
            'email'    => 'required|email|max:150|unique:users,email,' . $user->id,
            'image'    => 'nullable|image|max:2048',
            'bio'    => 'nullable|max:500',
        ]);

        if ($request->hasFile('image')) {
            // Hapus image lama jika ada
            if ($user->image && Storage::exists($user->image)) {
                Storage::delete($user->image);
            }
            
            // Simpan image baru
            $path = $request->file('image')->store('profile', 'public');
            $validated['image'] = $path;
        }

        $user->update($validated);

        return redirect()->route('profile.edit', $user->username)->with('success', 'Profile berhasil diperbarui!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);

        $user = Auth::user();

        // Cek apakah password lama benar
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->with('error', 'Old password does not match.');
        }

        // Update password baru
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }

}
