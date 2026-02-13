<?php

namespace Tests\Feature;

use App\Models\Offer;
use App\Models\Product;
use App\Services\PromotionEngine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class PromotionEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_apply_min_qty_offer()
    {
        // Create Category
        $category = \App\Models\Category::create(['name' => ['en' => 'Test Category'], 'slug' => 'test-cat']);

        // Create Product
        $product = Product::create([
            'name' => ['en' => 'Test Product'],
            'slug' => 'test-product',
            'price' => 100,
            'stock' => 10,
            'type' => 'supply',
            'category_id' => $category->id,
            'description' => ['en' => 'Test Desc'],
            'seo_title' => ['en' => 'seo'],
            'seo_description' => ['en' => 'seo'],
            'seo_keywords' => ['en' => 'seo']
        ]);

        // Create Offer: Buy 2 get 10% off
        Offer::create([
            'name' => 'Buy 2 Get 10% Off',
            'type' => 'percentage_off',
            'priority' => 1,
            'is_active' => true,
            'conditions' => [
                'type' => 'min_qty',
                'product_id' => $product->id,
                'value' => 2
            ],
            'actions' => [
                'type' => 'percentage_off',
                'value' => 10
            ]
        ]);

        // Mock Cart Items
        $cartItem = new \stdClass();
        $cartItem->product = $product;
        $cartItem->id = $product->id;
        $cartItem->price = $product->price;
        $cartItem->quantity = 2; // Meets condition

        $cartItems = collect([$cartItem]);

        // Run Engine
        $engine = new PromotionEngine();
        $result = $engine->applyOffers($cartItems);

        // Expected Discount: (100 * 2) * 10% = 20
        $this->assertEquals(20, $result['total_discount']);
    }

    public function test_apply_min_subtotal_offer()
    {
        $category = \App\Models\Category::create(['name' => ['en' => 'Test Category 2'], 'slug' => 'test-cat-2']);

        // Create Products
        $p1 = Product::create([
            'name' => ['en' => 'P1'], 
            'slug' => 'p1', 
            'price' => 200, 
            'stock' => 10, 
            'type' => 'supply', 
            'category_id' => $category->id,
            'description' => ['en' => 'd'], 
            'seo_title' => ['en' => 's'], 
            'seo_description' => ['en' => 'd'], 
            'seo_keywords' => ['en' => 'k']
        ]);
        $p2 = Product::create([
            'name' => ['en' => 'P2'], 
            'slug' => 'p2', 
            'price' => 300, 
            'stock' => 10, 
            'type' => 'supply', 
            'category_id' => $category->id,
            'description' => ['en' => 'd'], 
            'seo_title' => ['en' => 's'], 
            'seo_description' => ['en' => 'd'], 
            'seo_keywords' => ['en' => 'k']
        ]);

        // Create Offer: Spend 400 get 50 off
        Offer::create([
            'name' => 'Spend 400 Get 50 Off',
            'type' => 'fixed_amount',
            'priority' => 1,
            'is_active' => true,
            'conditions' => [
                'type' => 'min_subtotal',
                'value' => 400
            ],
             'actions' => [
                'type' => 'percentage_off', // Wait, my engine implementation for fixed amount action was likely missing in previous step?
                                          // Let's check logic. I implemented percentage_off action type. 
                                          // Let's test percentage_off first.
                'value' => 10 
            ]
        ]);
        
        // Mock Cart Items
        $i1 = new \stdClass(); $i1->id = $p1->id; $i1->product = $p1; $i1->price = $p1->price; $i1->quantity = 1;
        $i2 = new \stdClass(); $i2->id = $p2->id; $i2->product = $p2; $i2->price = $p2->price; $i2->quantity = 1;
        
        $cartItems = collect([$i1, $i2]); // Total 500

        $engine = new PromotionEngine();
        $result = $engine->applyOffers($cartItems);

        // Expected Discount: 500 * 10% = 50
        $this->assertEquals(50, $result['total_discount']);
    }

    public function test_apply_fixed_amount_offer()
    {
        $category = \App\Models\Category::create(['name' => ['en' => 'Test Category 3'], 'slug' => 'test-cat-3']);

        $p1 = Product::create([
            'name' => ['en' => 'P1'], 
            'slug' => 'p1', 
            'price' => 200, 
            'stock' => 10, 
            'type' => 'supply', 
            'category_id' => $category->id,
            'description' => ['en' => 'd'], 
            'seo_title' => ['en' => 's'], 
            'seo_description' => ['en' => 'd'], 
            'seo_keywords' => ['en' => 'k']
        ]);

        Offer::create([
            'name' => 'Spend 200 Get 50 Off',
            'type' => 'fixed_amount',
            'priority' => 1,
            'is_active' => true,
            'conditions' => [
                'type' => 'min_subtotal',
                'value' => 200
            ],
             'actions' => [
                'type' => 'fixed_amount',
                'value' => 50 
            ]
        ]);
        
        $i1 = new \stdClass(); $i1->id = $p1->id; $i1->product = $p1; $i1->price = $p1->price; $i1->quantity = 1;
        $cartItems = collect([$i1]);

        $engine = new PromotionEngine();
        $result = $engine->applyOffers($cartItems);

        $this->assertEquals(50, $result['total_discount']);
    }

    public function test_apply_buy_x_get_y_offer()
    {
        $category = \App\Models\Category::create(['name' => ['en' => 'Test Category 4'], 'slug' => 'test-cat-4']);

        $p1 = Product::create([
            'name' => ['en' => 'P1'], 
            'slug' => 'p1', 
            'price' => 100, 
            'stock' => 10, 
            'type' => 'supply', 
            'category_id' => $category->id,
            'description' => ['en' => 'd'], 
            'seo_title' => ['en' => 's'], 
            'seo_description' => ['en' => 'd'], 
            'seo_keywords' => ['en' => 'k']
        ]);

        Offer::create([
            'name' => 'Buy 2 Get 1 Free',
            'type' => 'buy_x_get_y',
            'priority' => 1,
            'is_active' => true,
            'conditions' => [
                'type' => 'min_qty',
                'product_id' => $p1->id,
                'value' => 2
            ],
            'actions' => [
                'type' => 'free_product',
                'free_product_id' => $p1->id,
                'value' => 1
            ]
        ]);

        $i1 = new \stdClass(); $i1->id = $p1->id; $i1->product = $p1; $i1->product_id = $p1->id; $i1->price = $p1->price; $i1->quantity = 2;
        $cartItems = collect([$i1]);

        $engine = new PromotionEngine();
        $result = $engine->applyOffers($cartItems);

        $this->assertEquals(100, $result['total_discount']);
    }
}
