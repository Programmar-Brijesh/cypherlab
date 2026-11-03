<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_builder_posts', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            $table->string('title');
            $table->string('slug')->unique();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained('blog_categories')
                ->nullOnDelete();

            $table->text('excerpt')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Editor Content
            |--------------------------------------------------------------------------
            */

            $table->longText('html')->nullable();
            $table->longText('css')->nullable();
            $table->longText('js')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Media
            |--------------------------------------------------------------------------
            */

            $table->string('featured_image')->nullable();

            /*
            |--------------------------------------------------------------------------
            | SEO
            |--------------------------------------------------------------------------
            */

            $table->string('seo_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('og_image')->nullable();
            $table->text('keywords')->nullable();

            $table->unsignedInteger('reading_time')->default(0);

            $table->enum('schema_type', [
                'Article',
                'HowTo',
                'FAQ'
            ])->default('Article');

            /*
            |--------------------------------------------------------------------------
            | Settings
            |--------------------------------------------------------------------------
            */

            $table->json('settings')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Analytics
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('views')->default(0);

            $table->boolean('is_featured')->default(false);

            /*
            |--------------------------------------------------------------------------
            | Publishing
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'draft',
                'published'
            ])->default('draft');

            $table->timestamp('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_builder_posts');
    }
};