<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Log_LogBase Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Log
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: LogBase.php 1174 2010-01-05 14:28:45Z tamari $
 */

// {{{ xFrameworkPX_Log_LogBase

/**
 * xFrameworkPX_Log_LogBase Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Log
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Log_LogBase
 */
abstract class xFrameworkPX_Log_LogBase extends xFrameworkPX_Object
{

    // {{{ props

    /**
     * エラーメッセージ
     *
     * @var string
     */
    protected $_logDir;

    /**
     * エラーメッセージ
     *
     * @var string
     */
    protected $_message;
    
    /**
     * パラメータオブジェクト
     *
     * @var array
     */
    protected $_param;
    
    // }}}
    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @param string $message 出力メッセージ
     * @param array $param XMLパラメータオブジェクト
     * @return void
     */
    public function __construct($conf)
    {

        // ログディレクトリ
        $this->_logDir = $conf['dir'];

        // メッセージ格納
        $this->_message = $conf['message'];

        // パラメータオブジェクト
        $this->_param = $conf['param'];
    }

    // }}}
    // {{{ execute
    
    /**
     * ロギング実行メソッド
     *
     * @param int $level ログレベル
     * @param array $location ロケーション情報
     * @return void
     */
    public abstract function execute($level, $location);

    // }}}
    // {{{ convertLevelString

    /**
     * ログレベル文字列変換
     *
     * @param $level ログレベル
     * @return ログレベル文字列
     */
    public function convertLevelString($level)
    {
        $logLevel = '';

        switch ($level) {
            case xFrameworkPX_Log::TRACE:
                $logLevel = 'TRACE';
                break;

            case xFrameworkPX_Log::DEBUG:
                $logLevel = 'DEBUG';
                break;

            case xFrameworkPX_Log::INFO:
                $logLevel = 'INFO';
                break;

            case xFrameworkPX_Log::WARNING:
                $logLevel = 'WARNING';
                break;

            case xFrameworkPX_Log::ERROR:
                $logLevel = 'ERROR';
                break;

            case xFrameworkPX_Log::FATAL:
                $logLevel = 'FATAL';
                break;
        }

        return $logLevel;
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
