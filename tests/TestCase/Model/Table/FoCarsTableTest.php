<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FoCarsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FoCarsTable Test Case
 */
class FoCarsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FoCarsTable
     */
    protected $FoCars;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.FoCars',
        'app.Games',
        'app.Users',
        'app.FoPositions',
        'app.FoDamages',
        'app.FoLogs',
        'app.FoMoveOptions',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('FoCars') ? [] : ['className' => FoCarsTable::class];
        $this->FoCars = $this->getTableLocator()->get('FoCars', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->FoCars);

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

    /**
     * Test createUserCar method
     *
     * @return void
     */
    public function testCreateUserCar(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
