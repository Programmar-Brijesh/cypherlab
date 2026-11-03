<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Support\Str;

class BlogBuilderPost extends Model
{
    use HasFactory, SoftDeletes;

    // Explicitly define the table since the name is a bit unique
    protected $table = 'blog_builder_posts';

    protected $appends = ['featured_image_url'];

    // Mass assignable attributes
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'category_id',
        'excerpt',
        'html',
        'css',
        'js',
        'featured_image',
        'seo_title',
        'meta_description',
        'canonical_url',
        'og_image',
        'keywords',
        'reading_time',
        'schema_type',
        'settings',
        'is_featured',
        'status',
        'published_at',
    ];

    // Cast attributes to native PHP types
    protected $casts = [
        'settings' => 'array', // Automatically serializes/deserializes JSON
        'is_featured' => 'boolean',
        'reading_time' => 'integer',
        'published_at' => 'datetime',
    ];

    public function getFeaturedImageUrlAttribute()
    {
        if (empty($this->featured_image)) {
            return null;
        }

        if (Str::startsWith($this->featured_image, ['http://', 'https://', '/'])) {
            return $this->featured_image;
        }

        return asset('uploads/blog/' . $this->featured_image);
    }

    public function getEstimatedReadingTimeAttribute(): int
    {
        if (!empty($this->reading_time) && (int) $this->reading_time > 0) {
            return (int) $this->reading_time;
        }

        $wordCount = str_word_count(strip_tags((string) ($this->html ?? $this->excerpt ?? '')));
        return max(1, (int) ceil($wordCount / 200));
    }

    /**
     * Relationship: A post belongs to a user (author).
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: A post belongs to a category.
     * Ensure you have a BlogCategory model created.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }
}