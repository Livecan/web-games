<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GamePlayerTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GamePlayerTable Test Case
 */
class GamePlayerTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\GamePlayerTable
     */
    protected $GamePlayer;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.GamePlayer',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('GamePlayer') ? [] : ['className' => GamePlayerTable::class];
        $this->GamePlayer = $this->getTableLocator()->get('GamePlayer', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->GamePlayer);

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
