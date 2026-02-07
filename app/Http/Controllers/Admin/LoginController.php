<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\Admin\LoginVerificationCode;
use App\Mail\Admin\AccountAccessed;

class LoginController extends Controller
{
    public function showLogin()
    {
        // Redirect to dashboard if already logged in as admin
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        // Redirect to onboarding if no admins exist
        if (Admin::count() === 0) {
            return redirect()->route('admin.onboarding');
        }

        return view('management.portal.admin.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $credentials['email'])->first();

        if (!$admin || !\Hash::check($credentials['password'], $admin->password)) {
            return back()->withErrors(['email' => 'Invalid credentials.']);
        }

        // Generate 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $admin->update([
            'login_verification_code' => $code,
            'login_verification_code_expires_at' => now()->addMinutes(5),
        ]);

        // Send verification code
        Mail::to($admin->email)->send(new LoginVerificationCode($admin, $code));

        // Store admin ID in session for verification
        $request->session()->put('admin_verify_id', $admin->id);

        return redirect()->route('admin.verify.show');
    }

    public function showVerify()
    {
        if (!session('admin_verify_id')) {
            return redirect()->route('admin.login');
        }
        return view('management.portal.admin.verify-code');
    }

    public function verifyCode(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);

        $adminId = session('admin_verify_id');
        if (!$adminId) {
            return redirect()->route('admin.login');
        }

        $admin = Admin::find($adminId);

        if (!$admin || 
            $admin->login_verification_code !== $request->code || 
            $admin->login_verification_code_expires_at < now()) {
            return back()->withErrors(['code' => 'Invalid or expired code.']);
        }

        // Clear verification code
        $admin->update([
            'login_verification_code' => null,
            'login_verification_code_expires_at' => null,
        ]);

        // Log in admin
        Auth::guard('admin')->login($admin);
        $request->session()->forget('admin_verify_id');
        $request->session()->regenerate();

        // Send account accessed notification
        Mail::to($admin->email)->send(new AccountAccessed(
            $admin,
            $request->ip(),
            now()->format('F j, Y g:i A')
        ));

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/management/portal/admin/login');
    }
}
