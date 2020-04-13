<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SessionController extends Controller
{
    /**
     * Show login screen
     */
    public function index()
    {
        return view('login');
    }

    /**
     * Generic pin session login
     */
    public function login(Request $request)
    {
        // Honeypot
        if (config('webbox.honeypot_enabled') && $request->input(config('webbox.honeypot_field')))
        {
            // Honeypot is filled
            $request->session()->put('authenticated', null);
            return view('login', [
                'status' => 'error',
                'message' => __('messages.honeypot.alert.nice')
            ]);
        }

        // Use something like env('PIN') here
        if ($request->input('session_pin') === config('webbox.authpin'))
        {
            // Do not throttle successful login attempts
            $request->session()->put('authenticated', time());
            $request->session()->put('sessionid', md5(uniqid()));
            return redirect()->route('home');
        }

        return view('login', [
            'status' => 'error',
            'message' => __('messages.token.alert')
        ]);
    }

    /**
     * Logout session cleanup
     */
    public function logout(Request $request)
    {
        // Remove upload directory (also handled by scheduler)
        Storage::disk('upload')->deleteDirectory($request->session()->get('sessionid'));
        // Remove authenticated timestamp
        $request->session()->put('authenticated', null);
        // Redirect to login screen
        return redirect()->route('auth.login');
    }
}
