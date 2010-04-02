<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Model_Behavior Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Model
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Behavior.php 616 2009-12-12 17:56:31Z  $
 */

// {{{ xFrameworkPX_Model_Behavior

/**
 * xFrameworkPX_Model_Behavior Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Model
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Model_Behavior
 */
abstract class xFrameworkPX_Model_Behavior extends xFrameworkPX_Object
{
    // {{{ props

    /**
     * モデルオブジェクト
     *
     * @var xFrameworkPX_Model
     */
    protected $module;

    // }}}
    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @param xFrameworkPX_Model
     * @return void
     */
    public function __construct($module)
    {
        $this->module = $module;
    }

    // }}}
    // {{{ __get

    /**
     * 読み出しオーバーロード
     *
     * @param string $name プロパティ名
     * @return mixed 対象オブジェクト
     */
    public function __get($name)
    {
        return $this->module->{$name};
    }

    // }}}
    // {{{ __set

    /**
     * 書き込みオーバーロード
     *
     * @param string $name プロパティ名
     * @param mixed $value 設定値
     * @return void
     */
    public function __set($name, $value)
    {
        $this->module->{$name} = $value;
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
