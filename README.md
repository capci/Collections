Capci\Collections
====

PHP7で利用可能なコレクションフレームワークです。
コレクションの操作をオブジェクト指向的なコーディングで可能にすることを目的としています。

## 概要

3つの主要なインターフェース（Sequence、Map、Set）と実装クラス（ArrayList、HashMap、HashSet）が存在します。

### Sequence

順序付けられたコレクションです。JavaのListに相当します。
実装クラスとしてArrayListが存在します。

以下のような操作が可能です。
* 要素の追加（add、addAll）、挿入（insert、insertAll）、置き換え（set）、取得（get、getOrDefault）、削除（remove）。
* ' [] ' 演算子による配列としてのアクセス。
* foreach構文による反復処理。
* 要素のカウント（count、isEmpty）、クリア（clear）、配列への変換（toArray）。
* 要素の調査（contains、containsAll）、探索（indexOf、lastIndexOf）。
* 要素のフィルタリング（filter）、変換（map）、並べ替え（sort）、ランダムな並べ替え（shuffle）。

### Map

連想配列です。arrayと違い、stringやintを含む**全ての型をキーに指定できます**。
実装クラスとしてHashMapが存在します。

以下のような操作が可能です。
* 要素の追加と置き換え（put、putAll）、取得（get、getOrDefault）、削除（remove）。
* ' [] ' 演算子による配列としてのアクセス。
* foreach構文による反復処理。
* 要素のカウント（count、isEmpty）、クリア（clear）、配列への変換（toArray）。
* 要素の調査（containsKey、containsValue）。
* 要素のフィルタリング（filter）、変換（map）。

### Set

重複要素のないコレクションです。
実装クラスとしてHashSetが存在します。

以下のような操作が可能です。
* 要素の追加（add、addAll）、削除（remove、removeAll）。
* foreach構文による反復処理。
* 要素のカウント（count、isEmpty）、クリア（clear）、配列への変換（toArray）。
* 要素の調査（contains、containsAll）。
* 要素のフィルタリング（filter）、変換（map）。

## 基本的な使い方

### ライブラリのロード
autoload.phpを読み込みます。

    require './Capci/Collections/autoload.php';

### ArrayList

#### 要素の追加（add、addAll）、挿入（insert、insertAll）、置き換え（set）、取得（get、getOrDefault）、削除（remove）。

    $list = new ArrayList();
    
    // 要素を末尾に追加。
    $list->add(0);  //=> [0]

    // 複数の要素を末尾に追加。arrayとTraversalを指定可能。
    $list->addAll([1, 2, 3]); //=> [0, 1, 2, 3]

    // 要素を途中に挿入。
    $list->insert(1, null); //=> [0, null, 1, 2, 3]

    // 複数の要素を途中に挿入。arrayとTraversalを指定可能。
    $list->insertAll(3, ['foo', 'bar']); //=> [0, null, 1, 'foo', 'bar', 2, 3]

    // 要素を置き換え。
    $list->set(6, 3.2); //=> [0, null, 1, 'foo', 'bar', 2, 3.2]

    // 要素の取得。存在しないインデックスの場合、OutOfRangeExceptionがスローされる。
    var_dump($list->get(5)); //=> 2

    // 要素の取得。存在しないインデックスの場合、デフォルト値が返される。
    var_dump($list->getOrDefault(-1, 'default')); //=> 'default'

    // 要素の削除。後続の要素は1つずつ左に移動する。
    $list->remove(0); //=> [null, 1, 'foo', 'bar', 2, 3.2]

#### ' [] ' 演算子による配列としてのアクセス。

    // 要素を末尾に追加。
    $list[] = 0;  //=> [0]
    $list[] = 1;  //=> [0, 1]
    
    // 要素を置き換え。
    $list[1] = 2; //=> [0, 2]
    
    // 要素の存在確認。
    var_dump(isset($list[2])); //=> false
    
    // 要素の取得。存在しないインデックスの場合、OutOfRangeExceptionがスローされる。
    var_dump($list[1]); //=> 2
    
    // unsetは非対応。BadMethodCallExceptionがスローされる。
    //unset($list[0]);

#### foreach構文による反復処理。

    $list->addAll(['foo', 'bar', 'hoge', 'piyo']);
    
    foreach ($list as $index => $value) {
        echo 'index: ';var_dump($index);
        echo 'value: ';var_dump($value);
        echo PHP_EOL;
    }

出力結果。Sequenceでは順番は保証される。

    index: int(0)
    value: string(3) "foo"
    
    index: int(1)
    value: string(3) "bar"
    
    index: int(2)
    value: string(4) "hoge"
    
    index: int(3)
    value: string(4) "piyo"


### HashMap

#### 要素の追加と置き換え（put、putAll）、取得（get、getOrDefault）、削除（remove）。

    $map = new HashMap();
    
    // 要素の追加
    $map->put(5, 'foo'); //=> [5 => 'foo']
    $map->putAll(['bar' => 1.23,　'hoge' => null]); //=> [5 => 'foo', 'bar' => 1.23, 'hoge' => null]
    
    // string、int以外にも、あらゆる型をキーに指定可能
    $o = new \stdClass();
    $map->put($o, true); //=> [5 => 'foo', 'bar' => 1.23, 'hoge' => null, object(stdClass) => true]
    $map->put(null, false); //=> [5 => 'foo', 'bar' => 1.23, 'hoge' => null, object(stdClass) => true, null => false]
    
    // 要素の置き換え
    $map->put(5, 'piyo'); //=> [5 => 'piyo', 'bar' => 1.23, 'hoge' => null, object(stdClass) => true, null => false]
    
    // 要素の取得。存在しないキーの場合、OutOfRangeExceptionがスローされる。
    var_dump($map->get($o)); //=> true

    // 要素の取得。存在しないキーの場合、デフォルト値が返される。
    var_dump($map->getOrDefault(1.23, 'default')); //=> 'default'
    
    // 要素の削除。
    $list->remove(null); //=> [5 => 'piyo', 'bar' => 1.23, 'hoge' => null, object(stdClass) => true]

