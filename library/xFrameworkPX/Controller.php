<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_ConfigInterface Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Controller.php 1465 2010-01-22 10:28:19Z kotsutsumi $
 */

// {{{ xFrameworkPX_ConfigInterface

/**
 * xFrameworkPX_ConfigInterface Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Controller
 */
abstract class xFrameworkPX_Controller extends xFrameworkPX_Util_Observable
{
    // {{{ props

    /**
     * 設定オブジェクト
     *
     * @var xFrameworkPX_Util_MixedCollection
     */
    protected $_conf;

    /**
     * ビューオブジェクト
     *
     * @var xFrameworkPX_View
     */
    protected $_view;

    /**
     * モジュール一覧配列
     *
     * @var array
     */
    public $modules = array();

    /**
     * コンポーネント一覧配列
     *
     * @var array
     */
    protected $_components = array();

    /**
     * コントローラクラス接尾辞
     *
     * @var string
     */
    protected $_suffix = "";

    /**
     * ユーザー定義コントローラー
     */
    private $_userController = array();

    // }}}
    // {{{ __get

    /**
     * 読み出しオーバーロード
     *
     * @param $name 名前
     * @return mixed オブジェクト
     */
    public function __get($name)
    {
        if ($name === 'post') {
            return $this->_conf->params->form;
        } else if ($name === 'get') {
            return $this->_conf->params->url;
        } else if ($name === 'cookie') {
            return $this->mix($_COOKIE);
        } else if ($name === 'files') {
            return $this->_conf->params->files;
        } else if ($name === 'args') {
            return $this->_conf->params->args;
        } else if ($name === 'conf') {
            return $this->_conf;
        } else if ($name === 'modules') {
            return $this->_modules;
        } else if (isset($this->modules[$name])) {
            return $this->modules[$name];
        } else if ($name === 'view') {
            return xFrameworkPX_View::getInstance();
        } else if ($name === 'log') {
            return xFrameworkPX_Log::getInstance();
        } else if ($name === 'debug') {
            return xFrameworkPX_Debug::getInstance();
        }

        return null;
    }

    // }}}
    // {{{ trace

    public function trace($var, $tag = '')
    {
        $bt = debug_backtrace();

        if (isset($bt[0]) && isset($bt[1])) {
            $cls = $bt[1]['class'];
            $method = $bt[1]['function'];
            $line = $bt[0]['line'];
            $this->debug->addTrace($cls, $method, $line, $var, $tag);
        }

    }

    // }}}
    // {{{ time

    /**
     * 実行時間計測開始メソッド
     */
    public function time()
    {
        $this->debug->time();
    }

    // }}}
    // {{{ timeEnd

    /**
     * 実行時間計測終了メソッド
     */
    public function timeEnd()
    {
        $this->debug->timeEnd();
    }

    // }}}
    // {{{ set

    /**
     * 値設定メソッド
     *
     * @param string $name 値名
     * @param mixed $value 値
     * @return void
     */
    public function set($name, $value)
    {

        // デバッグ用計測開始
        if ($this->_conf->pxconf['DEBUG'] >= 2) {
            $startTime = microtime(true);
        }

        xFrameworkPX_View::getInstance()->setUserData($name, $value);

        // デバッグプロファイル追加
        if ($this->_conf->pxconf['DEBUG'] >= 2) {
            xFrameworkPX_Debug::getInstance()->addProfileData(
                get_class($this),
                'xFrameworkPX_Controller',
                'setUp',
                'Controller',
                microtime(true) - $startTime
            );
        }

    }

    // }}}
    // {{{ get

    public function get($name)
    {
        // デバッグ用計測開始
        if ($this->_conf->pxconf['DEBUG'] >= 2) {
            $startTime = microtime(true);
        }

        $ret = xFrameworkPX_View::getInstance()->getUserData($name);

        // デバッグプロファイル追加
        if ($this->_conf->pxconf['DEBUG'] >= 2) {
            xFrameworkPX_Debug::getInstance()->addProfileData(
                get_class($this),
                'xFrameworkPX_Controller',
                'setUp',
                'Controller',
                microtime(true) - $startTime
            );
        }

        return $ret;
    }

