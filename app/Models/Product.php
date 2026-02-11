<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name', 'description', 'seo_title', 'seo_description', 'seo_keywords'];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'stock',
        'is_active',
        'type', // book, supply
        'isbn', // nullable, for books
        'pages', // nullable, for books
        'publisher', // nullable, for books
        'publication_year', // nullable
        'category_id',
        'brand_id', // nullable
        'image',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'product_author');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
