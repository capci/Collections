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
    
    /**
     * 空のマップを作成します。
     * 
     * 第1引数の$initialCapacityはハッシュテーブルの初期容量を決める値で、実際はこの値以上の最も小さい2のべき乗数が初期容量となります。
     * 
     * 第2引数の$loadFactorはリハッシュ（ハッシュテーブルの再構築）のタイミングを決める値で、
     * ハッシュテーブルのサイズに占める全要素の割合がこの値を超えた場合、リハッシュが行われます。
     * 
     * リハッシュはテーブルのサイズを2倍にし、ハッシュテーブルを再構築します。
     * この動作により、エントリー数が大きくなっても、エントリーの挿入と取得のコストが大幅に劣化することが防がれます。
     * 
     * エントリー数が膨大になり、あらかじめその数が想定できる場合、適した初期容量と負荷係数を選択することで性能を向上できます。
     * あくまで目安ですが、
     * <pre>
     * 初期容量 = (要素数 + α) / 負荷係数
     * </pre>
     * となるような初期容量を選択すると、エントリーの挿入と取得のコストのバランスがとられます。
     * 
     * 第3引数で、このマップで要素の比較に使用する、EqualityComparerオブジェクトを指定します。
     * 省略するかnullを渡した場合、デフォルトのEqualityComparerオブジェクトを使用します。
     * 
     * @see AbstractCollection::getDefaultEqualityComparer()
     * @see Set::getEqualityComparer()
     * 
     * @param int $initialCapacity 初期容量、デフォルト値は16。
     * @param float $loadFactor 負荷係数、デフォルト値は0.75。
     * @param EqualityComparer $equalityComparer このマップで要素の比較に使用するEqualityComparerオブジェクト。
     * @throws \InvalidArgumentException $initialCapacityが0以下、$loadFactorが負の数もしくはNaNの場合。
     */
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
        return array_fill(0, $size, []);
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
    
    /**
     * {@inheritdoc}
     */
    public function filter(\Closure $predicate) {
        $newTable = $this->createTable($newSize = count($this->table));
        
        $newCount = 0;
        foreach ($this as $e) {
            if($predicate($e)) {
                $index = $this->indexFor($e, $newSize);
                $newTable[$index][] = $e;
                ++$newCount;
            }
        }
        
        $this->table = $newTable;
        $this->count = $newCount;
    }
}