<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::latest();
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }
        
        $users = $query->paginate(20)->withQueryString();
        return view('management.portal.admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['products', 'orders.orderItems.product']);
        return view('management.portal.admin.users.show', compact('user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status' => 'active',
            'email_verified_at' => now(),
            'created_by' => [
                'type' => 'admin',
                'name' => auth()->guard('admin')->user()->name,
                'email' => auth()->guard('admin')->user()->email,
            ],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'status' => 'required|in:pending,active,suspended,blocked',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        $user->update([
            'deleted_by' => [
                'type' => 'admin',
                'id' => auth()->guard('admin')->id(),
                'name' => auth()->guard('admin')->user()->name,
                'deleted_at' => now()->toDateTimeString()
            ]
        ]);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }
    
    public function deleted()
    {
        $users = User::onlyTrashed()->paginate(20);
        return view('management.portal.admin.users.deleted', compact('users'));
    }
    
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        
        return back()->with('success', 'User restored successfully');
    }
    
    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();
        
        return back()->with('success', 'User permanently deleted');
    }
}