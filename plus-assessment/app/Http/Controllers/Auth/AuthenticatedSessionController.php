<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Notifications\NewDeviceNotification;
use Illuminate\Notifications\Notification;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        // The user is authenticated
        $user = Auth::user();
        $userLogs = $user->logs;

        // Make HTTP request to the IPAPI endpoint
        //hard coded access key for testing urposes.
        $response = Http::get('http://api.ipapi.com/api/check', [
            'access_key' => '4e58fd769ece22a04dcfccbf2b6168d5',
        ]);

        // Extract relevant information from the IPAPI response
        $userIpLocation = $response->json();
        $userUserAgent = $request->userAgent();

        // Check if this is the first login
        $firstLogin = $userLogs->count() === 0;

        // Check if the IP and User Agent match the records in the database
        $matchingLog = $userLogs->where([
            'ip_address' => $userIpLocation['ip'],
            'user_agent' => $userUserAgent,
        ])->first();

        // If it's not the first entry and there's no matching log, send an email
        if (!$firstLogin && !$matchingLog) {
            // Send email notification
            $this->sendNewDeviceNotification($user);
        }

        // Update user log after successful login with IPAPI information
        DB::table('user_logs')->insert([
            'user_id' => $user->id,
            'ip_address' => $userIpLocation['ip'],
            'ip_location' => $userIpLocation['location']['capital'],
            'user_agent' => $userUserAgent,
            'created_at' => now(),
        ]);

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    private function sendNewDeviceNotification($user)
    {
        // Send the notification
        $user->notify(new NewDeviceNotification());
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
