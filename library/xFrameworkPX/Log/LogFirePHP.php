<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Log_LogFirePHP Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Log
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: LogFirePHP.php 1174 2010-01-05 14:28:45Z tamari $
 */

// {{{ xFrameworkPX_Log_LogFirePHP

/**
 * xFrameworkPX_Log_LogFirePHP Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Log
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Log_LogFirePHP
 */
class xFrameworkPX_Log_LogFirePHP extends xFrameworkPX_Log_LogBase
{

    // {{{ execute

    /**
     * ロギング実行メソッド
     *
     * @param int $level ログレベル
     * @param array $location ロケーション情報
     */
    public function execute($level, $location)
    {
        if (file_exists('../library/FirePHPCore/FirePHP.class.php')) {
            include_once '../library/FirePHPCore/FirePHP.class.php';
        } else {
            throw new xFrameworkPX_Exception(sprintf(
                PX_ERR70000,
                '../library/FirePHPCore/FirePHP.class.php'
            ));
        }

        $firePhp = new FirePHP();

        switch( $level ) {
            case xFrameworkPX_Log::TRACE:
                $firePhp->fb($this->_message, FirePHP::TRACE);
                break;

            case xFrameworkPX_Log::DEBUG:
                // none.
                break;

            case xFrameworkPX_Log::INFO:
                $firePhp->fb($this->_message, FirePHP::INFO);
                break;

            case xFrameworkPX_Log::WARNING:
                $firePhp->fb($this->_message, FirePHP::WARN);
                break;

            case xFrameworkPX_Log::ERROR:
                $firePhp->fb($this->_message, FirePHP::ERROR);
                break;

            case xFrameworkPX_Log::FATAL:
                // none.
                break;
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
