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

/**
 * コレクションに格納可能な要素について、同値性の判定やハッシュコードの計算を行います。
 */
interface ElementsComparator {
    
    /**
     * 2つの要素が同値であるかを判定します。
     * 
     * 要素の同値性の判定が必要となるコレクションのメソッドは、このメソッドの実装により挙動が変わります。
     * 
     * @param mixed $e1 1つめの要素。
     * @param mixed $e2 2つめの要素。
     * @return bool 2つの要素が同値である場合true、そうでない場合false。
     */
    public function compareElements($e1, $e2): bool;
}