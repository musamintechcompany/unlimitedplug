<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Login - Admin Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow">
            <div>
                <h2 class="text-center text-3xl font-bold text-gray-900">Verify Your Login</h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    We've sent a 6-digit verification code to your email
                </p>
            </div>
            
            <form method="POST" action="{{ route('admin.verify.code') }}" class="mt-8 space-y-6">
                @csrf
                
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded">
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Verification Code</label>
                    <input type="text" name="code" id="code" required maxlength="6" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg text-center text-2xl tracking-widest focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="000000" autofocus>
                </div>
                
                <button type="submit" 
                        class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 font-medium transition">
                    Verify & Login
                </button>
                
                <div class="text-center">
                    <a href="{{ route('admin.login') }}" class="text-sm text-blue-600 hover:underline">
                        Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Auto-submit when 6 digits entered
        document.getElementById('code').addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '');
            if (this.value.length === 6) {
                this.form.submit();
            }
        });
    </script>
</body>
</html>
