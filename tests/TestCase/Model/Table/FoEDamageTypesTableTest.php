<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FoEDamageTypesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FoEDamageTypesTable Test Case
 */
class FoEDamageTypesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FoEDamageTypesTable
     */
    protected $FoEDamageTypes;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.FoEDamageTypes',
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
        $config = $this->getTableLocator()->exists('FoEDamageTypes') ? [] : ['className' => FoEDamageTypesTable::class];
        $this->FoEDamageTypes = $this->getTableLocator()->get('FoEDamageTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->FoEDamageTypes);

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
