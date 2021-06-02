<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FoTraversesFixture
 */
class FoTraversesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'fo_move_option_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'fo_position_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'fo_move_option_id' => ['type' => 'index', 'columns' => ['fo_move_option_id'], 'length' => []],
            'fo_position_id' => ['type' => 'index', 'columns' => ['fo_position_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fo_traverses_ibfk_2' => ['type' => 'foreign', 'columns' => ['fo_position_id'], 'references' => ['fo_positions', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'fo_traverses_ibfk_1' => ['type' => 'foreign', 'columns' => ['fo_move_option_id'], 'references' => ['fo_move_options', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
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
                'fo_move_option_id' => 1,
                'fo_position_id' => 1,
            ],
        ];
        parent::init();
    }
}
