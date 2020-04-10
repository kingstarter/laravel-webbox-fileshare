<?php

namespace App\Http\Controllers;

use App\Traits\SessionLifetime;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    use SessionLifetime;

    public function index()
    {
        return view('home')->with([
            'session' => md5(uniqid()),
            'time' => intval(session('authenticated')) * 1000,
            'ttl' => $this->getSessionLifetime()
        ]);
    }

    public function store(Request $request)
    {
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();

        return response()->json([
            'success' => $filename,
            'time' => intval(session('authenticated')) * 1000,
            'ttl' => $this->getSessionLifetime()
        ]);
    }
}
