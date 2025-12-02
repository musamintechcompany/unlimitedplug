<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class OnboardingController extends Controller
{
    public function show()
    {
        // Redirect to login if admin already exists
        if (Admin::count() > 0) {
            abort(404);
        }

        return view('management.portal.admin.onboarding');
    }

    public function store(Request $request)
    {
        // Redirect to login if admin already exists
        if (Admin::count() > 0) {
            abort(404);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'active',
            'email_verified_at' => now(), // Auto-verify super admin
        ]);

        Auth::guard('admin')->login($admin);

        return redirect()->route('admin.dashboard')->with('success', 'Welcome! Your admin account has been created successfully.');
    }
}