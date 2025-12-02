<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Enter your email address and we will send you a verification code to reset your password.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div x-data="{ 
        step: 1, 
        loading: false, 
        email: '', 
        message: '',
        error: '',
        async sendCode() {
            this.loading = true;
            this.error = '';
            try {
                const response = await fetch('{{ route('password.send-code') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({ email: this.email })
                });
                const data = await response.json();
                if (response.ok) {
                    this.message = data.message;
                    this.step = 2;
                } else {
                    this.error = data.message || 'Email not found in our system';
                }
            } catch (error) {
                this.error = 'Something went wrong. Please try again.';
            }
            this.loading = false;
        }
    }">
        <!-- Step 1: Email Address -->
        <div x-show="step === 1">
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input x-model="email" id="email" class="block mt-1 w-full" type="email" required autofocus />
                <div x-show="error" x-text="error" class="text-sm text-red-600 mt-2"></div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <button type="button" @click="sendCode()" :disabled="loading" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50">
                    <span x-show="!loading">{{ __('Send Verification Code') }}</span>
                    <span x-show="loading">Sending...</span>
                </button>
            </div>
        </div>

        <!-- Step 2: Verification Code and New Password -->
        <form method="POST" action="{{ route('password.reset-with-code') }}" x-show="step === 2" style="display: none;">
            @csrf
            <input type="hidden" name="email" x-model="email">
            
            <div x-show="message" x-text="message" class="mb-4 text-sm text-green-600"></div>
            
            <div class="mb-4">
                <x-input-label for="code" :value="__('Verification Code')" />
                <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" placeholder="Enter 6-digit code" maxlength="6" required />
                <x-input-error :messages="$errors->get('code')" class="mt-2" />
                <p class="text-sm text-gray-500 mt-1">Check your email for the verification code</p>
            </div>

            <div class="mb-4">
                <x-input-label for="password" :value="__('New Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between mt-4">
                <button type="button" @click="step = 1" class="text-sm text-gray-600 hover:text-gray-900">
                    {{ __('Back to Email') }}
                </button>
                <x-primary-button>
                    {{ __('Reset Password') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
