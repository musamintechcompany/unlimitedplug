<x-admin.app-layout>
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Deleted Users</h1>
            <p class="text-gray-600">Restore or permanently delete user accounts</p>
        </div>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            @if($users->isEmpty())
                <div class="p-8 text-center text-gray-500">
                    No deleted users
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deleted By</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deleted At</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($user->deleted_by)
                                            <div class="text-sm text-gray-900">
                                                @if($user->deleted_by['type'] === 'admin')
                                                    <span class="text-red-600 font-medium">Admin:</span> {{ $user->deleted_by['name'] }}
                                                @else
                                                    <span class="text-blue-600 font-medium">Self</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400">Unknown</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->deleted_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex gap-2">
                                            <form action="{{ route('admin.users.deleted.restore', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-800 font-medium">
                                                    Restore
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.users.deleted.force-delete', $user->id) }}" method="POST" onsubmit="return confirm('Permanently delete this user? This cannot be undone!')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                                    Delete Forever
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
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin.app-layout>
