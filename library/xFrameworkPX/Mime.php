<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Mime Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Mime.php 616 2009-12-12 17:56:31Z  $
 */

// {{{ xFrameworkPX_Mine

/**
 * xFrameworkPX_Mime Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Mine
 */
class xFrameworkPX_Mime extends xFrameworkPX_Object
{

    // {{{ base64

    /**
     * BASE64エンコードメソッド
     *
     * BASE64にエンコードします。
     * PHPでは送信できるファイルのデータ量が限られており、
     * そのままでは100Mを超えるようなファイルは送信できません。
     * xFrameworkPXでは、このメソッドを使用することによって回避します。
     * 第1引数にリソース型の変数（ファイルポインタ）、
     * 第2引数にファイル名を指定し、
     * URLをエンコードする必要がなければ第3引数のフラグをfalseにします。
     * 第1引数に記述したリソース型変数は、
     * 内部で76文字（バイト）で改行されるように設定されているので、
     * 改行を意識することなくBASE64にエンコードすることが可能です。
     *
     * <code>
     * <?php
     *
     *     // WRIRootディレクトリ下の'test'というディレクトリにある'test.txt'
     *     // というファイルをBASE64にエンコードする前にまず、fopenします。
     *     $fileMemory = fopen( '../WRIRoot/test/test.txt', 'r' );
     *     // $memory = fopen( "php://temp/maxmemory:$nMemory", 'r+' );
     *
     *     // 'test.txt'をBASE64にエンコードします。
     *     $encFile = $this->U->encode->base64( $fileMemory, '../WRIRoot/test/test.txt' );
     * ?>
     * </code>
     *
     * @param $memory メモリリソース
     * @param $fileName ファイル名
     * @param $urlEncode = true urlencodeフラグ
     * @return int 長さ
     */
    public static function base64($memory, $fileName, $urlEncode = true)
    {
        $fileHandle = fopen($fileName, 'rb');

        $cache = '';
        $eof = false;
        $length = 0;

        if (!$fileHandle) {
            return -1;
        }

        while (1) {

            if (!$eof) {
                if (!feof($fileHandle)) {
                    $row = fgets($fileHandle, 1024);
                } else {
                    $row = '';
                    $eof = true;
                }
            }

            if (!empty($cache)) {
                $row = $cache . $row;
            } elseif ($eof) {
                break;
            }

            $base64 = base64_encode($row);
            $put = '';

            if (strlen($base64) < 76) {
                if ($eof) {
                    $put = $base64 . "\r\n";
                    $cache = '';
                } else {
                    $cache = $row;
                }
            } elseif (strlen($base64) > 76) {

                do {
                    $put .= substr($base64, 0, 76) . "\r\n";
                    $base64 = substr($base64, 76);
                } while (strlen($base64) > 76);

                $cache = base64_decode($base64);
            } else {

                if ($base64{75} == '=') {
                    $cache = $row;
                } else {
                    $put = $base64 . "\r\n";
                    $cache = '';
                }

            }

            if (!empty($put)) {

                if ($urlEncode) {
                    $put = urlencode($put);
                }

                $length += strlen($put);
                fputs($memory, $put);
            }

        }

        fclose($fileHandle);

        return $length;
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
