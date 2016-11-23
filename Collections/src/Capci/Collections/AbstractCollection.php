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
 * コレクションのデフォルト実装を提供する抽象クラスです。
 * 
 * このクラスのサブクラスは、より効率的な実装となるようメソッドをオーバーライドしてください。
 */
abstract class AbstractCollection implements \IteratorAggregate, Collection {
    
    /**
     * {@inheritdoc}
     */
    public function count(): int {
        $count = 0;
        foreach ($this as $e) {
            ++$count;
        }
        return $count;
    }
    
    /**
     * {@inheritdoc}
     */
    public function isEmpty(): bool {
        return $this->count() === 0;
    }
    
    /**
     * {@inheritdoc}
     */
    public function toArray(): array {
        $array = [];
        foreach ($this as $e) {
            $array[] = $e;
        }
        return $array;
    }
    
    /**
     * このコレクションの全要素にアクセスするためのイテレータを返します。
     * 
     * @return \Traversable このコレクションの全要素にアクセスするためのイテレータ。
     */
    public abstract function getIterator(): \Traversable;
}