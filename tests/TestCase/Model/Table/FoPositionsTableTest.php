<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FoPositionsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FoPositionsTable Test Case
 */
class FoPositionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FoPositionsTable
     */
    protected $FoPositions;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.FoPositions',
        'app.FoTracks',
        'app.FoCurves',
        'app.FoCars',
        'app.FoDebris',
        'app.FoLogs',
        'app.FoMoveOptions',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('FoPositions') ? [] : ['className' => FoPositionsTable::class];
        $this->FoPositions = $this->getTableLocator()->get('FoPositions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->FoPositions);

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
