<?php

namespace App\Console\Commands;

use App\Models\Shop;
use App\Notifications\BusinessPermitNotification;
use Illuminate\Console\Command;

class SendBusinessPermitReminders extends Command
{
    protected $signature = 'permits:send-expiry-reminders';

    protected $description = 'Notify shop owners about upcoming or recently expired business permits';

    public function handle(): int
    {
        foreach ([30, 7, 1, 0] as $daysRemaining) {
            $expiryDate = today()->addDays($daysRemaining);

            Shop::with('user')
                ->whereNotNull('business_permit_path')
                ->whereDate('business_permit_expires_at', $expiryDate)
                ->where('business_permit_review_status', '!=', 'pending_review')
                ->where(function ($query) use ($daysRemaining, $expiryDate) {
                    $query->whereNull('business_permit_last_reminder_key')
                        ->orWhere('business_permit_last_reminder_key', '!=', $expiryDate->toDateString() . ':' . $daysRemaining);
                })
                ->chunkById(100, function ($shops) use ($daysRemaining, $expiryDate) {
                    foreach ($shops as $shop) {
                        if (!$shop->user) {
                            $this->warn("Shop {$shop->id} has no owner; skipping permit reminder.");
                            continue;
                        }

                        $message = match ($daysRemaining) {
                            0 => "The business permit for {$shop->shop_name} expires today.",
                            1 => "The business permit for {$shop->shop_name} expires tomorrow.",
                            default => "The business permit for {$shop->shop_name} expires in {$daysRemaining} days.",
                        };

                        $type = $daysRemaining === 0
                            ? 'business_permit_expired'
                            : 'business_permit_expiry_reminder';

                        $shop->user->notify(new BusinessPermitNotification($shop, $type, $message));
                        $shop->update([
                            'business_permit_last_reminder_key' => $expiryDate->toDateString() . ':' . $daysRemaining,
                        ]);
                    }
                });
        }

        $this->info('Business permit expiry reminders processed.');

        return self::SUCCESS;
    }
}
