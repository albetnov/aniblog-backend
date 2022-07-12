<?php

namespace App\Models;

use Database\Factories\BlogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'posted_by'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_blogs');
    }

    protected static function newFactory()
    {
        return BlogFactory::new();
    }
}
