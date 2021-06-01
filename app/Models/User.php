<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model {
    use HasFactory;

    protected $fillable = [
        "login",
        "password",
        "full_name",
        "email",
        "profile_picture",
        "role"
    ];

    protected $casts = [
        "login" => "string",
        "password" => "string",
        "full_name" => "string",
        "email" => "string",
        "profile_picture" => "string",
        "role" => "string"
    ];

    public function posts() {
        return $this->hasMany(Post::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function likes() {
        return $this->hasMany(Like::class);
    }
}
