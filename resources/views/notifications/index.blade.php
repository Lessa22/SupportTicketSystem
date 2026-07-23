@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto py-8">

<h1 class="text-3xl font-bold mb-6">
Notifications
</h1>

@forelse($notifications as $notification)

<div class="bg-white shadow rounded p-5 mb-4">

<p>

{{ $notification->message }}

</p>

<small class="text-gray-500">

{{ $notification->created_at->diffForHumans() }}

</small>

@if(!$notification->is_read)

<form
action="{{ route('notifications.read',$notification) }}"
method="POST"
class="mt-3">

@csrf
@method('PATCH')

<button
class="bg-blue-600 text-white px-3 py-2 rounded">

Mark as read

</button>

</form>

@endif

</div>

@empty

<div class="bg-white p-5 rounded shadow">

No notifications.

</div>

@endforelse

<div class="mt-5">

{{ $notifications->links() }}

</div>

</div>

@endsection