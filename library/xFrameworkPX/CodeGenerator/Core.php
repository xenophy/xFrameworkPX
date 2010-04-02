<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_CodeGenerator_Core Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_CodeGenerator
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Core.php 616 2009-12-12 17:56:31Z  $
 */

// {{{ xFrameworkPX_CodeGenerator_Core

/**
 * xFrameworkPX_CodeGenerator_Core Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_CodeGenerator
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_CodeGenerator_Core
 */
abstract class xFrameworkPX_CodeGenerator_Core
{
    // {{{ props

    /**
     * ジェネレーターファイルオブジェクト配列
     *
     * @var xFrameworkPX_Util_MixedCollection
     */
    protected $_files;

    // }}}
    // {{{ __construct

    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
        $this->_files = new xFrameworkPX_Util_MixedCollection();
    }

    // }}}
    // {{{ add

    /**
     * クラス定義追加メソッド
     *
     * @param xFrameworkPX_Util_MixedCollection $mixConf ファイル情報
     * @return mixed
     */
    abstract public function add( $conf );

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
