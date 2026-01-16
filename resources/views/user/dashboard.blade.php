<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="pb-6 border-b border-gray-200">
                <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="mt-2 text-gray-600">You have <span class="font-semibold text-blue-600">{{ $totalPurchases }}</span> {{ Str::plural('purchase', $totalPurchases) }}</p>
            </div>

            <!-- Quick Actions -->
            <div class="py-8 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
                <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide">
                    <!-- Browse Marketplace -->
                    <a href="{{ route('marketplace') }}" class="flex-shrink-0 w-40 p-6 border border-gray-200 rounded-lg hover:border-blue-500 hover:shadow-md transition">
                        <svg class="w-8 h-8 text-blue-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <h3 class="font-semibold text-gray-900 text-sm">Browse Marketplace</h3>
                    </a>

                    <!-- Software -->
                    <a href="{{ route('software') }}" class="flex-shrink-0 w-40 p-6 border border-gray-200 rounded-lg hover:border-blue-500 hover:shadow-md transition">
                        <svg class="w-8 h-8 text-green-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                        </svg>
                        <h3 class="font-semibold text-gray-900 text-sm">Software</h3>
                    </a>

                    <!-- My Purchases -->
                    <a href="{{ route('purchases.index') }}" class="flex-shrink-0 w-40 p-6 border border-gray-200 rounded-lg hover:border-blue-500 hover:shadow-md transition">
                        <svg class="w-8 h-8 text-purple-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                        </svg>
                        <h3 class="font-semibold text-gray-900 text-sm">My Purchases</h3>
                    </a>

                    <!-- How It Works -->
                    <a href="{{ route('how-it-works') }}" class="flex-shrink-0 w-40 p-6 border border-gray-200 rounded-lg hover:border-blue-500 hover:shadow-md transition">
                        <svg class="w-8 h-8 text-orange-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="font-semibold text-gray-900 text-sm">How It Works</h3>
                    </a>

                    <!-- Account Settings -->
                    <a href="{{ route('profile.edit') }}" class="flex-shrink-0 w-40 p-6 border border-gray-200 rounded-lg hover:border-blue-500 hover:shadow-md transition">
                        <svg class="w-8 h-8 text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <h3 class="font-semibold text-gray-900 text-sm">Settings</h3>
                    </a>
                </div>
            </div>

            <!-- Recent Purchases -->
            @if($recentPurchases->isNotEmpty())
                <div class="py-8">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Recent Purchases</h2>
                        <a href="{{ route('purchases.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All →</a>
                    </div>
                    <div class="space-y-4">
                        @foreach($recentPurchases as $order)
                            @foreach($order->items->take(1) as $item)
                                <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg hover:border-blue-500 transition">
                                    <!-- Thumbnail -->
                                    <div class="flex-shrink-0">
                                        @if($item->digitalAsset && $item->digitalAsset->banner)
                                            <img src="{{ Storage::url($item->digitalAsset->banner) }}" 
                                                 alt="{{ $item->asset_name }}" 
                                                 class="w-16 h-16 object-cover rounded border">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded border flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Details -->
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-semibold text-gray-900 truncate">{{ $item->asset_name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</p>
                                    </div>

                                    <!-- Action -->
                                    @if($item->digitalAsset)
                                        <a href="{{ route('purchases.show', $item->digitalAsset->id) }}" 
                                           class="flex-shrink-0 text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View →
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            @else
                <div class="py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <p class="mt-4 text-gray-500">No purchases yet</p>
                    <a href="{{ route('marketplace') }}" class="mt-4 inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                        Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>

    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</x-app-layout>
