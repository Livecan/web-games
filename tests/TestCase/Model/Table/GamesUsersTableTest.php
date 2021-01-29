<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GamesUsersTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GamesUsersTable Test Case
 */
class GamesUsersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\GamesUsersTable
     */
    protected $GamesUsers;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.GamesUsers',
        'app.Games',
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
        $config = $this->getTableLocator()->exists('GamesUsers') ? [] : ['className' => GamesUsersTable::class];
        $this->GamesUsers = $this->getTableLocator()->get('GamesUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->GamesUsers);

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
