<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_CodeGenerator_Php_Params Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Params.php 1171 2010-01-05 13:58:12Z tamari $
 */

// {{{ xFrameworkPX_CodeGenerator_Php_Params

/**
 * xFrameworkPX_CodeGenerator_Php_Params Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_CodeGenerator_Php_Params
 */
class xFrameworkPX_CodeGenerator_Php_Params
extends xFrameworkPX_CodeGenerator_Php_Generator
{

    // {{{ props

    /**
     * パラメータ名
     *
     * @var string
     */
    protected $_name;

    /**
     * デフォルト値設定
     *
     * @var string
     */
    protected $_value;

    // }}}
    // {{{ __construct

    /**
     * コンストラクタ
     *
     */
    public function __construct($conf)
    {

        // パラメータ名設定
        if ($conf->key_exists('paramName')) {
            $this->_name = (string)$conf->paramName;
        }

        // デフォルト値設定
        $this->_value = new stdClass;

        if ($conf->key_exists('value')) {
            $this->_value = $conf->value;
        }

        // パラメタ名未設定時Exception発生
        if (empty($this->_name)) {
            throw new xFrameworkPX_CodeGenerator_Php_Exception(
                'Params Name is Undefined.'
            );
        }

    }

    // }}}
    // {{{ render

    /**
     * レンダリング
     *
     * @return string コード
     */
    public function render()
    {
        $code = '';
        $value = '';

        // コード取得
        if (!$this->_isUndefined($this->_value)) {

            if (is_null($this->_value)) {
                $value = ' = null';
            } else if ($this->_value === '') {
                $value = " = ''";
            } else if (is_bool($this->_value)) {
                $value = ($this->_value) ? ' = true' : ' = false';
            } else {
                $value = sprintf(' = %s', $this->_value);
            }
        }

        $code = sprintf('%s%s', $this->_name, $value);

        return $code;
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
