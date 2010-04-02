<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_CodeGenerator_Php_Doc Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Doc.php 1171 2010-01-05 13:58:12Z tamari $
 */

// {{{ xFrameworkPX_CodeGenerator_Php_Doc

/**
 * xFrameworkPX_CodeGenerator_Php_Doc Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_CodeGenerator_Php_Doc
 */
abstract class xFrameworkPX_CodeGenerator_Php_Doc
extends xFrameworkPX_CodeGenerator_Php_Generator
{

    // {{{ props

    /**
     * Short Description
     *
     * @var string
     */
    protected $_shortDesc = '';

    /**
     * Long Description
     *
     * @var string
     */
    protected $_longDesc = '';

    /**
     * タグ
     *
     * @var xFrameworkPX_Util_MixedCollection
     */
    protected $_tags;

    // }}}
    // {{{ __construct

    /**
     * コンストラクタ
     *
     */
    public function __construct($conf)
    {

        // Short Description 設定
        if ($conf->key_exists('shortDesc')) {
            $this->_shortDesc = (string)$conf->shortDesc;
        }

        // Long Description 設定
        if ($conf->key_exists('longDesc')) {
            $this->_longDesc = (string)$conf->longDesc;
        }

    }

    // }}}
    // {{{ _getTags

    /**
     * タグコード取得
     *
     * @return string Tagコード
     */
    abstract protected function _getTags();

    // }}}
    // {{{ render

    /**
     * レンダー
     *
     * @return string DockBlockソースコード
     */
    public function render()
    {

        // DockBlock生成
        $doc  = '';
        $doc .= $this->_getCode('/**');

        // Short Description 生成
        foreach (explode('\n', $this->_shortDesc) as $line) {

            if ($line !== '') {
                $doc .= $this->_getCode(sprintf(' * %s', $line));
            } else {
                $doc .= $this->_getCode(' *');
            }

        }

        $doc .= $this->_getCode(' *');

        // Long Description生成
        if (!empty($this->_longDesc)) {

            foreach (explode('\n', $this->_longDesc) as $line) {

                if ($line !== '') {
                    $doc .= $this->_getCode(sprintf(' * %s', $line));
                } else {
                    $doc .= $this->_getCode(' *');
                }

            }

            $doc .= $this->_getCode(' *');
        }

        // タグ生成
        $doc .= $this->_getTags();
        $doc .= $this->_getCode(' */');

        return $doc;
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
