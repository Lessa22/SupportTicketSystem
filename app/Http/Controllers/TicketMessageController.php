<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;

class TicketMessageController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message'=>'required'
        ]);

        TicketMessage::create([
            'ticket_id'=>$ticket->id,
            'user_id'=>auth()->id(),
            'message'=>$request->message,
        ]);

        return back();
    }
}