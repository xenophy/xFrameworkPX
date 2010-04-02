<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_AllTests Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: AllTests.php 965 2009-12-26 05:24:33Z tamari $
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'xFrameworkPX_AllTests::main');
}

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once 'tests/CodeGeneratorTest.php';
require_once 'tests/ConfigTest.php';
require_once 'tests/ControllerTest.php';
require_once 'tests/DispatcherTest.php';
require_once 'tests/ExceptionTest.php';
require_once 'tests/extenderTest.php';
require_once 'tests/ModelTest.php';
require_once 'tests/ObjectTest.php';
require_once 'tests/VersionTest.php';
require_once 'tests/YamlTest.php';

require_once 'tests/CodeGenerator/AllTests.php';
require_once 'tests/Config/AllTests.php';
require_once 'tests/Controller/AllTests.php';
require_once 'tests/Model/AllTests.php';
require_once 'tests/Util/AllTests.php';
require_once 'tests/Validation/AllTests.php';
require_once 'tests/View/AllTests.php';

// }}}
// {{{ xFrameworkPX_AllTests

/**
 * xFrameworkPX_AllTests Class
 *
 * @category   Tests
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_AllTests
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
        $suite = new PHPUnit_Framework_TestSuite('xFrameworkPX');

        // {{{ xFrameworkPX_CodeGenerator_AllTests

        $suite->addTestSuite(xFrameworkPX_CodeGenerator_AllTests::suite());

        // }}}
        // {{{ xFrameworkPX_Config_AllTests

        $suite->addTestSuite(xFrameworkPX_Config_AllTests::suite());

        // }}}
        // {{{ xFrameworkPX_Controller_AllTests

        $suite->addTestSuite(xFrameworkPX_Controller_AllTests::suite());

        // }}}
        // {{{ xFrameworkPX_Model_AllTests

        $suite->addTestSuite(xFrameworkPX_Model_AllTests::suite());

        // }}}
        // {{{ xFrameworkPX_Util_AllTests

        $suite->addTestSuite(xFrameworkPX_Util_AllTests::suite());

        // }}}
        // {{{ xFrameworkPX_Validation_AllTests

        $suite->addTestSuite(xFrameworkPX_Validation_AllTests::suite());

        // }}}
        // {{{ xFrameworkPX_View_AllTests

        $suite->addTestSuite(xFrameworkPX_View_AllTests::suite());

        // }}}
        // {{{ xFrameworkPX_CodeGeneratorTest

        $suite->addTestSuite('xFrameworkPX_CodeGeneratorTest');

        // }}}
        // {{{ xFrameworkPX_ConfigTest

        $suite->addTestSuite('xFrameworkPX_ConfigTest');

        // }}}
        // {{{ xFrameworkPX_ControllerTest

        $suite->addTestSuite('xFrameworkPX_ControllerTest');

        // }}}
        // {{{ xFrameworkPX_DispatcherTest

        $suite->addTestSuite('xFrameworkPX_DispatcherTest');

        // }}}
        // {{{ xFrameworkPX_ExceptionTest

        $suite->addTestSuite('xFrameworkPX_ExceptionTest');

        // }}}
        // {{{ extenderTest

        $suite->addTestSuite('extenderTest');

        // }}}
        // {{{ xFrameworkPX_ModelTest

        $suite->addTestSuite('xFrameworkPX_ModelTest');

        // }}}
        // {{{ xFrameworkPX_ObjectTest

        $suite->addTestSuite('xFrameworkPX_ObjectTest');

        // }}}
        // {{{ xFrameworkPX_VersionTest

        $suite->addTestSuite('xFrameworkPX_VersionTest');

        // }}}
        // {{{ xFrameworkPX_YamlTest

        $suite->addTestSuite('xFrameworkPX_YamlTest');

        // }}}

        return $suite;
    }

}

// }}}
// {{{ xFrameworkPX_Config_AllTests::main

if (PHPUnit_MAIN_METHOD == 'xFrameworkPX_AllTests::main') {
    xFrameworkPX_AllTests::main();
}

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
