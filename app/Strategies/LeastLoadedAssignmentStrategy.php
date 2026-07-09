<?php

namespace App\Strategies;

use App\Contracts\AssignmentStrategyInterface;
use App\Models\Ticket;
use App\Models\User;

class LeastLoadedAssignmentStrategy implements AssignmentStrategyInterface
{
    public function assign(Ticket $ticket): ?User
    {
        return User::where('role', 'agent')
            ->withCount('assignedTickets')
            ->orderBy('assigned_tickets_count')
            ->first();
    }
}