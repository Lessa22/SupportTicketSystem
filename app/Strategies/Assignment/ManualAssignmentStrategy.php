<?php

namespace App\Strategies\Assignment;

use App\Models\Ticket;

class ManualAssignmentStrategy implements AssignmentStrategy
{
    public function assign(Ticket $ticket): void
    {
        $ticket->agent_id = null;
        $ticket->status = 'open';
        $ticket->save();
    }
}