<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

use App\Traits\HasImage;

class Setting extends Model
{
    use HasTranslations, HasImage;

    protected $fillable = [
        'logo',
        'favicon',
        'site_name',
        'site_description',
        'site_keywords',
        'contact_email',
        'contact_phone',
        'whatsapp_number',
        'currency',
        'free_shipping_threshold',
        'default_shipping_cost',
        'social_facebook',
        'social_twitter',
        'social_instagram',
        'social_linkedin',
        'social_snapchat',
        'social_youtube',
    ];

    public function getLogoAttribute($value)
    {
        return $this->resolveImagePath($value);
    }

    public function getFaviconAttribute($value)
    {
        return $this->resolveImagePath($value);
    }

    public $translatable = [
        'site_name',
        'site_description',
        'site_keywords',
    ];
}
