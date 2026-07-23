<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Support Tickets</h1>
                <p class="text-gray-600 mt-1">Browse, filter, and track all support request tickets</p>
            </div>
            <div>
                <a href="{{ route('tickets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-lg shadow inline-flex items-center gap-2 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Ticket
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-md">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-md">
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Filter Bar -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8 border border-gray-100">
            <form method="GET" action="{{ route('tickets.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Search</label>
                    <input type="text" name="search" placeholder="Title or content..." value="{{ request('search') }}" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Status</label>
                    <select name="status" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>Assigned</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                        <option value="reopened" {{ request('status') === 'reopened' ? 'selected' : '' }}>Reopened</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Priority</label>
                    <select name="priority" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Priorities</option>
                        @foreach($priorities as $priority)
                            <option value="{{ $priority->id }}" {{ request('priority') == $priority->id ? 'selected' : '' }}>
                                {{ $priority->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Category</label>
                    <select name="category" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-4 flex items-center gap-3 mt-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-5 py-2.5 rounded-lg shadow transition">
                        Apply Filters
                    </button>
                    <a href="{{ route('tickets.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm px-5 py-2.5 rounded-lg transition">
                        Reset
                    </a>

                    @if(Auth::user()->isAgent())
                        <div class="ms-auto flex items-center">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="assigned_to_me" value="1" {{ request('assigned_to_me') ? 'checked' : '' }} onchange="this.form.submit()" class="sr-only peer">
                                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                <span class="ms-3 text-sm font-medium text-gray-700">Assigned Only to Me</span>
                            </label>
                        </div>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tickets Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ticket Details</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Priority</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Assigned Agent</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">SLA Status</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($tickets as $ticket)
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="px-6 py-4">
                                    <a href="{{ route('tickets.show', $ticket) }}" class="text-base font-semibold text-blue-600 hover:underline block">
                                        {{ $ticket->title }}
                                    </a>
                                    <div class="flex items-center gap-3 text-xs text-gray-500 mt-1">
                                        <span>#{{ $ticket->id }}</span>
                                        <span>•</span>
                                        <span>Category: {{ optional($ticket->category)->name }}</span>
                                        <span>•</span>
                                        <span>Client: {{ optional($ticket->customer)->name ?? 'N/A' }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full uppercase tracking-wider {{ $ticket->status === 'open' ? 'bg-yellow-100 text-yellow-800' : ($ticket->status === 'assigned' ? 'bg-blue-100 text-blue-800' : ($ticket->status === 'in_progress' ? 'bg-indigo-100 text-indigo-800' : ($ticket->status === 'resolved' ? 'bg-green-100 text-green-800' : ($ticket->status === 'closed' ? 'bg-gray-100 text-gray-800' : 'bg-orange-100 text-orange-800')))) }}">
                                        {{ str_replace('_', ' ', $ticket->status) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ optional($ticket->priority)->name }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    @if($ticket->agent)
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-semibold">
                                                {{ strtoupper(substr($ticket->agent->name, 0, 1)) }}
                                            </div>
                                            <span>{{ $ticket->agent->name }}</span>
                                        </div>
                                    @else
                                        <span class="italic text-gray-400">Unassigned</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($ticket->sla_deadline)
                                        @if($ticket->isExpired())
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-md bg-red-100 text-red-800">
                                                Expired
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-medium rounded-md bg-green-100 text-green-800">
                                                {{ $ticket->remainingHours() }}h remaining
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('tickets.show', $ticket) }}" class="text-blue-600 hover:text-blue-900 font-semibold">View</a>
                                        @can('update', $ticket)
                                            <a href="{{ route('tickets.edit', $ticket) }}" class="text-amber-600 hover:text-amber-900 font-semibold">Edit</a>
                                        @endcan
                                        @can('delete', $ticket)
                                            <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this ticket?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">Delete</button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    No tickets found matching your criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $tickets->links() }}
            </div>
        </div>

    </div>
</x-app-layout>