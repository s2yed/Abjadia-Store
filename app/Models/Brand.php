<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Translatable\HasTranslations;
use App\Traits\HasImage;

class Brand extends Model
{
    use HasFactory, HasImage, HasTranslations; 

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'slug',
        'logo',
    ];

    public function getLogoAttribute($value)
    {
        return $this->resolveImagePath($value);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
