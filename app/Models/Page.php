<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

use App\Traits\HasImage;

class Page extends Model
{
    use HasFactory, HasTranslations, HasImage;

    public $translatable = ['title', 'content', 'meta_description'];

    protected $fillable = [
        'slug',
        'title',
        'content',
        'meta_description',
        'image_path',
        'is_active',
    ];

    public function getImagePathAttribute($value)
    {
        return $this->resolveImagePath($value);
    }
}
