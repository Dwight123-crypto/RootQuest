<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Manage Categories
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Add Category Form -->
            <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-200 mb-4">Add New Category</h3>
                <form action="{{ route('admin.categories.store') }}" method="POST" class="flex gap-4">
                    @csrf
                    <input type="text" name="name"
                           placeholder="Category name (e.g., Web, Crypto, Pwn)"
                           class="flex-1 bg-gray-700 border-gray-600 text-gray-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                           required>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">
                        Add Category
                    </button>
                </form>
                @error('name')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Categories List -->
            <div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Challenges</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($categories as $category)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-200">{{ $category->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-gray-400">{{ $category->challenges->count() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-center text-gray-500">
                                    No categories yet. Add one above!
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
