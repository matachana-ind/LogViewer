<?php namespace MatachanaInd\LogViewer\Providers;

use MatachanaInd\LogViewer\Contracts;
use MatachanaInd\LogViewer\Utilities;
use Arcanedev\Support\ServiceProvider;
use Illuminate\Support\Arr;

/**
 * Class     UtilitiesServiceProvider
 *
 * @package  MatachanaInd\LogViewer\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class UtilitiesServiceProvider extends ServiceProvider
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the service provider.
     */
    public function register()
    {
        parent::register();

        $this->registerLogLevels();
        $this->registerStyler();
        $this->registerLogMenu();
        $this->registerFilesystem();
        $this->registerFactory();
        $this->registerChecker();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Contracts\Utilities\LogLevels::class,
            Contracts\Utilities\LogStyler::class,
            Contracts\Utilities\LogMenu::class,
            Contracts\Utilities\Filesystem::class,
            Contracts\Utilities\Factory::class,
            Contracts\Utilities\LogChecker::class,
        ];
    }

    /* -----------------------------------------------------------------
     |  LogViewer Utilities
     | -----------------------------------------------------------------
     */

    /**
     * Register the log levels.
     */
    private function registerLogLevels()
    {
        $this->singleton(Contracts\Utilities\LogLevels::class, function ($app) {
            /**
             * @var  \Illuminate\Config\Repository       $config
             * @var  \Illuminate\Translation\Translator  $translator
             */
            $config     = $app['config'];
            $translator = $app['translator'];

            return new Utilities\LogLevels($translator, $config->get('log-viewer.locale'));
        });
    }

    /**
     * Register the log styler.
     */
    private function registerStyler()
    {
        $this->singleton(Contracts\Utilities\LogStyler::class, Utilities\LogStyler::class);
    }

    /**
     * Register the log menu builder.
     */
    private function registerLogMenu()
    {
        $this->singleton(Contracts\Utilities\LogMenu::class, Utilities\LogMenu::class);
    }

    /**
     * Register the log filesystem.
     */
    private function registerFilesystem()
    {
        $this->singleton(Contracts\Utilities\Filesystem::class, function ($app) {
            /**
             * @var  \Illuminate\Config\Repository      $config
             * @var  \Illuminate\Filesystem\Filesystem  $files
             */
            $config     = $app['config'];
            $files      = $app['files'];
            $filesystem = new Utilities\Filesystem($files, $config->get('log-viewer.storage-path'));

            $pattern = $config->get('log-viewer.pattern', []);
            $patternRealTime = $config->get('log-viewer.patternRealTime', []);

            $filesystem->setPattern(
                Arr::get($pattern, 'prefix',    Utilities\Filesystem::PATTERN_PREFIX),
                Arr::get($pattern, 'date',      Utilities\Filesystem::PATTERN_DATE),
                Arr::get($pattern, 'extension', Utilities\Filesystem::PATTERN_EXTENSION),
                Arr::get($patternRealTime, 'prefix',    Utilities\Filesystem::PATTERN_PREFIXRT),
                Arr::get($patternRealTime, 'name',      Utilities\Filesystem::PATTERN_NAME),
                Arr::get($patternRealTime, 'extension', Utilities\Filesystem::PATTERN_EXTENSIONRT)
            );

            return $filesystem;
        });
    }

    /**
     * Register the log factory class.
     */
    private function registerFactory()
    {
        $this->singleton(Contracts\Utilities\Factory::class, Utilities\Factory::class);
    }

    /**
     * Register the log checker service.
     */
    private function registerChecker()
    {
        $this->singleton(Contracts\Utilities\LogChecker::class, Utilities\LogChecker::class);
    }
}
