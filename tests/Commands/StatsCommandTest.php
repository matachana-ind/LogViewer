<?php namespace MatachanaInd\LogViewer\Tests\Commands;

use MatachanaInd\LogViewer\Tests\TestCase;

/**
 * Class     StatsCommandTest
 *
 * @package  MatachanaInd\LogViewer\Tests\Commands
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class StatsCommandTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_display_stats()
    {
        $this->artisan('log-viewer:stats')
             ->assertExitCode(0);
    }
}
