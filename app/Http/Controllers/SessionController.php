<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        if ($request->input('email_456'))
        {
            // Honeypot is filled
            sleep(1);   // Throttle for brute-force protection
            $request->session()->put('authenticated', null);
            return view('login', [
                'status' => 'error',
                'message' => 'Service derzeit nicht verfügbar, bitte probieren Sie es später erneut.'
            ]);
        }

        // Use something like env('PIN') here
        if ($request->input('session_pin') === '123456')
        {
            // Do not throttle successful login attempts
            $request->session()->put('authenticated', time());
            return redirect()->route('home');
        }

        sleep(1);   // Throttle for brute-force protection
        return view('login', [
            'status' => 'error',
            'message' => 'Falscher Sicherheitscode.'
        ]);
    }

    public function logout(Request $request)
    {
        $request->session()->put('authenticated', null);
        return redirect()->route('auth.login');
    }
}
