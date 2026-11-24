<?php

namespace Tests\Unit;

use App\Models\Shop;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ShopPermitValidityStatusTest extends TestCase
{
    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_permit_validity_status_is_derived_from_the_approved_permit_expiry(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-24 12:00:00'));

        $shopWithoutPermit = new Shop();
        $this->assertSame('not_submitted', $shopWithoutPermit->permit_validity_status);

        $validPermit = new Shop([
            'business_permit_path' => 'business-permits/current.pdf',
            'business_permit_expires_at' => '2027-01-01',
        ]);
        $this->assertSame('valid', $validPermit->permit_validity_status);

        $expiringPermit = new Shop([
            'business_permit_path' => 'business-permits/current.pdf',
            'business_permit_expires_at' => '2026-12-10',
        ]);
        $this->assertSame('expiring_soon', $expiringPermit->permit_validity_status);
        $this->assertSame('Expires in 16 days', $expiringPermit->permit_validity_label);

        $tomorrowPermit = new Shop([
            'business_permit_path' => 'business-permits/current.pdf',
            'business_permit_expires_at' => '2026-11-25',
        ]);
        $this->assertSame('Expires tomorrow', $tomorrowPermit->permit_validity_label);

        $todayPermit = new Shop([
            'business_permit_path' => 'business-permits/current.pdf',
            'business_permit_expires_at' => '2026-11-24',
        ]);
        $this->assertSame('Expires today', $todayPermit->permit_validity_label);

        $expiredPermit = new Shop([
            'business_permit_path' => 'business-permits/current.pdf',
            'business_permit_expires_at' => '2026-11-23',
        ]);
        $this->assertSame('expired', $expiredPermit->permit_validity_status);
    }
}
