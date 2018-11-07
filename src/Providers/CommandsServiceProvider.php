<?php namespace MatachanaInd\LogViewer\Providers;

use MatachanaInd\LogViewer\Commands;
use Arcanedev\Support\Providers\CommandServiceProvider as ServiceProvider;

/**
 * Class     CommandsServiceProvider
 *
 * @package  MatachanaInd\LogViewer\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CommandsServiceProvider extends ServiceProvider
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        Commands\PublishCommand::class,
        Commands\StatsCommand::class,
        Commands\CheckCommand::class,
    ];
}
