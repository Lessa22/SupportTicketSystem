<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Ticket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $query = Ticket::query();
        $recentQuery = Ticket::query();

        if ($user && $user->isCustomer()) {
            $query->where('customer_id', $user->id);
            $recentQuery->where('customer_id', $user->id);
        }

        $totalTickets = (clone $query)->count();
        $openTickets = (clone $query)->where('status', 'open')->count();
        $assignedTickets = (clone $query)->where('status', 'assigned')->count();
        $progressTickets = (clone $query)->where('status', 'in_progress')->count();
        $resolvedTickets = (clone $query)->where('status', 'resolved')->count();
        $closedTickets = (clone $query)->where('status', 'closed')->count();
        $recentTickets = $recentQuery->latest()->take(5)->get();
        $highPriority = (clone $query)->where('priority_id', 3)->count();
        $todayTickets = (clone $query)->whereDate('created_at', today())->count();

        $activeStrategy = Setting::get('active_assignment_strategy', 'round_robin');

        return view('dashboard', compact(
            'totalTickets',
            'openTickets',
            'assignedTickets',
            'progressTickets',
            'resolvedTickets',
            'closedTickets',
            'recentTickets',
            'highPriority',
            'todayTickets',
            'activeStrategy'
        ));
    }

    public function updateSettings(Request $request)
    {
        abort_unless(auth()->user()->isAdmin() || auth()->user()->isSupervisor(), 403);

        $request->validate([
            'active_assignment_strategy' => 'required|in:manual,round_robin,least_loaded',
        ]);

        Setting::set('active_assignment_strategy', $request->active_assignment_strategy);

        return back()->with('success', 'System assignment strategy updated successfully.');
    }
}