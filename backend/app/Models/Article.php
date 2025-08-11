<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Article extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'gallery',
        'status',
        'published_at',
        'meta_title',
        'meta_description',
        'tags',
        'views',
        'is_featured'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'gallery' => 'json',
        'tags' => 'json',
        'views' => 'integer',
        'is_featured' => 'boolean'
    ];

    /**
     * Get the author of the article
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope for published articles
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope for featured articles
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Increment views count
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Get reading time estimate
     */
    public function getReadingTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $readingSpeed = 200; // words per minute
        return ceil($wordCount / $readingSpeed);
    }
}
