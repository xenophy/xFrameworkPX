<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Log Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Log.php 1181 2010-01-06 03:27:06Z tamari $
 */

// {{{ xFrameworkPX_Log

/**
 * xFrameworkPX_Log Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Log
 */
class xFrameworkPX_Log extends xFrameworkPX_Object
{
    // {{{ props

    /**
     * インスタンスオブジェクト
     *
     * @var object
     */
    private static $_instance = null;

    /**
     * 設定情報
     *
     * @var object
     */
    protected $_conf = null;

    /**
     * TRACEタイプ定数
     *
     * @var int
     */
    const TRACE = 0;

    /**
     * DEBUGタイプ定数
     *
     * @var int
     */
    const DEBUG = 1;

    /**
     * INFOタイプ定数
     */
    const INFO  = 2;

    /**
     * WARNタイプ定数
     *
     * @var int
     */
    const WARNING  = 3;

    /**
     * ERRORタイプ定数
     *
     * @var int
     */
    const ERROR = 4;

    /**
     * FATALタイプ定数
     *
     * @var int
     */
    const FATAL = 5;

    // }}}
    // {{{ getInstance

    /**
     * インスタンス取得メソッド
     *
     * @param object $conf インスタンス生成元オブジェクト
     * @return ConfigManagerインスタンス
     */
    public static function getInstance($conf = null)
    {

        if (!isset(self::$_instance) && !is_null($conf)) {
            self::$_instance = new xFrameworkPX_Log();
            self::$_instance->_conf = $conf;
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
    // {{{ trace

    /**
     * TRACEログメソッド
     *
     * 設定するログレベルをTRACEレベル、
     * 第1引数に表示させるメッセージ、
     * 第2引数に設定するチャンネルを指定して、ログレベルを設定します。
     * チャンネルは何も指定しない場合、defaultに設定されます。
     *
     * @param string $message ロガーメッセージ
     * @param string $channel = default ロガーチャンネル
     * @return void
     */
    public function trace($message, $channel = 'default')
    {

        foreach (debug_backtrace() as $stack) {
            $location = $stack;
            break;
        }

        $this->_putLog($message, $location, $channel, self::TRACE);
    }

    // }}}
    // {{{ debug

    /**
     * DEBUGログメソッド
     *
     * 設定するログレベルをDEBUGレベル、
     * 第1引数に表示させるメッセージ、
     * 第2引数に設定するチャンネルを指定して、ログレベルを設定します。
     * チャンネルは何も指定しない場合、defaultに設定されます。
     *
     * @param string $message ロガーメッセージ
     * @param string $channel = default ロガーチャンネル
     * @return void
     */
    public function debug($message, $channel = 'default')
    {

        foreach (debug_backtrace() as $stack) {
            $location = $stack;
            break;
        }

        $this->_putLog($message, $location, $channel, self::DEBUG);
    }

    // }}}
    // {{{ info

    /**
     * INFOログメソッド
     *
     * 設定するログレベルをINFOレベル、
     * 第1引数に表示させるメッセージ、
     * 第2引数に設定するチャンネルを指定して、ログレベルを設定します。
     * チャンネルは何も指定しない場合、defaultに設定されます。
     *
     * @param string $message ロガーメッセージ
     * @param string $channel = default ロガーチャンネル
     * @return void
     */
    public function info($message, $channel = 'default')
    {

        foreach (debug_backtrace() as $stack) {
            $location = $stack;
            break;
        }

        $this->_putLog($message, $location, $channel, self::INFO);
    }

    // }}}
    // {{{ warning

    /**
     * WARNINGログメソッド
     *
     * 設定するログレベルをWARNINGレベル、
     * 第1引数に表示させるメッセージ、
     * 第2引数に設定するチャンネルを指定して、ログレベルを設定します。
     * チャンネルは何も指定しない場合、defaultに設定されます。
     *
     * @param string $message ロガーメッセージ
     * @param string $channel = default ロガーチャンネル
     * @return void
     */
    public function warning($message, $channel = 'default')
    {

        foreach (debug_backtrace() as $stack) {
            $location = $stack;
            break;
        }

        $this->_putLog($message, $location, $channel, self::WARNING);
    }

    // }}}
    // {{{ error

    /**
     * ERRORログメソッド
     *
     * 設定するログレベルをERRORレベル、
     * 第1引数に表示させるメッセージ、
     * 第2引数に設定するチャンネルを指定して、ログレベルを設定します。
     * チャンネルは何も指定しない場合、defaultに設定されます。
     *
     * @param string $message ロガーメッセージ
     * @param string $channel = default ロガーチャンネル
     * @return void
     */
    public function error($message, $channel = 'default')
    {

        foreach (debug_backtrace() as $stack) {
            $location = $stack;
            break;
        }

        $this->_putLog($message, $location, $channel, self::ERROR);
    }

    // }}}
    // {{{ fatal

    /**
     * FATALログメソッド
     *
     * 設定するログレベルをFATALレベル、
     * 第1引数に表示させるメッセージ、
     * 第2引数に設定するチャンネルを指定して、ログレベルを設定します。
     * チャンネルは何も指定しない場合、defaultに設定されます。
     *
     * @param string $message ロガーメッセージ
     * @param string $channel = default ロガーチャンネル
     * @return void
     */
    public function fatal($message, $channel = 'default')
    {

        foreach (debug_backtrace() as $stack) {
            $location = $stack;
            break;
        }

        $this->_putLog($message, $location, $channel, self::FATAL);
    }

    // }}}
    // {{{ putLog

    /**
     * ログ出力メソッド
     *
     * @param string $message ログメッセージ
     * @param array $location ロケーション情報配列
     * @param string $channel チャンネル名
     * @param int $level ログレベル
     * @return void
     */
    private function _putLog($message, $location, $channel, $level)
    {

        // {{{ ローカル変数初期化

        $config = $this->_conf->logconf;
        $loggerStack = array();
        $className = '';
        $path = '';
        $settingLevel = null;
        $settingChannel = strtolower($config->channel->$channel);
        $exec = null;

        // }}}

        if ($settingChannel === 'true' || $settingChannel === 'yes') {

            // 設定ログレベル取得
            switch(strtoupper((string)$config->loglevel)) {

                case 'TRACE':
                    $settingLevel = self::TRACE;
                    break;

                case 'DEBUG':
                    $settingLevel = self::DEBUG;
                    break;

                case 'INFO':
                    $settingLevel = self::INFO;
                    break;

                case 'WARNING':
                    $settingLevel = self::WARNING;
                    break;

                case 'ERROR':
                    $settingLevel = self::ERROR;
                    break;

                case 'FATAL':
                    $settingLevel = self::FATAL;
                    break;
            }

            // ログレベル設定にあわせて出力
            if ($settingLevel <= $level) {

                // ロガースタック取得
                $loggerStackConf = $config->loggers->$channel;

                if (sizeof($loggerStackConf->logger) === 1) {
                    $loggerStack[] = $loggerStackConf->logger;
                } else {
                    $loggerStack = $loggerStackConf->logger;
                }

                // ロガー実行
                foreach ($loggerStack as $logger) {

                    // クラス名とパスを取得
                    $className = (string)$logger->name;
                    $param = isset($logger->params) ? $logger->params : null;
                    $path = normalize_path('..' . DS . (string)$logger->path);

                    if (file_exists($path)) {
                        include_once $path;
                    } else {
                        $path = normalize_path(
                            $this->_conf->pxconf['PX_LIB_DIR'] . DS .
                            (string)$logger->path
                        );

                        if (file_exists($path)) {
                            include_once $path;
                        } else {
                            throw new xFrameworkPX_Exception(sprintf(
                                PX_ERR70000,
                                $path
                            ));
                        }

                    }

                    if (!class_exists($className)) {
                        throw new xFrameworkPX_Exception(sprintf(
                            PX_ERR70001,
                            $className
                        ));
                    }

                    $exec = new $className(array(
                        'message' => $message,
                        'param' => $param,
                        'dir' => $this->_conf->pxconf['LOG_DIR']
                    ));
                    $exec->execute($level, $location);
                }

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
