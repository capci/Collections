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
 * 任意の型をキーに指定可能な、連想配列です。
 * 
 * 全てのMapの基底インターフェースです。
 * 
 * マップの全ての要素のキーと値にアクセスする場合、foreach構文を使用して次のような書き方ができます。
 * 
 * foreach ($map as $key => $value) {<br>
 * &nbsp;&nbsp;&nbsp;&nbsp;...<br>
 * }
 */
interface Map extends Collection, \ArrayAccess {
    
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
    public function toArray(): array;
    
    /**
     * このマップで要素の比較に使用されている、EqualityComparerオブジェクトを返します。
     * 
     * EqualityComparerオブジェクトで同値とみなされる2つの要素は同じキーとみなされ、
     * キーを指定するメソッドは全て同じ動作をします。
     * 
     * @return EqualityComparer 要素の比較に使用されているEqualityComparerオブジェクト。
     */
    public function getEqualityComparer(): EqualityComparer;
    
    /**
     * このマップにキーと値を関連付けます。
     * 
     * すでに同じキーに関連付けられている値が存在する場合、新しい値で上書きされ、古い値が返されます。
     * 関連付けられている値が存在しない場合、nullを返します。
     * 
     * @param mixed $key キー。
     * @param mixed $value 値。
     * @return mixed|null すでに同じキーに関連付けられている値。存在しない場合null。
     */
    public function put($key, $value);
    
    /**
     * このマップに指定したマップの関連付けをコピーします。
     * 
     * すでに同じキーに関連付けられている値が存在する場合、新しい値で上書きされます。
     * 
     * @param Map $m コピーするマップ。
     */
    public function putAll(Map $m);
    
    /**
     * 指定されたキーに関連付けられている値を返します。
     * 
     * 関連付けられている値が存在しない場合、OutOfRangeExceptionがスローされます。
     * 
     * @param mixed $key キー。
     * @return mixed 指定されたキーに関連付けられている値。
     * @throws \OutOfRangeException 関連付けられている値が存在しない場合。
     */
    public function get($key);
    
    /**
     * 指定されたキーに関連付けられている値を返します。
     * 
     * 関連付けられている値が存在しない場合、引数で指定したデフォルト値を返します。
     * 
     * @param mixed $key キー。
     * @param mixed $defaultValue デフォルト値。
     * @return mixed 指定されたキーに関連付けられている値。
     * @throws \OutOfRangeException 関連付けられている値が存在しない場合。
     */
    public function getOrDefault($key, $defaultValue = null);
    
    /**
     * 指定されたキーの関連付けを削除します。
     * 
     * @param mixed $key キー。
     * @return bool 関連付けられている値が存在した場合true、そうでない場合false。
     */
    public function remove($key);
    
    /**
     * このマップに指定したキーの関連付けが存在するか調べます。
     * 
     * @param mixed $key キー。
     * @return bool このマップに指定したキーの関連付けが存在する場合true、そうでない場合false。
     */
    public function containsKey($key): bool;
    
    /**
     * このマップに指定した値が存在するか調べます。
     * 
     * @param mixed $value 値。
     * @return bool このマップに指定した値が存在する場合true、そうでない場合false。
     */
    public function containsValue($value): bool;
    
    /**
     * このマップのキーの集合を返します。
     * 
     * @return Set このマップのキーの集合。
     */
    public function keySet(): Set;

    /**
     * このマップにキーと値を関連付けます。
     * 
     * すでに同じキーに関連付けられている値が存在する場合、新しい値で上書きされます。
     * 
     * <b>！注意！</b><br>
     * $keyに整数値とみなせるstring値（例：'3'）を渡した場合、自動でint値にキャストされます。
     * すなわち、「 $map['3'] 」は「 $map[3] 」と解釈されます。<br>
     * これはPHPの実行環境のArrayAccessインターフェースの処理に起因しています。<br>
     * この問題を回避するには、putメソッドを使用してください。
     * 
     * @param mixed $key キー。
     * @param mixed $value 値。
     */
    public function offsetSet($key, $value);
    
    /**
     * 指定されたキーの関連付けを削除します。
     * 
     * <b>！注意！</b><br>
     * $keyに整数値とみなせるstring値（例：'3'）を渡した場合、自動でint値にキャストされます。
     * すなわち、「 $map['3'] 」は「 $map[3] 」と解釈されます。<br>
     * これはPHPの実行環境のArrayAccessインターフェースの処理に起因しています。<br>
     * この問題を回避するには、removeメソッドを使用してください。
     * 
     * @param mixed $key キー。
     */
    public function offsetUnset($key);
    
    /**
     * 指定されたキーに関連付けられている値を返します。
     * 
     * 関連付けられている値が存在しない場合、OutOfRangeExceptionがスローされます。
     * 
     * <b>！注意！</b><br>
     * $keyに整数値とみなせるstring値（例：'3'）を渡した場合、自動でint値にキャストされます。
     * すなわち、「 $map['3'] 」は「 $map[3] 」と解釈されます。<br>
     * これはPHPの実行環境のArrayAccessインターフェースの処理に起因しています。<br>
     * この問題を回避するには、getメソッドを使用してください。
     * 
     * @param mixed $key キー。
     * @return mixed 指定されたキーに関連付けられている値。
     * @throws \OutOfRangeException 関連付けられている値が存在しない場合。
     */
    public function offsetGet($key);
    
    /**
     * このマップに指定したキーの関連付けが存在し、かつその値がnullでないかを調べます。
     * 
     * <b>！注意！</b><br>
     * $keyに整数値とみなせるstring値（例：'3'）を渡した場合、自動でint値にキャストされます。
     * すなわち、「 $map['3'] 」は「 $map[3] 」と解釈されます。<br>
     * これはPHPの実行環境のArrayAccessインターフェースの処理に起因しています。<br>
     * この問題を回避するには、containsKeyメソッドを使用してください。
     * 
     * @param mixed $key キー。
     * @return bool このマップに指定したキーの関連付けが存在しかつその要素がnullでない場合true、そうでない場合false。
     */
    public function offsetExists($key);
}