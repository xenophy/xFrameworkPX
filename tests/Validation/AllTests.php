<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Validation_AllTests Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: AllTests.php 964 2009-12-25 17:23:11Z tamari $
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'xFrameworkPX_Validation_AllTests::main');
}

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once 'AlphaTest.php';
require_once 'AlphaNumericTest.php';
require_once 'BgColorTest.php';
require_once 'DateTest.php';
require_once 'EmailTest.php';
require_once 'HankakuTest.php';
require_once 'HankakuKanaTest.php';
require_once 'NotEmptyTest.php';
require_once 'NumberTest.php';
require_once 'PhoneTest.php';
require_once 'TextLengthTest.php';
require_once 'UrlTest.php';
require_once 'ZenkakuHiraTest.php';
require_once 'ZenkakuTest.php';
require_once 'ZenkakuKanaTest.php';
require_once 'ZenkakuNumTest.php';

// }}}
// {{{ xFrameworkPX_Validation_AllTests

/**
 * xFrameworkPX_Validation_AllTests Class
 *
 * @category   Tests
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_Validation_AllTests
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
            'xFrameworkPX , xFrameworkPX_Validation'
        );

        // }}}
        // {{{ xFrameworkPX_Validation_AlphaTest

        $suite->addTestSuite('xFrameworkPX_Validation_AlphaTest');

        // }}}
        // {{{ xFrameworkPX_Validation_AlphaNumericTest

        $suite->addTestSuite('xFrameworkPX_Validation_AlphaNumericTest');

        // }}}
        // {{{ xFrameworkPX_Validation_BgColorTest

        $suite->addTestSuite('xFrameworkPX_Validation_BgColorTest');

        // }}}
        // {{{ xFrameworkPX_Validation_DateTest

        $suite->addTestSuite('xFrameworkPX_Validation_DateTest');

        // }}}
        // {{{ xFrameworkPX_Validation_EmailTest

        $suite->addTestSuite('xFrameworkPX_Validation_EmailTest');

        // }}}
        // {{{ xFrameworkPX_Validation_HankakuTest

        $suite->addTestSuite('xFrameworkPX_Validation_HankakuTest');

        // }}}
        // {{{ xFrameworkPX_Validation_HankakuKanaTest

        $suite->addTestSuite('xFrameworkPX_Validation_HankakuKanaTest');

        // }}}
        // {{{ xFrameworkPX_Validation_NotEmptyTest

        $suite->addTestSuite('xFrameworkPX_Validation_NotEmptyTest');

        // }}}
        // {{{ xFrameworkPX_Validation_NumberTest

        $suite->addTestSuite('xFrameworkPX_Validation_NumberTest');

        // }}}
        // {{{ xFrameworkPX_Validation_PhoneTest

        $suite->addTestSuite('xFrameworkPX_Validation_PhoneTest');

        // }}}
        // {{{ xFrameworkPX_Validation_TextLengthTest

        $suite->addTestSuite('xFrameworkPX_Validation_TextLengthTest');

        // }}}
        // {{{ xFrameworkPX_Validation_UrlTest

        $suite->addTestSuite('xFrameworkPX_Validation_UrlTest');

        // }}}
        // {{{ xFrameworkPX_Validation_ZenkakuTest

        $suite->addTestSuite('xFrameworkPX_Validation_ZenkakuTest');

        // }}}
        // {{{ xFrameworkPX_Validation_ZenkakuHiraTest

        $suite->addTestSuite('xFrameworkPX_Validation_ZenkakuHiraTest');

        // }}}
        // {{{ xFrameworkPX_Validation_ZenkakuKanaTest

        $suite->addTestSuite('xFrameworkPX_Validation_ZenkakuKanaTest');

        // }}}
        // {{{ xFrameworkPX_Validation_ZenkakuNumTest

        $suite->addTestSuite('xFrameworkPX_Validation_ZenkakuNumTest');

        // }}}

        return $suite;
    }

}

// }}}
// {{{ xFrameworkPX_Validation_AllTests::main

if (PHPUnit_MAIN_METHOD == 'xFrameworkPX_Validation_AllTests::main') {
    xFrameworkPX_Validation_AllTests::main();
}

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
