<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    /**
     * Get categories for the navbar.
     */
    public function getNavbarCategories(int $limit = 12): Collection
    {
        return Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('name')
            ->take($limit)
            ->get();
    }
}
