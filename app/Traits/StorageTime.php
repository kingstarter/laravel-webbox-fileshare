<?php

namespace App\Traits;

use Carbon\Carbon;
use Spatie\Valuestore\Valuestore;

trait StorageTime
{
    /**
     * Get value store object
     */
    private function getValueStore()
    {
        return Valuestore::make(storage_path('app/lifetime.json'));
    }

    /**
     * Store a storage path with its removal timestamp
     */
    private function putStorageTime($path, $storageTime)
    {
        $this->getValueStore()->put($path, now()->add($storageTime)->toJSON());
    }

    private function getStorageTime($path)
    {
        return new Carbon($this->getValueStore()->get($path));
    }

    /**
     * Remove a storage path from value store
     */
    private function forgetStorageTime($path)
    {
        $this->getValueStore()->forget($path);
    }

    /**
     * Retrieve all outdated paths
     */
    private function allOutdatedStorages()
    {
        $now = now();
        $outdated = [];
        foreach ($this->getValueStore()->all() as $path => $time)
        {
            if ($now > new Carbon($time))
                array_push($outdated, $path);
        }
        return $outdated;
    }

    /**
     * Remove all outdated storage paths
     */
    private function clearOutdatedStorages()
    {
        $now = now();
        $vs = $this->getValueStore();
        foreach ($vs->all() as $path => $time)
        {
            if ($now > new Carbon($time))
                $vs->forget($path);
        }
    }

    /**
     * Remove everything from value store
     */
    private function wipeStorageTimes()
    {
        $this->getValueStore()->flush();
    }
}
