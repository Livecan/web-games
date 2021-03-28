<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FoCurvesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FoCurvesTable Test Case
 */
class FoCurvesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FoCurvesTable
     */
    protected $FoCurves;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.FoCurves',
        'app.FoTracks',
        'app.FoPositions',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('FoCurves') ? [] : ['className' => FoCurvesTable::class];
        $this->FoCurves = $this->getTableLocator()->get('FoCurves', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->FoCurves);

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
