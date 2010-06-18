<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_View_Smarty Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_View
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Smarty.php 1457 2010-01-21 13:51:11Z kotsutsumi $
 */

// {{{ xFrameworkPX_View_Smarty

/**
 * xFrameworkPX_View_Smarty Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_View
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_View_Smarty
 */
class xFrameworkPX_View_Smarty extends xFrameworkPX_View
{

    // {{{ props

    /**
     * Smartyオブジェクト
     *
     * @var Smarty
     */
    public $smarty;

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

            self::$_instance = new xFrameworkPX_View_Smarty($conf);

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
    protected function __construct($conf)
    {
        $viewConf = $conf->pxconf['VIEW'];

        // Smarty読み込み
        include_once 'Smarty/Smarty.class.php';

        // Smartyオブジェクト生成
        $this->smarty = new Smarty();

        $webroot_key = 'WEBROOT_DIR';
        if($conf->pxconf['BINDTRANSFER'] === true) {
            $webroot_key = 'BINDTRANSFER_DIR';
        }

        // テンプレートディレクトリ設定
        $this->smarty->template_dir = implode(
            DS,
            array(
                $conf->pxconf[$webroot_key]
            )
        );

        // テンプレートコンパイルディレクトリ
        $this->smarty->compile_dir = implode(
            DS,
            array(
                $conf->pxconf['CACHE_DIR'],
                'templates_c/'
            )
        );

        if (!file_exists($this->smarty->compile_dir)) {
            makeDirectory($this->smarty->compile_dir);
        }

        // 設定ディレクトリ設定
        $this->smarty->config_dir = implode(
            DS,
            array(
                $conf->pxconf['CONFIG_DIR']
            )
        );

        // キャッシュディレクトリ設定
        $this->smarty->cache_dir = implode(
            DS,
            array(
                $conf->pxconf['CACHE_DIR'],
                'cache/'
            )
        );

        if (!file_exists($this->smarty->cache_dir)) {
            makeDirectory($this->smarty->cache_dir);
        }

        // デバッグ設定
        $this->smarty->debugging = $viewConf['DEBUGGING'];

        // キャッシュ設定
        $this->smarty->caching = $viewConf['CACHING'];

        // 強制コンパイル設定
        $this->smarty->force_compile = $viewConf['FORCE_COMPILE'];

        // キャッシュサブディレクトリの使用
        $this->smarty->use_sub_dirs = $viewConf['USE_SUB_DIRS'];

        // 左デリミタ設定
        $this->smarty->left_delimiter = $viewConf['LEFT_DELIMITER'];

        // 左デリミタ設定
        $this->smarty->right_delimiter = $viewConf['RIGHT_DELIMITER'];

        // スーパークラスメソッドコール
        parent::__construct($conf);
    }

    // }}}
    // {{{ _checkUA

    /**
     * ユーザーエージェントチェックメソッド
     *
     */
    private function _checkUA($regex)
    {
        return preg_match($regex, $_SERVER['HTTP_USER_AGENT']);
    }

    // }}}
    // {{{ onRender

