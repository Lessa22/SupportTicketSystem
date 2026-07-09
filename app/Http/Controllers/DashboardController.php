<?php

namespace App\Http\Controllers;

use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [

            'totalTickets' => Ticket::count(),

            'openTickets' => Ticket::where('status','open')->count(),

            'closedTickets' => Ticket::where('status','closed')->count(),

        ]);
    }
}