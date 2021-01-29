<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * GamePlayerFixture
 */
class GamePlayerFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'game_player';
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'ID' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'Game_ID' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'Player_ID' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'turn_order' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'Game_ID' => ['type' => 'index', 'columns' => ['Game_ID'], 'length' => []],
            'Player_ID' => ['type' => 'index', 'columns' => ['Player_ID'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['ID'], 'length' => []],
            'game_player_ibfk_2' => ['type' => 'foreign', 'columns' => ['Game_ID'], 'references' => ['game', 'ID'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'game_player_ibfk_1' => ['type' => 'foreign', 'columns' => ['Player_ID'], 'references' => ['player', 'ID'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
                'ID' => 1,
                'Game_ID' => 1,
                'Player_ID' => 1,
                'turn_order' => 1,
            ],
        ];
        parent::init();
    }
}
