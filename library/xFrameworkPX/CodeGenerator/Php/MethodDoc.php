<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_CodeGenerator_Php_MethodDoc Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: MethodDoc.php 1171 2010-01-05 13:58:12Z tamari $
 */

// {{{ xFrameworkPX_CodeGenerator_Php_MethodDoc

/**
 * xFrameworkPX_CodeGenerator_Php_MethodDoc Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_CodeGenerator_Php_MethodDoc
 */
class xFrameworkPX_CodeGenerator_Php_MethodDoc
extends xFrameworkPX_CodeGenerator_Php_Doc
{

    // {{{ __construct

    /**
     * コンストラクタ
     *
     */
    public function __construct($conf)
    {

        if (!($conf instanceof xFrameworkPX_Util_MixedCollection)) {
            $conf = new xFrameworkPX_Util_MixedCollection();
        }

        // スーパークラスメソッドコール
        parent::__construct($conf);

        // タグ設定
        $this->_tags = new xFrameworkPX_Util_MixedCollection(array(
            'access' => '',
            'param' => '',
            'return' => ''
        ));

        if (
            $conf->key_exists('tags') &&
            ($conf->tags instanceof xFrameworkPX_Util_MixedCollection)
        ) {

            foreach ($conf->tags as $name => $txt) {
                $this->_tags->offsetSet($name, $txt);
            }

        }

    }

    // }}}
    // {{{ _getTags

    /**
     * メソッド用タグ取得
     *
     * @return string タグコード
     * @access protected
     */
    protected function _getTags()
    {
        $access = '';
        $param = null;
        $return = '';
        $doc = '';

        // メソッド用タグコード生成
        if (!empty($this->_tags->access)) {
            $access = '    ' . $this->_tags->access;
        }

        if (!empty($this->_tags->param)) {

            if (
                $this->_tags->param instanceof
                xFrameworkPX_Util_MixedCollection
            ) {

                $param = new xFrameworkPX_Util_MixedCollection();

                foreach ($this->_tags->param as $txt) {
                    $param->append('     ' . $txt);
                }

            } else {
                $param = '     ' . $this->_tags->param;
            }
        }

        if (!empty($this->_tags->return)) {
            $return = '    ' . $this->_tags->return;
        }

        if ($param instanceof xFrameworkPX_Util_MixedCollection) {

            foreach ($param as $item) {
                $doc .= $this->_getCode(
                    sprintf(' * @param%s', $item)
                );
            }

        } else {
            $doc .= $this->_getCode(sprintf(' * @param%s', $param));
        }

        $doc .= $this->_getCode(
            sprintf(' * @return%s', $return),
            sprintf(' * @access%s', $access)
        );

        return $doc;
    }

    // }}}
    // {{{ render

    public function render()
    {
        $indent = str_repeat(' ', $this->_indent);
        $code = array();
        $rendCode = parent::render();

        foreach (explode("\n", $rendCode) as $line) {
            $code[] = $indent . $line;
        }

        array_pop($code);

        return implode("\n", $code) . "\n";
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
