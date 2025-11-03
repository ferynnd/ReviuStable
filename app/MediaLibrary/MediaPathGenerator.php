<?php

namespace App\MediaLibrary;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class MediaPathGenerator implements PathGenerator
{
    // Folder Root (misalnya: 'feed/', 'reel/', dsb.)
    public function getPath(Media $media): string
    {
        // Menggunakan nama collection (feed, reel, carousel, story) sebagai folder utama
        return $media->collection_name . '/' . $this->getBasePath($media) . '/';
    }

    // Folder untuk Konversi (misalnya: 'feed/1/conversions/')
    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media) . 'conversions/';
    }
    
    // Folder untuk Gambar Responsif
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . 'responsive/';
    }

    /* Helper: ID dari Model (Post) */
    protected function getBasePath(Media $media): string
    {
        // Menggunakan ID dari Post untuk memecah folder (misalnya: 'feed/1/')
        return (string) $media->model->getKey(); 
    }
}