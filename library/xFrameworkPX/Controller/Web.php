<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Controller_Web Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Controller
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Web.php 1492 2010-03-30 01:28:37Z yasunaga $
 */

// {{{ xFrameworkPX_Controller_Web

/**
 * xFrameworkPX_Controller_Web Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Controller
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Controller_Web
 */
class xFrameworkPX_Controller_Web extends xFrameworkPX_Controller
{
    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @param xFrameworkPX_Util_MixedCollection $conf 設定オブジェクト
     * @return void
     */
    public function __construct($conf)
    {
        // デバッグ用計測開始
        if ($conf->pxconf['DEBUG'] >= 2) {
            $startTime = microtime(true);
        }

        $pxconf = $conf->pxconf;

        $webrootKey = 'WEBROOT_DIR';
        if ($pxconf['BINDTRANSFER'] === true) {
            $webrootKey = 'BINDTRANSFER_DIR';
        }

        // セッションコンポーネント設定
        $this->_components[] = array(
            'clsName' => 'xFrameworkPX_Controller_Component_' .
                         $pxconf['SESSION']['TYPE'] .
                         'Session',
            'bindName' => 'Session',
            'args' => $this->mix($pxconf['SESSION'])
        );

        // スーパークラスメソッドコール
        parent::__construct($conf);

        // イベント定義
        $this->addEvents('super', 'global');

        // サイト設定読み込み
        $this->_conf->offsetSet(
            'site',
            xFrameworkPX_Config_Site::getInstance()->import(
                $this->mix(
                    array(
                        'path' => $pxconf['WEBROOT_DIR'],
                        'filename' => $pxconf['CONFIG_PREFIX'] .
                                      $pxconf['CONFIG' ]['SITE'],
                        'cachepath' => $pxconf['CACHE_DIR']
                    )
                )
            )
        );

        // スーパーアクションコントローラー設定読み込み
        $this->_conf->offsetSet(
            'super',
            xFrameworkPX_Config_Super::getInstance()->import(
                $this->mix(
                    array(
                        'path' => $pxconf['WEBROOT_DIR'],
                        'filename' => $pxconf['CONFIG_PREFIX'] .
                                      $pxconf['CONFIG']['SUPER'],
                        'cachepath' => $pxconf['CACHE_DIR']
                    )
                )
            )
        );

        // グローバルアクションコントローラー設定ファイル検索
        $searchpath = explode(DS, normalize_path($this->getContentPath()));
        $deep = count($searchpath);

        $filename = $pxconf['CONFIG_PREFIX'] .
                    $pxconf['CONFIG']['GLOBAL'];
        $path = null;
        $relativepath = null;

        for ($i = 0;$i < $deep; ++$i) {

            $temp = $searchpath;

            for ($j = 0; $j < $i; ++$j) {
                array_pop($temp);
            }

            $path = normalize_path(
                implode(
                    DS,
                    array(
                        $pxconf[$webrootKey],
                        implode('/', $temp),
                        $filename
                    )
                )
            );

            if (file_exists($path)) {
                $path = normalize_path(
                    implode(
                        DS,
                        array(
                            $pxconf[$webrootKey],
                            implode('/', $temp)
                        )
                    )
                );
                $relativepath = normalize_path(
                    implode(
                        DS,
                        array(
                            implode('/', $temp)
                        )
                    )
                );

                break;
            }

        }

        // グローバルアクションコントローラー設定読み込み
        if (!is_null($path)) {
            $this->_conf->offsetSet(
                'global',
                xFrameworkPX_Config_Global::getInstance()->import(
                    $this->mix(
                        array(
                            'path' => $path,
                            'filename' => $filename,
                            'cachepath' => $pxconf['CACHE_DIR'] .
                                           $relativepath
                        )
                    )
                )
            );
        }

        if ($conf->pxconf['DEBUG'] >= 2) {
            xFrameworkPX_Debug::getInstance()->addProfileData(
                get_class($this),
                'xFrameworkPX_Controller_Web',
                '__construct',
                'Controller',
                microtime(true) - $startTime
            );
        }
    }

    // }}}
    // {{{ getLayout

