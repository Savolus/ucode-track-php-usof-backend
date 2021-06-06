<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements JWTSubject {
    use HasFactory, Notifiable;

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

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }
}
