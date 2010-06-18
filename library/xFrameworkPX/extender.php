<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * PHP Extender File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: extender.php 1181 2010-01-06 03:27:06Z tamari $
 */

/**
 * Directory Separator Shorthand
 */
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

// {{{ normalize_path

/**
 * パス正規化関数
 *
 * @param string $path パス
 * @return string 正規化されたパス
 */
function normalize_path($path, $separator = DS)
{
    $path = str_replace($separator, '/', $path);
    $path = preg_replace(
        '/(\/+)/i',
        '/',
        str_replace('\\', '/', $path)
    );

    if ($separator !== '/') {
        $path = str_replace('/', $separator, $path);
    }

    return $path;
}

// }}}
// {{{ file_forceput_contents

/**
 * ファイル出力関数
 *
 * ディレクトリが存在しない場合に自動生成してファイルを出力します。
 *
 * @param string $filename データを書き込むファイルへのパス。
 * @param string $data 書き込むデータ。
 * @param string $flags フラグ
 * @param string $context コンテキストリソース。
 * @see http://php.net/manual/ja/function.file-put-contents.php
 * @return string 正規化されたパス
 */
function file_forceput_contents($filename, $data, $flags = 0, $context = null)
{
    // {{{ ディレクトリ作成

    makeDirectory(dirname($filename));

    // }}}

    return file_put_contents(
        $filename,
        $data,
        $flags,
        $context
    );

}


// {{{ makeDirectory

/**
 * ディレクトリ生成メソッド
 *
 * @param string $dir 生成ディレクトリ
 * @param integer $mode パーミッション値(省略時:0755)
 * @return bool true:成功,false:失敗
 */
function makeDirectory($dir, $mode = 0755)
{

    // {{{ ディレクトリが存在するか、生成成功の場合は終了

    if (is_dir($dir) || @mkdir($dir, $mode)) {
        return true;
    }

    // }}}
    // {{{ ディレクトリ生成

    makeDirectory(dirname($dir), $mode);

    // }}}

    return @mkdir($dir, $mode);

}

// }}}
// {{{ removeDirectory

/**
 * ディレクトリ削除関数
 *
 * @param string $dir 削除ディレクトリ
 * @return void
 */
function removeDirectory($dir)
{
    if ($dh = @opendir($dir)) {
        while (false !== ($item = @readdir($dh))) {

            if ($item != '.' && $item != '..') {

                if (is_dir("$dir/$item")) {
                    removeDirectory("$dir/$item");
                } else {
                    @unlink("$dir/$item");
                }
            }
        }

        @closedir($dh);
        @rmdir($dir);
    }
}

// }}}
// {{{ file_copy

/**
 * ファイルコピー関数
 *
 * @param string $src コピー元ファイルパス
 * @param string $dest コピー先ファイルパス
 * @return int バイト数、失敗した場合はfalseを返します。
 */
function file_copy($dest, $src)
{

    return file_forceput_contents($dest, file_get_contents($src));

}

// }}}
// {{{ get_filename

/**
 * ファイル名取得関数
 *
 * @param string $file ファイルパス
 * @return string ファイル名
 */
function get_filename($file)
{

    $ret = '';

    if (defined('PATHINFO_FILENAME')) {

        $ret = pathinfo($file, PATHINFO_FILENAME);

    // @codeCoverageIgnoreStart
    } else if (strstr($file, '.')) {

        $ret = substr(
            pathinfo($file, PATHINFO_BASENAME),
            0,
            strrpos(
                pathinfo($file, PATHINFO_BASENAME),
                '.'
            )
        );

    }
    // @codeCoverageIgnoreEnd

    return $ret;

}

// }}}
// {{{ get_filelist

/**
 * ファイルリスト取得関数
 *
 * @param string $dir ディレクトリ
 * @param string $filter フィルター
 * @return array ファイルリスト
 */
function get_filelist($dir, $filter = null)
{
    $ret = array();

    if (!is_dir($dir)) {
        return $ret;
    }

    $iterator = new RecursiveDirectoryIterator($dir);

    foreach (
        new RecursiveIteratorIterator(
            $iterator,
            RecursiveIteratorIterator::CHILD_FIRST
        ) as $item
    ) {

        if (!$item->isDir()) {
            if (is_null($filter)) {
                $ret[] = $item->getPathname();
            } else {
                $valid = true;
                if (isset($filter['ext'])) {
                    if (
                        pathinfo(
                            $item->getPathname(),
                            PATHINFO_EXTENSION
                        ) !== $filter['ext']
                    ) {
                        $valid = false;
                    }
                }

                if (isset($filter['filename'])) {
                    if (
                        get_filename($item->getPathname())
                        !== $filter['filename']
                    ) {
                        $valid = false;
                    }
                }

                if ($valid === true) {
                    $ret[] = $item->getPathname();
                }
            }
        }
    }

    return $ret;
}

// }}}
// {{{ get_relative_url

/**
 * URL相対パス取得関数
 *
 * @param string $base ベースパス
 * @param string $target ターゲットパス
 * @return string 相対パス
 */
