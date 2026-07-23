<?php

namespace App\Strategies\Sla;

use App\Contracts\SlaStrategyInterface;
use App\Models\Ticket;

class SlaStrategyFactory
{
    public static function make(Ticket $ticket): SlaStrategyInterface
    {
        return match ((int)$ticket->priority_id) {
            1 => new LowPrioritySlaStrategy(),
            2 => new MediumPrioritySlaStrategy(),
            3 => new HighPrioritySlaStrategy(),
            default => new LowPrioritySlaStrategy(),
        };
    }
}
