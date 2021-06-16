<?php namespace Phuclh\Affast\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Phuclh\Affast\Affast
 */
class Affast extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Phuclh\Affast\Affast::class;
    }
}
