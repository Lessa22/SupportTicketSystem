<?php

namespace App\Strategies\Sla;

use App\Contracts\SlaStrategyInterface;
use App\Models\Ticket;
use Carbon\Carbon;

class MediumPrioritySlaStrategy implements SlaStrategyInterface
{
    public function calculateDeadline(Ticket $ticket): Carbon
    {
        return now()->addHours(48);
    }
}
