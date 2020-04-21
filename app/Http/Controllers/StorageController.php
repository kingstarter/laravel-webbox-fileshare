<?php

namespace App\Http\Controllers;

use App\Traits\StorageTime;
use App\Traits\StringAdditions;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class StorageController extends Controller
{
    use StringAdditions, StorageTime;

    // Static font-awesome mime icons
    private $faMimeIcons = [
        'archive'       => 'file-archive',
        'audio'         => 'file-audio',
        'code'          => 'file-code',
        'csv'           => 'file-csv',
        'excel'         => 'file-excel',
        'file'          => 'file',
        'folder'        => 'folder',
        'image'         => 'file-image',
        'pdf'           => 'file-pdf',
        'powerpoint'    => 'file-powerpoint',
        'text'          => 'file-alt',
        'video'         => 'file-video',
        'word'          => 'file-word',

        'unknown'       => 'file',
    ];

    /**
     * Select font awesome public icon based on mime type
     */
    private function selectMimeIcon($mime)
    {
        if ($this->startsWith($mime, 'text'))
        {
            switch ($mime)
            {
                case 'text/css':
                case 'text/html':
                case 'text/javascript':
                    return $this->faMimeIcons['code'];
                case 'text/csv':
                    return $this->faMimeIcons['csv'];
                case 'text/plain':
                    return $this->faMimeIcons['text'];
                default:
                    return $this->faMimeIcons['unknown'];
            }
        }

        if ($this->startsWith($mime, 'application'))
        {
            if ($this->contains($mime, 'word') || $this->contains($mime, 'text'))
                return $this->faMimeIcons['word'];
            if ($this->contains($mime, 'pdf'))
                return $this->faMimeIcons['pdf'];
            if ($this->contains($mime, 'excel') || $this->contains($mime, 'spreadsheet'))
                return $this->faMimeIcons['excel'];
            if ($this->contains($mime, 'zip') || $this->contains($mime, 'x-tar') || $this->contains($mime, 'x-bzip') || $this->contains($mime, 'vnd.rar') || $this->contains($mime, 'archive'))
                return $this->faMimeIcons['archive'];
            if ($this->contains($mime, 'powerpoint') || $this->contains($mime, 'presentation'))
                return $this->faMimeIcons['powerpoint'];
        }

        if ($this->startsWith($mime, 'audio'))
            return $this->faMimeIcons['audio'];

        if ($this->startsWith($mime, 'video'))
            return $this->faMimeIcons['video'];

        // For images with missing thumbnails
        if ($this->startsWith($mime, 'image'))
            return $this->faMimeIcons['image'];

        // Unknown
        return $this->faMimeIcons['unknown'];
    }

    /**
     * Index : public storage page
     */
    public function index(String $dir)
    {
        $files = [];
        $thumbs = [];

        foreach (Storage::disk('public')->files($dir."/thumbs") as $thumb)
        {
            array_push($thumbs, File::basename($thumb));
        }

        foreach (Storage::disk('public')->files($dir) as $file)
        {
            $basename = File::basename($file);
            $image = in_array($basename, $thumbs) ?
                Storage::disk('public')->url(File::dirname($file) . "/thumbs/" . File::basename($file)) :
                null;

            $mime = Storage::disk('public')->mimeType($file);
            array_push($files, [
                'basename'  => $basename,
                'image'     => $image,
                'icon'      => $image == null ? $this->selectMimeIcon(Storage::disk('public')->mimeType($file)) : null,
                'url'       => Storage::disk('public')->url($file),
                'mime'      => $mime,
                'ispicture' => $this->startsWith($mime, 'image'),
            ]);
        }

        return view('storage')->with([
            'directory' => $dir,
            'files' => $files,
            'endingdate' => $this->getStorageTime($dir)
                ->locale(config('app.locale'))->isoFormat('LL')
        ]);
    }

    /**
     * Generate storage zip archive for download containing full directory
     */
    public function archive(String $dir)
    {
        $zip = new ZipArchive;
        // $filename = 'archive_'.str_replace(' ', '-', now()->toDateTimeString()).'.zip';
        $basepath = $dir."/archive";
        if (!Storage::disk('public')->exists($basepath))
            Storage::disk('public')->makeDirectory($basepath);
        $path = $basepath . '/archive.zip';

        // Check if
        if (!Storage::disk('public')->exists($path))
        {
            // Zip not yet created, generate
            $realpath = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($path);
            if ($zip->open($realpath, ZipArchive::CREATE) === TRUE)
            {
                foreach (Storage::disk('public')->files($dir) as $file)
                {
                    $filepath = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($file);
                    $zip->addFile($filepath, 'archive/'.File::basename($file));
                }
                $zip->close();
            }
        }

        return Storage::disk('public')->exists($path) ?
            Storage::disk('public')->download($path) :
            response(__('messages.download.zip.err'), 500);
    }
}
