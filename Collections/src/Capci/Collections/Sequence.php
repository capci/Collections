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
 * 順序付けられたコレクションです。
 * 
 * 全てのSequenceの基底インターフェースです。
 */
interface Sequence extends Collection, \ArrayAccess {
    
    /**
     * このシーケンスの末尾に要素を追加します。
     * 
     * @param mixed $e 追加する要素。
     */
    public function add($e);
    
    /**
     * 指定した反復可能な値（arrayもしくはTraversalオブジェクト）に対しforeachで取得できる順番で、全ての要素をこのシーケンスの末尾に追加します。
     * 
     * 追加されるのは値のみで、キーは無視されます。
     * 
     * @param array|\Traversable $iterable 追加する反復可能な値。
     */
    public function addAll($iterable);
    
    /**
     * このシーケンスの指定した位置に要素を挿入します。
     * 
     * @param int $index 挿入する位置。
     * @param mixed $e 挿入する要素。
     * @throws \OutOfRangeException インデックスが範囲外の場合（$index < 0 || $index > count()）。
     */
    public function insert(int $index, $e);
    
    /**
     * このシーケンスの指定した位置に指定したコレクションの全要素を挿入します。
     * 
     * @param int $index 挿入する位置。
     * @param \Capci\Collections\Collection $c 挿入するコレクション。
     * @throws \OutOfRangeException インデックスが範囲外の場合（$index < 0 || $index > count()）。
     */
    public function insertAll(int $index, Collection $c);
    
    /**
     * このシーケンスの指定した位置の要素を返します。
     * 
     * @param int $index 返される要素の位置。
     * @return mixed 指定された位置の要素。
     * @throws \OutOfRangeException インデックスが範囲外の場合（$index < 0 || $index >= count()）。
     */
    public function get(int $index);
    
    /**
     * このシーケンスの指定した位置の要素を返します。
     * 
     * インデックスが範囲外の場合、引数で指定したデフォルト値を返します。
     * 
     * @param int $index 返される要素の位置。
     * @param mixed $defaultValue デフォルト値。
     * @return mixed 指定された位置の要素。
     */
    public function getOrDefault(int $index, $defaultValue = null);

    /**
     * このシーケンスの指定した位置の要素を置き換えます。
     * 
     * @param int $index 置き換える位置。
     * @param mixed $e 置き換える要素。
     * @return mixed 置き換えられた要素。
     * @throws \OutOfRangeException インデックスが範囲外の場合（$index < 0 || $index >= count()）。
     */
    public function set(int $index, $e);
    
    /**
     * このシーケンスの指定した位置の要素を削除し、後続の要素を1つずつ左に移動します。
     * 
     * @param int $index 削除する位置。
     * @return mixed 削除される要素。
     * @throws \OutOfRangeException インデックスが範囲外の場合（$index < 0 || $index >= count()）。
     */
    public function remove(int $index);
    
    /**
     * このシーケンスで要素の比較に使用されている、EqualityComparerオブジェクトを返します。
     * 
     * 以下のメソッドが、EqualityComparerオブジェクトの実装に依存します。
     * <ul>
     * <li>contains</li>
     * <li>containsAll</li>
     * <li>indexOf</li>
     * <li>lastIndexOf</li>
     * </ul>
     * 
     * @return EqualityComparer 要素の比較に使用されているEqualityComparerオブジェクト。
     */
    public function getEqualityComparer(): EqualityComparer;
    
    /**
     * このシーケンスに指定した要素が含まれているか調べます。
     * 
     * このメソッドはelementsEqualsメソッドの実装に影響を受けます。
     * @see Sequence::elementsEquals($e1, $e2)
     * 
     * @param mixed $e 調べる要素。
     * @return bool このシーケンスに指定した要素が含まれている場合true、そうでない場合false。
     */
    public function contains($e): bool;
    