function get_relative_url($base, $target)
{
    $ret = '';
    $baseTemp   = explode('/', $base);
    $targetTemp = explode('/', $target);

    do {
        if (empty($baseTemp) || empty($targetTemp)) {
            break;
        }
        $to = array_shift($baseTemp);
        $from = array_shift($targetTemp);
    } while ($to  == $from);

    return str_repeat('../', count($baseTemp));
}

// }}}
// {{{ stripslashes_deep

/**
 * クォート削除関数
 *
 * @param mixed $values クォート削除対象オブジェクト
 * @return mixed クォート削除後オブジェクト
 */
function stripslashes_deep($values)
{
    if (is_array($values)) {
        foreach ($values as $key => $value) {
            $values[$key] = stripslashes_deep($value);
        }
    } else {
        $values = stripslashes($values);
    }

    return $values;
}

// }}}
// {{{ sys_get_temp_dir

if (!function_exists('sys_get_temp_dir')) {

// @codeCoverageIgnoreStart
/**
 * 一時ファイル用ディレクトリパス取得関数
 *
 * @return string 一時ディレクトリのパス
 */
function sys_get_temp_dir()
{
    if (!empty($_ENV['TMP'])) {
        return realpath($_ENV['TMP']);
    } else if (!empty($_ENV['TMPDIR'])) {
        return realpath($_ENV['TMPDIR']);
    } else if (!empty($_ENV['TEMP'])) {
        return realpath($_ENV['TEMP']);
    } else {
        $tempfile = tempnam(md5(uniqid(rand(), true)), '');
        if ($tempfile) {
            $tempdir = realpath(dirname($tempfile));
            unlink($tempfile);
            return $tempdir;
        } else {
            return false;
        }
    }
}
// @codeCoverageIgnoreEnd

}

// }}}
// {{{ mb_convert_encoding_deep

/**
 * 多階層mb_convert_encoding関数
 *
 * @param $values 対象変換文字列、または配列
 * @param $to 変換文字コード
 * @param $from 現在の文字コード
 * @return mixed 変換後の値
 */
function mb_convert_encoding_deep($values, $to, $from = 'auto')
{
    if (is_array($values)) {

        foreach ($values as $key => $target) {
            $values[$key] = mb_convert_encoding_deep($target, $to, $from);
        }

    } elseif (!empty($values) && is_string($values)) {
        $values = mb_convert_encoding($values, $to, $from);
    }

    return $values;
}

// }}}
// {{{ startsWith

/**
 * 前方一致確認関数
 * $checkが$targetから始まるか判定します。
 *
 * @param string $check チェック文字列
 * @param string $target 対象文字列
 * @return boolean true:一致:false:不一致
 */
function startsWith($check, $target)
{
    return strpos($check, $target, 0) === 0;
}

// }}}
// {{{ endsWith

/**
 * 後方一致確認関数
 * $checkが$targetで終わるか判定します。
 *
 * @param string $check チェック文字列
 * @param string $target 対象文字列
 * @return boolean true:一致:false:不一致
 */
function endsWith($check, $target)
{
    // {{{ 文字列長が足りていない場合はFALSEを返します。

    $len = (strlen($check) - strlen($target));
    if ($len < 0) {
        return false;
    }

    // }}}

    return strpos($check, $target, $len) !== false;

}

// }}}
// {{{ matchesIn

/**
 * 部分一致確認関数
 * $checkの中に$targetが含まれているか判定します。
 *
 * @param string $check チェック文字列
 * @param string $target 対象文字列
 * @return boolean true:一致:false:不一致
 */
function matchesIn($check, $target)
{

    return strpos($check, $target) !== false;

}

// }}}
// {{{ lcfirst

if (!function_exists('lcfirst')) {

// @codeCoverageIgnoreStart
/**
 * 先頭文字を小文字にする関数
 *
 * @param string $target 対象文字列
 * @return string 変換後の文字列
 */
function lcfirst($target)
{
    if (!empty($target) && is_string($target)) {
        $target{0} = strtolower($target{0});
    }
    return $target;
}
// @codeCoverageIgnoreEnd
}

// }}}
// {{{ get_status_code

/**
 * ステータスコード取得関数
 *
 * @param number $code コード番号
 * @return string ステータスコード
 */
function get_status_code($code)
{

    $codes = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out'
    );

    if (isset($codes[$code])) {
        return $codes[$code];
    }

    return null;

}

// }}}
// {{{ is_secure

/**
 * SSL接続判定メソッド
 *
 * @return true:SSL接続,false:非SSL接続
 */
function is_secure()
{

    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
        return true;
    } else {
        return false;
    }

}

// }}}
// {{{ base_name

/**
 * 基底URL取得メソッド
 *
 * @return string 基底URL
 */
function base_name($https=false)
{
    $protocol = is_secure() ? 'https://' : 'http://';

    if ($https===true) {
        $protocol = 'https://';
    }

    $serverName = $_SERVER['SERVER_NAME'];
    $serverPort = '';
    
    if($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') {
        $serverPort = ':' . $_SERVER["SERVER_PORT"];
    }

    $path = str_replace('index.php', '', $_SERVER['PHP_SELF']);

    return $protocol . $serverName . $serverPort . $path;

}

