<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsCategoriesTable extends Migration {
    public function up() {
        Schema::create('posts_categories', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained('posts');
            $table->foreignId('category_id')->constrained('categories');
        });
    }
    public function down() {
        Schema::dropIfExists('posts_categories');
    }
}
