<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PasswordController extends Controller
{
    /**
     * Update the user's password (for logged in users).
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    /**
     * Display the forgot password form.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send verification code for password reset.
     */
    public function sendCode(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return response()->json([
                'message' => 'Email not found in our system.'
            ], 404);
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        session([
            'password_reset_code' => $code,
            'password_reset_email' => $request->email,
            'password_reset_expires' => now()->addMinutes(10)
        ]);

        \Mail::to($user)->send(new \App\Mail\PasswordResetCode($code));

        return response()->json([
            'message' => 'Verification code sent to your email address.'
        ]);
    }

    /**
     * Reset password with verification code.
     */
    public function resetWithCode(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'size:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!session('password_reset_code') || 
            session('password_reset_email') !== $request->email ||
            session('password_reset_code') !== $request->code ||
            now()->gt(session('password_reset_expires'))) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        session()->forget(['password_reset_code', 'password_reset_email', 'password_reset_expires']);

        return redirect()->route('login')->with('status', 'Password reset successfully!');
    }
}
