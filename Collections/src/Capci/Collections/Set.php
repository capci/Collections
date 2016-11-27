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

interface Set extends Collection {
    
    /**
     * 
     * @param mixed $e
     * @return bool Description
     */
    public function add($e): bool;
    
    /**
     * 
     * @param mixed $e
     * @return bool Description
     */
    //public function remove($e): bool;
}