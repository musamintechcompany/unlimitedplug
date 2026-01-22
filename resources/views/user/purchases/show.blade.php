<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button & Title -->
            <div class="flex items-center space-x-4 mb-6">
                <button onclick="window.history.back()" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <div>
                    <h2 class="font-semibold text-2xl text-gray-800">
                        {{ $product->name }}
                    </h2>
                    @php
                        $currency = $orderItem->order->currency;
                        $currencySymbol = config('payment.currencies.' . $currency . '.symbol', '$');
                    @endphp
                    <p class="text-sm text-gray-500">Purchased for {{ $currencySymbol }}{{ number_format($orderItem->price, 2) }} {{ $currency }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- File Preview Section -->
                @if($product->file && count($product->file) > 0)
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-8">
                        <div class="max-w-2xl mx-auto">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center">Download Package Contents</h3>
                            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-3">
                                @foreach($product->file as $filePath)
                                    @php
                                        $fileName = basename($filePath);
                                        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                        $fileSize = Storage::exists($filePath) ? Storage::size($filePath) : 0;
                                        $fileSizeFormatted = $fileSize > 0 ? number_format($fileSize / 1048576, 2) . ' MB' : 'Unknown';
                                        
                                        // Determine file type icon and color
                                        $iconColor = 'text-gray-600';
                                        $bgColor = 'bg-gray-100';
                                        if (in_array($extension, ['zip', 'rar', '7z'])) {
                                            $iconColor = 'text-purple-600';
                                            $bgColor = 'bg-purple-100';
                                        } elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])) {
                                            $iconColor = 'text-blue-600';
                                            $bgColor = 'bg-blue-100';
                                        } elseif (in_array($extension, ['pdf'])) {
                                            $iconColor = 'text-red-600';
                                            $bgColor = 'bg-red-100';
                                        } elseif (in_array($extension, ['doc', 'docx', 'txt'])) {
                                            $iconColor = 'text-blue-700';
                                            $bgColor = 'bg-blue-100';
                                        } elseif (in_array($extension, ['mp4', 'avi', 'mov', 'wmv'])) {
                                            $iconColor = 'text-pink-600';
                                            $bgColor = 'bg-pink-100';
                                        }
                                    @endphp
                                    <div class="flex items-center gap-4 p-3 hover:bg-gray-50 rounded-lg transition">
                                        <div class="{{ $bgColor }} p-3 rounded-lg">
                                            <svg class="w-8 h-8 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-900 truncate">{{ $fileName }}</p>
                                            <p class="text-sm text-gray-500">{{ strtoupper($extension) }} • {{ $fileSizeFormatted }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <!-- File Type Display -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-100 p-12 text-center">
                        <div class="inline-block bg-white rounded-lg p-6 shadow-lg">
                            <svg class="mx-auto h-20 w-20 text-blue-600 mb-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                            </svg>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ ucfirst($product->type) }}</h3>
                            <p class="text-gray-600">Digital Asset</p>
                        </div>
                    </div>
                @endif

                <div class="p-6 space-y-6">
                    <!-- Download Stats -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">
                            You've downloaded this {{ $orderItem->download_count }} {{ Str::plural('time', $orderItem->download_count) }}
                            @if($orderItem->last_downloaded_at)
                                • Last download: {{ $orderItem->last_downloaded_at->diffForHumans() }}
                            @endif
                        </p>
                    </div>

                    <!-- License Info -->
                    @if($product->license_type)
                        @php
                            $licenseType = $product->license_type;
                            $license = config('licenses.' . $licenseType, config('licenses.regular'));
                        @endphp
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                            <p class="text-sm text-blue-900 font-semibold mb-1">{{ $license['icon'] }} {{ $license['name'] }} Included</p>
                            <p class="text-xs text-blue-800">
                                {{ $license['description'] }}
                                <a href="{{ route('license.terms') }}" class="underline font-medium">View full terms</a>
                            </p>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('download', $orderItem->id) }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition text-center font-semibold">
                            Download Now
                        </a>
                        <button onclick="document.getElementById('reviewModal').classList.remove('hidden')" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition text-center font-semibold">
                            @if($userReview)
                                Edit Your Review
                            @else
                                Rate & Review
                            @endif
                        </button>
                    </div>

                    <div class="border-t pt-6" x-data="{ open: false }">
                        <button @click="open = !open" class="w-full flex items-center justify-between text-left">
                            <h3 class="text-lg font-semibold">Product Details</h3>
                            <svg class="w-5 h-5 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" x-collapse class="mt-4 space-y-6">
                            <!-- Banner Preview -->
                            @if($product->banner)
                                <div>
                                    <h4 class="font-semibold mb-2">Preview Image</h4>
                                    <img src="{{ Storage::url($product->banner) }}" alt="{{ $product->name }}" class="w-48 h-32 object-cover rounded border">
                                </div>
                            @endif

                            <!-- Description -->
                            <div>
                                <h4 class="font-semibold mb-2">Description</h4>
                                <div class="text-gray-700 prose max-w-none">{!! $product->description !!}</div>
                            </div>

                            <!-- Features -->
                            @if($product->features)
                                <div>
                                    <h4 class="font-semibold mb-2">Features</h4>
                                    <ul class="list-disc list-inside space-y-1 text-gray-700">
                                        @foreach($product->features as $feature)
                                            <li>{{ $feature }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Requirements -->
                            @if($product->requirements)
                                <div>
                                    <h4 class="font-semibold mb-2">Requirements</h4>
                                    <div class="text-gray-700 prose max-w-none">{!! $product->requirements !!}</div>
                                </div>
                            @endif

                            <!-- Tags -->
                            @if($product->tags)
                                <div>
                                    <h4 class="font-semibold mb-2">Tags</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($product->tags as $tag)
                                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    <div id="reviewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">Rate & Review</h3>
                <button onclick="document.getElementById('reviewModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="{{ route('reviews.store', $product) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2">Rating</label>
                    <div class="flex gap-2" x-data="{ rating: {{ $userReview ? $userReview->review_data['rating'] : 0 }} }">
                        <template x-for="star in 5" :key="star">
                            <button type="button" @click="rating = star" class="text-3xl focus:outline-none">
                                <span x-text="star <= rating ? '★' : '☆'" :class="star <= rating ? 'text-yellow-400' : 'text-gray-300'"></span>
                            </button>
                        </template>
                        <input type="hidden" name="rating" x-model="rating" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2">Comment (Optional)</label>
                    <textarea name="comment" rows="4" class="w-full border border-gray-300 rounded-lg p-3 text-sm" placeholder="Share your experience with this product...">{{ $userReview ? $userReview->review_data['comment'] : '' }}</textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('reviewModal').classList.add('hidden')" class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
