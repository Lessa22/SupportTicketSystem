<?php

namespace App\Contracts;

use App\Models\Ticket;

interface TicketRepositoryInterface
{
    public function all();

    public function find(int $id): ?Ticket;

    public function create(array $data): Ticket;

    public function update(Ticket $ticket, array $data): bool;

    public function delete(Ticket $ticket): bool;
}