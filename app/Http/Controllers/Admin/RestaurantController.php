<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::withCount('menus')->orderBy('created_at', 'desc')->get();
        return view('admin.restaurants.index', compact('restaurants'));
    }

    public function create()
    {
        return view('admin.restaurants.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'description' => 'nullable',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'table_capacity' => 'nullable|integer|min:1',
            'opening_hours' => 'nullable',
            'closing_hours' => 'nullable',
            'booking_advance_hours' => 'nullable|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('restaurants', 'public');
            $validated['image'] = $imagePath;
        }

        // Set default values if not provided
        $validated['table_capacity'] = $validated['table_capacity'] ?? 20;
        $validated['opening_hours'] = $validated['opening_hours'] ?? '08:00:00';
        $validated['closing_hours'] = $validated['closing_hours'] ?? '22:00:00';
        $validated['booking_advance_hours'] = $validated['booking_advance_hours'] ?? 2;

        Restaurant::create($validated);

        return redirect()->route('admin.restaurants.index')
            ->with('success', 'Restaurant created successfully!');
    }

    public function edit($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        return view('admin.restaurants.edit', compact('restaurant'));
    }

    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|max:100',
            'description' => 'nullable',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'table_capacity' => 'nullable|integer|min:1',
            'opening_hours' => 'nullable',
            'closing_hours' => 'nullable',
            'booking_advance_hours' => 'nullable|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($restaurant->image) {
                Storage::disk('public')->delete($restaurant->image);
            }
            
            $imagePath = $request->file('image')->store('restaurants', 'public');
            $validated['image'] = $imagePath;
        }

        $restaurant->update($validated);

        return redirect()->route('admin.restaurants.index')
            ->with('success', 'Restaurant updated successfully!');
    }

    public function destroy($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->delete();

        return redirect()->route('admin.restaurants.index')
            ->with('success', 'Restaurant deleted successfully!');
    }
}