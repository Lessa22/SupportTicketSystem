<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TicketMessageController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        Gate::authorize('addMessage', $ticket);

        $request->validate([
            'message'=>'required'
        ]);

        TicketMessage::create([
            'ticket_id'=>$ticket->id,
            'user_id'=>auth()->id(),
            'message'=>$request->message,
        ]);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Message sent successfully.');
    }
}