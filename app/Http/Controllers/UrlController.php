<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UrlController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $urls = auth()->user()->urls()->latest()->paginate(10);
        $totalClicks = auth()->user()->urls()->sum('clicks');
        $avgClicks = auth()->user()->urls()->avg('clicks') ?? 0;
        
        return view('dashboard', compact('urls', 'totalClicks', 'avgClicks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'original_url' => 'required|string|max:2048',
            'custom_code' => 'nullable|string|alpha_num|min:3|max:20|unique:urls,short_code',
        ]);

        $createdUrl = Url::create([
            'original_url' => $this->normalizeUrl($request->original_url),
            'short_code' => $request->custom_code ?: $this->generateUniqueShortCode(),
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'URL shortened successfully!')->with('url', $createdUrl);
    }

    public function show(string $shortCode)
    {
        $url = Url::where('short_code', $shortCode)->firstOrFail();
        
        $url->incrementClicks();
        
        return redirect($url->original_url);
    }

    public function destroy(Url $url)
    {
        $this->authorize('delete', $url);
        
        $url->delete();
        
        return back()->with('success', 'URL deleted successfully!');
    }

    public function update(Request $request, Url $url)
    {
        $this->authorize('update', $url);
        
        $request->validate([
            'original_url' => 'required|string|max:2048',
        ]);

        $url->update(['original_url' => $this->normalizeUrl($request->original_url)]);
        
        return back()->with('success', 'URL updated successfully!');
    }

    public function admin()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $urls = Url::with('user')->latest()->paginate(20);
        $stats = [
            'total_urls' => Url::count(),
            'total_clicks' => Url::sum('clicks'),
            'total_users' => \App\Models\User::count(),
        ];

        return view('admin.dashboard', compact('urls', 'stats'));
    }

    private function generateUniqueShortCode(): string
    {
        do {
            $shortCode = Str::random(6);
        } while (Url::where('short_code', $shortCode)->exists());

        return $shortCode;
    }

    private function normalizeUrl(string $url): string
    {
        return preg_match("~^(?:f|ht)tps?://~i", $url) ? $url : "http://{$url}";
    }
}
