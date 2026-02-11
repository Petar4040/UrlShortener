<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - URL Shortener</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-50 dark:bg-slate-900">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="/" class="text-2xl font-bold text-gray-900 dark:text-white">URL Shortener</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="/" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">Home</a>
                        <a href="{{ route('login') }}" class="bg-emerald-600 text-white hover:bg-emerald-700 px-4 py-2 rounded-md text-sm font-medium">Log in</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-2xl w-full space-y-8">
                <div class="text-center">
                    <h2 class="text-4xl font-extrabold text-emerald-600 dark:text-emerald-400">Create Account</h2>
                    <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">
                        Join us and start shortening URLs
                    </p>
                </div>

                <div class="bg-white dark:bg-slate-800 shadow-lg rounded-lg p-8">
                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name</label>
                            <input 
                                id="name" 
                                name="name" 
                                type="text" 
                                required 
                                autofocus 
                                autocomplete="name"
                                value="{{ old('name') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:bg-slate-700 dark:text-white text-sm"
                                placeholder="Enter your name"
                            >
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                required 
                                autocomplete="username"
                                value="{{ old('email') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:bg-slate-700 dark:text-white text-sm"
                                placeholder="Enter your email"
                            >
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                required 
                                autocomplete="new-password"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:bg-slate-700 dark:text-white text-sm"
                                placeholder="Create a password"
                            >
                            @error('password')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm Password</label>
                            <input 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                type="password" 
                                required 
                                autocomplete="new-password"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:bg-slate-700 dark:text-white text-sm"
                                placeholder="Confirm your password"
                            >
                            @error('password_confirmation')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <button 
                            type="submit"
                            class="w-full bg-emerald-600 text-white py-2 px-4 rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 font-medium text-sm transition-colors"
                        >
                            Create Account
                        </button>

                        <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-500 font-medium">
                                Sign in
                            </a>
                        </p>
                    </form>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white dark:bg-slate-800 border-t border-gray-200 dark:border-slate-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <p class="text-center text-gray-600 dark:text-gray-400 text-sm">
                    &copy; {{ date('Y') }} URL Shortener. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</body>
</html>
