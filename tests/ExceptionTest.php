<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_ExceptionTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: ExceptionTest.php 882 2009-12-23 09:36:22Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'xFrameworkPX/Version.php';
require_once 'xFrameworkPX/Exception.php';

// }}}
// {{{ xFrameworkPX_ExceptionTest

/**
 * xFrameworkPX_ExceptionTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_ExceptionTest extends PHPUnit_Framework_TestCase
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
    // {{{ test__construct

    /**
     * __constructテスト
     *
     * @return void
     */
    public function test__construct()
    {

        try {
            throw new xFrameworkPX_Exception('xFrameworkPX_ExceptionTest');

        } catch (xFrameworkPX_Exception $e) {

            // {{{ バッファリング開始

            ob_start();

            // }}}
            // {{{ スタックトーレス出力

            echo $e->printStackTrace();

            // }}}
            // {{{ コンテンツバッファ取得

            $content = ob_get_contents();

            // }}}
            // {{{ バッファクリア

            ob_end_clean();

            // }}}

            $this->assertTrue(strlen($content) > 0);

            $stackTrace = $e->getStackTrace(true);

            $this->assertStringStartsWith(
                '                        <tr>',
                $stackTrace
            );
        }

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
