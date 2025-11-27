@extends('layouts.admin')

@section('title', 'Manage Restaurants')
@section('page-title', 'Restaurants Management')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.restaurants.create') }}" class="bg-orange-600 text-white px-6 py-2 rounded hover:bg-orange-700">
        + Add New Restaurant
    </a>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Menus</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($restaurants as $restaurant)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $restaurant->id }}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $restaurant->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($restaurant->description, 50) }}</td>
                    <td class="px-6 py-4 text-sm text-yellow-600">⭐ {{ $restaurant->rating }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $restaurant->menus_count }} items</td>
                    <td class="px-6 py-4 text-sm">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.restaurants.edit', $restaurant->id) }}" class="text-blue-600 hover:underline">
                                Edit
                            </a>
                            <form action="{{ route('admin.restaurants.destroy', $restaurant->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">No restaurants found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection