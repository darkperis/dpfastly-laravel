<?php

namespace Darkpony\Fastly;

use Illuminate\Support\Facades\Facade;

class Fastly extends Facade
{
    protected static function getFacadeAccessor()
    {
        return FastlyCache::class;
    }
}
