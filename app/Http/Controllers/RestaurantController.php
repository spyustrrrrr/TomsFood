<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * Display a listing of restaurants
     */
    public function index()
    {
        $restaurants = Restaurant::orderBy('rating', 'desc')->get();
        
        return view('restaurants.index', compact('restaurants'));
    }

    /**
     * Display the specified restaurant with menus
     */
    public function show($id)
    {
        $restaurant = Restaurant::with('menus')->findOrFail($id);
        
        return view('restaurants.show', compact('restaurant'));
    }

    /**
     * Store a newly created restaurant
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'description' => 'nullable',
            'image' => 'nullable|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);

        $restaurant = Restaurant::create($validated);

        return redirect()->route('restaurants.show', $restaurant->id)
            ->with('success', 'Restaurant created successfully!');
    }

    /**
     * Update the specified restaurant
     */
    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|max:100',
            'description' => 'nullable',
            'image' => 'nullable|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);

        $restaurant->update($validated);

        return redirect()->route('restaurants.show', $restaurant->id)
            ->with('success', 'Restaurant updated successfully!');
    }

    /**
     * Remove the specified restaurant
     */
    public function destroy($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->delete();

        return redirect()->route('restaurants.index')
            ->with('success', 'Restaurant deleted successfully!');
    }
}