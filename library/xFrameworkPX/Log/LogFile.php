<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Log_LogFile Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Log
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: LogFile.php 1174 2010-01-05 14:28:45Z tamari $
 */

// {{{ xFrameworkPX_Log_LogFile

/**
 * xFrameworkPX_Log_LogFile Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Log
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Log_LogFile
 */
class xFrameworkPX_Log_LogFile extends xFrameworkPX_Log_LogBase
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
        $fileName = (string)$this->_param->filename;
        $quota = $this->_param->quota;
        $pathInfo = array();

        // 日付別用ファイル名生成
        if ($quota->date == 'true' || $quota->date == 'yes') {

            $pathInfo = pathinfo($fileName);

            // 現在の日付を取得
            $date = getdate();
            $formatDate = sprintf(
                '%04d%02d%02d', $date['year'], $date['mon'], $date['mday']
            );

            // ファイル名＋日付のファイル名作成
            if (isset($pathInfo['extension'])) {
                $fileName = $pathInfo['filename'] .
                            $formatDate . '.' . $pathInfo['extension'];
            } else {
                $fileName = $pathInfo['filename'] . $formatDate;
            }

        }

        $outputFileName = normalize_path($this->_logDir . DS . $fileName);

        // ローテート処理
        if (
            (int)$quota->size > 0 &&
            @filesize($outputFileName) >= (int)$quota->size
        ) {

            // 枝番号最高値取得
            $count = 1;

            while (file_exists($outputFileName . '.' . $count)) {
                $count++;
            }

            // リネーム処理
            for ($i = $count; $i > 0; --$i) {

                if (($i- 1) > 0) {
                    $suffix = ($i - 1);
                } else {
                    $suffix = '';
                }

                if ((int)$quota->limit > 0 && (int)$quota->limit <= $i) {
                    @unlink($outputFileName . '.' . $suffix);
                } else {
                    if ($suffix != '') {
                        @rename(
                            $outputFileName . '.' . $suffix,
                            $outputFileName . '.' . $i
                        );
                    } else {
                        @rename(
                            $outputFileName,
                            $outputFileName . '.' . $i
                        );
                    }
                }
            }
        }

        // ファイル出力
        $date = getdate();
        $buffer = '';
        $buffer = $buffer . sprintf(
            '%04d-%02d-%02d %02d:%02d:%02d',
            $date['year'],
            $date['mon'],
            $date['mday'],
            $date['hours'],
            $date['minutes'],
            $date['seconds']
        );
        $buffer = $buffer . ',' . sprintf(
            '% -6d',
            $location['line']
        ) . ' ';
        $buffer = $buffer . '[' . sprintf(
            '%05d',
            (function_exists('posix_getpid')) ? posix_getpid() : getmypid()
        ) . ']';
        $buffer = $buffer . ' ' . sprintf(
            '% -5s',
            $this->convertLevelString($level)
        );
        $buffer = $buffer . ' ' . $location['file'];
        $buffer = $buffer . ' - ' . $this->_message;
        $buffer = $buffer . "\n";

        file_forceput_contents($outputFileName, $buffer, FILE_APPEND);
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
