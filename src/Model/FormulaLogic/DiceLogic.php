<?php
declare(strict_types=1);

namespace App\Model\FormulaLogic;

/**
 * Description of MovementLogic
 *
 * @author roman
 */
class DiceLogic {
    
    private $dice = [   //TODO: adjust these according to the real dice
        0 => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18 ,19, 20],  //black
        1 => [1, 2],  //yellow - gear 1
        2 => [2, 3, 3, 4, 4, 4],  //orange - gear 2
        3 => [4, 5, 6, 7, 8],  //red - gear 3
        //TODO: do the rest of the dice
    ];
    
    public function getRoll($diceNumber) {
        return $this->dice[$diceNumber][random_int(0, count($this->dice[$diceNumber]))];
    }
}