    /**
     * レンダリングイベントハンドラ
     *
     * @return bool サスペンドフラグ
     * @access public
     */
    public function onRender()
    {
        // スーパークラスメソッドコール
        parent::onRender();

        // ユーザーデータアサイン
        $this->smarty->clear_all_assign();

        foreach ($this->_userData as $key => $value) {

            if ($key == 'smarty') {
                throw new xFrameworkPX_View_Exception(
                    'smarty変数を設定することはできません。'
                );
            }
            // @codeCoverageIgnoreStart

            $this->smarty->assign($key, $value);

            // @codeCoverageIgnoreEnd
        }

        // OS判定
        $isMac      = $this->_checkUA('/Mac/i')      ? true : false;
        $isWindows  = $this->_checkUA('/Win/i')      ? true : false;
        $isLinux    = $this->_checkUA('/linux/i')    ? true : false;

        $this->smarty->assign('isMac', $isMac);
        $this->smarty->assign('isWindows', $isWindows);
        $this->smarty->assign('isLinux', $isLinux);

        // ブラウザ判定
        $isOpera    = $this->_checkUA('/opera/i')    ? true : false;
        $isChrome   = $this->_checkUA('/chrome/i')   ? true : false;
        $isWebKit   = $this->_checkUA('/webkit/i')   ? true : false;
        $isSafari   = !$isChrome && $this->_checkUA('/safari/i')
                      ? true
                      : false;
        $isSafari2  = $isSafari && $this->_checkUA('/applewebkit\/4/i')
                      ? true
                      : false;
        $isSafari3  = $isSafari && $this->_checkUA('/version\/3/i')
                      ? true
                      : false;
        $isSafari4  = $isSafari && $this->_checkUA('/version\/4/i')
                      ? true
                      : false;
        $isIE       = !$isOpera && $this->_checkUA('/msie/i')
                      ? true
                      : false;
        $isIE7      = $isIE && $this->_checkUA('/msie 7/i')
                      ? true
                      : false;
        $isIE8      = $isIE && $this->_checkUA('/msie 8/i')
                      ? true
                      : false;
        $isIE6      = $isIE && !$isIE7 && !$isIE8
                      ? true
                      : false;
        $isGecko    = !$isWebKit && $this->_checkUA('/gecko/i')
                      ? true
                      : false;
        $isGecko2   = $isGecko && $this->_checkUA('/rv:1\.8/i')
                      ? true
                      : false;
        $isGecko3   = $isGecko && $this->_checkUA('/rv:1\.9/i')
                      ? true
                      : false;

        $isiPod = $this->_checkUA('/iPod/i') ? true : false;
        $isiPhone = $this->_checkUA('/iPhone/i') ? true : false;
        $isiPad = $this->_checkUA('/iPad/i') ? true : false;
        $isXperia = $this->_checkUA('/SonyEricsson(SO-01B|X10i)/i') ? true : false;
        $isAndroid = $this->_checkUA('/Android/i') ? true : false;

        $this->smarty->assign('isOpera', $isOpera);
        $this->smarty->assign('isChrome', $isChrome);
        $this->smarty->assign('isWebKit', $isWebKit);
        $this->smarty->assign('isSafari', $isSafari);
        $this->smarty->assign('isSafari2', $isSafari2);
        $this->smarty->assign('isSafari3', $isSafari3);
        $this->smarty->assign('isSafari4', $isSafari4);
        $this->smarty->assign('isIE', $isIE);
        $this->smarty->assign('isIE7', $isIE7);
        $this->smarty->assign('isIE8', $isIE8);
        $this->smarty->assign('isIE6', $isIE6);
        $this->smarty->assign('isGecko', $isGecko);
        $this->smarty->assign('isGecko2', $isGecko2);
        $this->smarty->assign('isGecko3', $isGecko3);
        $this->smarty->assign('isiPod', $isiPod);
        $this->smarty->assign('isiPhone', $isiPhone);
        $this->smarty->assign('isiPad', $isiPad);
        $this->smarty->assign('isXperia', $isXperia);
        $this->smarty->assign('isAndroid', $isAndroid);

        // デバッグ情報にユーザーデータを設定
        xFrameworkPX_Debug::getInstance()->addUserData('isOpera', $isOpera);
        xFrameworkPX_Debug::getInstance()->addUserData('isChrome', $isChrome);
        xFrameworkPX_Debug::getInstance()->addUserData('isWebKit', $isWebKit);
        xFrameworkPX_Debug::getInstance()->addUserData('isSafari', $isSafari);
        xFrameworkPX_Debug::getInstance()->addUserData('isSafari2', $isSafari2);
        xFrameworkPX_Debug::getInstance()->addUserData('isSafari3', $isSafari3);
        xFrameworkPX_Debug::getInstance()->addUserData('isSafari4', $isSafari4);
        xFrameworkPX_Debug::getInstance()->addUserData('isIE', $isIE);
        xFrameworkPX_Debug::getInstance()->addUserData('isIE6', $isIE6);
        xFrameworkPX_Debug::getInstance()->addUserData('isIE7', $isIE7);
        xFrameworkPX_Debug::getInstance()->addUserData('isIE8', $isIE8);
        xFrameworkPX_Debug::getInstance()->addUserData('isGecko', $isGecko);
        xFrameworkPX_Debug::getInstance()->addUserData('isGecko2', $isGecko2);
        xFrameworkPX_Debug::getInstance()->addUserData('isGecko3', $isGecko3);
        xFrameworkPX_Debug::getInstance()->addUserData('isiPod', $isiPod);
        xFrameworkPX_Debug::getInstance()->addUserData('isiPhone', $isiPhone);
        xFrameworkPX_Debug::getInstance()->addUserData('isiPad', $isiPad);
        xFrameworkPX_Debug::getInstance()->addUserData('isXperia', $isXperia);
        xFrameworkPX_Debug::getInstance()->addUserData('isAndroid', $isAndroid);

        // 相対位置設定
        $this->smarty->assign('relpath', $this->getRelativePath());

        // xFrameworkPX設定
        $this->smarty->assign('px', array(
            'webroot' => realpath($this->_pxconf['WEBROOT_DIR'])
        ));

        // デバッグ情報にユーザーデータを設定
        xFrameworkPX_Debug::getInstance()->addUserData('px', array(
            'webroot' => realpath($this->_pxconf['WEBROOT_DIR'])
        ));

        // サイト設定
        $site = xFrameworkPX_Config_Site::getInstance();
        $this->smarty->assign('site', array(
            'title' => (string)$site->site->title,
            'title_separator' => (string)$site->site->title_separator,
            'description' => (string)$site->site->description,
            'keywords' => (string)$site->site->keywords,
        ));

        // デバッグ情報にユーザーデータを設定
        xFrameworkPX_Debug::getInstance()->addUserData('site', array(
            'title' => (string)$site->site->title,
            'title_separator' => (string)$site->site->title_separator,
            'description' => (string)$site->site->description,
            'keywords' => (string)$site->site->keywords,
        ));

        // テンプレート出力
        if (
            file_exists(
                implode(
                    DS,
                    array(
                        '.',
                        $this->_conf->path,
                        $this->_templatefile
                    )
                )
            )
        ) {
            if ($this->_pxconf['DEBUG'] >= 2) {

                // データ文字列設定
                $data = implode(
                    PHP_EOL,
                    array(
                        sprintf('window.relpath = "%s";', $this->_conf->relpath),
                        sprintf(
                            'PXDEBUG_DATA = %s;',
                            xFrameworkPX_Debug::getInstance()->getResultData()
                        )
                    )
                );

                $shim = '';
                foreach ($this->_scripts as $script) {
                    $shim .= sprintf(
                        '<script type="text/javascript" src="%s"></script>',
                        $this->_conf->relpath . $script
                    );
                }

                $shim .= sprintf(
                    '<link rel="stylesheet" type="text/css" href="%s" />',
                    $this->_conf->relpath . 'xFrameworkPX/debug/resources/css/PXDebug-all.css'
                );

                $shim .= sprintf(
                    '<link rel="stylesheet" type="text/css" href="%s" />',
                    $this->_conf->relpath . 'xFrameworkPX/studio/resources/css/PXStudio-all.css'
                );

                $shim .= sprintf(
                    '<script type="text/javascript">%s</script>',
                    $data
                );

                $display = $this->smarty->fetch(
                    implode(
                        DS,
                        array(
                            '.',
                            $this->_conf->cp,
                            $this->_templatefile
                        )
                    )
                );

                $disable = false;
                $doc = new DOMDocument();

                $er = error_reporting();
                error_reporting(0);
                $doc->loadHTML($display);
                error_reporting($er);
                $body = $doc->getElementsByTagName('body');
                if($body) {
                    $item = $body->item(0);
                    if($item) {
                        $cssCls = $item->getAttribute('class');
                        if($cssCls) {
                            $css = explode(' ', $cssCls);
                            $disable = in_array('pxdebug-disable', $css);
                        }
                    }
                }

                if($disable === false) {
                    echo str_replace('</head>',$shim.'</head>', $display );
                } else {
                    echo $display;
                }

            } else {
                $this->smarty->display(
                    implode(
                        DS,
                        array(
                            '.',
                            $this->_conf->cp,
                            $this->_templatefile
                        )
                    )
                );
            }
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
