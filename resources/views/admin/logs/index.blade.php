<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Activity Logs
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Time</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">User</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Action</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($logs as $log)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-gray-400 text-sm">
                                    {{ $log->created_at->format('M d, H:i:s') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-gray-200">
                                    {{ $log->causer?->name ?? 'System' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded
                                        @if(str_contains($log->description, 'solved')) bg-green-900 text-green-300
                                        @elseif(str_contains($log->description, 'Failed')) bg-red-900 text-red-300
                                        @elseif(str_contains($log->description, 'Unauthorized')) bg-yellow-900 text-yellow-300
                                        @else bg-gray-700 text-gray-300
                                        @endif">
                                        {{ $log->description }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-400 text-sm max-w-xs truncate">
                                    @if($log->properties->isNotEmpty())
                                        {{ json_encode($log->properties->toArray()) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-gray-500">
                                    No activity logs yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $logs->links() }}
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-gray-300">&larr; Back to Dashboard</a>
            </div>
        </div>
    </div>
</x-app-layout>
