<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Log_LogMail Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Log
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: LogMail.php 1174 2010-01-05 14:28:45Z tamari $
 */

// {{{ xFrameworkPX_Log_LogMail

/**
 * xFrameworkPX_Log_LogMail Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Log
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Log_LogMail
 */
class xFrameworkPX_Log_LogMail extends xFrameworkPX_Log_LogBase
{

    // {{{ execute

    /**
     * ロギング実行メソッド
     *
     * @param int $level ログレベル文字列
     * @param array $location ロケーション情報
     */
    public function execute($level, $location)
    {
        $mail = new xFrameworkPX_Mail();
        $mailConf = $this->mix();
        $body = '';

        // 宛先アドレス取得
        $mailConf->to = $this->_param->to->addr;

        // 差出人アドレス取得
        $mailConf->from = $this->_param->from;

        // 件名取得
        $mailConf->subject = $this->_param->subject;

        // 本文取得
        $date = getdate();
        $body .= sprintf(
            '%04d-%02d-%02d %02d:%02d:%02d',
            $date['year'],
            $date['mon'],
            $date['mday'],
            $date['hours'],
            $date['minutes'],
            $date['seconds']
        );
        $body .= ',' . sprintf("% -6d", $location['line']) . ' ';
        $body .= '[' . sprintf(
            '%05d',
            (function_exists('posix_getpid')) ? posix_getpid() : getmypid()
        ) .
        ']';
        $body .= ' ' . sprintf(
            '% -5s', $this->convertLevelString($level)
        );
        $body .= ' ' . $location['file'];
        $body .= ' - ' . $this->_message . "\n";

        $mailConf->body = $body;

        // メール送信
        $mail->send($mailConf);
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
