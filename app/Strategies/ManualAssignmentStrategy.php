<?php

namespace App\Strategies;

use App\Contracts\AssignmentStrategyInterface;
use App\Models\Ticket;
use App\Models\User;

class ManualAssignmentStrategy implements AssignmentStrategyInterface
{
    public function assign(Ticket $ticket): ?User
    {
        return null;
    }
}