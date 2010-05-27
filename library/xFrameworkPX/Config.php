<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Config Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Config.php 974 2009-12-26 10:12:48Z kotsutsumi $
 */

// {{{ xFrameworkPX_Config

/**
 * xFrameworkPX_Config Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Config
 */
abstract class xFrameworkPX_Config
extends xFrameworkPX_Object
implements xFrameworkPX_ConfigInterface
{
    // {{{ props

    /**
     * インスタンス変数
     *
     * @var xFrameworkPX
     */
    protected static $_instance = null;

    /**
     * 設定SimpleXMLオブジェクト
     *
     * @var $xml
     */
    protected $_xml = null;

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
    // {{{ __get

    /**
     * 読み出しオーバーロード
     *
     * @param string $path XMLパス
     * @return mixed SimpleXMLElementオブジェクト
     */
    public function __get($path)
    {
        return $this->_xml->{$path};
    }

    // }}}
    // {{{ __set

    /**
     * 書き込みオーバーロード
     *
     * @param string $path XMLパス
     * @param mixed $value 設定値
     * @return void
     */
    public function __set($path, $value)
    {
        $this->_xml->{$path} = $value;
    }

    // }}}
    // {{{ getInstance

    /**
     * インスタンス取得メソッド
     *
     * @return xFrameworkPXインスタンス
     */
    public static function getInstance()
    {
    }

    // }}}
    // {{{ import

    /**
     * 設定ファイル読み込みメソッド
     *
     * MixedCollection内必須キー
     *
     * FileName         : ファイル名
     * Path             : パス
     * CachePath        : キャッシュパス
     *
     * @param xFrameworkPX_Util_MixedCollection $conf 設定オブジェクト
     * @return void
     */
    public function import($conf)
    {
        $filename = $conf->filename;
        $filepath = normalize_path(
            $conf->path . DS . $filename
        );
        $cachepath = normalize_path(
            $conf->cachepath . DS . $filename
        );

        // キャッシュファイル読み込み
        if (
            file_exists($filepath) && file_exists($cachepath) &&
            (filemtime($filepath) < filemtime($cachepath))
        ) {

            // キャッシュファイルから読み込む
            $ret = xFrameworkPX_Util_Serializer::unserialize(
                file_get_contents($cachepath)
            );
            
        } else {

            // XMLファイル読み込み
            $ret = @simplexml_load_string(
                file_get_contents($filepath)
            );

            // キャッシュ化
            if (!$ret == false) {

                // キャッシュ出力
                file_forceput_contents(
                    $cachepath,
                    xFrameworkPX_Util_Serializer::serialize($ret)
                );

            } else {

                // %sが存在しません。

                throw new xFrameworkPX_Config_Exception(
                    sprintf(PX_ERR35000, $filepath)
                );
            }
        }

        // SimpleXMLオブジェクト格納
        $this->_xml = $ret;

        return $this->_xml;

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
