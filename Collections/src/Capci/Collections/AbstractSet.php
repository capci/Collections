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
 * セットのデフォルト実装を提供する抽象クラスです。
 * 
 * このクラスのサブクラスは、より効率的な実装となるようメソッドをオーバーライドしてください。
 */
abstract class AbstractSet extends AbstractCollection implements Set {
    
    /**
     * 空のセットを作成します。
     * 
     * 第1引数で、このセットで要素の比較に使用する、EqualityComparerオブジェクトを指定します。
     * 省略するかnullを渡した場合、デフォルトのEqualityComparerオブジェクトを使用します。
     * 
     * @see AbstractSet::getDefaultEqualityComparer()
     * @see Set::getEqualityComparer()
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
        foreach ($this as $e) {
            $this->remove($e);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function addAll($iterable): bool {
        $modified = false;
        foreach ($iterable as $e) {
            if($this->add($e)) {
                $modified = true;
            }
        }
        return $modified;
    }
    
    /**
     * {@inheritdoc}
     */
    public function contains($e): bool {
        foreach ($this as $element) {
            if($this->elementsEquals($e, $element)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * {@inheritdoc}
     */
    public function containsAll($iterable): bool {
        foreach ($iterable as $e) {
            if(!$this->contains($e)) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function removeAll($iterable): bool {
        $modified = false;
        foreach ($iterable as $e) {
            if($this->remove($e)) {
                $modified = true;
            }
        }
        return $modified;
    }
    
    /**
     * {@inheritdoc}
     */
    public function filter(\Closure $predicate) {
        $filterd = [];
        foreach ($this as $e) {
            if($predicate($e) === true) {
                $filterd[] = $e;
            }
        }
        $this->clear();
        $this->addAll($filterd);
    }
}