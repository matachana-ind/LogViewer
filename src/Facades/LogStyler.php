<?php namespace MatachanaInd\LogViewer\Facades;

use MatachanaInd\LogViewer\Contracts\Utilities\LogStyler as LogStylerContract;
use Illuminate\Support\Facades\Facade;

/**
 * Class     LogStyler
 *
 * @package  MatachanaInd\LogViewer\Facades
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LogStyler extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return LogStylerContract::class; }
}
