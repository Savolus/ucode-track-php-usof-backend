<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration {
    public function up() {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->enum('type', [ 'like', 'dislike' ])->default('like');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('post_id')->nullable()->constrained('posts');
            $table->foreignId('comment_id')->nullable()->constrained('comments');
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('likes');
    }
}
