<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                        📊 Support Ticket Dashboard
                    </h1>
                    <p class="text-gray-600 mt-1">Real-time stats and assignment system configuration</p>
                </div>
                <div>
                    <a href="{{ route('tickets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow inline-flex items-center gap-2 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create New Ticket
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-md">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Assignment Strategy Settings Card (Visible to Admins & Supervisors) -->
            @if(Auth::user()->isAdmin() || Auth::user()->isSupervisor())
                <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-xl p-6 shadow-sm">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div>
                            <span class="px-2.5 py-1 bg-blue-100 text-blue-800 font-semibold text-xs rounded-full uppercase tracking-wider">Strategy Pattern Configuration</span>
                            <h2 class="text-lg font-bold text-gray-900 mt-2">Ticket Auto-Assignment Strategy</h2>
                            <p class="text-sm text-gray-600 mt-1">Select the active algorithm for distributing incoming support tickets among agents.</p>
                        </div>
                        <form action="{{ route('dashboard.settings') }}" method="POST" class="flex items-center gap-3">
                            @csrf
                            <select name="active_assignment_strategy" class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg text-sm shadow-sm font-medium">
                                <option value="manual" {{ $activeStrategy === 'manual' ? 'selected' : '' }}>Manual Assignment (Unassigned)</option>
                                <option value="round_robin" {{ $activeStrategy === 'round_robin' ? 'selected' : '' }}>Round-Robin (Sequential)</option>
                                <option value="least_loaded" {{ $activeStrategy === 'least_loaded' ? 'selected' : '' }}>Least Loaded (Lowest Workload)</option>
                            </select>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm px-4 py-2.5 rounded-lg shadow transition">
                                Apply Strategy
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Metrics Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white shadow rounded-xl p-6 border-l-4 border-blue-500">
                    <h2 class="text-sm font-medium text-gray-500">Total Tickets</h2>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalTickets }}</p>
                </div>

                <div class="bg-white shadow rounded-xl p-6 border-l-4 border-yellow-500">
                    <h2 class="text-sm font-medium text-gray-500">Open & Assigned</h2>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $openTickets + $assignedTickets }}</p>
                </div>

                <div class="bg-white shadow rounded-xl p-6 border-l-4 border-indigo-500">
                    <h2 class="text-sm font-medium text-gray-500">In Progress</h2>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $progressTickets }}</p>
                </div>

                <div class="bg-white shadow rounded-xl p-6 border-l-4 border-green-500">
                    <h2 class="text-sm font-medium text-gray-500">Resolved / Closed</h2>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $resolvedTickets + $closedTickets }}</p>
                </div>
            </div>

            <!-- Chart & Recent Tickets Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl shadow p-6 lg:col-span-1">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Tickets by Status</h2>
                    <div class="relative h-64">
                        <canvas id="ticketChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6 lg:col-span-2">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-gray-900">Recent Tickets</h2>
                        <a href="{{ route('tickets.index') }}" class="text-sm font-semibold text-blue-600 hover:underline">View All &rarr;</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($recentTickets as $ticket)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm">
                                            <a href="{{ route('tickets.show', $ticket) }}" class="font-semibold text-blue-600 hover:underline">
                                                {{ $ticket->title }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full uppercase tracking-wider {{ $ticket->status === 'open' ? 'bg-yellow-100 text-yellow-800' : ($ticket->status === 'assigned' ? 'bg-blue-100 text-blue-800' : ($ticket->status === 'in_progress' ? 'bg-indigo-100 text-indigo-800' : ($ticket->status === 'resolved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'))) }}">
                                                {{ str_replace('_', ' ', $ticket->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            {{ optional($ticket->priority)->name }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-3 text-sm text-gray-500 text-center">No tickets found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('ticketChart');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Open', 'Assigned', 'In Progress', 'Resolved', 'Closed'],
                datasets: [{
                    data: [
                        {{ $openTickets }},
                        {{ $assignedTickets }},
                        {{ $progressTickets }},
                        {{ $resolvedTickets }},
                        {{ $closedTickets }}
                    ],
                    backgroundColor: ['#EAB308', '#3B82F6', '#6366F1', '#22C55E', '#6B7280']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
</x-app-layout>