<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>URL Shortener</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-zinc-50 dark:bg-slate-900">
        <div class="min-h-screen flex flex-col">
            <!-- Navigation -->
            <nav class="bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">URL Shortener</h1>
                        </div>
                        <div class="flex items-center space-x-4">
                            <x-dark-mode-toggle />
                            @auth
                                <a href="{{ route('dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">Log in</a>
                                <a href="{{ route('register') }}" class="bg-emerald-600 text-white hover:bg-emerald-700 px-4 py-2 rounded-md text-sm font-medium">Register</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl w-full space-y-8 mt-12">
                    <div class="text-center">
                        <h2 class="text-4xl font-extrabold text-emerald-600 dark:text-emerald-400">
                            Shorten Your URLs
                        </h2>
                        <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">
                            Create short, memorable links in seconds
                        </p>
                    </div>

                    <!-- URL Shortener / QR Generator -->
                    <div class="bg-white dark:bg-slate-800 shadow-lg rounded-lg p-8">
                        <!-- Mode Toggle -->
                        <div class="flex justify-center mb-6">
                            <div class="inline-flex rounded-lg border border-gray-200 dark:border-slate-600 p-1 bg-gray-100 dark:bg-slate-700">
                                <button type="button" id="btnShorten" onclick="switchMode('shorten')"
                                    class="px-4 py-2 text-sm font-medium rounded-md transition-colors bg-emerald-600 text-white">
                                    Shorten URL
                                </button>
                                <button type="button" id="btnQR" onclick="switchMode('qr')"
                                    class="px-4 py-2 text-sm font-medium rounded-md transition-colors text-gray-700 dark:text-gray-300">
                                    Generate QR
                                </button>
                            </div>
                        </div>

                        <x-flash-message />

                        @guest
                            <div id="guestNotice" class="mb-6 bg-blue-50 dark:bg-blue-900/50 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                                <p class="text-blue-800 dark:text-blue-200 text-sm">
                                    <a href="{{ route('login') }}" class="font-medium underline">Log in</a> or 
                                    <a href="{{ route('register') }}" class="font-medium underline">create an account</a> to track your shortened URLs
                                </p>
                            </div>
                        @endguest

                        <!-- Shorten URL Form -->
                        <form method="POST" action="{{ route('urls.store') }}" id="shortenForm" class="space-y-6">
                            @csrf
                            
                            <div>
                                <label for="original_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Enter your URL or IP address
                                </label>
                                <input type="text" 
                                    name="original_url" 
                                    id="original_url" 
                                    required
                                    placeholder="example.com or 192.168.1.1"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:bg-slate-700 dark:text-white"
                                    value="{{ old('original_url') }}">
                                @error('original_url')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="custom_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Custom short code <span class="text-gray-400">(optional)</span>
                                </label>
                                <div class="flex items-center">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm mr-2">{{ url('/') }}/</span>
                                    <input type="text" 
                                        name="custom_code" 
                                        id="custom_code" 
                                        placeholder="my-link"
                                        class="flex-1 px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:bg-slate-700 dark:text-white"
                                        value="{{ old('custom_code') }}">
                                </div>
                                @error('custom_code')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" 
                                class="w-full bg-emerald-600 text-white py-3 px-6 rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 font-semibold text-lg transition-colors">
                                Shorten URL
                            </button>
                        </form>

                        <!-- QR Code Form -->
                        <div id="qrForm" class="space-y-6 hidden">
                            <div>
                                <label for="qr_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Enter your URL or IP address
                                </label>
                                <input type="text" 
                                    id="qr_url" 
                                    placeholder="example.com or 192.168.1.1"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:bg-slate-700 dark:text-white">
                            </div>

                            <button type="button" onclick="generateQR()"
                                class="w-full bg-emerald-600 text-white py-3 px-6 rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 font-semibold text-lg transition-colors">
                                Generate QR Code
                            </button>

                            <div id="qrResult" class="hidden">
                                <div class="flex flex-col items-center p-4 bg-gray-50 dark:bg-slate-900 rounded-lg">
                                    <img id="qrImage" src="" alt="QR Code" class="rounded-lg border border-gray-200 dark:border-gray-600">
                                    <p id="qrLabel" class="mt-3 text-sm text-gray-600 dark:text-gray-400 text-center break-all"></p>
                                    <a id="qrDownload" href="" download="qrcode.png" 
                                        class="mt-3 px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 text-sm font-medium">
                                        Download QR Code
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                        <div class="text-center p-6">
                            <div class="text-emerald-600 dark:text-emerald-400 mb-3">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Fast & Simple</h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">Shorten URLs instantly with just one click</p>
                        </div>
                        <div class="text-center p-6">
                            <div class="text-emerald-600 dark:text-emerald-400 mb-3">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Track Clicks</h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">Monitor performance with click analytics</p>
                        </div>
                        <div class="text-center p-6">
                            <div class="text-emerald-600 dark:text-emerald-400 mb-3">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Secure</h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">Your URLs are safe and private</p>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white dark:bg-slate-800 border-t border-gray-200 dark:border-slate-700 mt-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <p class="text-center text-gray-600 dark:text-gray-400 text-sm">
                        &copy; {{ date('Y') }} URL Shortener. All rights reserved.
                    </p>
                </div>
            </footer>
        </div>

        <script>
            function switchMode(mode) {
                const isShorten = mode === 'shorten';
                const btnShorten = document.getElementById('btnShorten');
                const btnQR = document.getElementById('btnQR');
                
                [btnShorten, btnQR].forEach((btn, i) => {
                    const active = (i === 0) === isShorten;
                    btn.classList.toggle('bg-emerald-600', active);
                    btn.classList.toggle('text-white', active);
                    btn.classList.toggle('text-gray-700', !active);
                    btn.classList.toggle('dark:text-gray-300', !active);
                });
                
                document.getElementById('shortenForm').classList.toggle('hidden', !isShorten);
                document.getElementById('qrForm').classList.toggle('hidden', isShorten);
                document.getElementById('guestNotice')?.classList.toggle('hidden', !isShorten);
            }

            function generateQR() {
                let url = document.getElementById('qr_url').value.trim();
                if (!url) return alert('Please enter a URL or IP address');
                if (!url.match(/^https?:\/\//i)) url = 'http://' + url;
                
                const encoded = encodeURIComponent(url);
                document.getElementById('qrImage').src = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encoded;
                document.getElementById('qrLabel').textContent = url;
                document.getElementById('qrDownload').href = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&format=png&data=' + encoded;
                document.getElementById('qrResult').classList.remove('hidden');
            }
        </script>
    </body>
</html>
