<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Config_Database Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Config
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Database.php 974 2009-12-26 10:12:48Z kotsutsumi $
 */

// {{{ xFrameworkPX_Config_Database

/**
 * xFrameworkPX_Config_Database Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Config
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Config_Database
 */
class xFrameworkPX_Config_Database extends xFrameworkPX_Config
{
    // {{{ props

    /**
     * インスタンス変数
     *
     * @var xFrameworkPX
     */
    protected static $_instance = null;

    // }}}
    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @return void
     */
    private function __construct()
    {

    }

    // }}}
    // {{{ getInstance

    /**
     * インスタンス取得メソッド
     *
     * @return xFrameworkPXインスタンス
     */
    public static function getInstance()
    {
        // インスタンス取得
        if (!isset(self::$_instance)) {
            self::$_instance = new xFrameworkPX_Config_Database();
        }

        return self::$_instance;
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
