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
 * マップのデフォルト実装を提供する抽象クラスです。
 * 
 * このクラスのサブクラスは、より効率的な実装となるようメソッドをオーバーライドしてください。
 */
abstract class AbstractMap extends AbstractCollection implements Map {
    
    /**
     * 空のマップを作成します。
     * 
     * 第1引数で、このマップで要素の比較に使用する、EqualityComparerオブジェクトを指定します。
     * 省略するかnullを渡した場合、デフォルトのEqualityComparerオブジェクトを使用します。
     * 
     * @see AbstractCollection::getDefaultEqualityComparer()
     * @see Map::getEqualityComparer()
     * 
     * @param EqualityComparer|null $equalityComparer このマップで要素の比較に使用するEqualityComparerオブジェクト。
     */
    public function __construct(EqualityComparer $equalityComparer = null) {
        parent::__construct($equalityComparer);
    }
    
    /**
     * {@inheritdoc}
     */
    public function clear() {
        foreach ($this as $key => $value) {
            $this->remove($key);
        }
    }
    
    /**
     * このコレクションと同等の内容の配列を返します。
     * 
     * Mapはキーに任意の型を指定できるのに対し、配列はintもしくはstringしか格納できません。<br>
     * そのため、MapのtoArrayメソッドは、配列の値にキーと値の2値を格納した配列を返します。
     * 
     * [<br>
     * &nbsp;&nbsp;&nbsp;&nbsp;[key1, value1],<br>
     * &nbsp;&nbsp;&nbsp;&nbsp;[key2, value2],<br>
     * &nbsp;&nbsp;&nbsp;&nbsp;...<br>
     * ]
     * 
     * @return array このコレクションと同等の内容の配列。
     */
    public function toArray(): array {
        $array = [];
        foreach ($this as $key => $value) {
            $array[] = [$key, $value];
        }
        return $array;
    }
    
    /**
     * {@inheritdoc}
     */
    public function putAll($iterable) {
        foreach ($iterable as $key => $value) {
            $this->put($key, $value);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function get($key) {
        foreach ($this as $k => $value) {
            if($this->elementsEquals($key, $k)) {
                return $value;
            }
        }
        throw new \OutOfRangeException('Key does not exists');
    }
    
    /**
     * {@inheritdoc}
     */
    public function getOrDefault($key, $defaultValue = null) {
        foreach ($this as $k => $value) {
            if($this->elementsEquals($key, $k)) {
                return $value;
            }
        }
        return $defaultValue;
    }
    
    /**
     * {@inheritdoc}
     */
    public function containsKey($key): bool {
        foreach ($this as $k => $value) {
            if($this->elementsEquals($key, $k)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * {@inheritdoc}
     */
    public function containsValue($value): bool {
        foreach ($this as $key => $v) {
            if($this->elementsEquals($value, $v)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * {@inheritdoc}
     */
    public function keySet(): Set {
        $set = new HashSet(16, 0.75, $this->getDefaultEqualityComparer());
        foreach ($this as $key => $value) {
            $set->add($key);
        }
        return $set;
    }
    
    /**
     * {@inheritdoc}
     */
    public function offsetSet($key, $value) {
        $this->put($key, $value);
    }
    
    /**
     * {@inheritdoc}
     */
    public function offsetUnset($key) {
        $this->remove($key);
    }
    
    /**
     * {@inheritdoc}
     */
    public function offsetGet($key) {
        return $this->get($key);
    }
    
    /**
     * {@inheritdoc}
     */
    public function offsetExists($key) {
        if(!$this->containsKey($key)) {
            return false;
        }
        return $this->get($key) !== null;
    }
    
    /**
     * {@inheritdoc}
     */
    public function filter(\Closure $predicate) {
        $filterd = [];
        foreach ($this as $key => $value) {
            if($predicate($key, $value) === true) {
                $filterd[] = [$key, $value];
            }
        }
        $this->clear();
        foreach ($filterd as list($key, $value)) {
            $this->put($key, $value);
        }
    }
}