<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->isAdmin() || $user->isSupervisor()) {
            return true;
        }

        if ($user->isAgent()) {
            return true;
        }

        return $ticket->customer_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->isAdmin() || $user->isSupervisor()) {
            return true;
        }

        if ($user->isAgent()) {
            return $ticket->agent_id === $user->id;
        }

        return $ticket->customer_id === $user->id;
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->isAdmin() || $user->isSupervisor();
    }

    public function changeStatus(User $user, Ticket $ticket): bool
    {
        if ($user->isAdmin() || $user->isSupervisor()) {
            return true;
        }

        if ($user->isAgent()) {
            return $ticket->agent_id === $user->id;
        }

        return $ticket->customer_id === $user->id;
    }

    public function addMessage(User $user, Ticket $ticket): bool
    {
        if ($user->isAdmin() || $user->isSupervisor() || $user->isAgent()) {
            return true;
        }

        return $ticket->customer_id === $user->id;
    }
}
