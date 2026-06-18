<?php

namespace App\Events;

use App\Models\Fee\FeeCollection;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FeeCollected
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public FeeCollection $collection;

    public function __construct(FeeCollection $collection)
    {
        $this->collection = $collection;
    }
}
