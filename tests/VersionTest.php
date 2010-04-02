<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_VersionTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: VersionTest.php 882 2009-12-23 09:36:22Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'xFrameworkPX/Version.php';

// }}}
// {{{ xFrameworkPX_VersionTest

/**
 * xFrameworkPX_VersionTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_VersionTest extends PHPUnit_Framework_TestCase
{

    // {{{ setUp

    /**
     * セットアップ
     *
     * @return void
     */
    protected function setUp()
    {

    }

    // }}}
    // {{{ tearDown

    /**
     * 終了処理
     *
     * @return void
     */
    protected function tearDown()
    {

    }

    // }}}
    // {{{ testCompare

    /**
     * testCompareテスト
     *
     * @return void
     */
    public function testCompare()
    {

        // {{{ 3.4

        $this->assertEquals(xFrameworkPX_Version::compare('3.4'), -1);

        // }}}
        // {{{ 3.5 Alpha

        $this->assertEquals(xFrameworkPX_Version::compare('3.5 Alpha'), 0);

        // }}}
        // {{{ 3.5

        $this->assertEquals(xFrameworkPX_Version::compare('3.5'), 1);

        // }}}
        // {{{ 3.5.1

        $this->assertEquals(xFrameworkPX_Version::compare('3.5.1'), 1);

        // }}}
        // {{{ 3.6

        $this->assertEquals(xFrameworkPX_Version::compare('3.6'), 1);

        // }}}

    }

    // }}}

}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
