<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display menus by restaurant
     */
    public function index($restaurantId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        $menus = Menu::where('restaurant_id', $restaurantId)->get();
        
        return view('menus.index', compact('restaurant', 'menus'));
    }

    /**
     * Display the specified menu
     */
    public function show($id)
    {
        $menu = Menu::with('restaurant')->findOrFail($id);
        
        return view('menus.show', compact('menu'));
    }

    /**
     * Store a newly created menu
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'name' => 'required|max:100',
            'description' => 'nullable',
            'price' => 'required|integer|min:0',
        ]);

        $menu = Menu::create($validated);

        return redirect()->route('restaurants.show', $menu->restaurant_id)
            ->with('success', 'Menu added successfully!');
    }

    /**
     * Update the specified menu
     */
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|max:100',
            'description' => 'nullable',
            'price' => 'required|integer|min:0',
        ]);

        $menu->update($validated);

        return redirect()->route('restaurants.show', $menu->restaurant_id)
            ->with('success', 'Menu updated successfully!');
    }

    /**
     * Remove the specified menu
     */
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $restaurantId = $menu->restaurant_id;
        $menu->delete();

        return redirect()->route('restaurants.show', $restaurantId)
            ->with('success', 'Menu deleted successfully!');
    }
}