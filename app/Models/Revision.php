<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
        'comment',
        'rev_at',
        'rev_number',
        'new_post_id',
    ];

    protected function casts(): array
    {
        return [
            'rev_at' => 'datetime',
        ];
    }

    public function post() 
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function newPost()
    {
        return $this->belongsTo(Post::class, 'new_post_id');
    }


    public function user() 
    {
        return $this->belongsTo(User::class);
    }
}
