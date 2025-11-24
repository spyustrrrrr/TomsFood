<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display homepage with list of restaurants
     */
    public function index()
    {
        $restaurants = Restaurant::orderBy('rating', 'desc')->get();
        
        return view('home', compact('restaurants'));
    }
}