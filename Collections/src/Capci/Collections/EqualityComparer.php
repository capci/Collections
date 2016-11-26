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
interface EqualityComparer {
    
    /**
     * 2つの要素が同値であるかを判定します。
     * 
     * 要素の同値性の判定が必要となるコレクションのメソッドは、このメソッドの実装により挙動が変わります。
     * 
     * @param mixed $e1 1つめの要素。
     * @param mixed $e2 2つめの要素。
     * @return bool 2つの要素が同値である場合true、そうでない場合false。
     */
    public function elementsEquals($e1, $e2): bool;
    
    /**
     * 任意の要素のハッシュ値を返します。
     * 
     * このメソッドは次の規約に従うよう、実装する必要があります。
     * <ul>
     * <li>elementsEqualsメソッドで同値とみなされる（trueが返される）2つの要素は、必ず同じハッシュ値を返します。</li>
     * <li>
     * elementsEqualsメソッドで同値とみなされない（falseが返される）2つの要素は、異なるハッシュ値を返す必要はありません。
     * ただし、できるだけ異なるハッシュ値を返した方が、ハッシュテーブルの性能が向上します。
     * </li>
     * </ul>
     * 
     * @see EqualityComparer::elementsEquals($e1, $e2)
     * 
     * @param mixed $e 要素。
     * @return int 要素のハッシュ値。
     */
    public function elementHashCode($e): int;
}