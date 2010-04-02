<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_CodeGenerator_Php_Props Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Props.php 1171 2010-01-05 13:58:12Z tamari $
 */

// {{{ xFrameworkPX_CodeGenerator_Php_Props

/**
 * xFrameworkPX_CodeGenerator_Php_Props Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_CodeGenerator_Php_Props
 */
class xFrameworkPX_CodeGenerator_Php_Props
extends xFrameworkPX_CodeGenerator_Php_Generator
{

    // {{{ props

    /**
     * プロパティ名
     *
     * @var string
     */
    protected $_name;

    /**
     * アクセス修飾子
     *
     * @var string
     */
    protected $_access = 'public';

    /**
     * スタティック設定
     *
     * @var static
     */
    protected $_static = false;

    /**
     * デフォルト値
     *
     * @var string
     */
    protected $_value;

    /**
     * 定数設定
     *
     * @var bool
     */
    protected $_const = false;

    /**
     * 自動定数名変換モード設定
     *
     * @var bool
     */
    protected $_constAutoUpper = true;

    /**
     * ドキュメント
     *
     * @var xFrameworkPX_CodeGenerator_Php_PropsDoc
     */
    protected $_doc;


    // }}}
    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @param xFrameworkPX_Util_MixedCollection $conf プロパティ情報
     */
    public function __construct($conf)
    {

        // プロパティ名設定
        if ($conf->key_exists('propsName')) {
            $this->_name = (string)$conf->propsName;
        }

        // アクセス修飾子設定
        if ($conf->key_exists('access')) {
            $this->_access = (string)$conf->access;
        }

        // スタティック設定
        if (
            $conf->key_exists('static') && is_bool($conf->static)
        ) {
            $this->_static = $conf->static;
        }

        // デフォルト値設定
        if ($conf->key_exists('value')) {
            $this->_value = $conf->value;
        }

        // 定数設定
        if ($conf->key_exists('const') && is_bool($conf->const)) {
            $this->_const = $conf->const;
        }

        // 定数名自動変換設定
        if ($conf->key_exists('autoupper') &&
            is_bool($conf->autoupper)) {
            $this->_constAutoUpper = $conf->autoupper;
        }

        // ドキュメントオブジェクト生成
        if ($conf->key_exists('doc')
            && $conf->doc instanceof xFrameworkPX_Util_MixedCollection
        ) {
            $this->_doc = new xFrameworkPX_CodeGenerator_Php_PropsDoc(
                $conf->doc
            );
        } else {
            $this->_doc = new xFrameworkPX_CodeGenerator_Php_PropsDoc(
                new xFrameworkPX_Util_MixedCollection(array(
                    'shortDesc' => ($this->_const && $this->_constAutoUpper)
                                    ? strtoupper($this->_name)
                                    : $this->_name,
                ))
            );
        }

        // プロパティ名未設定時Exception発生
        if (empty($this->_name)) {
            throw new xFrameworkPX_CodeGenerator_Php_Exception(
                'Property Name is Undefined.'
            );
        }

        // アクセス修飾子が規定のもの以外が設定された場合Exception発生
        if (
            $this->_access !== '' &&
            $this->_access !== 'public' &&
            $this->_access !== 'protected' &&
            $this->_access !== 'private'
        ) {
            throw new xFrameworkPX_CodeGenerator_Php_Exception(
                sprintf(
                    'Access "%s" is invalid type.',
                    $this->_access
                )
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
        $indent = str_repeat(' ', $this->_indent);
        $doc = '';
        $access = '';
        $value = '';
        $retCode = '';

        // アクセス修飾子生成
        if (!empty( $this->_access )) {
            $access = $this->_access . ' ';
        }

        // デフォルト値生成
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

        // ドキュメント生成
        $doc = $this->_doc->render();

        // コード取得
        $retCode .= $this->_getCode('');
        $retCode .= $doc;

        if ($this->_const) {
            if ($this -> _constAutoUpper) {
                $retCode .= $this->_getCode(
                    sprintf(
                        '%sconst %s%s;',
                        $indent,
                        strtoupper($this->_name),
                        $value
                    )
                );
            } else {
                $retCode .= $this->_getCode(
                    sprintf(
                        '%sconst %s%s;',
                        $indent,
                        $this->_name,
                        $value
                    )
                );
            }
        } else {

            if ($this->_static) {
                $retCode .= $this->_getCode(
                    sprintf(
                        '%s%sstatic $%s%s;',
                        $indent,
                        $access,
                        $this->_name,
                        $value
                    )
                );
            } else {
                $retCode .= $this->_getCode(
                    sprintf(
                        '%s%s$%s%s;',
                        $indent,
                        $access,
                        $this->_name,
                        $value
                    )
                );
            }
        }

        return $retCode;
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
