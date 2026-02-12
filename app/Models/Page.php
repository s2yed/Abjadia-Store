<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['title', 'content', 'meta_description'];

    protected $fillable = [
        'slug',
        'title',
        'content',
        'meta_description',
        'image_path',
        'is_active',
    ];
}
