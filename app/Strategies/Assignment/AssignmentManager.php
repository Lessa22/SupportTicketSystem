<?php

namespace App\Strategies\Assignment;

use App\Models\Ticket;

class AssignmentManager
{
    protected AssignmentStrategy $strategy;

    public function __construct(
        AssignmentStrategy $strategy
    ){
        $this->strategy = $strategy;
    }

    public function assign(Ticket $ticket)
    {
        $this->strategy->assign($ticket);
    }
}