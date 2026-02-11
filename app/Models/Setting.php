<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Setting extends Model
{
    use HasTranslations;

    protected $fillable = [
        'logo',
        'favicon',
        'site_name',
        'site_description',
        'site_keywords',
        'contact_email',
        'contact_phone',
        'whatsapp_number',
        'social_facebook',
        'social_twitter',
        'social_instagram',
        'social_linkedin',
        'social_snapchat',
        'social_youtube',
    ];

    public $translatable = [
        'site_name',
        'site_description',
        'site_keywords',
    ];
}
