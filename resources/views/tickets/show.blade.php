<x-app-layout>

    <div class="max-w-5xl mx-auto py-8">

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow rounded-lg p-8">

            <div class="flex justify-between items-center mb-6">

                <h1 class="text-3xl font-bold">
                    Ticket #{{ $ticket->id }}
                </h1>

                <a href="{{ route('tickets.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Back
                </a>

            </div>

            <hr class="mb-6">

            <div class="space-y-5">

                <div>
                    <h3 class="font-semibold text-gray-600">Title</h3>
                    <p class="text-lg">{{ $ticket->title }}</p>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-600">Description</h3>
                    <p>{{ $ticket->description }}</p>
                </div>

                <div class="grid grid-cols-2 gap-6">

                    <div>
                        <h3 class="font-semibold text-gray-600">Category</h3>
                        <p>{{ $ticket->category->name }}</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-600">Priority</h3>
                        <p>{{ $ticket->priority->name }}</p>
                    </div>

                </div>

                <div>

                    <h3 class="font-semibold text-gray-600 mb-2">Status</h3>

                    @php

                        $badge = match($ticket->status){

                            'open' => 'bg-blue-500',

                            'assigned' => 'bg-yellow-500',

                            'in_progress' => 'bg-indigo-500',

                            'resolved' => 'bg-green-600',

                            'closed' => 'bg-gray-700',

                            'reopened' => 'bg-purple-600',

                            default => 'bg-gray-500'
                        };

                    @endphp

                    <span class="{{ $badge }} text-white px-4 py-2 rounded-full">
                        {{ ucfirst(str_replace('_',' ', $ticket->status)) }}
                    </span>

                </div>

            </div>

            <hr class="my-8">

            <h2 class="text-xl font-bold mb-4">
                Change Status
            </h2>

            @if($ticket->status == 'open')
    <span class="px-2 py-1 rounded bg-green-100 text-green-700">Open</span>

@elseif($ticket->status == 'assigned')
    <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-700">Assigned</span>

@elseif($ticket->status == 'in_progress')
    <span class="px-2 py-1 rounded bg-blue-100 text-blue-700">In Progress</span>

@elseif($ticket->status == 'resolved')
    <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700">Resolved</span>

@else
    <span class="px-2 py-1 rounded bg-gray-200 text-gray-700">Closed</span>
@endif
        </div>
        <hr>

<h4>Activity History</h4>

<table class="table table-striped">

<thead>
<tr>
<th>Action</th>
<th>Description</th>
<th>Date</th>
</tr>
</thead>

<tbody>

@foreach($ticket->activityLogs as $log)

<tr>
<td>{{ ucfirst($log->action) }}</td>
<td>{{ $log->description }}</td>
<td>{{ $log->created_at }}</td>
</tr>

@endforeach

</tbody>

</table>

    </div>
    <div class="mt-6 p-4 bg-white rounded shadow">

<h2 class="font-bold text-lg">

SLA

</h2>

<p>

Deadline :

{{ $ticket->sla_deadline?->format('d/m/Y H:i') }}

</p>

@if($ticket->isExpired())

<span class="text-red-600 font-bold">

🔴 Expired

</span>

@else

<span class="text-green-600 font-bold">

🟢

{{ $ticket->remainingHours() }}

hours remaining

</span>

@endif

</div>
@if($ticket->agent)
<div class="mb-3">
    <strong>Assigned Agent :</strong>
    {{ $ticket->agent->name }}
</div>
@endif
<hr>

<h3 class="mt-4">Discussion</h3>

@forelse($ticket->messages as $message)

<div class="card mb-2">

    <div class="card-body">

        <strong>{{ $message->user->name }}</strong>

        <small class="text-muted float-end">
            {{ $message->created_at->format('d/m/Y H:i') }}
        </small>

        <p class="mt-2">
            {{ $message->message }}
        </p>

    </div>

</div>

@empty

<div class="alert alert-secondary">
    No messages yet.
</div>

@endforelse
<form method="POST"
      action="{{ route('tickets.messages.store',$ticket) }}">

    @csrf

    <div class="mb-3">

        <textarea
            name="message"
            class="form-control"
            rows="4"
            placeholder="Write a message..."
            required></textarea>

    </div>

    <button class="btn btn-primary">

        Send Message

    </button>

</form>

</x-app-layout>