<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DrTokensTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DrTokensTable Test Case
 */
class DrTokensTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DrTokensTable
     */
    protected $DrTokens;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.DrTokens',
        'app.Games',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('DrTokens') ? [] : ['className' => DrTokensTable::class];
        $this->DrTokens = $this->getTableLocator()->get('DrTokens', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->DrTokens);

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
