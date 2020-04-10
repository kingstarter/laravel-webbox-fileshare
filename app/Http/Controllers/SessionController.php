<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SessionController extends Controller
{
    private function clearUploadDirectory() {
        // TODO : currently single session - when someone else logs in, all is deleted
        Storage::disk('upload')->delete(
            Storage::disk('upload')->allFiles()
        );
    }

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
        if ($request->input('session_pin') === config('app.authpin'))
        {
            $this->clearUploadDirectory();
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
        $this->clearUploadDirectory();
        return redirect()->route('auth.login');
    }
}
