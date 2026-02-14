<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

use Spatie\Translatable\HasTranslations;

use App\Traits\HasImage;

class Banner extends Model
{
    use HasTranslations, HasImage;

    public $translatable = ['title', 'description'];

    public function getImagePathAttribute($value)
    {
        return $this->resolveImagePath($value);
    }

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'link',
        'position',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];
}
