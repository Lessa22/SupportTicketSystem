<?php

namespace App\Strategies\Assignment;

use App\Models\Ticket;

interface AssignmentStrategy
{
    public function assign(Ticket $ticket): void;
}