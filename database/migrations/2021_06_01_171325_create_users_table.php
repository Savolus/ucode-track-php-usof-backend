<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {
    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('login')->unique();
            $table->string('password');
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('profile_picture')->nullable()->default(null);
            $table->enum('role', [ 'admin', 'user' ])->default('user');
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('users');
    }
}
