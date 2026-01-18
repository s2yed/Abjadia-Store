<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Mail\OrderPlacedEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MoyasarPaymentTest extends TestCase
{
    use RefreshDatabase; 

    public function test_payment_page_loads_with_moyasar_config()
    {
        // 1. Create a User
        $user = User::factory()->create(['status' => 'active']);

        // 2. Create an Order
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total_price' => 100.00,
            'payment_method' => 'Card',
            'payment_status' => 'pending',
            'shipping_address' => 'Test Address',
        ]);

        // 3. Act as User and visit payment page
        $response = $this->actingAs($user)
                         ->get(route('moyasar.pay', $order->id));

        // 4. Assertions
        $response->assertStatus(200);
        $response->assertSee('Moyasar.init', false);
        $response->assertSee(config('services.moyasar.key'), false);
        $response->assertSee('10000', false); // Amount in halalas (100.00 * 100)
    }

    public function test_webhook_updates_order_status_and_sends_email()
    {
        Mail::fake();

        // 1. Create Order
        $user = User::factory()->create(['status' => 'active']);
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total_price' => 150.00,
            'payment_method' => 'Card',
            'payment_status' => 'pending',
            'shipping_address' => 'Test Address',
        ]);

        // 2. Mock Moyasar API Response
        $paymentId = 'pay_test_123456';
        Http::fake([
            "https://api.moyasar.com/v1/payments/{$paymentId}" => Http::response([
                'id' => $paymentId,
                'status' => 'paid',
                'amount' => 15000,
                'metadata' => ['order_id' => $order->id],
                'source' => ['type' => 'creditcard', 'company' => 'visa', 'message' => 'Approved']
            ], 200)
        ]);

        // 3. Send Webhook Request
        $response = $this->postJson(route('moyasar.webhook'), [
            'id' => $paymentId,
            'status' => 'paid'
        ]);

        // 4. Assertions
        $response->assertStatus(200);
        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('processing', $order->status);
        $this->assertEquals($paymentId, $order->transaction_id);

        Mail::assertSent(OrderPlacedEmail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }
}
