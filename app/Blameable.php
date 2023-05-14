<?php

namespace App;

use App\Observers\BlameableObserver;

trait Blameable
{
    public static function bootBlameable()
    {
        static::Observe(BlameableObserver::class);
    }
}