    // }}}
    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @param xFrameworkPX_Util_MixedCollection $conf 設定オブジェクト
     * @return void
     */
    public function __construct($conf = array())
    {
        // デバッグ用計測開始
        if ($conf['pxconf']['DEBUG'] >= 2) {
            $startTime = microtime(true);
        }

        // コンテンツパスキー設定
        $this->_cp = $conf['pxconf']['CONTENT_PATH_KEY'];

        // デフォルトアクション名設定
        $this->_defaultaction = $conf['pxconf']['DEFAULT_ACTION'];

        // 設定適用
        $this->_conf = $this->mix($conf);

        // イベント追加
        $this->addEvents('setUp', 'tearDown', $this->getActionName());

        // イベントリスナー追加
        $this->on('setUp', array($this, 'setUp'));
        $this->on('tearDown', array($this, 'tearDown'));

        // ログオブジェクト生成
        xFrameworkPX_Log::getInstance($conf);

        // ビューオブジェクト生成
        $clsName = sprintf(
            'xFrameworkPX_View_%s',
            $conf->pxconf['VIEW']['NAME']
        );
        eval($clsName . '::getInstance($conf);');

        // メールコンポーネント設定
        $this->_components[] = array(
            'clsName' => 'xFrameworkPX_Controller_Component_Mail',
            'bindName' => 'Mail'
        );

        // パラメータエンコーディング
        if (!is_null($this->paramEncode)) {

            foreach ($_POST as $key => $value) {
                $_POST[$key] = mb_convert_encoding_deep(
                    $_POST[$key],
                    "UTF-8",
                    $this->paramEncode
                );
            }
            $this->_conf->params->form = $this->mix($_POST);

            foreach ($_GET as $key => $value) {
                $_GET[$key] = mb_convert_encoding_deep(
                    $_GET[$key],
                    "UTF-8",
                    $this->paramEncode
                );
            }
            $this->_conf->params->url = $this->mix($_GET);
        }

        if (
            $conf['pxconf']['DEBUG'] >= 2 &&
            xFrameworkPX_Debug::getInstance()->isParameterSetted !== true
        ) {
            foreach ($_POST as $key => $value) {
                xFrameworkPX_Debug::getInstance()->addParameter(
                    $key,
                    $value,
                    'POST'
                );
            }

            foreach ($_GET as $key => $value) {
                xFrameworkPX_Debug::getInstance()->addParameter(
                    $key,
                    $value,
                    'GET'
                );
            }

            xFrameworkPX_Debug::getInstance()->isParameterSetted = true;
        }

        // コンポーネント生成
        foreach ($this->_components as $components) {
            $clsName = str_replace('_', '/', $components['clsName']);
            $bind = $components['bindName'];

            if (isset($components['args'])) {
                $args = $components['args'];
            } else {
                $args = array();
            }

            // コンポーネントオブジェクト生成
            $clsName = $components['clsName'];
            $cls = new $clsName($args);

            //  コンポーネントアクセスオブジェクト設定
            $this->{$bind} = $cls;
        }

        // デバッグプロファイル追加
        if ($conf['pxconf']['DEBUG'] >= 2) {
            xFrameworkPX_Debug::getInstance()->addProfileData(
                get_class($this),
                'xFrameworkPX_Controller',
                '__construct',
                'Controller',
                microtime(true) - $startTime
            );
        }

    }

    // }}}
    // {{{ setUp

