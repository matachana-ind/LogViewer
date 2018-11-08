<?php namespace MatachanaInd\LogViewer\Tests\Commands;

use MatachanaInd\LogViewer\Tests\TestCase;

/**
 * Class     CheckCommandTest
 *
 * @package  MatachanaInd\LogViewer\Tests\Commands
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CheckCommandTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_check()
    {
        $this->artisan('log-viewer:check')
             ->assertExitCode(0);
    }
}
