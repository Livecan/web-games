<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Livecan\EntityUtility;

use Livecan\EntityUtility\EntityRepositoryTrait;

/**
 *
 * @author roman
 */
trait EntitySaveTrait {
    use EntityRepositoryTrait;
    
    public function save() {
        return $this->_repository()->save($this);
    }
}
