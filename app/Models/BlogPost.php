<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class BlogPost extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'thumbnail',
        'category_id',
        'is_featured',
        'views',
        'read_time',
        'published_at'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class);
    }

    public function tags()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_post_tag');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
