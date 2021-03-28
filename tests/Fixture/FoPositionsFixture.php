<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FoPositionsFixture
 */
class FoPositionsFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'fo_track_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'order' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'fo_curve_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'is_finish' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'starting_position' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => 'NULL - not a starting position
number - particular starting position', 'precision' => null, 'autoIncrement' => null],
        'pos_x' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => 'per 100000 width', 'precision' => null, 'autoIncrement' => null],
        'pos_y' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => 'per 100000 height', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'fo_track_id' => ['type' => 'index', 'columns' => ['fo_track_id'], 'length' => []],
            'fo_curve_id' => ['type' => 'index', 'columns' => ['fo_curve_id'], 'length' => []],
            'order' => ['type' => 'index', 'columns' => ['order'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'starting_position' => ['type' => 'unique', 'columns' => ['fo_track_id', 'starting_position'], 'length' => []],
            'fo_positions_ibfk_2' => ['type' => 'foreign', 'columns' => ['fo_curve_id'], 'references' => ['fo_curves', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'fo_positions_ibfk_1' => ['type' => 'foreign', 'columns' => ['fo_track_id'], 'references' => ['fo_tracks', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
                'fo_track_id' => 1,
                'order' => 1,
                'fo_curve_id' => 1,
                'is_finish' => 1,
                'starting_position' => 1,
                'pos_x' => 1,
                'pos_y' => 1,
            ],
        ];
        parent::init();
    }
}
