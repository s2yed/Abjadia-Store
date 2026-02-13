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
        'weight',
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
        'publisher_id', // new nullable foreign key
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

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'product_author');
    }

    public function translators()
    {
        return $this->belongsToMany(Translator::class, 'product_translator');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
