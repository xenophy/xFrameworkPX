<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Controller_Action Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Controller
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Action.php 1417 2010-01-20 04:56:44Z kotsutsumi $
 */

// {{{ xFrameworkPX_Controller_Action

/**
 * xFrameworkPX_Controller_Action Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Controller
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Controller_Action
 */
abstract class xFrameworkPX_Controller_Action
extends xFrameworkPX_Controller_Web
{
    // {{{ props

    /**
     * RapidDriveモードフラグ
     *
     * @var bool
     */
    public $rapid = false;

    // }}}
    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @param xFrameworkPX_Util_MixedCollection $conf 設定オブジェクト
     * @return void
     */
    public function __construct($conf)
    {
        if ($conf['pxconf']['DEBUG'] >= 2) {
            $startTime = microtime(true);
        }
        
        // RapidDriveコンポーネント追加
        if ($this->rapid !== false) {
            $conf->rapid = $this->rapid;
            $this->_components[] = array(
                'clsName' => 'xFrameworkPX_Controller_Component_RapidDrive',
                'bindName' => 'RapidDrive',
                'args' => $conf
            );
        }

        // スーパークラスメソッドコール
        parent::__construct($conf);

        if ($this->_conf->pxconf['DEBUG'] >= 2) {
            xFrameworkPX_Debug::getInstance()->addProfileData(
                get_class($this),
                'xFrameworkPX_Controller_Action',
                '__construct',
                'Controller',
                microtime(true) - $startTime
            );
        }
    }

    // }}}
    // {{{ execute

    /**
     * コールバックメソッド
     *
     * @return bool サスペンドフラグ
     */
    public function execute()
    {
        if ($this->_conf->pxconf['DEBUG'] >= 2) {
            $startTime = microtime(true);
        }

        if (
            $this->RapidDrive
            instanceOf
            xFrameworkPX_Controller_Component_RapidDrive
        ) {
            // アクション名設定
            $this->RapidDrive->actionName = $this->getActionName();

            // 入力値設定
            $this->RapidDrive->get = $this->get;
            $this->RapidDrive->post = $this->post;

            // セッションオブジェクト設定
            $this->RapidDrive->Session = &$this->Session;

            // RapidDrive実行
            if (isset($this->rapid['module'])) {
                $module = $this->modules[$this->rapid['module']];
            } else {
                $module = $this->modules[reset($this->modules)];
            }
            $ret = $this->RapidDrive->dispatch(
                $this->rapid['mode'],
                $this->rapid,
                $module
            );

            // 処理結果をViewに設定
            foreach ($ret as $moderet) {
                foreach ($moderet as $key => $value) {
                    $this->set($key, $value);
                }
            }
        }

        if ($this->_conf->pxconf['DEBUG'] >= 2) {
            xFrameworkPX_Debug::getInstance()->addProfileData(
                get_class($this),
                'xFrameworkPX_Controller_Action',
                'execute',
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