    /**
     * このシーケンスに指定したコレクションの全要素が含まれているか調べます。
     * 
     * このメソッドはelementsEqualsメソッドの実装に影響を受けます。
     * @see Sequence::elementsEquals($e1, $e2)
     * 
     * @param Collection $c 調べるコレクション。
     * @return bool このシーケンスに指定したコレクションの全要素が含まれている場合true、そうでない場合false。
     */
    public function containsAll(Collection $c): bool;
    
    /**
     * このシーケンスで指定した要素が最初に検出された位置を返します。
     * 
     * 見つからなかった場合-1を返します。
     * 
     * このメソッドはelementsEqualsメソッドの実装に影響を受けます。
     * @see Sequence::elementsEquals($e1, $e2)
     * 
     * @param mixed $e 調べる要素。
     * @return int このシーケンスで指定した要素が最初に検出された位置。
     */
    public function indexOf($e): int;
    
    /**
     * このシーケンスで指定した要素が最後に検出された位置を返します。
     * 
     * 見つからなかった場合-1を返します。
     * 
     * このメソッドはelementsEqualsメソッドの実装に影響を受けます。
     * @see Sequence::elementsEquals($e1, $e2)
     * 
     * @param mixed $e 調べる要素。
     * @return int このシーケンスで指定した要素が最後に検出された位置。
     */
    public function lastIndexOf($e): int;
    
    /**
     * このシーケンスの一部を新しいシーケンスとして返します。
     * 
     * @param int $index 開始インデックス。
     * @param int $count 新しいシーケンスの要素数。
     * @return Sequence 切り出されたシーケンス。
     * @throws \OutOfRangeException インデックスが範囲外の場合（$index < 0 || $count < 0 || ($index + $count) > count()）。
     */
    public function range(int $index, int $count): Sequence;
    
    /**
     * このシーケンスの指定した位置の要素を置き換えます。
     * 
     * 第1引数にnullを渡した場合、このシーケンスの末尾に要素を追加します。
     * 
     * @param int|null $index 置き換える位置。
     * @param mixed $e 置き換える要素。
     * @throws \OutOfRangeException インデックスが範囲外の場合（$index < 0 || $index >= count()）。
     */
    public function offsetSet($index, $e);
    
    /**
     * シーケンスではこのメソッドはサポートされていません。
     * 
     * \BadMethodCallExceptionがスローされます。
     * 
     * @param int $index インデックス。
     * @throws \BadMethodCallException 必ずスローされます。
     */
    public function offsetUnset($index);
    
    /**
     * このシーケンスの指定した位置の要素を返します。
     * 
     * @param int $index 返される要素の位置。
     * @return mixed 指定された位置の要素。
     * @throws \OutOfRangeException インデックスが範囲外の場合（$index < 0 || $index >= count()）。
     */
    public function offsetGet($index);
    
    /**
     * 指定した位置に要素が存在し、かつその要素がnullでないかを調べます。
     * 
     * @param int $index 調べるインデックス。
     * @return bool 指定した位置に要素が存在し、かつその要素がnullでない場合true、そうでない場合false。
     */
    public function offsetExists($index);
    
    /**
     * このシーケンスを指定した比較関数を用いて順序付けし、並べ替えます。
     * 
     * 比較関数には2つの要素が引数で渡されます。次のルールに従って実装してください。
     * <ul>
     * <li>第1要素が第2要素より小さければ負の整数値。</li>
     * <li>第1要素が第2要素より大きければ正の整数値。</li>
     * <li>第1要素と第2要素が同じであれば0。</li>
     * </ul>
     * 
     * @param \Closure $comparator 比較関数。
     */
    public function sort(\Closure $comparator);
    
    /**
     * このシーケンスを指定したフィルタ関数を用いてフィルタリングします。
     * 
     * フィルタ関数でtrueが返された要素のみこのコレクションに残されます。
     * 
     * フィルタ関数の引数にはインデックスと要素が渡されます。
     * 
     * @param \Closure $predicate フィルタ関数。
     */
    public function filter(\Closure $predicate);
    
    /**
     * このシーケンスの順序をランダムに並べ替えます。
     */
    public function shuffle();
    
}