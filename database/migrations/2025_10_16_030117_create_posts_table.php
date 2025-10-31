<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("posts", function (Blueprint $table) {
            $table->id();

            $table
                ->foreignId("user_id")
                ->constrained("users")
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->string("title", 150);
            $table->text("caption");
            $table->tinyinteger("content_type"); // 1 = feed, 2 = carousel, 3 = story, 4 = reels
            $table
                ->enum("status", ["draft", "published", "revision"])
                ->default("draft");
            $table->json("hashtag")->nullable();
            $table->date("post_at");
            $table->string("slug")->unique();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("posts");
    }
};
