<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Manage Users
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Username</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Score</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">Role</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-200">{{ $user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-400">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-green-400">{{ $user->score }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <form action="{{ route('admin.users.update-role') }}" method="POST" class="inline-flex items-center gap-2">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <select name="is_admin" onchange="this.form.submit()"
                                                class="bg-gray-700 border-gray-600 text-gray-200 text-sm rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="0" {{ !$user->is_admin ? 'selected' : '' }}>User</option>
                                            <option value="1" {{ $user->is_admin ? 'selected' : '' }}>Admin</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-gray-300">&larr; Back to Dashboard</a>
            </div>
        </div>
    </div>
</x-app-layout>
