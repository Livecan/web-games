<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FoDamagesFixture
 */
class FoDamagesFixture extends TestFixture
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
        'fo_move_option_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'fo_log_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'wear_points' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'fo_e_damage_type_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => ''],
        'modified' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => ''],
        '_indexes' => [
            'fo_car_id' => ['type' => 'index', 'columns' => ['fo_car_id'], 'length' => []],
            'fo_move_option_id' => ['type' => 'index', 'columns' => ['fo_move_option_id'], 'length' => []],
            'fo_history_id' => ['type' => 'index', 'columns' => ['fo_log_id'], 'length' => []],
            'fo_e_damage_type_id' => ['type' => 'index', 'columns' => ['fo_e_damage_type_id'], 'length' => []],
            'created' => ['type' => 'index', 'columns' => ['created'], 'length' => []],
            'modified' => ['type' => 'index', 'columns' => ['modified'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fo_damages_ibfk_4' => ['type' => 'foreign', 'columns' => ['fo_move_option_id'], 'references' => ['fo_move_options', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'fo_damages_ibfk_3' => ['type' => 'foreign', 'columns' => ['fo_log_id'], 'references' => ['fo_logs', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'fo_damages_ibfk_2' => ['type' => 'foreign', 'columns' => ['fo_e_damage_type_id'], 'references' => ['fo_e_damage_types', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'fo_damages_ibfk_1' => ['type' => 'foreign', 'columns' => ['fo_car_id'], 'references' => ['fo_cars', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
                'fo_move_option_id' => 1,
                'fo_log_id' => 1,
                'wear_points' => 1,
                'fo_e_damage_type_id' => 1,
                'created' => '2021-03-27 13:22:06',
                'modified' => '2021-03-27 13:22:06',
            ],
        ];
        parent::init();
    }
}
