<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_CodeGenerator_Php_Generator Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Generator.php 616 2009-12-12 17:56:31Z  $
 */

// {{{ xFrameworkPX_CodeGenerator_Php_Generator

/**
 * xFrameworkPX_CodeGenerator_Php_Generator Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_CodeGenerator_Php_Generator
 */
abstract class xFrameworkPX_CodeGenerator_Php_Generator
{

    // {{{ const

    /**
     * UNDEFINED定数
     */
    const UNDEFINED = 'stdClass';

    // }}}
    // {{{ props

    /**
     * ラインフィード
     *
     * @var string
     */
    protected $_lf = "\n";

    /**
     * インデント
     *
     * @var int
     */
    protected $_indent = 4;

    // }}}
    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @param xFrameworkPX_Util_MixedCollection $conf 設定
     */
    public function __construct($conf)
    {

    }

    // }}}
    // {{{ render

    /**
     * レンダリング
     *
     * @return string ソースコード
     */
    abstract public function render();

    // }}}
    // {{{ _isUndefined

    /**
     * デフォルト値未設定確認
     *
     * @param mixed $value 値
     * @return bool true:未設定, false:設定済
     */
    protected function _isUndefined ($value)
    {
        $undefined = self::UNDEFINED;
        return ($value instanceof $undefined);
    }

    // }}}
    // {{{ getPXTags

    /**
     * PXタグ解析
     *
     * @param xFrameworkPX_Util_MixedCollection $method
     *        メソッドジェネレーター
     * @return array PXタグの解析結果
     */
    public function getPXTags($method)
    {
        return $this->getDocComment($method->getDocComment(), '@px');
    }

    // }}}
    // {{{ getDocComment

    /**
     * ドキュメントタグ解析メソッド
     *
     * @param string $doc ドキュメント
     * @param string $tag タグ
     * @return string タグ設定文字列、見つからない場合はnullを返します。
     */
    public function getDocComment($doc, $tag = '')
    {

        if (empty($tag)) {
            return $doc;
        }

        $matches = array();
        $match = preg_match_all(
            "/". $tag ."(.*)(\\r\\n|\\r|\\n)/i",
            $doc,
            $matches
        );

        if ($match > 0) {
            return $matches;
        }

        return null;
    }

    // }}}
    // {{{ isAutoImplement

    /**
     * AutoImplementチェック
     *
     * @param array $tags タグ解析結果の配列
     * @return bool true:有効, false:無効
     */
    public function isAutoImplement($tags)
    {
        $tagCount = count($tags[ 0 ]);
        $parsed = $tags[ 1 ];
        $ret = false;

        for ($i = 0; $i < $tagCount; ++$i) {
            list($order, $param) = explode(' ', trim($parsed[ $i ]));

            if ($order === 'autoimplement' && $param === 'true') {
                $ret = true;
                break;
            }

        }

        return $ret;
    }

    // }}}
    // {{{ isParentCall

    /**
     * ParentCallチェック
     *
     * @param array $tags タグ解析結果の配列
     * @return bool true:有効, false:無効
     */
    public function isParentCall($tags)
    {
        $tagCount = count($tags[ 0 ]);
        $parsed = $tags[ 1 ];
        $ret = false;

        for ($i = 0; $i < $tagCount; ++$i) {
            list($order, $param) = explode(' ', trim($parsed[ $i ]));

            if ($order === 'parentcall' && $param === 'true') {
                $ret = true;
                break;
            }

        }

        return $ret;
    }

    // }}}
    // {{{ _getCode

    /**
     * コード取得
     *
     * @return string コード
     */
    protected function _getCode()
    {
        $args = func_get_args();
        $ret = '';

        foreach ($args as $arg) {
            $ret .= $arg . $this->_lf;
        }

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
