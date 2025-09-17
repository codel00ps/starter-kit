<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    /**
     * Get all of the tags for the post.
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
        $this->addMediaCollection('gallery');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->performOnCollections('image')
            ->fit(Fit::Crop, 150, 107)
            ->format('webp');

        $this->addMediaConversion('large')
            ->performOnCollections('image')
            ->fit(Fit::Crop, 1000, 714)
            ->format('webp');

        $this->addMediaConversion('medium')
            ->performOnCollections('image')
            ->fit(Fit::Crop, 700, 500)
            ->format('webp');

        $this->addMediaConversion('large')
            ->performOnCollections('gallery')
            ->fit(Fit::Crop, 900, 600)
            ->format('webp');

        $this->addMediaConversion('medium')
            ->performOnCollections('gallery')
            ->fit(Fit::Crop, 600, 400)
            ->format('webp');

        $this->addMediaConversion('thumb')
            ->performOnCollections('gallery')
            ->fit(Fit::Crop, 150, 150)
            ->format('webp');
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