    /**
     * 開始イベントハンドラ
     *
     * @return bool サスペンドフラグ
     */
    public function setUp()
    {
        // デバッグ用計測開始
        if ($this->_conf->pxconf['DEBUG'] >= 2) {
            $startTime = microtime(true);
        }

        $webrootKey = 'WEBROOT_DIR';

        if ($this->_conf->pxconf['BINDTRANSFER'] === true) {
            $webrootKey = 'BINDTRANSFER_DIR';
        }

        // ローカル変数初期化
        $pxconf = $this->_conf->pxconf;
        $clsName = $this->getActionName();

        // パス設定
        if (PHP_SAPI === 'cli') {
            $path = normalize_path($pxconf['CONTROLLER_DIR']);
        } else {
            $path = normalize_path(
                $pxconf[$webrootKey] . DS . $this->getContentPath()
            );
        }

        $this->_conf->execpath = $path;

        // ファイル名設定
        if (PHP_SAPI === 'cli') {
            $filename = implode(
                array(
                    dirname(
                       str_replace('_', '/', $clsName)
                    ),
                    DS,
                    $pxconf['CONTROLLER_PREFIX'],
                    get_filename(
                        str_replace('_', '/', $clsName)
                    ),
                    $pxconf['CONTROLLER_EXTENSION']
                )
            );
        } else {
            $filename = implode(
                array(
                    $pxconf['CONTROLLER_PREFIX'],
                    $clsName,
                    $pxconf['CONTROLLER_EXTENSION']
                )
            );
        }

        // 読み込みクラスファイル名取得
        $includefilename = normalize_path(
            implode(
                DS,
                array($path, $filename)
            )
        );

        // 存在判定
        if (file_exists($includefilename)) {

            // コントローラークラス読み込み
            include_once $includefilename;

            // クラス存在確認
            if (!class_exists($clsName, false)) {

                // クラス存在しない場合はSuffixをつけて判定
                $baseName = $clsName;
                $clsName .= $this->_conf['pxconf']['CONTROLLER_CLASS_SUFFIX'];

                // アクション名取得時に接尾辞を加える
                $this->_suffix
                    = $this->_conf['pxconf']['CONTROLLER_CLASS_SUFFIX'];

                if (!class_exists($clsName, false)) {

                    throw new xFrameworkPX_Controller_Exception(
                        sprintf(
                            PX_ERR40000,
                            $includefilename,
                            $baseName . ' or ' .$clsName
                        )
                    );

                }

                // イベント追加
                $this->addEvents('setUp', 'tearDown', $clsName);
            }

            // コントローラーオブジェクト生成
            $cls = new $clsName($this->_conf);

            // イベントリスナー追加
            if (method_exists($cls, 'tearDown')) {
                $this->on('tearDown', array($cls, 'tearDown'));
            }

            if (method_exists($cls, 'execute')) {
                $this->on($clsName, array($cls, 'execute'));
                $this->_userController[$clsName] = $cls;
            }

            // モジュール生成
            foreach ($cls->modules as $name => $value) {

                if (is_string($value)) {
                    $name = $value;
                    if (isset($cls->forceConnect)) {
                        $value = array('conn' => $cls->forceConnect);
                    } else {
                        $value = array('conn' => 'default');
                    }
                }

                $clsPath = str_replace('_', DS, $name);
                $moduleFileName = normalize_path(
                    implode(
                        DS, array($pxconf['MODULE_DIR'], $clsPath . '.php')
                    )
                );

                if (file_exists($moduleFileName)) {

                    // モジュールクラスファイル読み込み
                    include_once $moduleFileName;
                }

                // 設定オブジェクト生成
                $conf = $this->mix($value);

                // データベース設定格納
                $conf->database = $this->_conf->dbconf;

                // PX動作設定格納
                $conf->px = $pxconf;

                // 実行パス設定
                $conf->execpath = $this->_conf->execpath;

                // コンテンツパス設定
                $conf->contentpath = $this->getContentPath();

                // モジュールオブジェクト生成
                $cls->modules[$name] = new $name($conf, $this);
            }

            // モジュール一覧設定
            foreach ($cls->modules as $name => $value) {

                if (is_string($value)) {
                    $name = $value;
                }

                $cls->modules[$name]->modules = $cls->modules;
            }

        }

        // デバッグプロファイル追加
        if ($this->_conf->pxconf['DEBUG'] >= 2) {
            xFrameworkPX_Debug::getInstance()->addProfileData(
                get_class($this),
                'xFrameworkPX_Controller',
                'setUp',
                'Controller',
                microtime(true) - $startTime
            );
        }

    }

    // }}}
    // {{{ tearDown

    /**
     * 終了イベントハンドラ
     *
     * @return bool サスペンドフラグ
     */
    public function tearDown()
    {
        // デバッグ用計測開始
        if ($this->_conf->pxconf['DEBUG'] >= 2) {
            $startTime = microtime(true);
        }

        // デバッグプロファイル追加
        if ($this->_conf->pxconf['DEBUG'] >= 2) {
            xFrameworkPX_Debug::getInstance()->addProfileData(
                get_class($this),
                'xFrameworkPX_Controller',
                'tearDown',
                'Controller',
                microtime(true) - $startTime
            );
        }

    }

    // }}}
    // {{{ invoke

    /**
     * 呼び出しメソッド
     *
     * @return void
     */
    public function invoke()
    {
        // デバッグ用計測開始
        if ($this->_conf->pxconf['DEBUG'] >= 2) {
            $startTime = microtime(true);
        }

        // 接尾辞を追加
        $actionName = $this->getActionName() . $this->_suffix;

        // イベントディスパッチ
        if ($this->hasListener($actionName)) {

            if ($this->_conf->pxconf['DEBUG'] >= 2) {
                $startUserTime = microtime(true);
            }

            $this->dispatch($actionName);

            if ($this->_conf->pxconf['DEBUG'] >= 2) {
                xFrameworkPX_Debug::getInstance()->addProfileData(
                    get_class($this),
                    get_class($this->_userController[$actionName]),
                    'execute',
                    'Controller',
                    microtime(true) - $startUserTime
                );
            }

        }

        // デバッグプロファイル追加
        if ($this->_conf->pxconf['DEBUG'] >= 2) {
            xFrameworkPX_Debug::getInstance()->addProfileData(
                get_class($this),
                'xFrameworkPX_Controller',
                'invoke',
                'Controller',
                microtime(true) - $startTime
            );
        }
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
