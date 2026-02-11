<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('My URLs') }}
            </h2>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 text-sm font-medium">
                    Admin Dashboard
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total URLs</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $urls->total() }}</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Clicks</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalClicks }}</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Average Clicks</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ $urls->total() > 0 ? number_format($avgClicks, 1) : '0' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- URL Shortener Form -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Create New Short URL</h3>
                    
                    <x-flash-message />

                    <form method="POST" action="{{ route('urls.store') }}" class="space-y-3">
                        @csrf
                        <div class="flex gap-3">
                            <input type="text" 
                                name="original_url" 
                                placeholder="example.com or 192.168.1.1" 
                                required
                                class="flex-1 px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 dark:bg-slate-700 dark:text-white">
                            <input type="text" 
                                name="custom_code" 
                                placeholder="custom-code (optional)" 
                                class="w-40 px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 dark:bg-slate-700 dark:text-white">
                            <button type="submit" 
                                class="bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700 font-medium">
                                Shorten
                            </button>
                        </div>
                        @error('custom_code')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </form>
                    @error('original_url')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- URLs List -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Your Shortened URLs</h3>
                    
                    @if($urls->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No URLs yet</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a short URL above.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-slate-900">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Original URL</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Short URL</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">QR Code</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Clicks</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                                    @foreach($urls as $url)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-white truncate max-w-md">
                                                    <a href="{{ $url->original_url }}" target="_blank" class="hover:text-emerald-600">
                                                        {{ Str::limit($url->original_url, 50) }}
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ $url->getShortUrl() }}" target="_blank" 
                                                        class="text-sm text-emerald-600 dark:text-emerald-400 hover:text-emerald-800">
                                                        {{ $url->short_code }}
                                                    </a>
                                                    <button onclick="copyUrl('{{ $url->getShortUrl() }}')" 
                                                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <button onclick="showQR('{{ urlencode($url->getShortUrl()) }}', '{{ $url->short_code }}')" 
                                                        class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h2M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                                        </svg>
                                                        View QR
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    {{ $url->clicks }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $url->created_at->diffForHumans() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <div class="flex items-center gap-3">
                                                    <button onclick="openEditModal({{ $url->id }}, '{{ addslashes($url->original_url) }}')" class="text-emerald-600 hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300">
                                                        Edit
                                                    </button>
                                                    <form method="POST" action="{{ route('urls.destroy', $url) }}" onsubmit="return confirm('Are you sure you want to delete this URL?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $urls->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-slate-800 rounded-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Edit URL</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PATCH')
                <input type="text" name="original_url" id="editUrl" 
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 dark:bg-slate-700 dark:text-white mb-4">
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="toggleModal('editModal', false)" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- QR Modal -->
    <div id="qrModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-slate-800 rounded-lg p-6 w-full max-w-sm mx-4 text-center">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">QR Code</h3>
            <img id="qrModalImage" src="" alt="QR Code" class="mx-auto rounded-lg border border-gray-200 dark:border-slate-600 mb-4">
            <a id="qrModalDownload" href="" download="" class="inline-block px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-sm">Download</a>
            <button onclick="toggleModal('qrModal', false)" class="block w-full mt-3 px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">Close</button>
        </div>
    </div>

    <script>
        function toggleModal(id, show) {
            document.getElementById(id).classList.toggle('hidden', !show);
            document.getElementById(id).classList.toggle('flex', show);
        }

        function openEditModal(id, url) {
            document.getElementById('editForm').action = '/urls/' + id;
            document.getElementById('editUrl').value = url;
            toggleModal('editModal', true);
        }

        function showQR(encodedUrl, shortCode) {
            const qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodedUrl;
            document.getElementById('qrModalImage').src = qrUrl;
            document.getElementById('qrModalDownload').href = qrUrl.replace('200x200', '300x300') + '&format=png';
            document.getElementById('qrModalDownload').download = 'qr-' + shortCode + '.png';
            toggleModal('qrModal', true);
        }
    </script>
</x-app-layout>
