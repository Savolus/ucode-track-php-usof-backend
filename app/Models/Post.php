<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model {
    use HasFactory;

    protected $fillable = [
        "title",
        "content",
        "status"
    ];

    protected $casts = [
        "title" => "string",
        "content" => "string",
        "status" => "string"
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class);
    }

    public function likes() {
        return $this->hasMany(Like::class);
    }
}
