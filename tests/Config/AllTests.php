<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Config_AllTests Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_Config
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: AllTests.php 931 2009-12-24 10:42:44Z tamari $
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'xFrameworkPX_Config_AllTests::main');
}

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once 'tests/Config/DatabaseTest.php';
require_once 'Tests/Config/FileTransferTest.php';
require_once 'Tests/Config/GlobalTest.php';
require_once 'Tests/Config/LogTest.php';
require_once 'Tests/Config/SiteTest.php';
require_once 'Tests/Config/SuperTest.php';

// }}}
// {{{ xFrameworkPX_Config_AllTests

/**
 * xFrameworkPX_Config_AllTests Class
 *
 * @category   Tests
 * @package    xFrameworkPX_Config
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_Config_AllTests
{

    public static function main()
    {
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
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite(
            'xFrameworkPX , xFrameworkPX_Config'
        );

        // }}}
        // {{{ xFrameworkPX_Config_DatabaseTest

        $suite->addTestSuite('xFrameworkPX_Config_DatabaseTest');

        // }}}
        // {{{ xFrameworkPX_Config_FileTransferTest

        $suite->addTestSuite('xFrameworkPX_Config_FileTransferTest');

        // }}}
        // {{{ xFrameworkPX_Config_GlobalTest

        $suite->addTestSuite('xFrameworkPX_Config_GlobalTest');

        // }}}
        // {{{ xFrameworkPX_Config_LogTest

        $suite->addTestSuite('xFrameworkPX_Config_LogTest');

        // }}}
        // {{{ xFrameworkPX_Config_SiteTest

        $suite->addTestSuite('xFrameworkPX_Config_SiteTest');

        // }}}
        // {{{ xFrameworkPX_Config_SuperTest

        $suite->addTestSuite('xFrameworkPX_Config_SuperTest');

        // }}}

        return $suite;
    }

}

// }}}
// {{{ xFrameworkPX_Config_AllTests::main

if (PHPUnit_MAIN_METHOD == 'xFrameworkPX_Config_AllTests::main') {
    xFrameworkPX_Config_AllTests::main();
}

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
