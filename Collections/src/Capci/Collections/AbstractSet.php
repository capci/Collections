<?php
/**
 * @package Capci\Collections
 * @version 0.9
 * @author capci https://github.com/capci
 * @link https://github.com/capci/Collections Capci\Collections
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

declare (strict_types = 1);

namespace Capci\Collections;

abstract class AbstractSet extends AbstractCollection implements Set {
    
    /**
     * {@inheritdoc}
     */
    public function clear() {
        foreach ($this as $e) {
            $this->remove($e);
        }
    }
}