<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    PostController,
    HashtagController,
    HomeController,
    CalendarController,
    UserController,
    ProfileController,
};

Route::get("/", function () {
    return Auth::check() ? redirect("/home") : redirect("/login");
});

Route::get("/login", [AuthController::class, "showLoginForm"])->name("login");
Route::post("/login", [AuthController::class, "login"])->name("login.submit");

Route::get("/forgot-password", [AuthController::class, "showForgot"])->name(
    "password.request",
);
Route::post("/forgot-password", [AuthController::class, "storeForgot"])->name(
    "password.email",
);

Route::get("/reset-password/{token}", [
    AuthController::class,
    "showReset",
])->name("password.reset");
Route::post("/reset-password", [AuthController::class, "storeReset"])->name(
    "password.update",
);

Route::middleware("auth")->group(function () {
    Route::get("/home", [HomeController::class, "index"])->name("home");

    Route::get("/calendar", [CalendarController::class, "index"])->name(
        "calendar.page",
    );
    Route::get("/calendar/list", [CalendarController::class, "list"])->name(
        "calendar.list",
    );

    Route::resource("tags", HashtagController::class)->only([
        "index",
        "store",
        "destroy",
    ]);

    Route::prefix("post")->group(function () {
        Route::get("/create", [PostController::class, "index"])->name(
            "post.index",
        );
        Route::post("/create", [PostController::class, "store"])->name(
            "post.store",
        );
        Route::get("/detail/{slug}", [PostController::class, "detail"])->name(
            "post.detail",
        );
        Route::post("/detail/{post}/like", [
            PostController::class,
            "like",
        ])->name("post.like");
        Route::post("/detail/{post}/comment", [
            PostController::class,
            "comment",
        ])->name("post.comment");
        Route::get("/detail/{post}/comments", [
            PostController::class,
            "comments",
        ])->name("post.comments");
        Route::get("/detail/{post}/status", [
            PostController::class,
            "initialStatus",
        ])->name("status");
        Route::post("/{slug}/share", [PostController::class, "share"])->name(
            "post.share",
        );
        // Halaman edit post
        Route::get("{slug}/edit", [PostController::class, "edit"])->name(
            "post.edit",
        );
        // Update post (via PUT/PATCH)
        Route::put("{slug}", [PostController::class, "update"])->name(
            "post.update",
        );
        Route::delete("{slug}", [PostController::class, "destroy"])->name(
            "post.delete",
        );
        // Route untuk membuat revisi dari post lama
        Route::get("/{slug}/revision", [
            PostController::class,
            "revision",
        ])->name("post.revision");
        Route::post("/{slug}/revision", [
            PostController::class,
            "storeRevision",
        ])->name("post.revision.store");
    });

    Route::prefix("users")
        ->name("users.")
        ->group(function () {
            Route::get("/", [UserController::class, "index"])->name("index");
            Route::get("/create", [UserController::class, "create"])->name(
                "create",
            );
            Route::post("/", [UserController::class, "store"])->name("store");
            Route::get("/{user}/edit", [UserController::class, "edit"])->name(
                "edit",
            );
            Route::put("/{user}", [UserController::class, "update"])->name(
                "update",
            );
            Route::delete("/{user}", [UserController::class, "destroy"])->name(
                "destroy",
            );
        });

    Route::prefix("profile")
        ->name("profile.")
        ->group(function () {
            Route::get("/edit", [ProfileController::class, "edit"])->name(
                "edit",
            );
            Route::get("/{username}", [
                ProfileController::class,
                "index",
            ])->name("page");
            Route::put("/", [ProfileController::class, "update"])->name(
                "update",
            );
            Route::post("/change-password", [
                ProfileController::class,
                "changePassword",
            ])->name("change-password");
            Route::get("/{username}/drafts/{type}", [
                ProfileController::class,
                "draftList",
            ])->name("drafts");
        });
    Route::post("/logout", [AuthController::class, "logout"])->name("logout");
});
