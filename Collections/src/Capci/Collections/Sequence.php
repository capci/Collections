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
interface Sequence extends Collection {
    
    /**
     * このシーケンスの末尾に要素を追加します。
     * 
     * @param mixed $e 追加する要素。
     */
    public function add($e);
    
    /**
     * このシーケンスの末尾に指定したコレクションの全要素を追加します。
     * 
     * @param \Capci\Collections\Collection $c 追加するコレクション。
     */
    public function addAll(Collection $c);
    
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
}