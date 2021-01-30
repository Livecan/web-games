<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GameStatesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GameStatesTable Test Case
 */
class GameStatesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\GameStatesTable
     */
    protected $GameStates;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.GameStates',
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
        $config = $this->getTableLocator()->exists('GameStates') ? [] : ['className' => GameStatesTable::class];
        $this->GameStates = $this->getTableLocator()->get('GameStates', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->GameStates);

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
}
