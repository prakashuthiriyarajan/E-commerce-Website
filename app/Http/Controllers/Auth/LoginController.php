<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        // If already logged in, redirect to appropriate dashboard
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }
        
        return view('auth.login');
    }

    /**
     * Handle login - Works for both Admin and Users
     */
    public function login(Request $request)
    {
        // Validate the input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        // Attempt to login
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Login successful - redirect based on role
            return $this->redirectBasedOnRole();
        }

        // Login failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email', 'remember'));
    }

    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }
        
        return view('auth.register');
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Create new user (always regular user, not admin)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => 0, // Regular users by default
            'email_verified_at' => now(),
        ]);

        // Auto login after registration
        Auth::login($user);

        // Redirect to homepage
        return redirect()->route('home')
            ->with('success', 'Registration successful! Welcome, ' . $user->name);
    }

    /**
     * Redirect user based on their role (Admin or User)
     */
    protected function redirectBasedOnRole()
    {
        $user = Auth::user();

        // Check if user is admin
        if ($user && $user->is_admin == 1) {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Welcome back, Admin ' . $user->name . '!');
        }

        // Regular user
        return redirect()->route('home')
            ->with('success', 'Welcome back, ' . $user->name . '!');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }
}