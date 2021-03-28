<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FoLogsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FoLogsTable Test Case
 */
class FoLogsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FoLogsTable
     */
    protected $FoLogs;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.FoLogs',
        'app.FoCars',
        'app.FoPositions',
        'app.FoDamages',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('FoLogs') ? [] : ['className' => FoLogsTable::class];
        $this->FoLogs = $this->getTableLocator()->get('FoLogs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->FoLogs);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
