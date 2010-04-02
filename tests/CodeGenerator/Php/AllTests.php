<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_CodeGenerator_Php_AllTests Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: AllTests.php 882 2009-12-23 09:36:22Z tamari $
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define(
        'PHPUnit_MAIN_METHOD',
        'xFrameworkPX_CodeGenerator_Php_AllTests::main'
    );
}

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../../');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Tests/CodeGenerator/Php/PropsTest.php';
require_once 'Tests/CodeGenerator/Php/ParamsTest.php';
require_once 'Tests/CodeGenerator/Php/MethodTest.php';
require_once 'Tests/CodeGenerator/Php/ClassTest.php';
require_once 'Tests/CodeGenerator/Php/FileTest.php';
require_once 'Tests/CodeGenerator/Php/FileDocTest.php';
require_once 'Tests/CodeGenerator/Php/ClassDocTest.php';
require_once 'Tests/CodeGenerator/Php/PropsDocTest.php';
require_once 'Tests/CodeGenerator/Php/MethodDocTest.php';

// }}}
// {{{ xFrameworkPX_CodeGenerator_Php_AllTests

/**
 * xFrameworkPX_CodeGenerator_Php_AllTests Class
 *
 * @category   Tests
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_CodeGenerator_Php_AllTests
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
            'xFrameworkPX , xFrameworkPX_CodeGenerator_Php'
        );

        // {{{ PropsTest

        $suite->addTestSuite('xFrameworkPX_CodeGenerator_Php_PropsTest');

        // }}}
        // {{{ ParamsTest

        $suite->addTestSuite('xFrameworkPX_CodeGenerator_Php_ParamsTest');

        // }}}
        // {{{ MethodTest

        $suite->addTestSuite('xFrameworkPX_CodeGenerator_Php_MethodTest');

        // {{{ ClassTest

        $suite->addTestSuite('xFrameworkPX_CodeGenerator_Php_ClassTest');

        // }}}
        // {{{ FileTest

        $suite->addTestSuite('xFrameworkPX_CodeGenerator_Php_FileTest');

        // }}}
        // {{{ FileDocTest

        $suite->addTestSuite('xFrameworkPX_CodeGenerator_Php_FileDocTest');

        // }}}
        // {{{ ClassDocTest

        $suite->addTestSuite(
            'xFrameworkPX_CodeGenerator_Php_ClassDocTest'
        );

        // }}}
        // {{{ PropsDocTest

        $suite->addTestSuite(
            'xFrameworkPX_CodeGenerator_Php_PropsDocTest'
        );

        // {{{ MethodDocTest

        $suite->addTestSuite(
            'xFrameworkPX_CodeGenerator_Php_MethodDocTest'
        );

        // }}}

        return $suite;
    }

}

// }}}
// {{{ xFrameworkPX_CodeGenerator_Php_AllTests::main

if (PHPUnit_MAIN_METHOD == 'xFrameworkPX_CodeGenerator_Php_AllTests::main') {
    xFrameworkPX_CodeGenerator_Php_AllTests::main();
}

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
