<?php

namespace App\Listeners;

use App\Events\FeeCollected;

class UpdateDueTracking
{
    public function handle(FeeCollected $event): void
    {
        $collection = $event->collection;
        $tracking = $collection->student->feeDueTrackings()->where('fee_structure_id', $collection->fee_structure_id)->first();

        if ($tracking) {
            $tracking->update([
                'paid_amount' => $tracking->paid_amount + $collection->paid_amount,
                'balance_amount' => max(0, $tracking->total_amount - ($tracking->paid_amount + $collection->paid_amount)),
                'status' => $collection->balance_amount <= 0 ? 'paid' : 'partial',
            ]);
        }
    }
}
