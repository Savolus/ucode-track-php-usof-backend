<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryPostTable extends Migration {
    public function up() {
        Schema::create('category_post', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained('posts');
            $table->foreignId('category_id')->constrained('categories');
        });
    }
    public function down() {
        Schema::dropIfExists('category_post');
    }
}
