<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FormulaGamesFixture
 */
class FormulaGamesFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'Games';
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'name' => ['type' => 'string', 'length' => 30, 'null' => false, 'default' => null, 'collate' => 'utf8_bin', 'comment' => '', 'precision' => null],
        'creator_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'game_state_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'game_type_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => ''],
        'modified' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => ''],
        '_indexes' => [
            'game_state_id' => ['type' => 'index', 'columns' => ['game_state_id'], 'length' => []],
            'creator_id' => ['type' => 'index', 'columns' => ['creator_id'], 'length' => []],
            'game_type_id' => ['type' => 'index', 'columns' => ['game_type_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'games_ibfk_2' => ['type' => 'foreign', 'columns' => ['game_type_id'], 'references' => ['game_types', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'games_ibfk_1' => ['type' => 'foreign', 'columns' => ['creator_id'], 'references' => ['users', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'fk_game_state' => ['type' => 'foreign', 'columns' => ['game_state_id'], 'references' => ['game_states', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
                'name' => 'Lorem ipsum dolor sit amet',
                'creator_id' => 1,
                'game_state_id' => 1,
                'game_type_id' => 1,
                'created' => '2021-03-27 12:07:13',
                'modified' => '2021-03-27 12:07:13',
            ],
        ];
        parent::init();
    }
}
