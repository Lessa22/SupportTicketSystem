<x-app-layout>

<div class="max-w-7xl mx-auto py-8">

<h1 class="text-3xl font-bold mb-8">

Users Management

</h1>

<table class="w-full bg-white shadow rounded">

<thead>

<tr class="bg-gray-200">

<th class="p-3">Name</th>

<th>Email</th>

<th>Role</th>

<th>Action</th>

</tr>

</thead>

<tbody>

@foreach($users as $user)

<tr class="border-b">

<td class="p-3">

{{ $user->name }}

</td>

<td>

{{ $user->email }}

</td>

<td>

{{ $user->role }}

</td>

<td>

<form
action="{{ route('admin.role',$user) }}"
method="POST">

@csrf

@method('PUT')

<select
name="role"
class="border rounded">

<option
value="customer">

Customer

</option>

<option
value="agent">

Agent

</option>

<option
value="supervisor">

Supervisor

</option>

<option
value="admin">

Admin

</option>

</select>

<button
class="bg-blue-600 text-white px-3 py-1 rounded">

Save

</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</x-app-layout>