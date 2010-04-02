<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Object Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Object.php 1210 2010-01-08 09:05:34Z kotsutsumi $
 */

// {{{ xFrameworkPX_Object

/**
 * xFrameworkPX_Object Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Object
 */
class xFrameworkPX_Object
{
    // {{{ props

    /**
     * コンテンツパスキー
     *
     * @var string
     */
    protected $_cp = 'cp';

    /**
     * CLIアクション名
     *
     * @var string
     */
    public $cliAction = 'index';

    /**
     * デフォルトアクション名
     *
     * @var string
     */
    protected $_defaultaction = 'index';

    // }}}
    // {{{ toString

    /**
     * クラス名取得メソッド
     *
     * @return クラス名
     */
    public function toString()
    {
        return get_class($this);
    }

    // }}}
    // {{{ env

    /**
     * サーバー変数取得メソッド
     *
     * @param string $name サーバー変数キー
     * @return mixed サーバー変数値
     */
    public static function env($name)
    {
        if (isset($_SERVER[$name])) {
            return $_SERVER[$name];
        }

        return null;
    }

    // }}}
    // {{{ refererAction

    /**
     * リファラーによるファイル名取得メソッド
     *
     * @param string $default デフォルトファイル名
     * @return ファイル名
     */
    public static function refererAction($default = 'index.html')
    {
        // HTTP_REFERERがない場合はnullを返す
        if (is_null(self::env('HTTP_REFERER'))) {
            return null;
        }

        // HTTP_REFERERからファイルパス取得
        $referer = self::env('HTTP_REFERER');

        if (pathinfo($referer, PATHINFO_EXTENSION) === '') {
            $referer .= $default;
        }

        return get_filename($referer);
    }

    // }}}
    // {{{ redirect

    // @codeCoverageIgnoreStart
    /**
     * リダイレクトメソッド
     *
     * @param mixed $url リダイレクト先URL
     * @param int $status ステータスコード
     * @param boolean $exit ステータスコード
     * @return ディレクトリパス
     */
    public function redirect($url, $status = null, $exit = true)
    {

        // ステータスコード送信
        if (!empty($status)) {

            // ヘッダー送信
            header(
                sprintf(
                    'HTTP/1.1 %s %s',
                    $status,
                    get_status_code($status)
                )
            );
        }

        // ロケーションヘッダー送信
        header(sprintf('Location: %s', $url));

        // 終了
        if ($exit === true) {
            exit(0);
        }
    }
    // @codeCoverageIgnoreEnd

    // }}}
    // {{{ mix

    /**
     * xFrameworkPX_Util_MixedCollection生成メソッド
     *
     * @param $input 配列あるいはオブジェクト
     * @param $flag 制御フラグ
     * @return xFrameworkPX_Util_MixedCollectionオブジェクト
     */
    public function mix($input = array(), $flag = ArrayObject::ARRAY_AS_PROPS)
    {
         return new xFrameworkPX_Util_MixedCollection($input, $flag);
    }

    // }}}
    // {{{ getAccessFileName

    /**
     * アクセスファイル名取得メソッド
     *
     * @return string アクセスファイル名
     */
    public function getAccessFileName()
    {
        $path = null;

        // コンテンツパス取得
        if (isset($_GET[$this->_cp])) {
            $path = $_GET[$this->_cp];
        }

        return array_pop(explode('/', $path));
    }

    // }}}
    // {{{ getActionName

    /**
     * アクション名取得メソッド
     *
     * @param string $name コンテンツパスキー名
     * @return string アクション名
     */
    public function getActionName()
    {
        if (PHP_SAPI === 'cli') {

            $ret = $this->cliAction;

        } else {

            // コンテンツパス追加
            $path = null;
            if (isset($_GET[$this->_cp])) {
                $path = $_GET[$this->_cp];
            }

            // アクション名生成
            $ret = get_filename(end(explode('/', $path)));
            if ($ret === '') {
                $ret = $this->_defaultaction;
            }

        }

        return $ret;
    }

    // }}}
    // {{{ getContentPath

    /**
     * コンテンツパス取得メソッド
     *
     * @param string $name コンテンツパスキー名
     * @return string コンテンツパス
     * @access public
     */
    public function getContentPath()
    {
        // $_GETからコンテンツパス取得
        $path = '/';
        if (isset($_GET[$this->_cp])) {
            $path = $_GET[$this->_cp];
        }

        // パス正規化
        $path = normalize_path($path, '/');

        // パス分割
        $parts = explode('/', $path);

        // 最後のアイテムを削除
        array_pop($parts);

        // 配列を結合
        return implode('/', $parts);
    }

    // }}}
    // {{{ getParams

    /**
     * パラメータ取得メソッド
     *
     * @return xFrameworkPX_Util_MixedCollection
     * @access public
     */
    public function getParams()
    {
        $params = $this->mix();
        $params->form = $this->mix();
        $params->url = $this->mix();
        $params->files = $this->mix();
        $params->args = $this->mix();

        // リクエストメソッドごとに設定
        if (strtolower(PHP_SAPI) === 'cli') {

            // 引数のパラメータ設定
            array_shift($_SERVER['argv']);
            $argv = array();
            foreach ($_SERVER['argv'] as $arg) {
                if (substr($arg, 0, 2) == '--') {
                    $eqPos = strpos($arg, '=');
                    if ($eqPos === false) {
                        $key = substr($arg, 2);
                        $argv[$key] = isset($argv[$key])
                                         ? $argv[$key] : true;
                    } else {
                        $key = substr($arg, 2, $eqPos-2);
                        $argv[$key] = substr($arg, $eqPos+1);
                    }
                } else if (substr($arg, 0, 1) == '-') {
                    if (substr($arg, 2, 1) == '=') {
                        $key = substr($arg, 1, 1);
                        $argv[$key] = substr($arg, 3);
                    } else {
                        $chars = str_split(substr($arg, 1));
                        foreach ($chars as $char) {
                            $key = $char;
                            $argv[$key] = isset($argv[$key])
                                             ? $argv[$key] : true;
                        }
                    }
                } else {
                    $argv[] = $arg;
                }
            }

            $params->args->import($argv);

        } else {

            if (isset($_POST)) {

                if (ini_get('magic_quotes_gpc') === '1') {

                    $temp = stripslashes_deep($_POST);

                } else {

                    $temp = $_POST;

                }

                $params->form->import($temp);
                $params->form->_method = $_SERVER['REQUEST_METHOD'];

            }

            if (isset($_GET)) {
                if (ini_get('magic_quotes_gpc') === '1') {
                    $url = stripslashes_deep($_GET);
                } else {
                    $url = $_GET;
                }
                $params->url->import($url);
            }

            if (isset($_FILES)) {
                $params->files->import($_FILES);
            }

        }

        return $params;
    }

    // }}}
    // {{{ getRelativePath

    /**
     * アクセス相対位置
     *
     * @param string $name コンテンツパスキー名
     * @return string 相対位置
     */
    public function getRelativePath()
    {
        return str_repeat(
            '../',
            count(
                explode('/', $this->getContentPath())
            ) - 1
        );
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
