<x-admin.app-layout>
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Reviews Management</h1>
            <p class="text-gray-600">Approve or delete product reviews</p>
        </div>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            @if($reviews->isEmpty())
                <div class="p-8 text-center text-gray-500">
                    No reviews yet
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Images</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($reviews as $review)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $review->reviewer ? $review->reviewer->name : 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $review->reviewer ? $review->reviewer->email : 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $review->reviewable ? $review->reviewable->name : 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="text-lg {{ $i <= $review->review_data['rating'] ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                            @endfor
                                            <span class="ml-2 text-sm text-gray-600">({{ $review->review_data['rating'] }})</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs truncate">
                                            {{ $review->review_data['comment'] ?? 'No comment' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if(!empty($review->review_data['images']))
                                            <div class="flex gap-1">
                                                @foreach(array_slice($review->review_data['images'], 0, 3) as $image)
                                                    <img src="{{ asset('storage/' . $image) }}" alt="Review image" class="w-12 h-12 object-cover rounded">
                                                @endforeach
                                                @if(count($review->review_data['images']) > 3)
                                                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-600">
                                                        +{{ count($review->review_data['images']) - 3 }}
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-400">No images</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($review->is_approved)
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Approved</span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $review->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex gap-2">
                                            @if(!$review->is_approved)
                                                <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-green-600 hover:text-green-800 font-medium">
                                                        Approve
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Delete this review?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-4 border-t">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin.app-layout>
