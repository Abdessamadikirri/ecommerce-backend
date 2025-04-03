<?php

namespace App\Http\Controllers;

use App\Events\userLoggedIn;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserApiController extends Controller
{
    public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        'role' => 'required|string|max:255',
        'image' => 'nullable|sometimes|image|mimes:jpg,png,jpeg,svg,webp,gif|max:2028'
    ]);

    $imagePath = null;  

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('images', 'public');
    }

    $user = User::create([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'tel' => $request->input('tel'),
        'password' => Hash::make($request->input('password')),
        'role' => $request->input('role'),
        'image' => $imagePath
    ]);
    Auth::login($user);
    return response()->json([
        'message' => 'User registered successfully',
        'user' => $user
    ], 201);
}


public function login(Request $request)
{
    // Validate incoming request
    $request->validate([
        'email' => 'required|email|max:255',
        'password' => 'required|string'
    ]);

    // Find the user by email
    $user = User::where('email', $request->input('email'))->first();

    // Check if the user exists and the password is correct
    if (!$user || !Hash::check($request->input('password'), $user->password)) {
        return response()->json(['message' => 'Invalid email or password'], 401);
    }

    // Authenticate the user by creating a session (stateful authentication)
    Auth::login($user);
    event(new userLoggedIn($user));
    // Return a successful response with the user data
    return response()->json([
        'message' => 'Logged in successfully',
        'user' => $user->makeHidden('cart'),
    ], 200);
}

    public function logout(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        Auth()-> guard('web')->logout();
         $request->session()->invalidate();
         $request->session()->regenerateToken();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
