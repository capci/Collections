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
 * 内部状態の管理に配列を使用したシーケンスです。
 */
class ArrayList extends AbstractSequence {
    
    private $array;
    
    /**
     * 空のシーケンスを作成します。
     */
    public function __construct() {
        $this->array = [];
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int {
        return count($this->array);
    }
    
    /**
     * {@inheritdoc}
     */
    public function isEmpty(): bool {
        return empty($this->array);
    }
    
    /**
     * {@inheritdoc}
     */
    public function clear() {
        $this->array = [];
    }
    
    /**
     * {@inheritdoc}
     */
    public function toArray(): array {
        return $this->array;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): \Traversable {
       return new \ArrayIterator($this->array); 
    }
    
    /**
     * {@inheritdoc}
     */
    public function add($e) {
        $this->array[] = $e;
    }
    
    /**
     * {@inheritdoc}
     */
    public function addAll(Collection $c) {
        foreach ($c as $e) {
            $this->array[] = $e;
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function insert(int $index, $e) {
        if($index < 0 || $index > $this->count()) {
            throw new \OutOfRangeException('Index is out of range: ' . $index);
        }
        array_splice($this->array, $index, 0, [$e]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function insertAll(int $index, Collection $c) {
        if($index < 0 || $index > $this->count()) {
            throw new \OutOfRangeException('Index is out of range: ' . $index);
        }
        array_splice($this->array, $index, 0, $c->toArray());
    }
    
    /**
     * {@inheritdoc}
     */
    public function get(int $index) {
        if($index < 0 || $index >= $this->count()) {
            throw new \OutOfRangeException('Index is out of range: ' . $index);
        }
        return $this->array[$index];
    }
    
    /**
     * {@inheritdoc}
     */
    public function set(int $index, $e) {
        $ret = $this->get($index);
        $this->array[$index] = $e;
        return $ret;
    }
    
    /**
     * {@inheritdoc}
     */
    public function remove(int $index) {
        $ret = $this->get($index);
        array_splice($this->array, $index, 1);
        return $ret;
    }
    
    /**
     * {@inheritdoc}
     */
    public function offsetExists($index) {
        return isset($this->array[$index]);
    }
}