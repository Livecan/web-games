<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FoCurvesFixture
 */
class FoCurvesFixture extends TestFixture
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
        'fo_next_curve_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'stops' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'name' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'collate' => 'utf8_bin', 'comment' => '', 'precision' => null],
        '_indexes' => [
            'track_id' => ['type' => 'index', 'columns' => ['fo_track_id'], 'length' => []],
            'next_curve_id' => ['type' => 'index', 'columns' => ['fo_next_curve_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fo_curves_ibfk_2' => ['type' => 'foreign', 'columns' => ['fo_next_curve_id'], 'references' => ['fo_curves', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'fo_curves_ibfk_1' => ['type' => 'foreign', 'columns' => ['fo_track_id'], 'references' => ['fo_tracks', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
                'fo_next_curve_id' => 1,
                'stops' => 1,
                'name' => 'Lorem ipsum dolor ',
            ],
        ];
        parent::init();
    }
}
