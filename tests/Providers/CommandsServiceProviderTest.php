<?php namespace MatachanaInd\LogViewer\Tests\Providers;

use MatachanaInd\LogViewer\Providers\CommandsServiceProvider;
use MatachanaInd\LogViewer\Tests\TestCase;

/**
 * Class     CommandsServiceProviderTest
 *
 * @package  MatachanaInd\LogViewer\Tests\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CommandsServiceProviderTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \MatachanaInd\LogViewer\Providers\CommandsServiceProvider */
    private $provider;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    protected function setUp()
    {
        parent::setUp();

        $this->provider = $this->app->getProvider(CommandsServiceProvider::class);
    }

    protected function tearDown()
    {
        unset($this->provider);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $expectations = [
            \Illuminate\Support\ServiceProvider::class,
            \Arcanedev\Support\ServiceProvider::class,
            CommandsServiceProvider::class
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->provider);
        }
    }

    /** @test */
    public function it_can_provides()
    {
        $expected = [
            \MatachanaInd\LogViewer\Commands\PublishCommand::class,
            \MatachanaInd\LogViewer\Commands\StatsCommand::class,
            \MatachanaInd\LogViewer\Commands\CheckCommand::class,
        ];

        static::assertSame($expected, $this->provider->provides());
    }
}
