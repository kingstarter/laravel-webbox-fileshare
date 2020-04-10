<?php

namespace App\Traits;

trait SessionLifetime
{
    /**
     * Get session lifetime as intval
     */
    public function getSessionLifetime(int $fallback = 300) {
        return !!($ttl = intval(config('session.lifetime'))) ?
            $ttl : $fallback;
    }
}
