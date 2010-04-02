<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_CodeGenerator_Php_Class Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Class.php 1171 2010-01-05 13:58:12Z tamari $
 */

// {{{ xFrameworkPX_CodeGenerator_Php_Class

/**
 * xFrameworkPX_CodeGenerator_Php_Class Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_CodeGenerator_Php_Class
 */
class xFrameworkPX_CodeGenerator_Php_Class
extends xFrameworkPX_CodeGenerator_Php_Generator
{

    // {{{ props

    /**
     * クラス名
     *
     * @var string
     */
    protected $_name = '';

    /**
     * ファイナル設定
     *
     * @var bool
     */
    protected $_final = false;

    /**
     * アブストラクト設定
     *
     * @var bool
     */
    protected $_abstract = false;

    /**
     * 親クラス名
     *
     * @var string
     */
    protected $_parentCls = '';

    /**
     * プロパティ
     *
     * @var xFrameworkPX_Util_MixedCollection
     */
    protected $_props;

    /**
     * メソッド
     *
     * @var xFrameworkPX_Util_MixedCollection
     */
    protected $_method;

    /**
     * ドキュメント
     *
     * @var xFrameworkPX_CodeGenerator_Php_Doc
     */
    protected $_doc;

    // }}}
    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @param xFrameworkPX_Util_MixedCollection $conf ファイル情報
     */
    public function __construct( $conf )
    {

        if (!($conf instanceof xFrameworkPX_Util_MixedCollection)) {
            $conf = new xFrameworkPX_Util_MixedCollection();
        }

        // スーパークラスメソッドコール
        parent::__construct($conf);

        // クラス名設定
        if ($conf->key_exists('clsName')) {
            $this->_name = (string)$conf->clsName;
        }

        // ファイナル設定
        if ($conf->key_exists('final') && is_bool($conf->final)) {
            $this->_final = $conf->final;
        }

        // アブストラクト設定
        if ($conf->key_exists('abstract')
            && is_bool($conf->abstract)) {
            $this->_abstract = $conf->abstract;
        }

        // ドキュメントオブジェクト生成
        if ( $conf->key_exists('doc')
            && ($conf->doc instanceof xFrameworkPX_Util_MixedCollection)
        ) {
            $this->_doc= new xFrameworkPX_CodeGenerator_Php_ClassDoc(
                $conf->doc
            );
        } else {
            $this->_doc = new xFrameworkPX_CodeGenerator_Php_ClassDoc(
                new xFrameworkPX_Util_MixedCollection(array(
                    'shortDesc' => $this->_name
                ))
            );
        }

        // プロパティオブジェクト生成
        $this->_props = new xFrameworkPX_Util_MixedCollection();

        // メソッドオブジェクト生成
        $this->_method = new xFrameworkPX_Util_MixedCollection();

        // プロパティ生成
        if ($conf->key_exists('props')
            && $conf->props instanceof xFrameworkPX_Util_MixedCollection) {

            foreach ($conf->props as $key => $prop) {

                $prop->offsetSet('propsName', $key);

                $this->_props->append(
                    new xFrameworkPX_CodeGenerator_Php_Props($prop)
                );

            }
        }

        // メソッド生成
        if ($conf->key_exists('method')
            && $conf->props instanceof xFrameworkPX_Util_MixedCollection) {

            foreach ($conf->method as $methodConf) {
                $this->_method->append(
                    new xFrameworkPX_CodeGenerator_Php_Method($methodConf)
                );
            }

        }

        // クラス名未設定時Exception発生
        if (empty($this->_name)) {
            throw new xFrameworkPX_CodeGenerator_Php_Exception(
                'Class Name is Undefined.'
            );
        }

    }

    // }}}
    // {{{ setParent

    /**
     * 親クラス設定
     *
     * @param string $parent 親クラス名
     * @return void
     */
    public function setParent($parent)
    {
        $this->_parentCls = (string)$parent;
    }

    // }}}
    // {{{ setReflectionMember

    /**
     * 関係メンバー生成
     *
     * @return void
     */
    public function setReflectionMember()
    {

        if ( !empty( $this->_parentCls ) ) {

            // 親クラス解析
            $reflection = new ReflectionClass( $this->_parentCls );

            // 自動生成メソッド判定
            foreach ($reflection->getMethods() as $method) {

                if (
                    $this->isAutoImplement($this->getPXTags($method))
                ) {

                    // メソッド追加
                    $this->_method->offsetSet(
                        $method->getName(),
                        new xFrameworkPX_CodeGenerator_Php_Method(
                            new xFrameworkPX_Util_MixedCollection(array(
                                'methodName' => $method->getName(),
                                'access' => 'public',
                                'parentCall' => $this->isParentCall(
                                    $this->getPXTags($method)
                                )
                            ) )
                        )
                    );
                }

            }

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
        $extendsClass = '';
        $props = '';
        $methods = '';
        $retCode = '';

        // 継承クラス生成
        if (!empty( $this->_parentCls)) {
            $extendsClass = sprintf(' extends %s', $this->_parentCls);
        }

        // プロパティ生成
        foreach ($this->_props as $prop) {
            $props .= $prop->render();
        }

        // メソッド生成
        foreach ($this->_method as $method) {
            $methods .= $method->render();
        }

        // ドキュメント生成
        $retCode .= $this->_doc->render();

        if ($this->_final) {
            $retCode .= 'final ';
        } else {
            if ($this->_abstract) {
                $retCode .= 'abstract ';
            }
        }

        // クラス出力
        $retCode .= $this->_getCode(
            sprintf('class %s%s {', $this->_name, $extendsClass),
            $props,
            $methods,
            '}'
        );

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
