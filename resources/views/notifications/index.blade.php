<x-app-layout>

<div class="max-w-5xl mx-auto py-8">

<h2 class="text-2xl font-bold mb-6">
My Notifications
</h2>

@foreach($notifications as $notification)

<div class="bg-white shadow rounded p-4 mb-3">

{{ $notification->message }}

<div class="text-gray-500 text-sm">

{{ $notification->created_at }}

</div>

</div>

@endforeach

</div>

</x-app-layout>