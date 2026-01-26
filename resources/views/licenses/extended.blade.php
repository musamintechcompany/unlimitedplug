<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="text-5xl">⭐</span>
                        <h1 class="text-3xl font-bold text-gray-900">Extended License</h1>
                    </div>
                    
                    <p class="text-lg text-gray-600 mb-8">
                        Use for unlimited projects. Can use in client work.
                    </p>

                    <div class="bg-green-50 border-l-4 border-green-500 p-6 mb-6">
                        <h2 class="text-xl font-bold text-green-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            What You CAN Do ✅
                        </h2>
                        <ul class="space-y-2 text-gray-700">
                            <li>✅ Use for UNLIMITED projects</li>
                            <li>✅ Use for personal or commercial purposes</li>
                            <li>✅ Use in multiple client projects</li>
                            <li>✅ Modify and customize as needed</li>
                        </ul>
                    </div>

                    <div class="bg-red-50 border-l-4 border-red-500 p-6 mb-6">
                        <h2 class="text-xl font-bold text-red-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            What You CANNOT Do ❌
                        </h2>
                        <ul class="space-y-2 text-gray-700">
                            <li>❌ Cannot resell or redistribute original files</li>
                            <li>❌ Cannot share download with others</li>
                        </ul>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 mb-6">
                        <h2 class="text-xl font-bold text-blue-900 mb-4">📖 Example</h2>
                        <div class="text-gray-700 space-y-3">
                            <p class="text-green-700">✅ <strong>OK:</strong> Use it in unlimited personal projects</p>
                            <p class="text-green-700">✅ <strong>OK:</strong> Use it for multiple client projects</p>
                            <p class="text-green-700">✅ <strong>OK:</strong> Build and sell websites using this product</p>
                            <p class="text-red-700">❌ <strong>NOT OK:</strong> Resell the original files</p>
                        </div>
                    </div>

                    <div class="mt-8 p-6 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2">Compare Licenses</h3>
                        <div class="flex gap-3">
                            <a href="{{ route('license.regular') }}" class="text-blue-600 hover:underline">View Regular License →</a>
                            <a href="{{ route('license.commercial') }}" class="text-blue-600 hover:underline">View Commercial License →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
