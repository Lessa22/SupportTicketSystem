@csrf

<div class="mb-4">
    <label class="block font-semibold mb-2">Title</label>

    <input
        type="text"
        name="title"
        value="{{ old('title', $ticket->title ?? '') }}"
        class="w-full border rounded-lg p-3">

    @error('title')
        <p class="text-red-500">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">

    <label class="block font-semibold mb-2">

        Description

    </label>

    <textarea
        name="description"
        rows="6"
        class="w-full border rounded-lg p-3">{{ old('description', $ticket->description ?? '') }}</textarea>

    @error('description')
        <p class="text-red-500">{{ $message }}</p>
    @enderror

</div>

<div class="grid grid-cols-2 gap-4">

    <div>

        <label>Category</label>

        <select
            name="category_id"
            class="w-full border rounded-lg p-3">

            @foreach($categories as $category)

                <option value="{{ $category->id }}">

                    {{ $category->name }}

                </option>

            @endforeach

        </select>

    </div>

    <div>

        <label>Priority</label>

        <select
            name="priority_id"
            class="w-full border rounded-lg p-3">

            @foreach($priorities as $priority)

                <option value="{{ $priority->id }}">

                    {{ $priority->name }}

                </option>

            @endforeach

        </select>

    </div>

</div>

<div class="mt-8">

    <button
        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

        Save Ticket

    </button>

</div>