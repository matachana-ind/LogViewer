<?php namespace MatachanaInd\LogViewer\Tests\Tables;

use MatachanaInd\LogViewer\Contracts\Table as TableContract;
use MatachanaInd\LogViewer\Tables\StatsTable;
use MatachanaInd\LogViewer\Tests\TestCase;

/**
 * Class     StatsTableTest
 *
 * @package  MatachanaInd\LogViewer\Tests\Tables
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class StatsTableTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \MatachanaInd\LogViewer\Tables\StatsTable */
    private $table;

    /** @var array */
    private $rawData;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    protected function setUp()
    {
        parent::setUp();

        $this->table = new StatsTable(
            $this->rawData = $this->getLogViewerInstance()->stats(),
            $this->getLogLevelsInstance()
        );
    }

    protected function tearDown()
    {
        unset($this->table);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(StatsTable::class, $this->table);
    }

    /** @test */
    public function it_can_make_instance()
    {
        $this->table = StatsTable::make(
            $this->getLogViewerInstance()->stats(),
            $this->getLogLevelsInstance()
        );

        $this->assertTable($this->table);
    }

    /** @test */
    public function it_can_get_header()
    {
        $this->assertTableHeader($this->table);
    }

    /** @test */
    public function it_can_get_rows()
    {
        $this->assertTableRows($this->table);
    }

    /** @test */
    public function it_can_get_footer()
    {
        $this->assertTableFooter($this->table);
    }

    /** @test */
    public function it_can_get_raw_data()
    {
        $this->assertEquals(
            $this->rawData,
            $this->table->data()
        );
    }

    /** @test */
    public function it_can_get_totals()
    {
        $totals = $this->table->totals();

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $totals);
    }

    /** @test */
    public function it_can_get_json_data_for_chart()
    {
        $json = $this->table->totalsJson();

        $this->assertJson($json);
    }

    /** @test */
    public function it_can_get_stats_table_via_log_viewer()
    {
        /** @var  \MatachanaInd\LogViewer\Contracts\LogViewer  $logViewer */
        $logViewer = $this->app->make(\MatachanaInd\LogViewer\Contracts\LogViewer::class);

        static::assertTable($logViewer->statsTable());
    }

    /** @test */
    public function it_can_get_stats_table_via_log_factory()
    {
        /** @var  \MatachanaInd\LogViewer\Contracts\Utilities\Factory  $logFactory */
        $logFactory = $this->app->make(\MatachanaInd\LogViewer\Contracts\Utilities\Factory::class);

        $this->assertTable($logFactory->statsTable());
    }

    /* -----------------------------------------------------------------
     |  Custom Assertions
     | -----------------------------------------------------------------
     */

    /**
     * Assert table instance.
     *
     * @param  \MatachanaInd\LogViewer\Contracts\Table  $table
     */
    protected static function assertTable(TableContract $table)
    {
        self::assertTableHeader($table);
        self::assertTableRows($table);
        self::assertTableFooter($table);
    }

    /**
     * Assert table header.
     *
     * @param  \MatachanaInd\LogViewer\Contracts\Table  $table
     */
    protected static function assertTableHeader(TableContract $table)
    {
        $header = $table->header();

        self::assertCount(10, $header);
        // TODO: Add more assertions to check the content
    }

    /**
     * Assert table rows.
     *
     * @param  \MatachanaInd\LogViewer\Contracts\Table  $table
     */
    protected static function assertTableRows(TableContract $table)
    {
        foreach ($table->rows() as $date => $row) {
            self::assertDate($date);
            self::assertCount(10, $row);

            foreach ($row as $key => $value) {
                switch ($key) {
                    case 'date':
                        self::assertDate($value);
                        break;

                    case 'all':
                        self::assertEquals(8, $value);
                        break;

                    default:
                        self::assertEquals(1, $value);
                        break;
                }
            }
        }
    }

    /**
     * Assert table footer.
     *
     * @param  \MatachanaInd\LogViewer\Contracts\Table  $table
     */
    protected static function assertTableFooter(TableContract $table)
    {
        foreach ($table->footer() as $key => $value) {
            self::assertEquals($key === 'all' ? 16 : 2, $value);
        }
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the LogViewer instance.
     *
     * @return \MatachanaInd\LogViewer\Contracts\LogViewer
     */
    protected function getLogViewerInstance()
    {
        return $this->app->make(\MatachanaInd\LogViewer\Contracts\LogViewer::class);
    }

    /**
     * Get the LogLevels instance.
     *
     * @return \MatachanaInd\LogViewer\Contracts\Utilities\LogLevels
     */
    protected function getLogLevelsInstance()
    {
        return $this->app->make(\MatachanaInd\LogViewer\Contracts\Utilities\LogLevels::class);
    }
}
