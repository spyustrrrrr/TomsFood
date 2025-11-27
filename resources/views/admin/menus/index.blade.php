@extends('layouts.admin')

@section('title', 'Manage Menus')
@section('page-title', 'Menus Management')

@section('content')
<div class="mb-6 flex justify-between items-center gap-4">
    <div class="flex gap-4 flex-1">
        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.menus.index') }}" class="flex gap-3 items-center">
            <select name="restaurant_id" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" onchange="this.form.submit()">
                <option value="">All Restaurants</option>
                @foreach($restaurants as $restaurant)
                    <option value="{{ $restaurant->id }}" {{ request('restaurant_id') == $restaurant->id ? 'selected' : '' }}>
                        {{ $restaurant->name }}
                    </option>
                @endforeach
            </select>
            
            @if(request('restaurant_id'))
                <a href="{{ route('admin.menus.index') }}" class="bg-orange-600 text-white py-2 rounded hover:bg-orange-700 whitespace-nowrap px-4">
                    Clear Filter
                </a>
            @endif
        </form>
    </div>
    
    <a href="{{ route('admin.menus.create') }}" class="bg-orange-600 text-white px-6 py-2 rounded hover:bg-orange-700 whitespace-nowrap">
        + Add New Menu
    </a>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Menu Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Restaurant</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($menus as $menu)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $menu->id }}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $menu->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $menu->restaurant->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900 font-semibold">{{ $menu->formatted_price }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($menu->description, 40) }}</td>
                    <td class="px-6 py-4 text-sm">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.menus.edit', $menu->id) }}" class="text-blue-600 hover:underline">
                                Edit
                            </a>
                            <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">No menus found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection