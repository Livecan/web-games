<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DrTurnsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DrTurnsTable Test Case
 */
class DrTurnsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DrTurnsTable
     */
    protected $DrTurns;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.DrTurns',
        'app.Games',
        'app.Players',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('DrTurns') ? [] : ['className' => DrTurnsTable::class];
        $this->DrTurns = $this->getTableLocator()->get('DrTurns', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->DrTurns);

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
