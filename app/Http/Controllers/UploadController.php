<?php

namespace App\Http\Controllers;

use App\Mail\SharedLink;
use App\Traits\SessionLifetime;
use App\Traits\StorageTime;
use App\Traits\StringAdditions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Facades\Image;

class UploadController extends Controller
{
    use SessionLifetime, StringAdditions, StorageTime;

    /**
     * Check all image files and create thumbnails within a subdirectory
     */
    public function createThumbs($publicPath)
    {
        $fullPath = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($publicPath);
        if (!Storage::disk('public')->exists($publicPath) || !File::isDirectory($fullPath))
            return false;

        // Create thumb directory
        $thumbPath = $publicPath . "/thumbs";
        if (!Storage::disk('public')->exists($thumbPath))
            Storage::disk('public')->makeDirectory($thumbPath);

        foreach (Storage::disk('public')->allFiles($publicPath) as $file)
        {
            $name = File::basename($file);
            $path = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($file);
            $mime = Storage::disk('public')->mimeType($file);

            if ($this->startsWith($mime, "image") && !Storage::disk('public')->exists($thumbPath . "/" . $name))
            {
                try {
                    // Create thumb of image and store beside it
                    Image::make($path)
                        ->widen   (300, function($constraint) { $constraint->upsize(); })
                        ->heighten(300, function($constraint) { $constraint->upsize(); })
                        ->save(File::dirname($path) . "/thumbs/" . $name);
                } catch (NotReadableException $e) {
                    // Skip file
                    continue;
                }
            }
        }

        return true;
    }

    /**
     * Show uploading page
     */
    public function index()
    {
        return view('home')->with([
            'time' => intval(session('authenticated')) * 1000,
            'ttl' => $this->getSessionLifetime()
        ]);
    }

    /**
     * Upload and store a file
     */
    public function upload(Request $request)
    {
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $sessId = $request->session()->get('sessionid');

        if (!Storage::disk('upload')->exists($sessId))
            Storage::disk('upload')->makeDirectory($sessId);
        Storage::disk('upload')->putFileAs($sessId, $file, $filename);

        // TODO : create thumbs for images

        return response()->json([
            // 'success' => $filename,
            'time' => intval(session('authenticated')) * 1000,
            'ttl' => $this->getSessionLifetime()
        ]);
    }

    /**
     * Store uploaded files to public path, return back shared directory
     */
    public function store(Request $request)
    {
        $sessId = $request->session()->get('sessionid');
        $storageTime = $request->input('time');

        if (empty($storageTime))
            $storageTime = '1 month';

        // Make directory
        if (!Storage::disk('public')->exists($sessId))
            Storage::disk('public')->makeDirectory($sessId);

        $files = Storage::disk('upload')->allFiles($sessId);
        foreach ($files as $path) {
            $srcPath = Storage::disk('upload')->getDriver()->getAdapter()->applyPathPrefix($path);
            $tarPath = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($path);
            File::move($srcPath, $tarPath);
        }

        $this->createThumbs($sessId);
        $this->putStorageTime($sessId, $storageTime);

        return response()->json([
            'success' => $sessId,
            'time' => intval(session('authenticated')) * 1000,
            'ttl' => $this->getSessionLifetime(),
            'url' => 'share/'.$sessId
        ]);
    }

    /**
     * Send an email with the given session id as link
     */
    public function sendmail(Request $request)
    {
        $sessId = $request->session()->get('sessionid');
        Mail::to($request->input('email'))
          ->send(new SharedLink(
            $sessId,
            $this->getStorageTime($sessId)->isoFormat('LLLL')
          ));

        return response()->json(['success' => true]);
    }
}
