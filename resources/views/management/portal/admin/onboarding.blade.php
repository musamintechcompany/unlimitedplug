<x-admin.guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-black bg-clip-text text-transparent">
            Admin Onboarding
        </h2>
        <p class="text-gray-600 mt-2">Create your super admin account to get started</p>
    </div>

    <!-- Welcome Message -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <h3 class="text-sm font-semibold text-blue-800 mb-1">Welcome to {{ config('app.name') }}!</h3>
        <p class="text-blue-700 text-xs">This is a one-time setup process that will be disabled after completion.</p>
    </div>

    <form method="POST" action="{{ route('admin.onboarding.store') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation"
                            required autocomplete="new-password" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="w-full justify-center">
                {{ __('Create Super Admin Account') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Security Note -->
    <div class="mt-4 text-center">
        <p class="text-xs text-gray-500">
            🔒 This page will be automatically disabled after setup is complete.
        </p>
    </div>
</x-admin.guest-layout>