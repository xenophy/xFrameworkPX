<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Controller_Component_Session Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Controller_Component
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Session.php 1173 2010-01-05 14:22:46Z tamari $
 */

// {{{ xFrameworkPX_Controller_Component_Session

/**
 * xFrameworkPX_Controller_Component_Session Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Controller_Component
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Controller_Component_Session
 */
abstract class xFrameworkPX_Controller_Component_Session
extends xFrameworkPX_Controller_Component
{
    // {{{ props

    /*
     * セッションタイムアウト秒
     */
    protected $_timeout = 30;

    // }}}
    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @param xFrameworkPX_Util_MixedCollection $conf 設定オブジェクト
     */
    public function __construct($conf)
    {

        // タイムアウト秒設定
        $this->_timeout = (int)$conf->TIMEOUT;

        // セッション名設定
        session_name($conf->ID);

        // 自動スタート設定
        if ($conf->AUTO_START === true) {
            @session_start();
        }
    }

    // }}}
    // {{{ read

    /**
     * セッションリードメソッド
     *
     * セッションに保持されている値を読み込みます。
     * あらかじめセッションに書き込まれているキー名を引数に指定し、
     * 値を読み込みます。
     *
     * @param $key キー名
     * @return mixed セッションに書き込まれた値
     */
    public function &read($key)
    {
        $temp = null;

        if (isset($_SESSION[$key])) {
            $temp =& $_SESSION[$key];
        }

        return $temp;
    }

    // }}}
    // {{{ readall

    /**
     * 全セッションリードメソッド
     *
     * セッションに保持されている値を全て読み込みます。
     * あらかじめセッションに書き込まれている値を全て読み込みます。
     *
     * @return array セッション値配列
     */
    public function &readall()
    {
        return $_SESSION;
    }

    // }}}
    // {{{ write

    /**
     * セッションライトメソッド
     *
     * セッションにキーを渡して値を書き込みます。
     * このキーはPHPの$_SESSION[ 'キー名' ]に相当します。
     * 一度書き込まれたパラメータはdestroyメソッドやremoveメソッド、
     * clearメソッドを呼び出すまで保持されます。
     *
     * @param $key キー名
     * @return void
     */
    public function write($key, $objValue)
    {
        $_SESSION[$key] = $objValue;
    }

    // }}
    // {{{ remove

    /**
     * セッション削除メソッド
     *
     * セッションに保持されている値を取得し、削除します。
     * あらかじめセッションに書き込まれているキー名を引数に指定し、
     * 一度値を取得し、同時に値を削除します。
     *
     * @param $key キー名
     * @return mixed 削除されたセッション値
     *               存在しない場合はnullを返却します。
     */
    public function &remove($key)
    {
        $temp = null;

        if (isset($_SESSION[$key])) {
            $temp =& $_SESSION[$key];
            unset($_SESSION[$key]);
        }

        return $temp;
    }

    // }}}
    // {{{ clear

    /**
     * セッションクリアメソッド
     *
     * セッションに保持されている全ての値を消去します。
     * $_SESSION変数の値を全て消すため、一度クリアすると、
     * もう一度書き込みが行われなければ、
     * セッションの値は読み込むことができません。
     *
     * @return void
     */
    public function clear()
    {
        $_SESSION = array();
    }

    // }}}
    // {{{ destroy

    /**
     * セッション消滅メソッド
     *
     * セッション自体を消滅させます。
     * このメソッドはPHPのsession_destroy()に相当します。
     * このメソッドのみではメモリ内の$_SESSION変数は残り続けてしまうので、
     * 値を含める全てを破棄したい場合は、clearの後にdestroyを使用してください。
     * また、xFrameworkPXでは、アクションが走る度に内部でPHPのsession_start()に
     * 相当する動作を行っているので注意が必要です。
     *
     * @return void
     */
    public function destroy()
    {
        session_destroy();
        $this->clear();
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
