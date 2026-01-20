<x-admin.app-layout>
    <div class="max-w-7xl mx-auto">
        <h1 class="text-center text-xl font-semibold text-gray-900 mb-8">Select a product type to proceed</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach(config('product.types') as $key => $type)
                <div class="border border-gray-200 rounded-lg p-6 flex flex-col justify-between min-h-[230px]">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-base font-semibold text-gray-900">{{ $type['name'] }}</span>
                            @if($type['badge'])
                                <span class="text-[11px] font-semibold px-1.5 py-0.5 rounded bg-{{ $type['badge_color'] }}-100 text-{{ $type['badge_color'] }}-800">{{ $type['badge'] }}</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 leading-relaxed mb-5">
                            {{ $type['description'] }}
                        </p>
                    </div>
                    @if($type['enabled'])
                        <a href="{{ route('admin.products.create', ['type' => $key]) }}" class="w-full py-2.5 px-4 border border-gray-300 rounded-md bg-white text-sm font-semibold text-gray-900 hover:bg-gray-50 text-center">
                            Next
                        </a>
                    @else
                        <button class="w-full py-2.5 px-4 border border-gray-300 rounded-md bg-gray-100 text-sm font-semibold text-gray-900 hover:bg-gray-200">
                            @if($type['badge'] === 'Pro')
                                Upgrade to pro
                            @else
                                Coming Soon
                            @endif
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-admin.app-layout>
