<x-app-layout>

<div class="max-w-7xl mx-auto py-8">

<div class="flex justify-between items-center mb-8">

<h1 class="text-3xl font-bold">

Tickets

</h1>

<a
href="{{ route('tickets.create') }}"
class="bg-blue-600 text-white px-5 py-3 rounded">

New Ticket

</a>

</div>

@if(session('success'))

<div class="bg-green-200 p-4 rounded mb-5">

{{ session('success') }}

</div>

@endif

<table class="w-full border">

<thead>

<tr>

<th class="p-3">Title</th>

<th>Status</th>

<th>Category</th>

<th>Priority</th>

<th>Actions</th>

</tr>

</thead>

<tbody>

@foreach($tickets as $ticket)

<tr class="border-t">

<td class="p-3">

{{ $ticket->title }}

</td>

<td>

{{ ucfirst($ticket->status) }}

</td>

<td>

{{ $ticket->category->name }}

</td>

<td>

{{ $ticket->priority->name }}

</td>

<td>

<a
href="{{ route('tickets.show',$ticket) }}"
class="text-blue-600">

View

</a>

|

<a
href="{{ route('tickets.edit',$ticket) }}"
class="text-yellow-600">

Edit

</a>

|

<form
action="{{ route('tickets.destroy',$ticket) }}"
method="POST"
class="inline">

@csrf

@method('DELETE')

<button
onclick="return confirm('Delete this ticket ?')"
class="text-red-600">

Delete

</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

<div class="mt-8">

{{ $tickets->links() }}

</div>

</div>

</x-app-layout>