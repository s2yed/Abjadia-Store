<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Setting;
use App\Models\ShippingZone;
use App\Models\ShippingRate;
use App\Services\ShippingService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShippingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $shippingService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->shippingService = new ShippingService();
        Setting::create(['site_name' => 'Test', 'currency' => 'EG', 'free_shipping_threshold' => 200]);
    }

    public function test_calculate_with_global_threshold()
    {
        // No zone, subtotal below threshold
        $result = $this->shippingService->calculate(125);
        $this->assertFalse($result['is_free']);
        
        // No zone, subtotal above threshold
        $result = $this->shippingService->calculate(250);
        $this->assertTrue($result['is_free']);
        $this->assertEquals(0, $result['cost']);
    }

    public function test_calculate_with_zone_and_rate()
    {
        $company = \App\Models\ShippingCompany::create(['name' => 'Test Company', 'is_active' => true]);
        $zone = ShippingZone::create(['name' => 'Cairo', 'coverage_areas' => 'Cairo']);
        ShippingRate::create([
            'shipping_company_id' => $company->id,
            'shipping_zone_id' => $zone->id,
            'calculation_type' => 'flat',
            'cost' => 50,
            'free_shipping_threshold' => 300
        ]);

        // Subtotal below rate threshold and global threshold
        $result = $this->shippingService->calculate(125, $zone->id);
        $this->assertFalse($result['is_free']);
        $this->assertEquals(50, $result['cost']);

        // Subtotal above global threshold (200) but below rate threshold (300)
        // Note: Global threshold should still apply if it's lower? 
        // According to our logic: Offer > Setting > Rate? Actually my service did: Zone Rate > Global Setting.
        $result = $this->shippingService->calculate(250, $zone->id);
        $this->assertTrue($result['is_free']); // Free by global setting
        
        // Subtotal above rate threshold
        $result = $this->shippingService->calculate(350, $zone->id);
        $this->assertTrue($result['is_free']);
    }
}
