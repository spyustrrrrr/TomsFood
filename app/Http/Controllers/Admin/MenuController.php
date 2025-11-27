<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::with('restaurant');
        
        // Filter by restaurant if selected
        if ($request->filled('restaurant_id')) {
            $query->where('restaurant_id', $request->restaurant_id);
        }
        
        $menus = $query->orderBy('created_at', 'desc')->get();
        $restaurants = Restaurant::all(); // For filter dropdown
        
        return view('admin.menus.index', compact('menus', 'restaurants'));
    }

    public function create()
    {
        $restaurants = Restaurant::all();
        return view('admin.menus.create', compact('restaurants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'name' => 'required|max:100',
            'description' => 'nullable',
            'price' => 'required|integer|min:0',
        ]);

        Menu::create($validated);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu created successfully!');
    }

    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        $restaurants = Restaurant::all();
        return view('admin.menus.edit', compact('menu', 'restaurants'));
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'name' => 'required|max:100',
            'description' => 'nullable',
            'price' => 'required|integer|min:0',
        ]);

        $menu->update($validated);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu updated successfully!');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu deleted successfully!');
    }
}