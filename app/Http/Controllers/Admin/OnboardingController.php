<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use App\Mail\Admin\WelcomeSuperAdmin;

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
            'email_verified_at' => now(),
            'welcome_email_sent_at' => now(),
            'created_by' => ['type' => 'self'],
        ]);

        // Create and assign super-admin role to first admin
        $role = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'admin']);
        $admin->assignRole($role);

        // Send welcome email
        Mail::to($admin->email)->send(new WelcomeSuperAdmin($admin));

        Auth::guard('admin')->login($admin);

        return redirect()->route('admin.dashboard')->with('success', 'Welcome! Your admin account has been created successfully.');
    }
}