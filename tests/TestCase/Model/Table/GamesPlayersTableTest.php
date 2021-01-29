<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GamesPlayersTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GamesPlayersTable Test Case
 */
class GamesPlayersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\GamesPlayersTable
     */
    protected $GamesPlayers;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.GamesPlayers',
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
        $config = $this->getTableLocator()->exists('GamesPlayers') ? [] : ['className' => GamesPlayersTable::class];
        $this->GamesPlayers = $this->getTableLocator()->get('GamesPlayers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->GamesPlayers);

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
