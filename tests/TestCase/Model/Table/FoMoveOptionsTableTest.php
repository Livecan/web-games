<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FoMoveOptionsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FoMoveOptionsTable Test Case
 */
class FoMoveOptionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FoMoveOptionsTable
     */
    protected $FoMoveOptions;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.FoMoveOptions',
        'app.FoCars',
        'app.FoPositions',
        'app.FoDamages',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('FoMoveOptions') ? [] : ['className' => FoMoveOptionsTable::class];
        $this->FoMoveOptions = $this->getTableLocator()->get('FoMoveOptions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->FoMoveOptions);

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
