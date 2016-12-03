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
 * 重複要素のないコレクションです。
 * 
 * 全てのSetの基底インターフェースです。
 */
interface Set extends Collection {
    
    /**
     * このセットに指定された要素が存在しない場合、要素を追加します。
     * 
     * @param mixed $e 追加する要素。
     * @return bool このセットの内容が変更された場合true、そうでない場合false。
     */
    public function add($e): bool;
    
    /**
     * 指定した反復可能な値（arrayもしくはTraversalオブジェクト）に対しforeachで取得できる順番で、全ての要素をこのセットに追加します。
     * 
     * 既に同値の要素がある場合、要素の上書きはされません。<br>
     * 追加されるのは値のみで、キーは無視されます。
     * 
     * @param array|\Traversable $iterable 追加する反復可能な値。
     * @return bool このセットの内容が変更された場合true、そうでない場合false。
     */
    public function addAll($iterable): bool;
    
    /**
     * 指定された要素がこのセットに存在するか調べます。
     * 
     * @param mixed $e 調べる要素。
     * @return bool このセットに指定した要素が含まれている場合true、そうでない場合false。
     */
    public function contains($e): bool;
    
    /**
     * このセットに指定した反復可能な値（arrayもしくはTraversalオブジェクト）の全要素が含まれているか調べます。
     * 
     * 調査されるのは値のみで、キーは無視されます。
     * 
     * @param array|\Traversable $iterable 調べる反復可能な値。
     * @return bool このセットに指定した反復可能な値の全要素が含まれている場合true、そうでない場合false。
     */
    public function containsAll($iterable): bool;

    /**
     * このセットから指定した要素を削除します。
     * 
     * @param mixed $e 削除する要素。
     * @return bool このセットの内容が変更された場合true、そうでない場合false。
     */
    public function remove($e): bool;
    
    /**
     * このセットから指定した反復可能な値（arrayもしくはTraversalオブジェクト）の全ての要素を削除します。
     * 
     * 削除されるのは値のみで、キーは無視されます。
     * 
     * @param array|\Traversable $iterable 削除する反復可能な値。
     * @return bool このセットの内容が変更された場合true、そうでない場合false。
     */
    public function removeAll($iterable): bool;
    
    /**
     * このセットを指定したフィルタ関数を用いてフィルタリングします。
     * 
     * フィルタ関数でtrueが返された要素のみこのコレクションに残されます。
     * 
     * フィルタ関数の引数には要素が渡されます。
     * 
     * @param \Closure $predicate フィルタ関数。
     */
    public function filter(\Closure $predicate);
    
    /**
     * このセットの全要素に指定したマッパー関数を適用し、値を変換します。
     * 
     * マッパー関数の戻り値が新しい要素となります。
     * 変換の結果、同値の要素が生成され、結果としてこのセットの要素数が少なくなる可能性があります。
     * 
     * マッパー関数の引数には要素が渡されます。
     * 
     * @param \Closure $mapper マッパー関数。
     */
    public function map(\Closure $mapper);
}