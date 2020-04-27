<?php

namespace App\Console\Commands;

use App\Traits\StorageTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class StorageCleanup extends Command
{
    use StorageTime;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:cleanup
                            {--f|force : Force removal of all directories, regardless of storage lifetime}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup all old storage directories';

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
        // Force option
        $force = $this->option('force');

        // Get paths and remove
        $paths = $force ? Storage::disk('public')->directories() : $this->allOutdatedStorages();
        $this->info(print_r($paths, true));
        foreach ($paths as $path)
        {
            if (Storage::disk('public')->exists($path))
                Storage::disk('public')->deleteDirectory($path);
            $this->forgetStorageTime($path);
        }
        if ($force)
            $this->wipeStorageTimes();
        else
            $this->clearOutdatedStorages();
    }
}
