<x-admin.app-layout>
    <div class="p-6 max-w-4xl">
        <h1 class="text-2xl font-bold mb-6">{{ isset($coupon) ? 'Edit' : 'Create' }} Coupon</h1>

        <form action="{{ isset($coupon) ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
            @csrf
            @if(isset($coupon))
                @method('PUT')
            @endif

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-2">Coupon Code *</label>
                    <input type="text" name="code" value="{{ old('code', isset($coupon) ? $coupon->code : '') }}" required class="w-full border rounded px-3 py-2 uppercase" placeholder="SAVE20">
                    @error('code')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Type *</label>
                    <select name="type" required class="w-full border rounded px-3 py-2">
                        <option value="percentage" {{ old('type', isset($coupon) ? $coupon->type : '') === 'percentage' ? 'selected' : '' }}>Percentage</option>
                        <option value="fixed" {{ old('type', isset($coupon) ? $coupon->type : '') === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Value *</label>
                    <input type="number" step="0.01" name="value" value="{{ old('value', isset($coupon) ? $coupon->value : '') }}" required class="w-full border rounded px-3 py-2">
                    @error('value')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Minimum Purchase</label>
                    <input type="number" step="0.01" name="min_purchase" value="{{ old('min_purchase', isset($coupon) ? $coupon->min_purchase : '') }}" class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Max Uses (Total)</label>
                    <input type="number" name="max_uses" value="{{ old('max_uses', isset($coupon) ? $coupon->max_uses : '') }}" class="w-full border rounded px-3 py-2" placeholder="Unlimited">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Max Uses Per User *</label>
                    <input type="number" name="max_uses_per_user" value="{{ old('max_uses_per_user', isset($coupon) ? $coupon->max_uses_per_user : 1) }}" required class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Valid From</label>
                    <input type="datetime-local" name="valid_from" value="{{ old('valid_from', isset($coupon) && $coupon->valid_from ? $coupon->valid_from->format('Y-m-d\TH:i') : '') }}" class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Valid Until</label>
                    <input type="datetime-local" name="valid_until" value="{{ old('valid_until', isset($coupon) && $coupon->valid_until ? $coupon->valid_until->format('Y-m-d\TH:i') : '') }}" class="w-full border rounded px-3 py-2">
                </div>
            </div>

            <div class="mt-6">
                <label class="flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', isset($coupon) ? $coupon->is_active : true) ? 'checked' : '' }} class="mr-2">
                    <span class="text-sm font-medium">Active</span>
                </label>
            </div>

            <div class="flex gap-4 mt-6">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    {{ isset($coupon) ? 'Update' : 'Create' }} Coupon
                </button>
                <a href="{{ route('admin.coupons.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-admin.app-layout>
