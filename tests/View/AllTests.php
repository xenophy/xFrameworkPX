<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_View_AllTests Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_View
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: AllTests.php 965 2009-12-26 05:24:33Z tamari $
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'xFrameworkPX_View_AllTests::main');
}

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once 'SmartyTest.php';

// }}}
// {{{ xFrameworkPX_View_AllTests

/**
 * xFrameworkPX_View_AllTests Class
 *
 * @category   Tests
 * @package    xFrameworkPX_View
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_View_AllTests
{

    public static function main()
    {

        // {{{ テストランナー実行

        PHPUnit_TextUI_TestRunner::run(
            self::suite(),
            array(
                'configuration' => '_files/config.xml',
                'coverageSource' => './TestResult/coverage-report',
                'reportDirectory' => './TestResult/report',
                'reportYUI' => true,
                'reportCharset' => 'UTF-8'
            )
        );

        // }}}

    }

    public static function suite()
    {

        // {{{ TestSuite生成

        $suite = new PHPUnit_Framework_TestSuite(
            'xFrameworkPX , xFrameworkPX_View'
        );

        // }}}
        // {{{ xFrameworkPX_View_SmartyTest

        $suite->addTestSuite('xFrameworkPX_View_SmartyTest');

        // }}}

        return $suite;
    }

    // }}}

}

// }}}
// {{{ xFrameworkPX_View_AllTests::main

if (PHPUnit_MAIN_METHOD == 'xFrameworkPX_View_AllTests::main') {
    xFrameworkPX_View_AllTests::main();
}

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
