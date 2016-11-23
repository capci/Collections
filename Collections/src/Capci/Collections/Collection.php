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
 * 任意の要素の集合です。
 * 
 * 全てのSequence、Set、Mapの基底インターフェースとなります。
 */
interface Collection extends \Countable, \Traversable {
    
    /**
     * このコレクションの要素数を返します。
     * 
     * @return int このコレクションの要素数。
     */
    public function count(): int;
    
    /**
     * このコレクションが空であるかを調べます。
     * 
     * @return bool このコレクションが空の場合true、そうでない場合false。
     */
    public function isEmpty(): bool;
    
    /**
     * このコレクションを空にします。
     */
    public function clear();
    
    /**
     * このコレクションと同等の内容の配列を返します。
     * 
     * @return array このコレクションと同等の内容の配列。
     */
    public function toArray(): array;
}