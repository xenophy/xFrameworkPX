<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_ViewTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: ViewTest.php 882 2009-12-23 09:36:22Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'xFrameworkPX/Exception.php';
require_once 'xFrameworkPX/View.php';

// }}}
// {{{ xFrameworkPX_ViewTest

/**
 * xFrameworkPX_ViewTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_ViewTest extends PHPUnit_Framework_TestCase
{

    /**
     * セットアップ
     *
     * @return void
     */
    protected function setUp()
    {

    }

    /**
     * 終了処理
     *
     * @return void
     */
    protected function tearDown()
    {

    }

    /**
     * setLayoutテスト
     *
     * @return void
     */
    public function testSetLayout()
    {

    }

}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
