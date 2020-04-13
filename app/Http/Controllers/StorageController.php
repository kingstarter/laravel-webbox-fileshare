<?php

namespace App\Http\Controllers;

use App\Traits\StorageTime;
use App\Traits\StringAdditions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
        'pdf'           => 'file-pdf',
        'powerpoint'    => 'file-powerpoint',
        'text'          => 'file-alt',
        'video'         => 'file-video',
        'word'          => 'file-word',

        'unknown'       => 'file',
    ];

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
    }

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

            array_push($files, [
                'basename' => $basename,
                'image' => $image,
                'icon' => $image == null ? $this->selectMimeIcon(Storage::disk('public')->mimeType($file)) : null,
                'url' => Storage::disk('public')->url($file),
                'mime' => Storage::disk('public')->mimeType($file)
            ]);
        }

        return view('storage')->with([
            'directory' => $dir,
            'files' => $files,
            'endingdate' => $this->getStorageTime($dir)
                ->locale(config('app.locale'))->isoFormat('LL')
        ]);
    }

}
