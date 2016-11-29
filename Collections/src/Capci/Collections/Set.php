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
     * このセットに指定したコレクションの全要素が含まれているか調べます。
     * 
     * @param Collection $c 調べるコレクション。
     * @return bool このセットに指定したコレクションの全要素が含まれている場合true、そうでない場合false。
     */
    public function containsAll(Collection $c): bool;

    /**
     * このセットから指定した要素を削除します。
     * 
     * @param mixed $e 削除する要素。
     * @return bool このセットの内容が変更された場合true、そうでない場合false。
     */
    public function remove($e): bool;
    
    /**
     * このセットから指定したコレクションの全ての要素を削除します。
     * 
     * @param Collection $c 削除するコレクション。
     * @return bool このセットの内容が変更された場合true、そうでない場合false。
     */
    public function removeAll(Collection $c): bool;
}