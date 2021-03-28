<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FoGamesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FoGamesTable Test Case
 */
class FoGamesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FoGamesTable
     */
    protected $FoGames;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.FoGames',
        'app.Games',
        'app.FoTracks',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('FoGames') ? [] : ['className' => FoGamesTable::class];
        $this->FoGames = $this->getTableLocator()->get('FoGames', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->FoGames);

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
