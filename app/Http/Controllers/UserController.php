<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $userAuth = Auth::id(); // user yang login
        $query = User::where("id", "!=", $userAuth);

        if ($request->has("search") && $request->search != "") {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where("username", "like", "%{$keyword}%")->orWhere(
                    "fullname",
                    "like",
                    "%{$keyword}%",
                );
            });
        }

        $users = $query->orderByDesc("id")->get();

        return view("user.page", compact("users", "request"));
    }

    public function create()
    {
        $roles = Role::all();
        return view("user.create", compact("roles"));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "fullname" => "required|string|max:255",
            "username" => "required|string|max:100|unique:users,username",
            "email" => "required|email|max:150|unique:users,email",
            "role" => "required|exists:roles,name",
        ]);

        $user = User::create([
            "fullname" => $validated["fullname"],
            "username" => $validated["username"],
            "email" => $validated["email"],
            "is_active" => true,
            "password" => Hash::make("1234"),
        ]);

        // Assign role
        $user->assignRole($validated["role"]);

        return redirect()
            ->route("users.index")
            ->with("success", "User berhasil ditambahkan!");
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view("user.create", compact("user", "roles"));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            "fullname" => "required|string|max:255",
            "username" =>
                "required|string|max:100|unique:users,username," . $user->id,
            "email" => "required|email|max:150|unique:users,email," . $user->id,
            "status" => "nullable|boolean",
            "role" => "required|exists:roles,name",
        ]);

        // Update user
        $user->update([
            "fullname" => $validated["fullname"],
            "username" => $validated["username"],
            "email" => $validated["email"],
            "is_active" => $validated["status"] ?? $user->status,
        ]);

        // Update role (hapus role lama, assign baru)
        $user->syncRoles([$validated["role"]]);

        return redirect()
            ->route("users.index")
            ->with("success", "User berhasil diperbarui!");
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route("users.index")
            ->with("success", "User berhasil dihapus!");
    }
}
