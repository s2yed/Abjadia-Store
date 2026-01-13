<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convert Products
        DB::table('products')->get()->each(function ($product) {
            $name = $product->name;
            $description = $product->description;

            // Check if name is already JSON
            if (!$this->isJson($name)) {
                DB::table('products')->where('id', $product->id)->update([
                    'name' => json_encode(['en' => $name, 'ar' => $name]),
                ]);
            }

            // Check if description is already JSON
            if ($description && !$this->isJson($description)) {
                DB::table('products')->where('id', $product->id)->update([
                    'description' => json_encode(['en' => $description, 'ar' => $description]),
                ]);
            }
        });

        // Convert Categories
        DB::table('categories')->get()->each(function ($category) {
            $name = $category->name;
            if (!$this->isJson($name)) {
                DB::table('categories')->where('id', $category->id)->update([
                    'name' => json_encode(['en' => $name, 'ar' => $name]),
                ]);
            }
        });

        // Convert Banners
        DB::table('banners')->get()->each(function ($banner) {
            $title = $banner->title;
            $description = $banner->description;

            if ($title && !$this->isJson($title)) {
                DB::table('banners')->where('id', $banner->id)->update([
                    'title' => json_encode(['en' => $title, 'ar' => $title]),
                ]);
            }

            if ($description && !$this->isJson($description)) {
                DB::table('banners')->where('id', $banner->id)->update([
                    'description' => json_encode(['en' => $description, 'ar' => $description]),
                ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No easy way to reverse as we don't know which field was the "original"
        // but typically it would be English.
    }

    private function isJson($string)
    {
        if (!is_string($string)) return false;
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
};
