<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FoTracksTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FoTracksTable Test Case
 */
class FoTracksTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FoTracksTable
     */
    protected $FoTracks;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.FoTracks',
        'app.FoCurves',
        'app.FoGames',
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
        $config = $this->getTableLocator()->exists('FoTracks') ? [] : ['className' => FoTracksTable::class];
        $this->FoTracks = $this->getTableLocator()->get('FoTracks', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->FoTracks);

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
