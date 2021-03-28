<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FoLogsFixture
 */
class FoLogsFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'fo_car_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'fo_position_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'gear' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'roll' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ranking' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'type' => ['type' => 'char', 'length' => 1, 'null' => false, 'default' => null, 'collate' => 'utf8_bin', 'comment' => 'I - initial
M - move
D - damage
R - repair
F - finish', 'precision' => null],
        '_indexes' => [
            'fo_car_id' => ['type' => 'index', 'columns' => ['fo_car_id'], 'length' => []],
            'fo_position_id' => ['type' => 'index', 'columns' => ['fo_position_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fo_logs_ibfk_2' => ['type' => 'foreign', 'columns' => ['fo_position_id'], 'references' => ['fo_positions', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'fo_logs_ibfk_1' => ['type' => 'foreign', 'columns' => ['fo_car_id'], 'references' => ['fo_cars', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
                'fo_car_id' => 1,
                'fo_position_id' => 1,
                'gear' => 1,
                'roll' => 1,
                'ranking' => 1,
                'type' => '',
            ],
        ];
        parent::init();
    }
}
