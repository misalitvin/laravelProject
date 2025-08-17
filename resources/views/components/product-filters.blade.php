<form method="GET" class="mb-4 flex flex-wrap gap-4 items-center">

    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
           class="border border-gray-300 rounded px-3 py-1" />

    <select name="sort" class="border border-gray-300 rounded px-3 py-1">
        <option value="">Sort by Name</option>
        <option value="name_asc" @selected(request('sort') === 'name_asc')>Name Ascending</option>
        <option value="name_desc" @selected(request('sort') === 'name_desc')>Name Descending</option>
    </select>

    <input type="number" name="price_min" value="{{ request('price_min') }}" min="0"
           placeholder="Min Price" class="border border-gray-300 rounded px-3 py-1 w-24" />

    <input type="number" name="price_max" value="{{ request('price_max') }}" min="0"
           placeholder="Max Price" class="border border-gray-300 rounded px-3 py-1 w-24" />

    <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700">
        Apply
    </button>
</form>
