<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FoPosition2PositionsFixture
 */
class FoPosition2PositionsFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'fo_position2positions';
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'fo_position_from_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'fo_position_to_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'is_left' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'is_straight' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'is_right' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'is_curve' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'is_equal_distance' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'is_pitlane_move' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        '_indexes' => [
            'fo_position_from_id' => ['type' => 'index', 'columns' => ['fo_position_from_id'], 'length' => []],
            'fo_position_to_id' => ['type' => 'index', 'columns' => ['fo_position_to_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fo_position2positions_ibfk_2' => ['type' => 'foreign', 'columns' => ['fo_position_to_id'], 'references' => ['fo_positions', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'fo_position2positions_ibfk_1' => ['type' => 'foreign', 'columns' => ['fo_position_from_id'], 'references' => ['fo_positions', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
                'fo_position_from_id' => 1,
                'fo_position_to_id' => 1,
                'is_left' => 1,
                'is_straight' => 1,
                'is_right' => 1,
                'is_curve' => 1,
                'is_equal_distance' => 1,
                'is_pitlane_move' => 1,
            ],
        ];
        parent::init();
    }
}
