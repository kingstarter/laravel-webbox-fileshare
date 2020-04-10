<?php

namespace App\Http\Controllers;

use App\Traits\SessionLifetime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        Storage::disk('upload')->putFileAs('./', $file, $filename);

        return response()->json([
            // 'success' => $filename,
            'time' => intval(session('authenticated')) * 1000,
            'ttl' => $this->getSessionLifetime()
        ]);
    }
}
