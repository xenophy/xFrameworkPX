<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_CodeGenerator_Php_Method Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Method.php 1171 2010-01-05 13:58:12Z tamari $
 */

// {{{ xFrameworkPX_CodeGenerator_Php_Method

/**
 * xFrameworkPX_CodeGenerator_Php_Method Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_CodeGenerator_Php_Method
 */
class xFrameworkPX_CodeGenerator_Php_Method
extends xFrameworkPX_CodeGenerator_Php_Generator
{

    // {{{ props

    /**
     * クラス名
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
     * @var bool
     */
    protected $_static = false;

    /**
     * アブストラクト設定
     *
     * @var bool
     */
    protected $_abstract = false;

    /**
     * ファイナル設定
     *
     * @var bool
     */
    protected $_final = false;

    /**
     * パラメータ
     *
     * @var xFrameworkPX_Util_MixedCollection
     */
    protected $_params;

    /**
     * ブロックコード
     *
     * @var string
     */
    protected $_blockCode;

    /**
     * ドキュメントオブジェクト
     *
     * @var xFrameworkPX_CodeGenerator_Php_MethodDoc
     */
    protected $_doc;

    /**
     * 親クラスのメソッド呼出し設定
     *
     * @var bool
     */
    protected $_parentCall = false;

    /**
     * 親クラスのメソッド呼び出し時パラメーター
     *
     * @var xFrameworkPX_Util_MixedCollection
     */
    protected $_parentParams;

    // }}}
    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @param xFrameworkPX_Util_MixedCollection $conf メソッド設定
     */
    public function __construct($conf)
    {

        if (!($conf instanceof xFrameworkPX_Util_MixedCollection)) {
            $conf = new xFrameworkPX_Util_MixedCollection();
        }

        // メソッド名設定
        if ($conf->key_exists('methodName')) {
            $this->_name = (string)$conf->methodName;
        }

        // アクセス修飾子の設定
        if ($conf->key_exists('access')) {
            $this->_access = (string)$conf->access;
        }

        // スタティック設定
        if ($conf->key_exists('static') &&
            is_bool($conf->static)) {
            $this->_static = $conf->static;
        }

        // アブストラクト設定
        if ($conf->key_exists('abstract') && is_bool($conf->abstract)) {
            $this->_abstract = $conf->abstract;
        }

        // ファイナル設定
        if ($conf->key_exists('final') && is_bool($conf->final)) {
            $this->_final = $conf->final;
        }

        // パラメータ設定
        $this->_params = new xFrameworkPX_Util_MixedCollection();

        if (
            $conf->key_exists('params') &&
            $conf->params instanceof xFrameworkPX_Util_MixedCollection
        ) {

            foreach ($conf->params as $paramsConf) {

                if (
                    $paramsConf instanceof xFrameworkPX_Util_MixedCollection
                ) {
                    $this->_params->append(
                        new xFrameworkPX_CodeGenerator_Php_Params($paramsConf)
                    );
                }

            }

        }

        // ブロックコード設定
        if ($conf->key_exists('blockCode')) {
            $this->_blockCode = (string)$conf->blockCode;
        }

        // ドキュメントオブジェクト生成
        if (
            $conf->key_exists('doc') &&
            $conf->doc instanceof xFrameworkPX_Util_MixedCollection
        ) {
            $this->_doc= new xFrameworkPX_CodeGenerator_Php_MethodDoc(
                $conf->doc
            );
        } else {
            $this->_doc = new xFrameworkPX_CodeGenerator_Php_MethodDoc(
                new xFrameworkPX_Util_MixedCollection(array(
                    'shortDesc' => $this->_name,
                    'tags' => new xFrameworkPX_Util_MixedCollection(array(
                        'access' => $this->_access
                    ))
                ))
            );
        }

        // 親クラスメソッド呼出し設定
        $this->_parentParams = new xFrameworkPX_Util_MixedCollection();

        if ($conf->key_exists('parentCall') && is_bool($conf->parentCall)) {
            $this->_parentCall = $conf->parentCall;

            // 親クラスメソッド呼び出し時パラメーター設定
            if ($conf->key_exists('parentParams') &&
                $conf->parentParams instanceof
                xFrameworkPX_Util_MixedCollection
            ) {

                foreach ($conf->parentParams as $paramsConf) {

                    if (
                        $paramsConf instanceof
                        xFrameworkPX_Util_MixedCollection
                    ) {
                        $this->_parentParams->append(
                            new xFrameworkPX_CodeGenerator_Php_Params(
                                $paramsConf
                            )
                        );
                    }

                }

            }

        }

        // メソッド名未設定時Exception発生
        if (empty($this->_name)) {
            throw new xFrameworkPX_CodeGenerator_Php_Exception(
                'Method Name is Undefined.'
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

        // abstract設定が有効時アクセス修飾子にprivateが設定された場合
        // Exception発生

        if ($this->_abstract && $this->_access === 'private') {
            throw new xFrameworkPX_CodeGenerator_Php_Exception(
                '"private" is invalid type when abstract config enabled'
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
        $access = '';
        $params = '';
        $innerIndent = str_repeat(' ', $this->_indent * 2);
        $innerCode = '';
        $retCode = '';

        // アクセス修飾子生成
        if (!empty($this->_access)) {
            $access = $this->_access . ' ';
        }

        // パラメーター生成
        if ($this->_params->count() > 0) {
            $params .= ' ';

            foreach ($this->_params as $index => $param) {

                if ($index !== ($this->_params->count() - 1)) {
                    $params .= $param->render() . ', ';
                } else {
                    $params .= $param->render();
                }
            }

            $params .= ' ';
        }

        // 親クラスのメソッド呼び出し生成
        if ($this->_parentCall === true) {

            $parentParams = '';
            if ($this->_parentParams->count() > 0) {
                $parentParams .= ' ';

                foreach ($this->_parentParams as $index => $param) {
                    if ($index !== ($this->_parentParams->count() - 1)) {
                        $parentParams .= $param->render() . ', ';
                    } else {
                        $parentParams .= $param->render();
                    }
                }

                $parentParams .= ' ';
            }

            $innerCode .= $this->_lf;
            $innerCode .= $innerIndent . sprintf(
                'parent::%s(%s);',
                $this->_name,
                $parentParams
            );
            $innerCode .= $this->_lf;

        }

        // ブロックコード生成
        if (!empty($this->_blockCode)) {
            $arrBlockCode = array();
            foreach (explode($this->_lf, $this->_blockCode) as $code) {

                if (trim($code) !== '') {
                    $arrBlockCode[] = $innerIndent . $code;
                } else {
                    $arrBlockCode[] = $code;
                }

            }

            $innerCode .= $this->_lf;
            $innerCode .= implode($this->_lf, $arrBlockCode);
            $innerCode .= $this->_lf;

        }

        // コード取得
        $retCode .= $this->_getCode('');

        // ドキュメント生成
        $retCode .= $this->_doc->render();

        if ($this->_final) {

            if ($this->_static) {
                $retCode .= $this->_getCode(
                    sprintf(
                        '%sfinal %sstatic function %s(%s) {',
                        $indent,
                        $access,
                        $this->_name,
                        $params
                    ),
                    $innerCode,
                    $indent . '}'
                );

            } else {
                $retCode .= $this->_getCode(
                    sprintf(
                        '%sfinal %sfunction %s(%s) {',
                        $indent,
                        $access,
                        $this->_name,
                        $params
                    ),
                    $innerCode,
                    $indent . '}'
                );
            }

        } else {

            if ($this->_abstract) {
                $retCode .= $this->_getCode(
                    sprintf(
                        '%sabstract %sfunction %s(%s);',
                        $indent,
                        $access,
                        $this->_name,
                        $params
                    )
                );
            } else {

                if ($this->_static) {
                    $retCode .= $this->_getCode(
                        sprintf(
                            '%s%sstatic function %s(%s) {',
                            $indent,
                            $access,
                            $this->_name,
                            $params
                        ),
                        $innerCode,
                        $indent . '}'
                    );
                } else {
                    $retCode .= $this->_getCode(
                        sprintf(
                            '%s%sfunction %s(%s) {',
                            $indent,
                            $access,
                            $this->_name,
                            $params
                        ),
                        $innerCode,
                        $indent . '}'
                    );
                }

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