// }}}
// {{{ get_ip

/**
 * IPアドレス取得関数
 *
 * @return string IPアドレス
 */
function get_ip()
{
    return getenv('REMOTE_ADDR');
}

// }}}
// {{{ is_ipv6

/**
 * IPv6判定関数
 *
 * @return boolean true:IPv6,false:IPv4
 */
function is_ipv6($ip = '')
{
    if ($ip === '') {
        $ip = get_ip();
    }

    if (substr_count($ip, ':') > 0 && substr_count($ip, '.') == 0) {
        return true;
    } else {
        return false;
    }
}

// }}}
// {{{ uncompress_ipv6

/**
 * IPv6アドレス展開関数
 *
 * @param string IPv6アドレス
 * @return string 展開アドレス
 */
function uncompress_ipv6($ip = '')
{
    if ($ip === '') {
        $ip = get_ip();
    }

    if (strstr($ip, '::')) {
        $i = 0;
        $e = explode(":", $ip);
        $s = 8 - sizeof($e) + 1;

        foreach ($e as $key => $val) {
            if ($val == '') {
                for (; $i <= $s; $i++) {
                    $newip[] = 0;
                }
            } else {
                $newip[] = $val;
            }
        }
        $ip = implode(':', $newip);
    }

    return $ip;
}

// }}}
// {{{ uncompress_ipv6

/**
 * IPv6アドレス圧縮関数
 *
 * @param string IPv6アドレス
 * @return string 圧縮アドレス
 */
function compress_ipv6($ip ='')
{
    if ($ip === '') {
        $ip = get_ip();
    }

    if (!strstr($ip, '::')) {

        $e = explode(':', $ip);
        $zeros = array(0);
        $result = array_intersect($e, $zeros);

        if (sizeof($result) >= 6) {

            if ($e[0]==0) {
                $newip[] = "";
            }

            foreach ($e as $key=>$val) {
                if ($val !=='0') {
                    $newip[] = $val;
                }
            }

            $ip = implode('::', $newip);
        }

    }

    return $ip;
}

// }}}
// {{{ encrypt

/**
 * Blowfish暗号化関数
 */
function encrypt($key, $text)
{
    if (empty($key)) {
        trigger_error('An illegal key was specified.', E_USER_ERROR);
    }

    srand();
    $ivSize = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
    $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);

    return base64_encode(
        $iv . mcrypt_encrypt(
            MCRYPT_BLOWFISH,
            $key,
            $text,
            MCRYPT_MODE_CBC,
            $iv
        )
    );
}

// }}}
// {{{ decrypt

/**
 * Blowfish複合化関数
 */
function decrypt($key, $base64)
{
    if (empty($key)) {
        trigger_error('An illegal key was specified.', E_USER_ERROR);
    }

    $ivEncrypt = base64_decode($base64);

    $ivSize = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
    $iv = substr($ivEncrypt, 0, $ivSize);
    $encrypt = substr($ivEncrypt, $ivSize);

    return rtrim(
        mcrypt_decrypt(
            MCRYPT_BLOWFISH,
            $key,
            $encrypt,
            MCRYPT_MODE_CBC,
            $iv
        ),
        "\0"
    );
}

// }}}
// {{{ move_file

/**
 * ファイルアップロードメソッド
 *
 * @param $fieldname input type=imageのname
 * @param $filename 移動先ファイル名
 * @param $fileinfo $_FILES配列
 * @param $filemode ファイル移動時のパーミッション指定
 * @param $create 移動先ディレクトリの自動作成指定 true:作成する false:作成しない
 * @param $dirmode ディレクトリ自動作成時のパーミッション指定
 * @return boolean true:正常移動 false:移動失敗
 */
function move_file(
    $fieldname,
    $filename,
    $fileinfo,
    $filemode = 0666,
    $create = true,
    $dirmode = 0777
)
{

    if (
        ($fileinfo['error'][$fieldname] === UPLOAD_ERR_OK) &&
        ($fileinfo['size'][$fieldname] > 0) &&
        @is_uploaded_file($fileinfo['tmp_name'][$fieldname])
    ) {

        $dir = dirname($filename);

        if (!@is_readable($dir)) {

            if ($create && !makeDirectory($dir, $dirmode)) {
                $error = 'Failed to create the upload directory. "%s"';
                $error = sprintf($error, $dir);
                trigger_error($error, E_USER_ERROR);
            }

            @chmod($dir, $dirmode);

        } else if (!@is_dir($dir)) {

            $error = 'Upload file path does not exist. "%s"';
            $error = sprintf($error, $dir);
            trigger_error($error, E_USER_ERROR);

        } else if (!@is_writable($dir)) {

            $error = 'Not have permissions to write to the directory. "%s"';
            $error = sprintf($error, $dir);
            trigger_error($error, E_USER_ERROR);

        }

        if (@move_uploaded_file($fileinfo['tmp_name'][$fieldname], $filename)) {
            @chmod($filename, $filemode);
            return true;
        }
    }

    return false;

}

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
