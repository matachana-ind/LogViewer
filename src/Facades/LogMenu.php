<?php namespace MatachanaInd\LogViewer\Facades;

use MatachanaInd\LogViewer\Contracts\Utilities\LogMenu as LogMenuContract;
use Illuminate\Support\Facades\Facade;

/**
 * Class     LogMenu
 *
 * @package  MatachanaInd\LogViewer\Facades
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LogMenu extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return LogMenuContract::class; }
}
