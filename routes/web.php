<?php

use App\Http\Controllers\UrlController;
use Illuminate\Support\Facades\Route;

////Home page with URL shortening form
Route::get('/', function () {
    return view('welcome');
})->name('home');

////Dashboard - user's URLs with stats
Route::get('/dashboard', [UrlController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

////Admin dashboard
Route::get('/admin/dashboard', [UrlController::class, 'admin'])
    ->middleware(['auth', 'admin'])
    ->name('admin.dashboard');

////URL management routes - allow both guests and authenticated users to create
////Rate limited to 10 requests per minute to prevent spam
Route::post('/urls', [UrlController::class, 'store'])->middleware('throttle:10,1')->name('urls.store');

////Authenticated-only routes
Route::middleware('auth')->group(function () {
    Route::patch('/urls/{url}', [UrlController::class, 'update'])->name('urls.update');
    Route::delete('/urls/{url}', [UrlController::class, 'destroy'])->name('urls.destroy');
});

require __DIR__.'/auth.php';

////Short URL redirect must be last to avoid conflicts
////Match 3-20 character alphanumeric codes
Route::get('/{shortCode}', [UrlController::class, 'show'])
    ->where('shortCode', '[a-zA-Z0-9\-]{3,20}')
    ->name('urls.show');
