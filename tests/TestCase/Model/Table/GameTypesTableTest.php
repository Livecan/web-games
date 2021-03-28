<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GameTypesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GameTypesTable Test Case
 */
class GameTypesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\GameTypesTable
     */
    protected $GameTypes;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.GameTypes',
        'app.Games',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('GameTypes') ? [] : ['className' => GameTypesTable::class];
        $this->GameTypes = $this->getTableLocator()->get('GameTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->GameTypes);

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
