<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class UploadCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:cleanup
                            {olderThan=3 : Remove upload directories older than x hours.}
                            {--f|force : Force removal of all directories, regardless of last modified time}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup all old upload directories';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get older-than argument in seconds
        $olderThan = intval($this->argument('olderThan'));
        if (!$olderThan) $olderThan = 3;
        $olderThan *= 3600;
        // Current timestamp in seconds
        $now = time();
        // Force option
        $force = $this->option('force');

        foreach (Storage::disk('upload')->directories() as $dir)
        {
            // Delete all that is older than 3 hours (or force deletion)
            if (!!$force || ($now - Storage::disk('upload')->lastModified($dir) > $olderThan))
            {
                Storage::disk('upload')->deleteDirectory($dir);
            }
        }
    }
}
