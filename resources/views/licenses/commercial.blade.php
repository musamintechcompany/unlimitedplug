<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="text-5xl">💼</span>
                        <h1 class="text-3xl font-bold text-gray-900">Commercial License</h1>
                    </div>
                    
                    <p class="text-lg text-gray-600 mb-8">
                        Full commercial rights. Can resell and redistribute.
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
                            <li>✅ Full commercial usage rights</li>
                            <li>✅ Can resell as part of your product</li>
                            <li>✅ Can redistribute with modifications</li>
                            <li>✅ Modify and customize as needed</li>
                        </ul>
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 mb-6">
                        <h2 class="text-xl font-bold text-yellow-900 mb-4">⚠️ Important Notes</h2>
                        <ul class="space-y-2 text-gray-700">
                            <li>• You can resell the product as part of your own product</li>
                            <li>• You can redistribute with modifications</li>
                            <li>• You cannot claim original authorship</li>
                            <li>• Attribution to original creator is appreciated but not required</li>
                        </ul>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 mb-6">
                        <h2 class="text-xl font-bold text-blue-900 mb-4">📖 Example</h2>
                        <div class="text-gray-700 space-y-3">
                            <p class="text-green-700">✅ <strong>OK:</strong> Use it in unlimited projects</p>
                            <p class="text-green-700">✅ <strong>OK:</strong> Resell as part of your product/service</p>
                            <p class="text-green-700">✅ <strong>OK:</strong> Redistribute modified versions</p>
                            <p class="text-green-700">✅ <strong>OK:</strong> Include in SaaS products</p>
                        </div>
                    </div>

                    <div class="mt-8 p-6 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2">Compare Licenses</h3>
                        <div class="flex gap-3">
                            <a href="{{ route('license.regular') }}" class="text-blue-600 hover:underline">View Regular License →</a>
                            <a href="{{ route('license.extended') }}" class="text-blue-600 hover:underline">View Extended License →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