    /**
     * レイアウト取得メソッド
     *
     * @return xFrameworkPX_Util_MixedCollection レイアウト情報オブジェクト
     */
    protected function getLayout()
    {
        // デバッグ用計測開始
        if ($this->_conf->pxconf['DEBUG'] >= 2) {
            $startTime = microtime(true);
        }

        $pxconf = $this->_conf->pxconf;
        $templatefile = '';

        $webrootKey = 'WEBROOT_DIR';
        if ($pxconf['BINDTRANSFER'] === true) {
            $webrootKey = 'BINDTRANSFER_DIR';
        }

        if (isset($this->templatefile)) {
            $templatefile = $this->templatefile;
        } else {
            $templatefile = $this->getActionName() . '.html';
        }

        $ret = $this->mix(
            array(
                'file' => $templatefile,
                'path' => normalize_path(
                    implode(
                        DS,
                        array(
                            $pxconf[$webrootKey],
                            $this->getContentPath()
                        )
                    )
                ),
                'cp' => $this->getContentPath(),
                'relpath' => $this->getRelativePath(),
            )
        );

        if ($this->_conf->pxconf['DEBUG'] >= 2) {
            xFrameworkPX_Debug::getInstance()->addProfileData(
                get_class($this),
                'xFrameworkPX_Controller_Web',
                'getLayout',
                'Controller',
                microtime(true) - $startTime
            );
        }

        return $ret;
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
        if ($this->_conf->pxconf['DEBUG'] >= 2) {
            $startTime = microtime(true);
        }

        // スーパークラスメソッドコール
        parent::setUp();

        // ローカル変数初期化
        $pxconf = $this->_conf->pxconf;

        // スーパーアクションコントローラー読み込み
        if (!is_null($this->_conf->super)) {
            foreach ($this->_conf->super->controller as $xmlController) {

                $clsName = (string)$xmlController;

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

                $includefilename = normalize_path(
                    implode(
                        DS,
                        array(
                            $pxconf['CONTROLLER_DIR'],
                            $filename
                        )
                    )
                );

                // ファイル存在確認
                if (!file_exists($includefilename)) {
                    throw new xFrameworkPX_Controller_Exception(
                        sprintf(PX_ERR41000, $includefilename)
                    );
                }

                // 読み込み
                include_once $includefilename;

                // クラス存在確認
                if (!class_exists($clsName)) {
                    throw new xFrameworkPX_Controller_Exception(
                        sprintf(PX_ERR41001, $includefilename, $clsName)
                    );
                }

                // コントローラーオブジェクト生成
                $cls = new $clsName($this->_conf);

                // イベントリスナー追加
                if (method_exists($cls, 'execute')) {
                    $this->on('super', array($cls, 'execute'));
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
                    $modulePath = normalize_path(
                        implode(
                            DS,
                            array($pxconf['MODULE_DIR'], $clsPath . '.php')
                        )
                    );

                    if (file_exists($modulePath)) {

                        // モジュールクラスファイル読み込み
                        include_once $modulePath;
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
        }

        // グローバルアクションコントローラー読み込み
        if (!is_null($this->_conf->global)) {
            foreach ($this->_conf->global->controller as $xmlController) {

                $clsName = (string)$xmlController;

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

                $includefilename =  normalize_path(
                    implode(
                        DS,
                        array(
                            $pxconf['CONTROLLER_DIR'],
                            $filename
                        )
                    )
                );

                // ファイル存在確認
                if (!file_exists($includefilename)) {
                    throw new xFrameworkPX_Controller_Exception(
                        sprintf(PX_ERR41000, $includefilename)
                    );
                }

                // 読み込み
                include_once $includefilename;

                // クラス存在確認
                if (!class_exists($clsName)) {
                    throw new xFrameworkPX_Controller_Exception(
                        sprintf(PX_ERR41001, $includefilename, $clsName)
                    );
                }

                // コントローラーオブジェクト生成
                $cls = new $clsName($this->_conf);

                // イベントリスナー追加
                if (method_exists($cls, 'execute')) {
                    $this->on('global', array($cls, 'execute'));
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
                    $modulePath = normalize_path(
                        implode(
                            DS,
                            array($pxconf['MODULE_DIR'], $clsPath . '.php')
                        )
                    );

                    if (file_exists($modulePath)) {

                        // モジュールクラスファイル読み込み
                        include_once $modulePath;
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
        }

        if ($this->_conf->pxconf['DEBUG'] >= 2) {
            xFrameworkPX_Debug::getInstance()->addProfileData(
                get_class($this),
                'xFrameworkPX_Controller_Web',
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
        if ($this->_conf->pxconf['DEBUG'] >= 2) {
            $startTime = microtime(true);
        }

        parent::tearDown();

        if ($this->_conf->pxconf['DEBUG'] >= 2) {
            xFrameworkPX_Debug::getInstance()->addProfileData(
                get_class($this),
                'xFrameworkPX_Controller_Web',
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
        if ($this->_conf->pxconf['DEBUG'] >= 2) {
            $startTime = microtime(true);
        }

        // イベントディスパッチ
        if ($this->hasListener('setUp')) {
            $this->dispatch('setUp');
        }
        if ($this->hasListener('super')) {
            $this->dispatch('super');
        }
        if ($this->hasListener('global')) {
            $this->dispatch('global');
        }

        // スーパークラスメソッドコール
        parent::invoke();

        // レイアウト設定取得＆設定
        xFrameworkPX_View::getInstance()->setLayout($this->getLayout());

        // ビューディスパッチ
        if (!xFrameworkPX_View::getInstance()->dispatch('beforerender')) {
            return;
        }
        if (!xFrameworkPX_View::getInstance()->dispatch('render')) {
            return;
        }
        if (!xFrameworkPX_View::getInstance()->dispatch('afterrender')) {
            return;
        }

        // イベントディスパッチ
        if ($this->hasListener('tearDown')) {
            $this->dispatch('tearDown');
        }

        if ($this->_conf->pxconf['DEBUG'] >= 2) {
            xFrameworkPX_Debug::getInstance()->addProfileData(
                get_class($this),
                'xFrameworkPX_Controller_Web',
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
