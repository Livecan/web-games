<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FoPosition2PositionsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FoPosition2PositionsTable Test Case
 */
class FoPosition2PositionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FoPosition2PositionsTable
     */
    protected $FoPosition2Positions;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.FoPosition2Positions',
        'app.FoPositions',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('FoPosition2Positions') ? [] : ['className' => FoPosition2PositionsTable::class];
        $this->FoPosition2Positions = $this->getTableLocator()->get('FoPosition2Positions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->FoPosition2Positions);

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
