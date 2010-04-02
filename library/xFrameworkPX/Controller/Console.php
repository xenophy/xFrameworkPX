<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Controller_Console Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Controller
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Console.php 1210 2010-01-08 09:05:34Z kotsutsumi $
 */

// {{{ xFrameworkPX_Controller_Console

/**
 * xFrameworkPX_Controller_Console Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Controller
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Controller_Console
 */
class xFrameworkPX_Controller_Console extends xFrameworkPX_Controller
{
    // {{{ props

    /**
     * 設定オブジェクト
     *
     * @var xFrameworkPX_Util_MixedCollection
     */
    protected $_conf;

    // }}}
    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @return void
     */
    public function __construct($conf)
    {
        // デバッグ用計測開始
        if ($conf['pxconf']['DEBUG'] >= 2) {
            $startTime = microtime(true);
        }

        // 設定オブジェクト生成
        $this->_conf = $conf;

        if (isset($this->args->app)) {
            $this->cliAction = $this->args->app;
        }

        // スーパークラスメソッドコール
        parent::__construct($conf);

        // デバッグプロファイル追加
        if ($conf['pxconf']['DEBUG'] >= 2) {
            xFrameworkPX_Debug::getInstance()->addProfileData(
                get_class($this),
                'xFrameworkPX_Controller_Console',
                '__construct',
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
     * @access public
     */
    public function invoke()
    {
        // デバッグ用計測開始
        if ($this->_conf->pxconf['DEBUG'] >= 2) {
            $startTime = microtime(true);
        }

        // イベントディスパッチ
        if ($this->hasListener('setUp')) {
            $this->dispatch('setUp');
        }

        // スーパークラスメソッドコール
        parent::invoke();

        // イベントディスパッチ
        if ($this->hasListener('tearDown')) {
            $this->dispatch('tearDown');
        }

        // デバッグプロファイル追加
        if ($this->_conf->pxconf['DEBUG'] >= 2) {
            xFrameworkPX_Debug::getInstance()->addProfileData(
                get_class($this),
                'xFrameworkPX_Controller_Console',
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
