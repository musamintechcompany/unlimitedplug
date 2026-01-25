<x-guest1-layout>
    <x-slot name="title">Newsletter Confirmation</x-slot>
    
    <section class="py-20 bg-gray-50">
        <div class="max-w-md mx-auto px-4 text-center">
            @if($success)
                <div class="bg-green-50 border border-green-200 rounded-lg p-8">
                    <svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Success!</h1>
                    <p class="text-gray-600">{{ $message }}</p>
                    <a href="{{ route('home') }}" class="inline-block mt-6 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                        Go to Homepage
                    </a>
                </div>
            @else
                <div class="bg-red-50 border border-red-200 rounded-lg p-8">
                    <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Error</h1>
                    <p class="text-gray-600">{{ $message }}</p>
                    <a href="{{ route('home') }}" class="inline-block mt-6 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                        Go to Homepage
                    </a>
                </div>
            @endif
        </div>
    </section>
</x-guest1-layout>
