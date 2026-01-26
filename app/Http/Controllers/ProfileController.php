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
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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
