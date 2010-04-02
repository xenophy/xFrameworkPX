<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Util_MixedCollection Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Util
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: MixedCollection.php 1177 2010-01-05 14:49:57Z tamari $
 */

// {{{ xFrameworkPX_Util_MixedCollection

/**
 * xFrameworkPX_Util_MixedCollection Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Util
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Util_MixedCollection
 */
class xFrameworkPX_Util_MixedCollection extends ArrayObject
{

    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @param $input 配列あるいはオブジェクト
     * @param $flag 制御フラグ
     * @return void
     */
    public function __construct(
        $input = array(), $flag = ArrayObject::ARRAY_AS_PROPS
    )
    {

        // スーパークラスメソッドコール
        parent::__construct($input, $flag);
    }

    // }}}
    // {{{ first

    /**
     * 先頭アイテム取得
     *
     * @return mixed アイテム
     */
    public function first()
    {
        return reset($this);
    }

    // }}}
    // {{{ in

    /**
     * 要素が存在するかチェックする
     *
     * @param mixed $value チェック対象データ
     * @param bool $strict 型チェックフラグ
     * @retun bool true:存在する,false:存在しない
     */
    public function in($value, $strict = false)
    {
        return in_array($value, $this->getArrayCopy(), $strict);
    }

    // }}}
    // {{{ import

    /**
     * 配列をxFrameworkPX_Util_MixedCollectionとして取り込む
     *
     * @param array $import インポート配列
     * @param int $deep 階層
     * @retun xFrameworkPX_Util_MixedCollection
     */
    public function import($import, $deep = 0)
    {
        $ret = new xFrameworkPX_Util_MixedCollection();

        if (is_array($import)) {

            foreach ($import as $key => $value) {

                if (is_array($value)) {

                    if ($deep == 0) {
                        $this->{ $key } = $this->import($value, $deep + 1);
                    } else {
                        $ret->{ $key } = $this->import($value, $deep + 1);
                    }

                } else {

                    if ($deep == 0) {
                        $this->{ $key } = $value;
                    } else {
                        $ret->{ $key } = $value;
                    }

                }

            }

        }

        if ($deep == 0) {
            $ret = $this;
        }

        return $ret;
    }

    // }}}
    // {{{ key_exists

    /**
     * キーが存在するかチェックする
     *
     * @param mixed $key キー名 配列のキー名に使用できる値
     * @retun bool true:存在する,false:存在しない
     */
    public function key_exists($key)
    {
        return array_key_exists($key, $this->getArrayCopy());
    }

    // }}}
    // {{{ last

    /**
     * 最終アイテム取得
     *
     * @return mixed アイテム
     */
    public function last()
    {
        return end($this);
    }

    // }}}
    // {{{ offsetSetAll

    /**
     * 一括追加メソッド
     *
     * @param array $set 追加配列、キーと値を持つ配列をしていして、
     *                   offsetSetを内部的に呼び出します。
     * @return void
     */
    public function offsetSetAll($set)
    {

        foreach ($set as $key => $value) {
            $this->offsetSet($key, $value);
        }

    }

    // }}}
    // {{{ pop

    /**
     * 配列の末尾から要素を取り除く
     *
     * @return mixed 末尾データ
     */
    public function pop()
    {
        $temp = $this->getArrayCopy();
        $pop = array_pop($temp);
        $this->exchangeArray($temp);

        return $pop;
    }

    // }}}
    // {{{ push

    /**
     * 一つ以上の要素を配列の最後に追加する
     *
     * @param mixed $var  [, mixed $...  ] 追加するデータ
     * @return xFrameworkPX_Util_MixedCollection 処理後オブジェクト
     */
    public function push()
    {
        $temp = $this->getArrayCopy();

        foreach (func_get_args() as $arg) {
            array_push($temp, $arg);
        }

        $this->exchangeArray($temp);

        return $this;
    }

    // }}}
    // {{{ reverse

    /**
     * 配列の要素の順番を逆にする
     *
     * @param bool $preserveKeys キー保持フラグ
     * @retun xFrameworkPX_Util_MixedCollection 処理後オブジェクト
     */
    public function reverse($preserveKeys = false)
    {
        $this->exchangeArray(
            array_reverse($this->getArrayCopy(), $preserveKeys)
        );

        return $this;
    }

    // }}}
    // {{{ shift

    /**
     * 配列の先頭から要素を取り除く
     *
     * @return mixed 先頭データ
     */
    public function shift()
    {
        $temp = $this->getArrayCopy();
        $shift = array_shift($temp);
        $this->exchangeArray($temp);

        return $shift;
    }

    // }}}
    // {{{ shuffle

    /**
     * 配列の要素の順番をランダムに入れ替える
     *
     * @retun xFrameworkPX_Util_MixedCollection 処理後オブジェクト
     */
    public function shuffle()
    {
        $temp = $this->getArrayCopy();
        shuffle($temp);
        $this->exchangeArray($temp);

        return $this;
    }

    // }}}
    // {{{ unshift

    /**
     * 一つ以上の要素を配列の先頭に追加する
     *
     * @param mixed $var  [, mixed $...  ] 追加するデータ
     * @return xFrameworkPX_Util_MixedCollection 処理後オブジェクト
     */
    public function unshift()
    {
        $temp = $this->getArrayCopy();

        foreach (func_get_args() as $arg) {
            array_unshift($temp, $arg);
        }

        $this->exchangeArray($temp);

        return $this;
    }

    // }}}

}

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