#### ' [] ' 演算子による配列としてのアクセス。

    // 要素の追加
    $map[5] = 'foo'; //=> [5 => 'foo']
    
    // string、int以外にも、あらゆる型をキーに指定可能
    $o = new \stdClass();
    $map[$o] = true; //=> [5 => 'foo', object(stdClass) => true]
    $map[null] = false; //=> [5 => 'foo', object(stdClass) => true, null => false]
    
    // 要素の置き換え
    $map[5] = 'piyo'; //=> [5 => 'piyo', object(stdClass) => true, null => false]
    
    // 要素の存在確認。
    var_dump(isset($map[$o])); //=> true
    
    // 要素の取得。存在しないキーの場合、OutOfRangeExceptionがスローされる。
    var_dump($map[null]); //=> false
    
    // キーの削除。
    unset($map[$o]); //=> [5 => 'piyo', null => false]

#### foreach構文による反復処理。

    $map[1]                               = 'key is int 1';
    $map['foo']                           = 'key is string "foo"';
    $map[true]                            = 'key is bool true';
    $map[1.23]                            = 'key is float 1.23';
    $map[null]                            = 'key is null';
    $map[ [] ]                            = 'key is empty array';
    $map[$o = new \stdClass()]            = 'key is stdClass object';
    $map[$h = fopen('php://output', 'r')] = 'key is resource';
    
    foreach ($map as $key => $value) {
        echo 'key  : ';var_dump($key);
        echo 'value: ';var_dump($value);
        echo PHP_EOL;
    }

出力結果。Mapでは順番は保証されない。

    key  : NULL
    value: string(11) "key is null"

    key  : int(1)
    value: string(12) "key is int 1"

    key  : string(3) "foo"
    value: string(19) "key is string "foo""

    key  : array(0) {
    }
    value: string(18) "key is empty array"

    key  : object(stdClass)#4 (0) {
    }
    value: string(22) "key is stdClass object"

    key  : float(1.23)
    value: string(17) "key is float 1.23"

    key  : bool(true)
    value: string(16) "key is bool true"

    key  : resource(12) of type (stream)
    value: string(15) "key is resource"

### HashSet

#### 要素の追加（add、addAll）、削除（remove、removeAll）。

    $set = new HashSet();
    
    // 要素を追加。
    $set->add(0);  //=> [0]

    // 複数の要素を追加。arrayとTraversalを指定可能。
    $set->addAll([1, 2, 3]); //=> [0, 1, 2, 3]
    
    // 重複する要素は追加されない。
    $set->add(2);  //=> [0, 1, 2, 3]
    
    // 要素の削除。
    $set->remove(0); //=> [1, 2, 3]

#### foreach構文による反復処理。

    $set = new HashSet();

    $set->addAll([true, 'foo', 1, 1.23, null, [], new \stdClass(), fopen('php://output', 'w')]);
    
    foreach ($set as $value) {
        echo 'value: ';var_dump($value);
        echo PHP_EOL;
    }

出力結果。Setでは順番は保証されない。

    value: NULL
    
    value: string(3) "foo"
    
    value: int(1)
    
    value: array(0) {
    }
    
    value: object(stdClass)#4 (0) {
    }
    
    value: float(1.23)
    
    value: bool(true)
    
    value: resource(12) of type (stream)


## 同値性とハッシュコード

Sequence、Map、Setのデフォルトの動作は、同値性を '===' 演算子で判断します。

    $map = new HashMap();
    
    $key1 = new \stdClass();
    $key2 = new \stdClass();
    
    $map->put($key1, 'value');
    
    // キーが存在しないと判断され、OutOfRangeExceptionがスローされる。
    var_dump($map->get($key2)); //=> throws OutOfRangeException

この動作は、EqualityComparerインターフェースを実装したオブジェクトをコンストラクタに渡すことで、自由に変更することができます。

    // 第3引数で意図した動作をするEqualityComparerオブジェクトを渡す。
    $map = new HashMap(16, 0.75, new class implements EqualityComparer {
        
            /*
             * 2つの要素が同値である場合true、そうでない場合falseを返します。
             */
            public function elementsEquals($e1, $e2): bool {
                //同じ型であれば同値とみなす。
                if($e1 === $e2) {
                    return true;
                }
                return gettype($e1) === gettype($e2);
            }
            
            /*
             * 次の2つの規約に従って、要素のハッシュ値を返します。
             * 
             * 同値の要素は、必ず同じハッシュ値を返します。
             * 
             * 同値でない要素は、必ずしも同じハッシュ値を返す必要はありませんが、
             * できるだけ異なるハッシュ値を返した方が、HashMapやHashSetの性能が向上します。
             */
            public function elementHashCode($e): int {
                return crc32(gettype($e));
            }
        });
    
    $key1 = new \stdClass();
    $key2 = new \stdClass();
    
    $map->put($key1, 'value');
    
    // $key1と$key2は同値と判断される。
    var_dump($map->get($key2)); //=> 'value'


## ライセンス

MIT License http://www.opensource.org/licenses/mit-license.php
