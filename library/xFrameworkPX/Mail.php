<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Mail Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Mail.php 1409 2010-01-20 03:03:29Z kotsutsumi $
 */

// {{{ xFrameworkPX_Mail

/**
 * xFrameworkPX_Mail Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Mail
 */
class xFrameworkPX_Mail extends xFrameworkPX_Object
{

    // {{{ _extractMailAddr

    /**
     * メールアドレス抽出メソッド
     *
     * @param $addr 宛先名を含むメールアドレス
     * @return メールアドレス
     */
    private function _extractMailAddr($addr)
    {

        if (!mb_strpos($addr, '<')) {
            return $addr;
        }

        return mb_substr(
            $addr,
            mb_strpos($addr, '<') + 1,
            mb_strpos($addr, '>') - mb_strpos($addr, '<') - 1
        );
    }

    // }}}
    // {{{ _extractName

    /**
     * 宛先名抽出メソッド
     *
     * @param $addr 宛先名を含むメールアドレス
     * @return 宛先名
     */
    private function _extractName($addr)
    {
        if (!mb_strpos($addr, '<')) {
            return "";
        }

        return mb_substr($addr, 0, mb_strpos($addr, '<'));
    }

    // }}}
    // {{{ _makeAddrList

    /**
     * メールアドレス文字列作成メソッド
     *
     * @param $address 宛先名を含むメールアドレス文字列、または配列
     * @return 送信可能宛先文字列
     */
    private function _makeAddrList($address)
    {
        $addresses = array();
        $temp = array();

        if (!is_array($address)) {
            $addresses[] = $address;
        } else {
            $addresses = $address;
        }

        foreach ($addresses as $item) {
            $item = trim($item);

            $tempAddr = $this->_extractMailAddr($item);
            $tempName = $this->_extractName($item);

            if ($tempName != '') {
                $tempName = mb_convert_encoding(
                    $tempName, "ISO-2022-JP-ms", "UTF-8"
                );
                $tempName =
                    "=?ISO-2022-JP-ms?B?" . base64_encode($tempName) . "?=";
            }

            if ($tempName) {
                $temp[] = $tempName . '<' . $tempAddr . '>';
            } else {
                $temp[] = $tempAddr;
            }

        }

        return implode(',', $temp);
    }

    // }}}
    // {{{ _genHeader

    /**
     * 
     *
     * @param xFrameworkPX_Util_MixedCollection $conf
     * @return string
     */
    private function _genHeader($conf)
    {
        $ret = "From: " . $conf->from . "\n";

        if (isset($conf->cc)) {
            $ret .= "Cc: " . $this->_makeAddrList($conf->cc) . "\n";
        }

        if (isset($conf->bcc)) {
            $ret .= "Bcc: " . $this->_makeAddrList($conf->bcc) . "\n";
        }

        $ret .= implode(
            "",
            array(
                "X-Mailer: " . $conf->mailer,
                "MIME-Version: 1.0",
                "Content-Type: text/plain; charset=\"ISO-2022-JP-ms\"",
                "Content-Transfer-Encoding: 7bit"
            )
        );

        return $ret;
    }

    // }}}
    // {{{ send

