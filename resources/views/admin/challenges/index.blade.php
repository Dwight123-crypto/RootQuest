<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Manage Challenges
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Add Challenge Form -->
            <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-200 mb-4">Add New Challenge</h3>
                <form action="{{ route('admin.challenges.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Title</label>
                            <input type="text" name="title" value="{{ old('title') }}"
                                   class="w-full bg-gray-700 border-gray-600 text-gray-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                            @error('title') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Category</label>
                            <select name="category_id"
                                    class="w-full bg-gray-700 border-gray-600 text-gray-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                    required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-400 mb-1">Description</label>
                        <textarea name="description" rows="4"
                                  class="w-full bg-gray-700 border-gray-600 text-gray-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                  required>{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Flag</label>
                            <input type="text" name="flag" value="{{ old('flag') }}"
                                   placeholder="FLAG{...}"
                                   class="w-full bg-gray-700 border-gray-600 text-gray-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                            @error('flag') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Points</label>
                            <input type="number" name="points" value="{{ old('points', 100) }}" min="1"
                                   class="w-full bg-gray-700 border-gray-600 text-gray-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                            @error('points') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Challenge File (optional)</label>
                            <input type="file" name="challenge_file"
                                   class="w-full bg-gray-700 border-gray-600 text-gray-200 rounded-md file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-indigo-600 file:text-white">
                            @error('challenge_file') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">
                        Create Challenge
                    </button>
                </form>
            </div>

            <!-- Add Hint Form -->
            <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-200 mb-4">Add Hint to Challenge</h3>
                <form action="{{ route('admin.hints.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-1">
                            <label class="block text-sm font-medium text-gray-400 mb-1">Challenge</label>
                            <select name="challenge_id"
                                    class="w-full bg-gray-700 border-gray-600 text-gray-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                    required>
                                <option value="">Select a challenge</option>
                                @foreach($challenges as $challenge)
                                    <option value="{{ $challenge->id }}">{{ $challenge->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-400 mb-1">Hint Content</label>
                            <input type="text" name="content"
                                   class="w-full bg-gray-700 border-gray-600 text-gray-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Cost (points)</label>
                            <div class="flex gap-2">
                                <input type="number" name="cost" value="50" min="0"
                                       class="flex-1 bg-gray-700 border-gray-600 text-gray-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                       required>
                                <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700">
                                    Add
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Challenges List -->
            <div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Title</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Category</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-300 uppercase">Points</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-300 uppercase">Hints</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-300 uppercase">File</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-300 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($challenges as $challenge)
                            <tr>
                                <td class="px-4 py-3 text-gray-200">{{ $challenge->title }}</td>
                                <td class="px-4 py-3 text-gray-400">{{ $challenge->category->name }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="bg-indigo-600 text-white text-sm px-2 py-1 rounded">{{ $challenge->points }}</span>
                                </td>
                                <td class="px-4 py-3 text-center text-gray-400">{{ $challenge->hints->count() }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($challenge->hasFile())
                                        <span class="text-green-400">Yes</span>
                                    @else
                                        <span class="text-gray-500">No</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <form action="{{ route('admin.challenges.destroy', $challenge) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this challenge?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 text-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                                    No challenges yet. Add one above!
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
