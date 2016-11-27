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
 * ハッシュテーブルで実装されたセットです。
 */
class HashSet extends AbstractSet {
    
    private $table;
    private $count;
    private $loadFactor;
    private $rehashThreshold;
    
    public function __construct(int $initialCapacity = 16, float $loadFactor = 0.75, EqualityComparer $equalityComparer = null) {
        if($initialCapacity <= 0) {
            throw new \InvalidArgumentException('Initial capacity must be greater than 0: ' . $initialCapacity);
        }
        if($loadFactor <= 0.0 || is_nan($loadFactor)) {
            throw new \InvalidArgumentException('Load factor is invalid: ' . $loadFactor);
        }
        parent::__construct($equalityComparer);
        
        $size = 1;
        while($size < $initialCapacity) {
            $size <<= 1;
        }
        $this->table = $this->createTable($size);
        $this->count = 0;
        $this->loadFactor = $loadFactor;
        $this->rehashThreshold = intval($size * $loadFactor);
    }
    
    private function createTable($size): array {
        $table = [];
        for($i = 0; $i < $size; ++$i) {
            $table[] = [];
        }
        return $table;
    }
    
    private function indexFor($key, int $size): int {
        return $this->elementHashCode($key) & ($size - 1);
    }
    
    private function rehash() {
        $newTable = $this->createTable($newSize = count($this->table) << 1);
        
        foreach ($this as $e) {
            $index = $this->indexFor($e, $newSize);
            $newTable[$index][] = $e;
        }
        
        $this->table = $newTable;
        $this->rehashThreshold = intval($newSize * $this->loadFactor);
    }
    
    /**
     * {@inheritdoc}
     */
    public function count(): int {
        return $this->count;
    }
    
    /**
     * {@inheritdoc}
     */
    public function isEmpty(): bool {
        return $this->count === 0;
    }
    
    /**
     * {@inheritdoc}
     */
    public function clear() {
        $this->table = $this->createTable(count($this->table));
        $this->count = 0;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getIterator(): \Traversable {
        foreach ($this->table as $list) {
            foreach ($list as $e) {
                yield $e;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function add($e): bool {
        $index = $this->indexFor($e, count($this->table));
        
        foreach ($this->table[$index] as $element) {
            if($this->elementsEquals($e, $element)) {
                return false;
            }
        }
        
        $this->table[$index][] = $e;
        if(++$this->count >= $this->rehashThreshold) {
            $this->rehash();
        }
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function contains($e): bool {
        $index = $this->indexFor($e, count($this->table));
        foreach ($this->table[$index] as $element) {
            if($this->elementsEquals($e, $element)) {
                return true;
            }
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($e): bool {
        $index = $this->indexFor($e, count($this->table));
        foreach ($this->table[$index] as $i => $element) {
            if($this->elementsEquals($e, $element)) {
                array_splice($this->table[$index], $i, 1);
                $this->count--;
                return true;
            }
        }
        return false;
    }
}