<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PlayerTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PlayerTable Test Case
 */
class PlayerTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PlayerTable
     */
    protected $Player;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Player',
        'app.Game',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Player') ? [] : ['className' => PlayerTable::class];
        $this->Player = $this->getTableLocator()->get('Player', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Player);

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
