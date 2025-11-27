<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Laravel Auth by default uses 'email' field
        // Since we use 'username', we need custom authentication
        $user = User::where('username', $credentials['username'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();

            // Redirect based on user role
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome to Admin Panel!');
            }

            return redirect()->intended('/')->with('success', 'Login successful!');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    /**
     * Show register form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle register request
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:users|max:100',
            'email' => 'required|email|unique:users|max:100',
            'password' => 'required|min:6|confirmed',
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Registration successful!');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully!');
    }
}