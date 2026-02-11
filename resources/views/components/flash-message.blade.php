@props(['showCopyButton' => true])

@if(session('success'))
    <div class="mb-4 bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-700 rounded-lg p-4">
        <p class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</p>
        @if(session('url') && $showCopyButton)
            <div class="mt-3 flex items-center gap-3">
                <input type="text" value="{{ session('url')->getShortUrl() }}" readonly 
                    class="flex-1 px-3 py-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-md text-sm text-gray-900 dark:text-white"
                    id="shortUrl">
                <button onclick="copyToClipboard()" 
                    class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 text-sm font-medium">
                    Copy
                </button>
            </div>
        @endif
    </div>
@endif

@if(session('error'))
    <div class="mb-4 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-700 rounded-lg p-4">
        <p class="text-red-800 dark:text-red-200 font-medium">{{ session('error') }}</p>
    </div>
@endif
