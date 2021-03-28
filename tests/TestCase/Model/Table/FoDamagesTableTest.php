<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FoDamagesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FoDamagesTable Test Case
 */
class FoDamagesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FoDamagesTable
     */
    protected $FoDamages;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.FoDamages',
        'app.FoCars',
        'app.FoMoveOptions',
        'app.FoLogs',
        'app.FoEDamageTypes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('FoDamages') ? [] : ['className' => FoDamagesTable::class];
        $this->FoDamages = $this->getTableLocator()->get('FoDamages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->FoDamages);

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
