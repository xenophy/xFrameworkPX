<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_CodeGenerator_Php_File Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: File.php 1171 2010-01-05 13:58:12Z tamari $
 */

// {{{ xFrameworkPX_CodeGenerator_Php_File

/**
 * xFrameworkPX_CodeGenerator_Php_File Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_CodeGenerator_Php_File
 */
class xFrameworkPX_CodeGenerator_Php_File
extends xFrameworkPX_CodeGenerator_Php_Generator
{

    // {{{ props

    /**
     * ライターオブジェクト
     *
     * @var xFrameworkPX_CodeGenerator_Php_Class
     */
    protected $_writer = null;

    /**
     * ファイル名
     *
     * @var string
     */
    protected $_name = null;

    /**
     * ドキュメントオブジェクト
     *
     * @var xFrameworkPX_CodeGenerator_Php_ClassDoc
     */
    protected $_doc;

    // }}}
    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @param xFrameworkPX_Util_MixedCollection $conf ファイル情報
     */
    public function __construct($conf)
    {

        if (!($conf instanceof xFrameworkPX_Util_MixedCollection)) {
            $conf = new xFrameworkPX_Util_MixedCollection();
        }

        $this->_writer = null;

        if ($conf->key_exists('filename')) {
            $this->_name = (string)$conf->filename;
        }

        if ($conf->key_exists('type')) {
            switch(strtolower((string)$conf->type)) {
                case 'class':
                    $this->_writer =
                        new xFrameworkPX_CodeGenerator_Php_Class($conf);
                    break;
            }

        }

        if (
            $conf->key_exists('doc') &&
            ($conf->doc instanceof xFrameworkPX_Util_MixedCollection)
        ) {
            $this->_doc= new xFrameworkPX_CodeGenerator_Php_FileDoc(
                $conf->doc
            );
        } else {
            $this->_doc = new xFrameworkPX_CodeGenerator_Php_FileDoc(
                new xFrameworkPX_Util_MixedCollection(array())
            );
        }

        // ファイル名未設定時Exception発生
        if (empty($this->_name)) {
            throw new xFrameworkPX_CodeGenerator_Php_Exception(
                'File Name is Undefined.'
            );
        }

        // ライターオブジェクトがなければException発生
        if (is_null($this->_writer)) {
            throw new xFrameworkPX_CodeGenerator_Php_Exception(
                'Type "' . $conf->type . '" is invalid type.'
            );
        }

    }

    // }}}
    // {{{ getWriter

    /**
     * ライターオブジェクト取得
     *
     * @return xFrameworkPX_CodeGenerator_Php_Class
     *          クラスコードジェネレーターオブジェクト
     */
    public function getWriter()
    {
        return $this->_writer;
    }

    // }}}
    // {{{ render

    /**
     * レンダリング
     *
     * @return void
     */
    public function render()
    {
        $file = '';

        // ファイル生成
        $file .= $this->_getCode(
            '<?php',
            '',
            $this->_doc->render(),
            $this->_writer->render(),
            '?>'
        );

        file_put_contents($this->_name, $file);
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
