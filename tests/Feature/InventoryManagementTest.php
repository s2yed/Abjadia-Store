<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class InventoryManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $category;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->category = Category::create([
            'name' => ['en' => 'Test Category', 'ar' => 'تصنيف تجريبي'],
            'slug' => 'test-category',
            'is_active' => true,
        ]);

        $this->product = Product::create([
            'name' => ['en' => 'Test Product', 'ar' => 'منتج تجريبي'],
            'slug' => 'test-product',
            'description' => ['en' => 'Desc', 'ar' => 'وصف'],
            'price' => 100,
            'stock' => 10,
            'category_id' => $this->category->id,
            'is_active' => true,
            'type' => 'book',
        ]);
    }

    public function test_stock_is_reduced_on_cod_order()
    {
        $user = User::factory()->create(['status' => 'active']);
        $this->actingAs($user);

        session(['cart' => [
            $this->product->id => [
                'id' => $this->product->id,
                'name' => 'Test Product',
                'price' => 100,
                'quantity' => 2
            ]
        ]]);

        $response = $this->post(route('checkout.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'address' => 'Test Address',
            'phone' => '123456789',
            'payment_method' => 'COD',
        ]);

        $response->assertRedirect();
        
        $this->product->refresh();
        $this->assertEquals(8, $this->product->stock); // 10 - 2
    }

    public function test_stock_is_reduced_on_card_payment_webhook()
    {
        $user = User::factory()->create(['status' => 'active']);
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total_price' => 100,
            'payment_method' => 'Card',
            'payment_status' => 'pending',
            'shipping_address' => 'Test',
        ]);

        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'quantity' => 3,
            'price' => 100,
            'total' => 300,
        ]);

        $paymentId = 'pay_test_999';
        Http::fake([
            "https://api.moyasar.com/v1/payments/{$paymentId}" => Http::response([
                'id' => $paymentId,
                'status' => 'paid',
                'metadata' => ['order_id' => $order->id],
            ])
        ]);

        $this->postJson(route('moyasar.webhook'), [
            'id' => $paymentId,
            'status' => 'paid'
        ]);

        $this->product->refresh();
        $this->assertEquals(7, $this->product->stock); // 10 - 3
    }

    public function test_checkout_fails_if_insufficient_stock()
    {
        $user = User::factory()->create(['status' => 'active']);
        $this->actingAs($user);

        session(['cart' => [
            $this->product->id => [
                'id' => $this->product->id,
                'name' => 'Test Product',
                'price' => 100,
                'quantity' => 11 // More than available 10
            ]
        ]]);

        $response = $this->post(route('checkout.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'address' => 'Test Address',
            'phone' => '123456789',
            'payment_method' => 'COD',
        ]);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error');
    }
}
