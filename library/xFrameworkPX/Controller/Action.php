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

        // WiseTagコンポーネント設定
        $this->_components[] = array(
            'clsName' => 'xFrameworkPX_Controller_Component_WiseTag',
            'bindName' => 'Tag',
            'args' => $conf->pxconf['WISE_TAG']
        );

        // スーパークラスメソッドコール
        parent::__construct($conf);

        // セッションオブジェクト設定
        $this->Tag->setSession($this->Session);

        // WiseTag初期化
        $this->Tag->init();

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
            $sessName = sprintf(
                '%s_%s',
                $this->RapidDrive->sessName,
                $this->getActionName()
            );

            $this->Tag->init($sessName);
            $rdSess = $this->Session->read($this->RapidDrive->sessName);
            $prevActionPath = (isset($this->rapid['prevAction']))
                            ? sprintf(
                                '%s/%s',
                                $this->getContentPath(),
                                $this->rapid['prevAction']
                            )
                            : '';
            $nextActionPath = (isset($this->rapid['nextAction']))
                            ? sprintf(
                                '%s/%s',
                                $this->getContentPath(),
                                $this->rapid['nextAction']
                            )
                            : '';

            $lastActionPath = (!is_null($rdSess) && isset($rdSess['lastAction']))
                            ? $rdSess['lastAction']
                            : '';

            // 実行コマンド取得
            if (
                $nextActionPath !== '' && $lastActionPath !== '' &&
                $nextActionPath == $lastActionPath
            ) {
                $this->RapidDrive->cmd = 'back';
            } else {
                $this->RapidDrive->cmd = 'init';
            }

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

            // WiseTag初期化
            if ($this->RapidDrive->cmd == 'init') {
                $this->Tag->clear($sessName);

                if (isset($ret[0]['wiseTag'])) {

                    // WiseTag設定
                    $this->Tag->add($ret[0]['wiseTag']);

                    unset($ret[0]['wiseTag']);
                }

            } else if ($this->RapidDrive->cmd == 'back') {

                if (isset($ret[0]['wiseTag'])) {

                    foreach ($ret[0]['wiseTag'] as $wiseTag) {

                        if (isset($wiseTag['type']) && $wiseTag['type'] !== 'form') {
                            $editCond = array();

                            if (isset($wiseTag['type'])) {
                                $editCond['type'] = $wiseTag['type'];
                            }

                            if (isset($wiseTag['name'])) {
                                $editCond['name'] = $wiseTag['name'];
                            }

                            // WiseTag再設定
                            $this->Tag->edit($wiseTag, $editCond);
                        } else if (isset($wiseTag[0])) {

                            foreach($wiseTag as $key => $data) {
                                $editCond = array();

                                if (isset($data['type'])) {
                                    $editCond['type'] = $data['type'];
                                }

                                if (isset($wiseTag['name'])) {
                                    $editCond['name'] = $data['name'];
                                }

                                $editCond['count'] = $key + 1;

                                // WiseTag再設定
                                $this->Tag->edit($data, $editCond);
                            }

                        }

                    }

                    unset($ret[0]['wiseTag']);
                }

            }

            $this->Tag->gen($sessName);

            // 処理結果をViewに設定
            foreach ($ret as $moderet) {
                $this->set('rd', $moderet);

                /*
                foreach ($moderet as $key => $value) {
                    $this->set($key, $value);
                }
                */
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
