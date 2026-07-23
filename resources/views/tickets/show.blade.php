<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <!-- Top Header & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-3xl font-bold text-gray-900">Ticket #{{ $ticket->id }}</h1>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full uppercase tracking-wider {{ $ticket->status === 'open' ? 'bg-yellow-100 text-yellow-800' : ($ticket->status === 'assigned' ? 'bg-blue-100 text-blue-800' : ($ticket->status === 'in_progress' ? 'bg-indigo-100 text-indigo-800' : ($ticket->status === 'resolved' ? 'bg-green-100 text-green-800' : ($ticket->status === 'closed' ? 'bg-gray-100 text-gray-800' : 'bg-orange-100 text-orange-800')))) }}">
                        {{ str_replace('_', ' ', $ticket->status) }}
                    </span>
                </div>
                <p class="text-gray-500 text-sm mt-1">
                    Submitted by <span class="font-medium text-gray-700">{{ optional($ticket->customer)->name ?? 'Client' }}</span> on {{ $ticket->created_at->format('M d, Y \a\t H:i') }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('tickets.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm transition">
                    &larr; Back to List
                </a>
                @can('update', $ticket)
                    <a href="{{ route('tickets.edit', $ticket) }}" class="bg-amber-500 hover:bg-amber-600 text-white font-semibold px-4 py-2 rounded-lg text-sm shadow transition">
                        Edit Ticket
                    </a>
                @endcan
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Main Content Area (Title, Description, Discussion Thread) -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Ticket Description Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">{{ $ticket->title }}</h2>
                    <div class="prose max-w-none text-gray-700 leading-relaxed whitespace-pre-line">
                        {{ $ticket->description }}
                    </div>
                </div>

                <!-- Discussion Thread -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Discussion & Updates
                    </h3>

                    <div class="space-y-6 mb-8">
                        @forelse($ticket->messages as $message)
                            <div class="flex gap-4 {{ $message->user_id === Auth::id() ? 'flex-row-reverse' : '' }}">
                                <div class="w-9 h-9 rounded-full shrink-0 flex items-center justify-center font-bold text-sm {{ $message->user_id === Auth::id() ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                                    {{ strtoupper(substr(optional($message->user)->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="max-w-xl bg-gray-50 border border-gray-100 rounded-xl p-4 {{ $message->user_id === Auth::id() ? 'bg-blue-50/60 border-blue-100' : '' }}">
                                    <div class="flex items-center justify-between gap-4 mb-2">
                                        <span class="font-semibold text-sm text-gray-900">{{ optional($message->user)->name }}</span>
                                        <span class="text-xs text-gray-400">{{ $message->created_at->format('M d, H:i') }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $message->message }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 italic text-center py-4">No messages yet. Be the first to reply!</p>
                        @endforelse
                    </div>

                    <!-- New Message Form -->
                    @can('addMessage', $ticket)
                        <form method="POST" action="{{ route('tickets.messages.store', $ticket) }}" class="border-t border-gray-100 pt-6">
                            @csrf
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Add a Reply</label>
                            <textarea name="message" rows="3" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm" placeholder="Write your response here..." required></textarea>
                            <div class="mt-3 flex justify-end">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-5 py-2.5 rounded-lg shadow transition">
                                    Post Message
                                </button>
                            </div>
                        </form>
                    @endcan
                </div>

            </div>

            <!-- Sidebar (Controls, Metadata, SLA, Activity Log) -->
            <div class="space-y-8">

                <!-- State Transition Control Box -->
                @can('changeStatus', $ticket)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">State Pattern Controls</h3>
                        <form method="POST" action="{{ route('tickets.changeStatus', $ticket) }}">
                            @csrf
                            @method('PATCH')
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Change Ticket Status</label>
                            <select name="status" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 mb-4">
                                <option value="assigned" {{ $ticket->status === 'assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                <option value="reopened" {{ $ticket->status === 'reopened' ? 'selected' : '' }}>Reopened</option>
                            </select>
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm py-2.5 rounded-lg shadow transition">
                                Update Status
                            </button>
                        </form>
                    </div>
                @endcan

                <!-- Metadata & SLA Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3">Ticket Information</h3>

                    <div>
                        <span class="block text-xs text-gray-400 uppercase font-semibold">Category</span>
                        <span class="text-sm font-medium text-gray-800">{{ optional($ticket->category)->name }}</span>
                    </div>

                    <div>
                        <span class="block text-xs text-gray-400 uppercase font-semibold">Priority</span>
                        <span class="text-sm font-medium text-gray-800">{{ optional($ticket->priority)->name }}</span>
                    </div>

                    <div>
                        <span class="block text-xs text-gray-400 uppercase font-semibold">Assigned Agent</span>
                        <span class="text-sm font-medium text-gray-800">{{ optional($ticket->agent)->name ?? 'Unassigned' }}</span>
                    </div>

                    <div class="border-t border-gray-100 pt-3">
                        <span class="block text-xs text-gray-400 uppercase font-semibold mb-1">SLA Deadline</span>
                        @if($ticket->sla_deadline)
                            <div class="text-sm font-medium text-gray-800">{{ $ticket->sla_deadline->format('M d, Y H:i') }}</div>
                            <div class="mt-1">
                                @if($ticket->isExpired())
                                    <span class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded bg-red-100 text-red-800">
                                        Expired
                                    </span>
                                @else
                                    <span class="inline-block px-2.5 py-0.5 text-xs font-medium rounded bg-green-100 text-green-800">
                                        {{ $ticket->remainingHours() }} hours remaining
                                    </span>
                                @endif
                            </div>
                        @else
                            <span class="text-sm text-gray-400 italic">No SLA assigned</span>
                        @endif
                    </div>
                </div>

                <!-- Activity Log Timeline -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b border-gray-100 pb-3">Activity Audit Log</h3>
                    <div class="space-y-4">
                        @forelse($ticket->activityLogs as $log)
                            <div class="border-l-2 border-blue-500 pl-3 py-1">
                                <p class="text-xs font-semibold text-gray-800">{{ $log->description }}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">{{ $log->created_at->format('M d, H:i') }}</p>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 italic">No activities recorded yet.</p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>