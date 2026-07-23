<?php

namespace App\Repositories;

use App\Contracts\TicketRepositoryInterface;
use App\Models\Ticket;

class TicketRepository implements TicketRepositoryInterface
{
  public function all()
{
    $query = Ticket::with(['category', 'priority', 'agent', 'customer']);

    $user = auth()->user();
    if ($user && $user->isCustomer()) {
        $query->where('customer_id', $user->id);
    } elseif ($user && $user->isAgent()) {
        if (request('assigned_to_me')) {
            $query->where('agent_id', $user->id);
        }
    }

    if (request('search')) {
        $query->where(function ($q) {
            $q->where('title', 'like', '%' . request('search') . '%')
              ->orWhere('description', 'like', '%' . request('search') . '%');
        });
    }

    if (request('status')) {
        $query->where('status', request('status'));
    }

    if (request('priority')) {
        $query->where('priority_id', request('priority'));
    }

    if (request('category')) {
        $query->where('category_id', request('category'));
    }

    return $query
        ->latest()
        ->paginate(10)
        ->withQueryString();
}

    public function find(int $id): ?Ticket
    {
        return Ticket::find($id);
    }

    public function create(array $data): Ticket
    {
        return Ticket::create($data);
    }

    public function update(Ticket $ticket, array $data): bool
    {
        return $ticket->update($data);
    }

    public function delete(Ticket $ticket): bool
    {
        return $ticket->delete();
    }
}