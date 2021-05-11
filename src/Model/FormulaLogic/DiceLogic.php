<?php
declare(strict_types=1);

namespace App\Model\FormulaLogic;

/**
 * Description of MovementLogic
 *
 * @author roman
 */
class DiceLogic {
    
    private $dice = [
        0 => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18 ,19, 20, ],  //black
        1 => [1, 2, ],  //yellow - gear 1
        2 => [2, 3, 3, 4, 4, 4, ],  //orange - gear 2
        3 => [4, 5, 6, 6, 7, 7, 8, 8, ],  //red - gear 3
        4 => [7, 8, 9, 10, 11, 12, ], //green - gear 4
        5 => [11, 12, 13, 14, 15, 16, 17, 18, 19, 20],  //purple - gear 5
        6 => [21, 22, 23, 24, 25, 26, 27, 28, 29, 30],  //blue - gear 6
    ];
    
    const BLACK_SHOCKS_THRESHOLD = 4;
    const BLACK_COLLISION_THRESHOLD = 4;
    const BLACK_POOR_START_TOP = 1;
    const BLACK_FAST_START_LOW = 20;
    
    private static $diceLogicSingleton;
    
    public static function getDiceLogic() {
        if (self::$diceLogicSingleton === null) {
            self::$diceLogicSingleton = new DiceLogic();
        }
        return self::$diceLogicSingleton;
    }
    
    public function getRoll($diceNumber) {
        return $this->dice[$diceNumber][random_int(0, count($this->dice[$diceNumber]) - 1)];
    }
}
