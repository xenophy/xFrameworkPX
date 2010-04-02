<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_CodeGenerator_Php_FileDoc Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: FileDoc.php 1171 2010-01-05 13:58:12Z tamari $
 */

// {{{ xFrameworkPX_CodeGenerator_Php_FileDoc

/**
 * xFrameworkPX_CodeGenerator_Php_FileDoc Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_CodeGenerator_Php_FileDoc
 */
class xFrameworkPX_CodeGenerator_Php_FileDoc
extends xFrameworkPX_CodeGenerator_Php_Doc
{

    // {{{ __construct

    /**
     * コンストラクタ
     *
     */
    public function __construct( $conf )
    {

        if (is_null($conf)) {
            $conf = new xFrameworkPX_Util_MixedCollection();
        }

        // スーパークラスメソッドコール
        parent::__construct($conf);

        // タグ設定
        $this->_tags = new xFrameworkPX_Util_MixedCollection(array(
            'filesource' => '',
            'copyright' => '',
            'link' => '',
            'package' => '',
            'since' => '',
            'version' => '',
            'license' => ''
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
    // {{{ getFileDoc

    /**
     * ファイル用タグ取得
     *
     * @return string タグコード
     */
    protected function _getTags()
    {
        $fileSource = '';
        $copyright = '';
        $link = '';
        $package = '';
        $since = '';
        $version = '';
        $license = '';
        $doc = '';

        // ファイル用タグコード生成
        if (!empty($this->_tags->filesource)) {
            $fileSource = '     ' . $this->_tags->filesource;
        }

        if (!empty($this->_tags->copyright)) {
            $copyright = '      ' . $this->_tags->copyright;
        }

        if (!empty($this->_tags->link)) {
            $link = '           ' . $this->_tags->link;
        }

        if (!empty($this->_tags->package)) {
            $package = '        ' . $this->_tags->package;
        }

        if (!empty($this->_tags->since)) {
            $since = '          ' . $this->_tags->since;
        }

        if (!empty($this->_tags->version)) {
            $version = '        ' . $this->_tags->version;
        }

        if (!empty($this->_tags->license)) {
            $license = '        ' . $this->_tags->license;
        }

        $doc .= $this->_getCode(
            sprintf(' * @filesource%s', $fileSource),
            sprintf(' * @copyright%s', $copyright),
            sprintf(' * @link%s', $link),
            sprintf(' * @package%s', $package),
            sprintf(' * @since%s', $since),
            sprintf(' * @version%s', $version),
            sprintf(' * @license%s', $license)
        );

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
