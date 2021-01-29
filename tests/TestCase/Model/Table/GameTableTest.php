<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GameTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GameTable Test Case
 */
class GameTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\GameTable
     */
    protected $Game;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Game',
        'app.Player',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Game') ? [] : ['className' => GameTable::class];
        $this->Game = $this->getTableLocator()->get('Game', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Game);

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
