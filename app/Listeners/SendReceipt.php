<?php

namespace App\Listeners;

use App\Events\FeeCollected;
use Illuminate\Support\Facades\Log;

class SendReceipt
{
    public function handle(FeeCollected $event): void
    {
        Log::info("Fee receipt sent for collection: {$event->collection->receipt_no}");
    }
}
