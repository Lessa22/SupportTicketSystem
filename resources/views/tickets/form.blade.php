@csrf

<div class="mb-4">
    <label class="block font-semibold text-gray-700 mb-2">Title</label>
    <input type="text" name="title" value="{{ old('title', $ticket->title ?? '') }}" class="w-full border-gray-300 rounded-lg p-3 text-sm focus:border-blue-500 focus:ring-blue-500">
    @error('title')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label class="block font-semibold text-gray-700 mb-2">Description</label>
    <textarea name="description" rows="6" class="w-full border-gray-300 rounded-lg p-3 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $ticket->description ?? '') }}</textarea>
    @error('description')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div>
        <label class="block font-semibold text-gray-700 mb-2">Category</label>
        <select name="category_id" class="w-full border-gray-300 rounded-lg p-3 text-sm focus:border-blue-500 focus:ring-blue-500">
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $ticket->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block font-semibold text-gray-700 mb-2">Priority</label>
        <select name="priority_id" class="w-full border-gray-300 rounded-lg p-3 text-sm focus:border-blue-500 focus:ring-blue-500">
            @foreach($priorities as $priority)
                <option value="{{ $priority->id }}" {{ old('priority_id', $ticket->priority_id ?? '') == $priority->id ? 'selected' : '' }}>
                    {{ $priority->name }}
                </option>
            @endforeach
        </select>
        @error('priority_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

@if(isset($agents) && (Auth::user()->isAdmin() || Auth::user()->isSupervisor()))
    <div class="mb-6 bg-blue-50/50 p-4 rounded-lg border border-blue-100">
        <label class="block font-semibold text-gray-700 mb-2">Assign Agent (Supervisor / Admin Control)</label>
        <select name="agent_id" class="w-full border-gray-300 rounded-lg p-3 text-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">-- Unassigned --</option>
            @foreach($agents as $agent)
                <option value="{{ $agent->id }}" {{ old('agent_id', $ticket->agent_id ?? '') == $agent->id ? 'selected' : '' }}>
                    {{ $agent->name }} ({{ $agent->email }})
                </option>
            @endforeach
        </select>
        @error('agent_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
@endif

<div class="mt-8 flex items-center gap-4">
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow transition">
        Save Ticket
    </button>
    <a href="{{ route('tickets.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-3 rounded-lg transition">
        Cancel
    </a>
</div>