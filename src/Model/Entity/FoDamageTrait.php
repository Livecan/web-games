<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Entity;

/**
 *
 * @author roman
 */
trait FoDamageTrait {
    public function getDamageByType(int $foDamageType) : FoDamage {
        return collection($this->fo_damages)->firstMatch(['type' => $foDamageType]);
    }
}
