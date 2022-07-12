<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'details'];

    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'category_blogs');
    }

    protected static function newFactory()
    {
        return CategoryFactory::new();
    }
}
