<?php

namespace App\Traits;

trait HasImage
{
    /**
     * Get the resolved image URL.
     *
     * @param string|null $value
     * @return string|null
     */
    public function getImageAttribute($value)
    {
        return $this->resolveImagePath($value);
    }

    /**
     * Helper to resolve path for any attribute (e.g. logo, photo)
     */
    protected function resolveImagePath($value)
    {
        if (!$value) {
            return null;
        }
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        $value = ltrim($value, '/');

        if (file_exists(public_path($value))) {
            return asset($value);
        }

        if (str_starts_with($value, 'storage/')) {
            return asset($value);
        }

        return asset('storage/' . $value);
    }
}
