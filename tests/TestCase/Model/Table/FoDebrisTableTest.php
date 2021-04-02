<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FoDebrisTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FoDebrisTable Test Case
 */
class FoDebrisTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FoDebrisTable
     */
    protected $FoDebris;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.FoDebris',
        'app.Games',
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
        $config = $this->getTableLocator()->exists('FoDebris') ? [] : ['className' => FoDebrisTable::class];
        $this->FoDebris = $this->getTableLocator()->get('FoDebris', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->FoDebris);

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
