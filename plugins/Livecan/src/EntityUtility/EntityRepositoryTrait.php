<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Livecan\EntityUtility;

use Cake\ORM\Locator\LocatorAwareTrait;

/**
 *
 * @author roman
 */
trait EntityRepositoryTrait {
    use LocatorAwareTrait;
    
    abstract public function getSource(): string;
    abstract public function setSource(string $alias);
    
    private function _repository() {
        $source = $this->getSource();
        if (empty($source)) {
            list(, $class) = namespaceSplit(get_class($this));
            $source = Inflector::pluralize($class);
            $this->setSource($source);
        }

        return $this->getTableLocator()->get($source);
    }
}
