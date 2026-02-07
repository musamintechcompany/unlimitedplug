<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // Check if email is being changed
        if ($request->email !== $user->email) {
            // Generate and send verification code
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $user->email_verification_code = $code;
            $user->verification_code_expires_at = now()->addMinutes(10);
            $user->save();
            
            // Send verification code to NEW email
            \Illuminate\Support\Facades\Mail::to($request->email)->send(
                new \App\Mail\EmailChangeVerification($code)
            );
            
            // Store new email in session temporarily
            session(['pending_email' => $request->email]);
            
            return Redirect::route('profile.edit')->with('status', 'verification-code-sent');
        }
        
        // Update other fields
        $user->fill($request->validated());
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
    
    /**
     * Verify email change code.
     */
    public function verifyEmailChange(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);
        
        $user = $request->user();
        
        if (!$user->email_verification_code || $user->verification_code_expires_at < now()) {
            return back()->withErrors(['code' => 'Verification code has expired.']);
        }
        
        if ($user->email_verification_code !== $request->code) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }
        
        // Update email
        $user->email = session('pending_email');
        $user->email_verified_at = now();
        $user->email_verification_code = null;
        $user->verification_code_expires_at = null;
        $user->save();
        
        session()->forget('pending_email');
        
        return Redirect::route('profile.edit')->with('status', 'email-updated');
    }
    
    /**
     * Resend email verification code.
     */
    public function resendCode(Request $request): RedirectResponse
    {
        $user = $request->user();
        $pendingEmail = session('pending_email');
        
        if (!$pendingEmail) {
            return Redirect::route('profile.edit');
        }
        
        // Generate new code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->email_verification_code = $code;
        $user->verification_code_expires_at = now()->addMinutes(10);
        $user->save();
        
        // Resend to pending email
        \Illuminate\Support\Facades\Mail::to($pendingEmail)->send(
            new \App\Mail\EmailChangeVerification($code)
        );
        
        return Redirect::route('profile.edit')->with('status', 'verification-code-sent');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        
        $user->update([
            'deleted_by' => [
                'type' => 'user',
                'id' => $user->id,
                'name' => $user->name,
                'deleted_at' => now()->toDateTimeString()
            ]
        ]);
        
        // Notify admins about account deletion
        $this->notifyAdmins($user);

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    
    private function notifyAdmins($user)
    {
        $admins = \App\Models\Admin::all();
        
        foreach ($admins as $admin) {
            $notification = \App\Models\Notification::create([
                'notifiable_type' => \App\Models\Admin::class,
                'notifiable_id' => $admin->id,
                'type' => 'user_deleted_account',
                'title' => 'User Deleted Account',
                'message' => $user->name . ' (' . $user->email . ') deleted their account.',
                'data' => json_encode(['user_id' => $user->id, 'user_email' => $user->email])
            ]);
            
            broadcast(new \App\Events\AdminNotificationCreated($notification));
        }
        
        broadcast(new \App\Events\AnalyticsUpdated());
    }
}
