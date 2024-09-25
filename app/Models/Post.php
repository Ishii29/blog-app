<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

     public function author()
     {
         return $this->belongsTo(User::class, 'user_id'); // Adjust if the foreign key differs
     }

     public function comments()
     {
         return $this->hasMany(Comment::class);
     }
}