    /**
     * メール送信メソッド
     *
     * メールを送信します。
     * ブラウザ上からメール送信するようなフォームを作成したい場合に使用します。
     * 第5引数のオプションを指定することで、
     * CCやBCC、添付ファイル等の設定が可能です。
     * 面倒な設定などが内部で自動的に行われ、
     * 最終的にはPHPの関数であるmailを呼び出しています。
     *
     * <code>
     * <?php
     *
     *     // 送信先を宛先様、送信元を差出人、題名がxMailer Test、
     *     // 本文がThis is a xMailer test Mail.でCCとBCCを指定し、
     *     // WRIRootディレクトリ下の_actionsというディレクトリにある
     *     // test.phpのファイルをファイル名xMailertest.phpとして送信します。
     *     $this->U->mailer->send(
     *                            ' 宛先様 <to@xxxxxx.com> ',
     *                            ' 差出人 <from@xxxxxx.com> ',
     *                            ' xMailer Test ',
     *                            ' This is a xMailer test Mail. ',
     *                            array(
     *                                ' cc '  => ' cc  <cc@xxxxxx.com> ',
     *                                ' bcc   => ' bcc <bcc@xxxxxx.com>',
     *                                ' file ' => array(  
     *                                                array(
     *                                                    ' name ' => ' xMailertest.php ',
     *                                                    ' path ' =>       XF_APPLICATION_PATH .  '_actions/test.php '
     *                                                )
     *                                            )
     *                            )
     *                       );
     *
     * ?>
     * </code>
     *
     * @param $strTo 送信元メールアドレス
     * @param $strFrom 送信先メールアドレス
     * @param $strSubject メールタイトル
     * @param $strBody 本文
     * @param $arrOption オプション配列
     * @return boolean true:成功 false:失敗
     */
    public function send($conf)
    {
        // 配列で指定された場合、xFrameworkPX_Util_MixedCollectinに変換
        if (is_array($conf)) {
            $conf = $this->mix($conf);
        }

        $boundary = "Boundary_" . uniqid("b");

        // 送信先チェック\
        if (!isset($conf->to)) {
            throw new xFrameworkPX_Exception('送信先が設定されていません。');
        }

        // 差出人チェック
        if (!isset($conf->from)) {
            throw new xFrameworkPX_Exception('差出人が設定されていません。');
        }

        $conf->from = $this->_makeAddrList($conf->from);

        // 内部のエンコード取得/設定
        $mbLanguage = mb_language();
        $mbInternalEncoding = mb_internal_encoding();

        mb_language('japanese');
        mb_internal_encoding('UTF-8');

        // タイトル設定
        if (!isset($conf->subject)) {
            $conf->subject = '無題';
        }

        $conf->subject = mb_convert_encoding(
            $conf->subject,
            'ISO-2022-JP-ms',
            'UTF-8'
        );
        $conf->subject = sprintf(
            "=?ISO-2022-JP?B?%s?=",
            base64_encode($conf->subject)
        );

        // メーラー設定
        if (!isset($conf->mailer)) {
            $conf->mailer = 'PHP/Mail';
        }

        // 本文設定
        if (!isset($conf->body)) {
            $conf->body = '';
        }

        $conf->body = mb_convert_encoding(
            $conf->body,
            "ISO-2022-JP-ms",
            "UTF-8"
        );

        // 添付ファイル設定
        if (isset($conf->files)) {
            $body  = '';
            $body .= "--" . $boundary . "\n";
            $body .= "Content-Type: text/plain; charset=ISO-2022-JP\n";
            $body .= "Content-Transfer-Encoding: 7bit\n\n";
            $body .= $conf->body;
            $body .= "\n";
            $body .= "--" . $boundary . "\n";

            $i=0;

            foreach ($conf->files as $file) {

                if (is_array($file)) {
                    $fileName = $file[ 'name' ];
                    $filePath = $file[ 'path' ];
                } else {
                    $fileName = $file;
                    $filePath = $file;
                }

                $tail = '';

                if (sizeof($conf->files)-1 == $i) {
                    $tail = '--';
                }

                // メモリリソース取得
                $memoryByte = 1024 * 1024 * 5; // 5MB
                $memory = fopen("php://temp/maxmemory:$memoryByte", 'r+');

                // 添付するファイルパス取得
                $loadFile = $filePath;

                // Windowsならパスをエンコード
                if (
                    isset($_ENV[ 'OS' ]) &&
                    preg_match("/window/i", $_ENV[ 'OS' ])
                ) {
                    $loadFile = mb_convert_encoding(
                        $loadFile, 'sjis', 'utf-8'
                    );
                }

                // 添付ファイルをbase64化
                $lengthData = xFrameworkPX_Mime::base64(
                    $memory,
                    $loadFile,
                    false
                );

                // テンポラリストリームポインタを先頭に戻す
                rewind($memory);

                // 添付ファイル内容取得
                $fileObject = chunk_split(
                    stream_get_contents($memory, -1, 0)
                );

                fclose($memory);

                // ファイル名をエンコード
                $file = mb_convert_encoding(
                    $fileName, "ISO-2022-JP-ms", "UTF-8"
                );

                // 添付ファイル書き込み
                $body .= sprintf(
                    "Content-Type: application/octet-stream; name=\"%s\"\n",
                    $file
                );
                $body .= "Content-Transfer-Encoding: base64\r\n";
                $body .= sprintf(
                    "Content-Disposition: attachment; filename=\"%s\"\n\n",
                    $file
                );

                $body .= $fileObject . "\n";
                $body .= "--" . $boundary . $tail ."\n";

                $i++;
            }

            $conf->body = $body;

            $header = "From: " . $conf->from . "\n";

            if (isset($conf->cc)) {
                $header .= "Cc: " . $this->_makeAddrList($conf->cc) . "\n";
            }

            if (isset($conf->bcc)) {
                $header .= "Bcc: " . $this->_makeAddrList($conf->bcc) . "\n";
            }

            $header .= implode(
                "\n",
                array(
                    "X-Mailer: " . $conf->mailer,
                    "MIME-Version: 1.0",
                    "Content-Type: multipart/mixed; boundary=\"$boundary\"",
                    "Content-Transfer-Encoding: 7bit"
                )
            );

        } else {

            $header = "From: " . $conf->from . "\n";

            if (isset($conf->cc)) {
                $header .= "Cc: " . $this->_makeAddrList($conf->cc) . "\n";
            }

            if (isset($conf->bcc)) {
                $header .= "Bcc: " . $this->_makeAddrList($conf->bcc) . "\n";
            }

            $header .= implode(
                "\n",
                array(
                    "X-Mailer: " . $conf->mailer,
                    "MIME-Version: 1.0",
                    "Content-Type: text/plain; charset=\"ISO-2022-JP\"",
                    "Content-Transfer-Encoding: 7bit"
                )
            );
        }

        // メール送信
        $ret = mail(
            $this->_makeAddrList($conf->to),
            $conf->subject,
            $conf->body,
            $header
        );

        // 内部エンコード復帰
        mb_language($mbLanguage);
        mb_internal_encoding($mbInternalEncoding);

        return $ret;
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
