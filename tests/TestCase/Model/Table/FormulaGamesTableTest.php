<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FormulaGamesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FormulaGamesTable Test Case
 */
class FormulaGamesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FormulaGamesTable
     */
    protected $FormulaGames;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.FormulaGames',
        'app.Users',
        'app.GameStates',
        'app.GameTypes',
        'app.DrResults',
        'app.DrTurns',
        'app.FoCars',
        'app.FoDebris',
        'app.FoGames',
        'app.DrTokens',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('FormulaGames') ? [] : ['className' => FormulaGamesTable::class];
        $this->FormulaGames = $this->getTableLocator()->get('FormulaGames', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->FormulaGames);

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
