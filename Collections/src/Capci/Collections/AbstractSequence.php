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
 * シーケンスのデフォルト実装を提供する抽象クラスです。
 * 
 * このクラスのサブクラスは、より効率的な実装となるようメソッドをオーバーライドしてください。
 */
abstract class AbstractSequence extends AbstractCollection implements Sequence {
    
    /**
     * {@inheritdoc}
     */
    public function clear() {
        while (!$this->isEmpty()) {
            $this->remove(0);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addAll(Collection $c) {
        foreach ($c as $e) {
            $this->add($e);
        } 
    }
    
    /**
     * {@inheritdoc}
     */
    public function insert(int $index, $e) {
        $modified = false;
        $elements = $this->toArray();
        $this->clear();
        foreach ($elements as $i => $element) {
            if($i === $index) {
                $this->add($e);
                $modified = true;
            }
            $this->add($element);
        }
        if(!$modified) {
            if($index === $this->count()) {
                $this->add($e);
            } else {
                throw new \OutOfRangeException('Index is out of range: ' . $index);
            }
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function insertAll(int $index, Collection $c) {
        foreach ($c as $e) {
            $this->insert($index++, $e);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function get(int $index) {
        foreach ($this as $i => $e) {
            if($i === $index) {
                return $e;
            }
        }
        throw new \OutOfRangeException('Index is out of range: ' . $index);
    }
    
    /**
     * {@inheritdoc}
     */
    public function set(int $index, $e) {
        $modified = false;
        $elements = $this->toArray();
        $this->clear();
        foreach ($elements as $i => $element) {
            if($i === $index) {
                $this->add($e);
                $ret = $element;
                $modified = true;
            } else {
                $this->add($element);
            }
        }
        if(!$modified) {
            throw new \OutOfRangeException('Index is out of range: ' . $index);
        }
        return $ret;
    }
    
    /**
     * {@inheritdoc}
     */
    public function offsetSet($index, $e) {
        if($index === null) {
            $this->add($e);
        } else {
            $this->set($index, $e);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function offsetUnset($index) {
        throw new \BadMethodCallException('Method "offsetUnset" is not supported');
    }
    
    /**
     * {@inheritdoc}
     */
    public function offsetGet($index) {
        return $this->get($index);
    }
    
    /**
     * {@inheritdoc}
     */
    public function offsetExists($index) {
        if($index >= 0 && $index < $this->count()) {
            return $this->get($index) !== null;
        }
        return false;
    }
    
    /**
     * 2つの要素が同値であるかを判定します。
     * 
     * 要素の同値性の判定が必要となるコレクションのメソッドは、このメソッドの実装により挙動が変わります。
     * 
     * このメソッドの実装は、以下のメソッドの挙動に影響を与えます。
     * <ul>
     * <li>contains</li>
     * <li>containsAll</li>
     * <li>indexOf</li>
     * <li>lastIndexOf</li>
     * </ul>
     * 
     * このクラスでは、'==='演算子で比較します。
     * 
     * @param mixed $e1 1つめの要素。
     * @param mixed $e2 2つめの要素。
     * @return bool 2つの要素が同値である場合true、そうでない場合false。
     */
    public function compareElements($e1, $e2): bool {
        return parent::compareElements($e1, $e2);
    }
    
    /**
     * {@inheritdoc}
     */
    public function contains($e): bool {
        foreach ($this as $element) {
            if($this->compareElements($e, $element)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * {@inheritdoc}
     */
    public function containsAll(Collection $c): bool {
        foreach ($c as $e) {
            if(!$this->contains($e)) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function indexOf($e): int {
        foreach ($this as $index => $element) {
            if($this->compareElements($e, $element)) {
                return $index;
            }
        }
        return -1;
    }
    
    /**
     * {@inheritdoc}
     */
    public function lastIndexOf($e): int {
        for ($index = $this->count() - 1; $index >= 0; --$index) {
            if($this->compareElements($e, $this->get($index))) {
                return $index;
            }
        }
        return -1;
    }
    
    /**
     * {@inheritdoc}
     */
    public function range(int $index, int $count): Sequence {
        $subSequence = new ArrayList();
        for ($i = $index; $i < ($index + $count); ++$i) {
            $subSequence->add($this->get($i));
        }
        return $subSequence;
    }
}