<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Util_Observable Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Util
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Observable.php 1177 2010-01-05 14:49:57Z tamari $
 */

// {{{ xFrameworkPX_Util_Observable

/**
 * xFrameworkPX_Util_Observable Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Util
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Util_Observable
 */
class xFrameworkPX_Util_Observable extends xFrameworkPX_Object
{

    // {{{ props

    /**
     * サスペンドキュー配列
     *
     * @var $_suspendQueue
     */
    private $_suspendQueue = array();

    /**
     * サスペンドキューフラグ
     *
     * @var $_queueSuspended
     */
    private $_queueSuspended = false;

    /**
     * サスペンドフラグ
     *
     * @var $_suspended
     */
    private $_suspended = false;

    /**
     * イベントサスペンドフラグ
     *
     * @var $_eventSuspend
     */
    protected $_eventSuspend = true;

    /**
     * リスナー配列
     *
     * @var $_listeners
     */
    protected $_listeners = array();

    // }}}
    // {{{ _dispatch

    /**
     * イベントディスパッチ
     *
     * @param mixed $callback コールバック設定
     * @param array $args コールバック引数配列
     * @return bool
     */
    private function _dispatch($callback, $args)
    {

        if (is_string($callback)) {

            if (function_exists($callback) === false) {
                throw new xFrameworkPX_Util_Observable_Exception(PX_ERR20002);
            }

        } else if (is_array($callback) && count($callback) === 2) {

            if (method_exists($callback[ 0 ], $callback[ 1 ]) === false) {
                throw new xFrameworkPX_Util_Observable_Exception(PX_ERR20002);
            }

        } else {
            throw new xFrameworkPX_Util_Observable_Exception(PX_ERR20002);
        }

        return call_user_func_array($callback, $args);
    }

    // }}}
    // {{{ addEvents

    /**
     * イベント登録
     *
     * @param string $eventName1
     * @param string $eventName2
     * @param string ...
     * @return bool 既存イベントが存在する場合、falseを返します。
     *              指定されたイベント名がすべて未登録の場合は、
     *              trueを返します。
     */
    public function addEvents()
    {
        $ret = true;

        // 配列のキーと値を反転する
        $addEvents = array_flip(func_get_args());

        // イベントを登録する
        foreach ($addEvents as $event => $key) {

            // 未登録のイベントのみ登録する
            if (!isset($this->_listeners[ $event ])) {
                $this->_listeners[ $event ] = $key;
            } else {
                $ret = false;
            }

        }

        return $ret;
    }

    // }}}
    // {{{ addListener

    /**
     * イベント追加
     *
     * @params string $eventName イベント名
     * @param mixed $callback コールバック設定
     * @return void
     */
    public function addListener($eventName, $callback)
    {
        if (!isset($this->_listeners[$eventName])) {
            throw new xFrameworkPX_Util_Observable_Exception(
                sprintf(PX_ERR20001, $eventName)
            );
        }

        if (!is_array($this->_listeners[$eventName])) {
            $this->_listeners[$eventName] = array();
        }

        array_push($this->_listeners[$eventName], $callback);
    }

    // }}}
    // {{{ dispatch

    /**
     * イベントディスパッチ
     *
     * @params string $eventName イベント名
     * @params mixed 引数1
     * @params mixed 引数2
     * @params mixed ...
     * @return bool
     */
    public function dispatch()
    {

        // 引数取得
        $args = func_get_args();

        // イベント名取得
       $eventName= array_shift($args);

        $ret = array();

        // イベント存在確認
        if ($this->hasListener($eventName)) {

            // コールバック呼び出し
            foreach ($this->_listeners[$eventName] as $callback) {

                if ($this->_suspended) {

                    if ($this->_queueSuspended) {
                        $cb = xFrameworkPX_Util_Serializer::serialize(
                            $callback
                        );

                        array_push(
                            $this->_suspendQueue,
                            array(
                                'event_name' => $eventName,
                               'callback'=> $cb,
                               'arguments'=> $args
                            )
                        );
                    }

                } else {
                    $temp = $this->_dispatch($callback, $args);

                    if ($temp === false && $this->_eventSuspend === true) {
                        return false;
                        break;
                    }

                    $ret[] = $temp;
                }

            }

            return $ret;
        } else {

            // %sイベントは登録されていません。
            throw new xFrameworkPX_Util_Observable_Exception(
                sprintf(PX_ERR20001, $eventName)
            );
        }

    }

    // }}}
    // {{{ hasListener

    /**
     * イベントリスナー存在確認
     *
     * @param string $eventName
     * @return bool true:存在、false:存在しない
     */
    public function hasListener($eventName)
    {
        $ret = false;

        if (isset($this->_listeners[$eventName])) {
            if (
                is_array($this->_listeners[$eventName]) &&
                count(isset($this->_listeners[$eventName])) > 0
            ) {
                $ret = true;
            }
        }

        return $ret;
    }

    // }}}
    // {{{ on

    /**
     * イベント追加
     *
     * @params string $eventName イベント名
     * @param mixed $callback コールバック設定
     * @return void
     */
    public function on($eventName, $callback)
    {
        $this->addListener($eventName, $callback);
    }

    // }}}
    // {{{ purgeListeners

    /**
     * イベントリスナー全削除
     *
     * @return void
     */
    public function purgeListeners()
    {
        unset($this->_listeners);
        $this->_listeners = array();
    }

    // }}}
    // {{{ removeListener

    /**
     * イベント削除
     *
     * @params string $eventName イベント名
     * @param mixed $callback コールバック設定
     * @return void
     */
    public function removeListener($eventName, $callback)
    {

        if (!isset($this->_listeners[$eventName])) {
            throw new xFrameworkPX_Util_Observable_Exception(
                sprintf(PX_ERR20001, $eventName)
            );
        }

        if ($this->hasListener($eventName)) {
            foreach (
                $this->_listeners[$eventName] as
                $key => $registedCallback
            ) {
                if (
                    xFrameworkPX_Util_Serializer::serialize($callback) ===
                    xFrameworkPX_Util_Serializer::serialize($registedCallback)
                ) {
                    unset($this->_listeners[$eventName][$key]);
                }
            }

            $this->_listeners[$eventName] = array_values(
                $this->_listeners[$eventName]
            );
        }

    }

    // }}}
    // {{{ resumeEvents

    /**
     * イベントレジューム
     *
     */
    public function resumeEvents()
    {

        foreach ($this->_suspendQueue as $queue) {
            $callback = xFrameworkPX_Util_Serializer::unserialize(
                $queue['callback']
            );

            if (
                $this->_dispatch($callback, $queue['arguments']) === false
            ) {
                break;
            }

        }

        $this->_suspended = false;
        $this->_queueSuspended = false;

        unset($this->_suspendQueue);
        $this->_suspendQueue = array();
    }

    // }}}
    // {{{ suspendEvents

    /**
     * イベント停止
     *
     * @param bool $queueSuspended trueを設定した場合、停止させたイベント
     *                              をresumeEventsコール時に実行される
     *                              キューに保存します。
     *                              初期値:false
     */
    public function suspendEvents($queueSuspended = false)
    {
        $this->_suspended = true;
        $this->_queueSuspended = $queueSuspended;
    }

    // }}}
    // {{{ un

    /**
     * イベント削除
     *
     * @params string $eventName イベント名
     * @param mixed $callback コールバック設定
     * @return void
     */
    public function un($eventName, $callback)
    {
        $this->removeListener($eventName, $callback);
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
