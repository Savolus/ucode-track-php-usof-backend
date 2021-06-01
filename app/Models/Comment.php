<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model {
    use HasFactory;

    protected $fillable = [
        "content"
    ];

    protected $casts = [
        "content" => "string"
    ];

    public function likes() {
        return $this->hasMany(Like::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function post() {
        return $this->belongsTo(Post::class);
    }
}
