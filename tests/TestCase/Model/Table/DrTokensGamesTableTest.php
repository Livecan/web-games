<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DrTokensGamesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DrTokensGamesTable Test Case
 */
class DrTokensGamesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DrTokensGamesTable
     */
    protected $DrTokensGames;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.DrTokensGames',
        'app.Games',
        'app.DrTokens',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('DrTokensGames') ? [] : ['className' => DrTokensGamesTable::class];
        $this->DrTokensGames = $this->getTableLocator()->get('DrTokensGames', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->DrTokensGames);

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
