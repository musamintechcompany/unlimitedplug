<!-- Login Modal -->
<div x-data="{ showLogin: {{ $errors->any() ? 'true' : 'false' }} }" @open-login-modal.window="showLogin = true; document.body.style.overflow = 'hidden'" x-init="if (showLogin) document.body.style.overflow = 'hidden'" x-cloak>
    <!-- Modal Overlay -->
    <div x-show="showLogin" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-50 z-50" @click="showLogin = false; document.body.style.overflow = 'auto'" style="display: none;"></div>
    
    <!-- Modal Content -->
    <div x-show="showLogin" x-transition:enter="transform transition ease-out duration-300" x-transition:enter-start="scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100" x-transition:leave="transform transition ease-in duration-300" x-transition:leave-start="scale-100 opacity-100" x-transition:leave-end="scale-95 opacity-0" class="fixed inset-0 flex items-center justify-center z-50 p-4" @click="showLogin = false; document.body.style.overflow = 'auto'" style="display: none;">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md" @click.stop>
            <!-- Modal Header -->
            <div class="p-6">
                <div class="flex items-center justify-center mb-4">
                    <x-application-logo class="w-12 h-12 fill-current text-gray-500" />
                    <span class="ml-2 text-xl font-bold bg-gradient-to-r from-blue-600 to-black bg-clip-text text-transparent">{{ config('app.name', 'Laravel') }}</span>
                </div>
                <h3 class="text-sm text-gray-600 dark:text-gray-400 text-center">Login your {{ config('app.name') }} account</h3>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <!-- Email Address -->
                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="block mb-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif

                        <x-primary-button class="ms-3">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>
                </form>
                
                <!-- Register Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-500 font-medium">Sign up</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>