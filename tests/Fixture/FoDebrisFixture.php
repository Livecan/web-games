<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FoDebrisFixture
 */
class FoDebrisFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'game_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'fo_position_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => ''],
        'modified' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => ''],
        '_indexes' => [
            'fo_position_id' => ['type' => 'index', 'columns' => ['fo_position_id'], 'length' => []],
            'game_id' => ['type' => 'index', 'columns' => ['game_id'], 'length' => []],
            'created' => ['type' => 'index', 'columns' => ['created'], 'length' => []],
            'modified' => ['type' => 'index', 'columns' => ['modified'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fo_debris_ibfk_2' => ['type' => 'foreign', 'columns' => ['fo_position_id'], 'references' => ['fo_positions', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'fo_debris_ibfk_1' => ['type' => 'foreign', 'columns' => ['game_id'], 'references' => ['games', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_bin'
        ],
    ];
    // phpcs:enable
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'game_id' => 1,
                'fo_position_id' => 1,
                'created' => '2021-04-02 14:24:56',
                'modified' => '2021-04-02 14:24:56',
            ],
        ];
        parent::init();
    }
}
