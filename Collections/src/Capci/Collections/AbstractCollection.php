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
    
    private static $defaultEqualityComparer = null;
    
    /**
     * デフォルトのEqualityComparerオブジェクトを返します。
     * 
     * このメソッドで取得できるEqualityComparerオブジェクトは、同値性の判定に '===' 演算子を使用します。
     * 
     * @return EqualityComparer デフォルトのEqualityComparerオブジェクト。
     */
    public static function getDefaultEqualityComparer(): EqualityComparer {
        if(self::$defaultEqualityComparer === null) {
            self::$defaultEqualityComparer = new class implements EqualityComparer {
                public function elementsEquals($e1, $e2): bool {
                    return $e1 === $e2;
                }
                public function elementHashCode($e): int {
                    if(is_string($e)) {
                        return crc32($e);
                    }
                    if(is_int($e)) {
                        return $e;
                    }
                    if(is_object($e)) {
                        return crc32(spl_object_hash($e));
                    }
                    if(is_bool($e)) {
                        return $e ? 1231 : 1237;
                    }
                    if(is_null($e)) {
                        return 0;
                    }
                    if(is_float($e)) {
                        return unpack('q', pack('d', $e))[1];
                    }
                    if(is_array($e)) {
                        $hash = 1;
                        foreach ($e as $k => $v) {
                            $hash *= 31;
                            if(is_float($hash)) {
                                $hash = $this->elementHashCode($hash);
                            }
                            $hash += $this->elementHashCode($k) ^ $this->elementHashCode($v);
                            if(is_float($hash)) {
                                $hash = $this->elementHashCode($hash);
                            }
                        }
                        return $hash;
                    }
                    if(is_resource($e)) {
                        return crc32(strval($e));
                    } else if(gettype($e) === 'unknown type') {
                        // closed resource
                        return crc32(strval($e));
                    }
                    return 0;
                }
            };
        }
        return self::$defaultEqualityComparer;
    }

    /**
     * @var EqualityComparer
     */
    private $equalityComparer;
    
    /**
     * 空のコレクションを作成します。
     * 
     * 第1引数で、このコレクションで要素の比較に使用する、EqualityComparerオブジェクトを指定します。<br>
     * 省略するかnullを渡した場合、getDefaultEqualityComparerメソッドで取得できる、
     * デフォルトのEqualityComparerオブジェクトを使用します。
     * 
     * @see AbstractCollection::getDefaultEqualityComparer()
     * 
     * @param EqualityComparer|null $equalityComparer このシーケンスで要素の比較に使用するEqualityComparerオブジェクト。
     */
    public function __construct(EqualityComparer $equalityComparer = null) {
        if($equalityComparer === null) {
            $equalityComparer = self::getDefaultEqualityComparer();
        }
        $this->equalityComparer = $equalityComparer;
    }

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
    
    /**
     * {@inheritdoc}
     */
    public function getEqualityComparer(): EqualityComparer {
        return $this->equalityComparer;
    }
    
    /**
     * 2つの要素が同値であるかを、このコレクションのコンストラクタで指定されたEqualityComparerオブジェクトを使用して判定します。
     * 
     * @see EqualityComparer::elementsEquals($e1, $e2)
     * 
     * @param mixed $e1 1つめの要素。
     * @param mixed $e2 2つめの要素。
     * @return bool 2つの要素が同値である場合true、そうでない場合false。
     */
    protected function elementsEquals($e1, $e2): bool {
        return $this->equalityComparer->elementsEquals($e1, $e2);
    }
    
    /**
     * 任意の要素のハッシュ値を、このコレクションのコンストラクタで指定されたEqualityComparerオブジェクトを使用して計算します。
     * 
     * @see EqualityComparer::elementHashCode($e)
     * @see AbstractCollection::elementsEquals($e1, $e2)
     * 
     * @param mixed $e 要素。
     * @return int 要素のハッシュ値。
     */
    protected function elementHashCode($e): int {
        return $this->equalityComparer->elementHashCode($e);
    }
}