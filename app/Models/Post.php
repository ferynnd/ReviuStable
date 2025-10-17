<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Http\Request;

class Post extends Model implements HasMedia
{

    use HasFactory, InteractsWithMedia;
    
    protected $fillable = [
        'user_id',
        'title',
        'caption',
        'content_type',
        'status',
        'hastag',
        'post_at'
    ];

    protected function casts(): array
    {
        return [
            'hastag' => 'array',
            'content_type' => 'integer',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('reels')->singleFile(); // video tunggal
        $this->addMediaCollection('carousel'); // multiple images
        $this->addMediaCollection('story'); // multiple image
        $this->addMediaCollection('feed')->singleFile(); // image tunggal
    }

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function revision() 
    {
        return $this->hasMany(Revision::class);
    }

    public function like() 
    {
        return $this->hasMany(Like::class);
    }

    public function comment() 
    {
        return $this->hasMany(Comment::class);
    }

    public function attachMedia(Request $request): void
    {
        switch ($this->content_type) {
            case 1: // Reels (single video)
                $this->addMediaFromRequest('media')->toMediaCollection('reels');
                break;

            case 2: // Carousel (multiple image)
                foreach ($request->file('media', []) as $file) {
                    $this->addMedia($file)->toMediaCollection('carousel');
                }
                break;

            case 3: // Story (single/multiple)
                foreach ($request->file('media', []) as $file) {
                    $this->addMedia($file)->toMediaCollection('story');
                }
                break;

            case 4: // Feed (single image)
                $this->addMediaFromRequest('media')->toMediaCollection('feed');
                break;
        }
    }
}
