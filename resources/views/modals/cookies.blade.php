<div x-data="{ show: !localStorage.getItem('cookies_accepted') }" 
     x-show="show" 
     x-cloak
     class="fixed bottom-0 left-0 right-0 z-50 p-4 md:p-6"
     style="display: none;">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-2xl border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">🍪 We Use Cookies</h3>
                <p class="text-sm text-gray-600">
                    This site uses cookies to enhance your browsing experience, personalize content, and analyze our traffic. 
                    By clicking "Accept", you consent to our use of cookies. 
                    <a href="{{ route('policy') }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 underline">Learn more</a>
                </p>
            </div>
            <div class="flex gap-3 w-full md:w-auto">
                <button @click="localStorage.setItem('cookies_accepted', 'true'); show = false" 
                        class="flex-1 md:flex-none px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                    Accept
                </button>
                <button @click="localStorage.setItem('cookies_accepted', 'true'); show = false" 
                        class="flex-1 md:flex-none px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                    Decline
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
