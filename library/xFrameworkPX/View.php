<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_View Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: View.php 1462 2010-01-22 09:09:23Z kotsutsumi $
 */

// {{{ xFrameworkPX_View

/**
 * xFrameworkPX_View Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_View
 */
class xFrameworkPX_View extends xFrameworkPX_Util_Observable
{
    // {{{ props

    /**
     * テンプレートファイル名
     *
     * @var string
     */
    protected $_templatefile;

    /**
     * ユーザーデーターオブジェクト
     *
     * @var xFrameworkPX_Util_MixedCollection
     */
    protected $_userData;

    /**
     * 設定オブジェクト
     *
     * @var xFrameworkPX_Util_MixedCollection
     */
    protected $_conf;

    /**
     * xFrameworkPX設定オブジェクト
     *
     * @var array
     */
    protected $_pxconf;

    /**
     * デバッグコード
     *
     * @var array
     */
    protected $_debug;

    /**
     * デバッグスクリプト一覧
     */
    protected $_scripts;

    /**
     * インスタンス変数
     *
     * @var xFrameworkPX
     */
    protected static $_instance = null;

    // }}}
    // {{{ getInstance

    /**
     * インスタンス取得メソッド
     *
     * @return xFrameworkPXインスタンス
     */
    public static function getInstance($conf = null)
    {
        // インスタンス取得
        if (!isset(self::$_instance) && !is_null($conf)) {
            self::$_instance = new xFrameworkPX_View();

            // xFrameworkPX設定格納
            self::$_instance->_pxconf = $conf->pxconf;
        }

        return self::$_instance;
    }

    // }}}
    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @return void
     */
    protected function __construct()
    {
        // イベント定義
        $this->addEvents('beforerender', 'render', 'afterrender');

        // イベント登録
        $this->on('beforerender', array($this, 'onBeforeRender'));
        $this->on('render', array($this, 'onRender'));
        $this->on('afterrender', array($this, 'onAfterRender'));

        // ユーザーデーターオブジェクト生成
        $this->_userData = new xFrameworkPX_Util_MixedCollection();
    }

    // }}}
    // {{{ __clone

    /**
     * インスタンス複製メソッド
     *
     * @return void
     */
    public final function __clone()
    {
        throw new xFrameworkPX_Config_Exception(
            sprintf(PX_ERR90001, get_class($this))
        );
    }

    // }}}
    // {{{ setLayout

    /**
     * レイアウト情報設定メソッド
     *
     * @param xFrameworkPX_Util_MixedCollection レイアウト情報
     * @return void
     */
    public function setLayout($conf)
    {
        // 設定オブジェクト格納
        $this->_conf = $conf;

        // テンプレートファイル名設定
        $this->_templatefile = $conf->file;
    }

    // }}}
    // {{{ __get

    /**
     * 読み出しオーバーロード
     *
     * @param string $name プロパティ名
     * @return mixed オブジェクト
     */
    public function __get($name)
    {

    }

    // }}}
    // {{{ onBeforeRender

    /**
     * レンダリング前イベントハンドラ
     *
     * @return bool サスペンドフラグ
     */
    public function onBeforeRender()
    {
        if ($this->_pxconf['DEBUG'] >= 2) {

            // 処理終了時間設定
            xFrameworkPX_Debug::getInstance()->endTime();
            xFrameworkPX_Debug::getInstance()->addProfileData(
                '',
                '[TOTAL]',
                '',
                '合計処理時間',
                xFrameworkPX_Debug::getInstance()->getProcessingTime()
            );

            // ユーザーデータ格納
            foreach ($this->_userData as $key => $value) {
                xFrameworkPX_Debug::getInstance()->addUserData($key, $value);
            }

            $this->_scripts = array(
                'extjs/adapter/ext/ext-base.js',
                'extjs/ext-all-debug.js',
                //'extjs/src/locale/ext-lang-ja.js',

                // xFrameworkPX Debug Tools
                'xFrameworkPX/debug/pxdebug.js',
                'xFrameworkPX/debug/extdirect.html?wd=xFrameworkPX/debug&rp=xFrameworkPX/debug/',

                // xFrameworkPX Studio
//                'xFrameworkPX/studio/pxstudio.js?nocache=' . time(),

                'xFrameworkPX/studio/src/app/Console.js?nocache=' . time(),
                'xFrameworkPX/studio/src/app/Namespace.js?nocache=' . time(),
                'xFrameworkPX/studio/src/app/App.js?nocache=' . time(),
                'xFrameworkPX/studio/src/ux/Ext.extender.js?nocache=' . time(),
                'xFrameworkPX/studio/src/ux/Phantom.js?nocache=' . time(),
                'xFrameworkPX/studio/src/widgets/Viewport.js?nocache=' . time(),
                'xFrameworkPX/studio/src/widgets/NavigationPanel.js?nocache=' . time(),
                'xFrameworkPX/studio/src/widgets/tree/FileTreePanel.js?nocache=' . time(),
                'xFrameworkPX/studio/src/widgets/tree/VirtualScreenTreePanel.js?nocache=' . time(),
                'xFrameworkPX/studio/src/widgets/VirtualScreenWindow.js?nocache=' . time(),



                'xFrameworkPX/studio/extdirect.html?wd=xFrameworkPX/studio&rp=xFrameworkPX/studio/',

            );
        }
    }

    // }}}
    // {{{ onRender

    /**
     * レンダリングイベントハンドラ
     *
     * @return bool サスペンドフラグ
     */
    public function onRender()
    {

    }

    // }}}
    // {{{ onAfterRender

    /**
     * レンダリング後イベントハンドラ
     *
     * @return bool サスペンドフラグ
     */
    public function onAfterRender()
    {

    }

    // }}}
    // {{{ setDebugData

    /**
     * ユーザーデーター設定メソッド
     *
     * @param array $value デバッグコード配列
     * @return void
     */
    public function setDebugData($value)
    {
        $this->_debug = $value;
    }

    // }}}
    // {{{ setUserData

    /**
     * ユーザーデーター設定メソッド
     *
     * @param string $name View変数名
     * @param mixed $xValue 値
     * @return void
     */
    public function setUserData($name, $value)
    {
        $this->_userData->{$name} = $value;
    }

    // }}}
    // {{{ getUserData

    /**
     * ユーザーデーター取得メソッド
     *
     * @param string $name View変数名
     * @param mixed $xValue 値
     * @return void
     */
    public function getUserData($name)
    {
        if (isset($this->_userData[$name])) {
            return $this->_userData[$name];
        }

        return null;
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
