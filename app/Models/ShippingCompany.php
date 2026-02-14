<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Translatable\HasTranslations;
use App\Traits\HasImage;

class ShippingCompany extends Model
{
    use HasFactory, HasImage, HasTranslations;

    public $translatable = ['name'];

    protected $fillable = ['name', 'logo', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getLogoAttribute($value)
    {
        return $this->resolveImagePath($value);
    }
}
