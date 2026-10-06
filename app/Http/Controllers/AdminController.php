<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\BlogPost;
use App\Models\SupportedSite;
use App\Models\AnalyticsEvent;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function showLogin(Request $request)
    {
        if ($request->session()->get('admin_authenticated')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:190'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        $emailMatches = hash_equals(strtolower((string) config('services.admin.email')), strtolower($credentials['email']));
        $passwordMatches = Hash::check($credentials['password'], (string) config('services.admin.password_hash'));

        if (!$emailMatches || !$passwordMatches) {
            return back()->withInput($request->only('email'))->withErrors(['email' => 'The email or password is incorrect.']);
        }

        $request->session()->regenerate();
        $request->session()->put('admin_authenticated', true);
        $request->session()->put('admin_email', config('services.admin.email'));

        return redirect()->intended(route('admin.dashboard'));
    }

    public function dashboard(Request $request)
    {
        $today = now()->startOfDay();
        $dailyActivity = collect(range(6, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo);
            return [
                'label' => $date->format('D'),
                'date' => $date->format('Y-m-d'),
                'visitors' => AnalyticsEvent::where('event_type', 'visit')->whereDate('created_at', $date->format('Y-m-d'))->distinct('ip_address')->count('ip_address'),
                'downloads' => AnalyticsEvent::where('event_type', 'download')->whereDate('created_at', $date->format('Y-m-d'))->count(),
            ];
        });
        $chartMaximum = max(1, (int) $dailyActivity->max(function ($day) {
            return max($day['visitors'], $day['downloads']);
        }));

        return view('admin.dashboard', [
            'adminEmail' => $request->session()->get('admin_email'),
            'phpVersion' => PHP_VERSION,
            'laravelVersion' => app()->version(),
            'postCount' => BlogPost::count(),
            'publishedCount' => BlogPost::published()->count(),
            'siteCount' => SupportedSite::active()->count(),
            'totalVisitors' => AnalyticsEvent::where('event_type', 'visit')->distinct('ip_address')->count('ip_address'),
            'todayVisitors' => AnalyticsEvent::where('event_type', 'visit')->where('created_at', '>=', $today)->distinct('ip_address')->count('ip_address'),
            'totalPageViews' => AnalyticsEvent::where('event_type', 'visit')->count(),
            'totalDownloads' => AnalyticsEvent::where('event_type', 'download')->count(),
            'todayDownloads' => AnalyticsEvent::where('event_type', 'download')->where('created_at', '>=', $today)->count(),
            'recentDownloads' => AnalyticsEvent::where('event_type', 'download')->latest('created_at')->limit(15)->get(),
            'topPlatforms' => AnalyticsEvent::where('event_type', 'download')
                ->whereNotNull('platform')
                ->select('platform', DB::raw('COUNT(*) as total'))
                ->groupBy('platform')->orderByDesc('total')->limit(6)->get(),
            'dailyActivity' => $dailyActivity,
            'chartMaximum' => $chartMaximum,
        ]);
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['admin_authenticated', 'admin_email']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('status', 'You have been logged out securely.');
    }
}
