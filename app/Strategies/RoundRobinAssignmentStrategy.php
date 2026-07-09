<?php

namespace App\Strategies;

use App\Contracts\AssignmentStrategyInterface;
use App\Models\Ticket;
use App\Models\User;

class RoundRobinAssignmentStrategy implements AssignmentStrategyInterface
{
    public function assign(Ticket $ticket): ?User
    {
        return User::where('role', 'agent')
            ->orderBy('id')
            ->first();
    }
}