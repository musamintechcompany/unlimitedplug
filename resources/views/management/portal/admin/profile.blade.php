<x-admin.app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Profile') }}
        </h2>
    </x-slot>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Admin Profile</h1>
        <p class="text-gray-600 mt-2">Manage your admin account settings</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Profile Information</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <p class="text-gray-900">{{ auth()->guard('admin')->user()->name }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <p class="text-gray-900">{{ auth()->guard('admin')->user()->email }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Account Created</label>
                <p class="text-gray-900">{{ auth()->guard('admin')->user()->created_at->format('M d, Y') }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Last Updated</label>
                <p class="text-gray-900">{{ auth()->guard('admin')->user()->updated_at->format('M d, Y') }}</p>
            </div>
        </div>
        
        <div class="mt-6 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition-colors">
                Back to Dashboard
            </a>
        </div>
    </div>
</div>
</x-admin.app-layout>