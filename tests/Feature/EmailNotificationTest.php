<?php

namespace Tests\Feature;

use App\Mail\OrderPlacedEmail;
use App\Mail\OrderStatusChangedEmail;
use App\Mail\WelcomeEmail;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_welcome_email_is_sent_on_registration()
    {
        Mail::fake();

        $response = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        Mail::assertSent(WelcomeEmail::class, function ($mail) {
            return $mail->hasTo('test@example.com');
        });
    }

    public function test_order_placed_email_is_sent_on_cod_checkout()
    {
        Mail::fake();
        
        $user = User::factory()->create(['status' => 'active']);
        $this->actingAs($user);

        $category = Category::create([
            'name' => ['en' => 'Test Category', 'ar' => 'تصنيف تجريبي'],
            'slug' => 'test-category',
            'is_active' => true,
        ]);

        $product = Product::create([
            'name' => ['en' => 'Test Product', 'ar' => 'منتج تجريبي'],
            'slug' => 'test-product',
            'description' => ['en' => 'Test Description', 'ar' => 'وصف تجريبي'],
            'price' => 100,
            'stock' => 10,
            'category_id' => $category->id,
            'is_active' => true,
            'type' => 'book',
        ]);
        
        // Add item to cart session
        session()->put('cart', [
            $product->id => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1
            ]
        ]);

        $response = $this->post(route('checkout.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'address' => 'Test Address',
            'phone' => '123456789',
            'payment_method' => 'COD',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionMissing('error');
        $response->assertRedirect(); // Should redirect to order show page

        Mail::assertSent(OrderPlacedEmail::class);
    }
    
    public function test_status_change_email_is_sent_on_update()
    {
        Mail::fake();

        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total_price' => 100,
            'payment_method' => 'COD',
            'payment_status' => 'pending',
            'shipping_address' => 'Test Address'
        ]);

        // Update status
        $order->update(['status' => 'shipped']);

        Mail::assertSent(OrderStatusChangedEmail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }
}
