<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('settings')) {
    /**
     * Get a setting value from the database
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function settings(string $key, $default = null)
    {
        return Cache::remember('setting_' . $key, 3600, function () use ($key, $default) {
            $setting = Setting::first();
            
            if (!$setting) {
                return $default;
            }

            // Check if the key exists in the settings
            if (isset($setting->{$key})) {
                return $setting->{$key};
            }

            // For JSON fields, try to decode and access
            $data = $setting->toArray();
            
            return $data[$key] ?? $default;
        });
    }
}
