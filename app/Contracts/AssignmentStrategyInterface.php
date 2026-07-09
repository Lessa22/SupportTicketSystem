<?php

namespace App\Contracts;

use App\Models\Ticket;
use App\Models\User;

interface AssignmentStrategyInterface
{
    public function assign(Ticket $ticket): ?User;
}