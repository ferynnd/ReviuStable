<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
        'like_at',
    ];

    protected function casts(): array
    {
        return [
            'like_at' => 'datetime',
        ];
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
