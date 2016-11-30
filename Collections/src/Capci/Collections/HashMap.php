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
 * ハッシュテーブルで実装された連想配列です。
 * 
 * マップの全ての要素のキーと値にアクセスする場合、foreach構文を使用して次のような書き方ができます。
 * 
 * foreach ($map as $key => $value) {<br>
 * &nbsp;&nbsp;&nbsp;&nbsp;...<br>
 * }
 */
class HashMap extends AbstractMap {
    
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
     * ハッシュテーブルのサイズに占める全エントリーの割合がこの値を超えた場合、リハッシュが行われます。
     * 
     * リハッシュはテーブルのサイズを2倍にし、ハッシュテーブルを再構築します。
     * この動作により、エントリー数が大きくなっても、エントリーの挿入と取得のコストが大幅に劣化することが防がれます。
     * 
     * エントリー数が膨大になり、あらかじめその数が想定できる場合、適した初期容量と負荷係数を選択することで性能を向上できます。
     * あくまで目安ですが、
     * <pre>
     * 初期容量 = (エントリー数 + α) / 負荷係数
     * </pre>
     * となるような初期容量を選択すると、エントリーの挿入と取得のコストのバランスがとられます。
     * 
     * 第3引数で、このマップで要素の比較に使用する、EqualityComparerオブジェクトを指定します。
     * 省略するかnullを渡した場合、デフォルトのEqualityComparerオブジェクトを使用します。
     * 
     * @see AbstractCollection::getDefaultEqualityComparer()
     * @see Map::getEqualityComparer()
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
        
        foreach ($this as $key => $value) {
            $index = $this->indexFor($key, $newSize);
            $newTable[$index][] = $key;
            $newTable[$index][] = $value;
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
            for($i = 0, $len = count($list); $i < $len; $i += 2) {
                yield $list[$i] => $list[$i + 1];
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function put($key, $value) {
        $index = $this->indexFor($key, count($this->table));
        
        for($i = 0, $len = count($this->table[$index]); $i < $len; $i += 2) {
            if($this->elementsEquals($key, $this->table[$index][$i])) {
                $oldValue = $this->table[$index][$i + 1];
                $this->table[$index][$i] = $key;
                $this->table[$index][$i + 1] = $value;
                return $oldValue;
            }
        }
        
        $this->table[$index][] = $key;
        $this->table[$index][] = $value;
        if(++$this->count >= $this->rehashThreshold) {
            $this->rehash();
        }
        return null;
    }
    
    /**
     * {@inheritdoc}
     */
    public function get($key) {
        $index = $this->indexFor($key, count($this->table));
        for($i = 0, $len = count($this->table[$index]); $i < $len; $i += 2) {
            if($this->elementsEquals($key, $this->table[$index][$i])) {
                return $this->table[$index][$i + 1];
            }
        }
        throw new \OutOfRangeException('Key does not exists');
    }
    
    /**
     * {@inheritdoc}
     */
    public function getOrDefault($key, $defaultValue = null) {
        $index = $this->indexFor($key, count($this->table));
        for($i = 0, $len = count($this->table[$index]); $i < $len; $i += 2) {
            if($this->elementsEquals($key, $this->table[$index][$i])) {
                return $this->table[$index][$i + 1];
            }
        }
        return $defaultValue;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key) {
        $index = $this->indexFor($key, count($this->table));
        for($i = 0, $len = count($this->table[$index]); $i < $len; $i += 2) {
            if($this->elementsEquals($key, $this->table[$index][$i])) {
                array_splice($this->table[$index], $i, 2);
                $this->count--;
                return true;
            }
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function containsKey($key): bool {
        $index = $this->indexFor($key, count($this->table));
        for($i = 0, $len = count($this->table[$index]); $i < $len; $i += 2) {
            if($this->elementsEquals($key, $this->table[$index][$i])) {
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
        
        $count = 0;
        foreach ($this as $key => $value) {
            if($predicate($key, $value)) {
                $index = $this->indexFor($key, $newSize);
                $newTable[$index][] = $key;
                $newTable[$index][] = $value;
                ++$count;
            }
        }
        
        $this->table = $newTable;
        $this->count = $count;
    }
}