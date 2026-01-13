<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Translatable\HasTranslations;

class Author extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name', 'bio'];

    protected $fillable = [
        'name',
        'bio',
        'image',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_author');
    }
}
