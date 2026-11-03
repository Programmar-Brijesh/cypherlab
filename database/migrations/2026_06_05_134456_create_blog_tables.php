<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Categories
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Posts
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->string('thumbnail')->nullable();
            $table->foreignId('category_id')->constrained('blog_categories');
            $table->boolean('is_featured')->default(false);
            $table->integer('views')->default(0);
            $table->integer('read_time')->default(5);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // Tags
        Schema::create('blog_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Post-Tag pivot
        Schema::create('blog_post_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_post_id')->constrained()->onDelete('cascade');
            $table->foreignId('blog_tag_id')->constrained()->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_post_tag');
        Schema::dropIfExists('blog_tags');
        Schema::dropIfExists('blog_posts');
        Schema::dropIfExists('blog_categories');
    }
};
