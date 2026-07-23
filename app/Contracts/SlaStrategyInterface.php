<?php

namespace App\Contracts;

use App\Models\Ticket;
use Carbon\Carbon;

interface SlaStrategyInterface
{
    public function calculateDeadline(Ticket $ticket): Carbon;
}
