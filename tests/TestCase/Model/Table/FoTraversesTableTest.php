<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FoTraversesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FoTraversesTable Test Case
 */
class FoTraversesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FoTraversesTable
     */
    protected $FoTraverses;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.FoTraverses',
        'app.FoMoveOptions',
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
        $config = $this->getTableLocator()->exists('FoTraverses') ? [] : ['className' => FoTraversesTable::class];
        $this->FoTraverses = $this->getTableLocator()->get('FoTraverses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->FoTraverses);

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
