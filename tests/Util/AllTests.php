<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Util_AllTests Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_Util
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: AllTests.php 1161 2010-01-05 01:32:30Z tamari $
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'xFrameworkPX_Util_AllTests::main');
}

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once 'ObservableTest.php';
require_once 'MixedCollectionTest.php';
require_once 'SerializerTest.php';
require_once 'FormatTest.php';

// }}}
// {{{ xFrameworkPX_Util_AllTests

/**
 * xFrameworkPX_Util_AllTests Class
 *
 * @category   Tests
 * @package    xFrameworkPX_Util
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_Util_AllTests
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
            'xFrameworkPX , xFrameworkPX_Util'
        );

        // }}}
        // {{{ xFrameworkPX_Util_ObservableTest

        $suite->addTestSuite('xFrameworkPX_Util_ObservableTest');

        // }}}
        // {{{ xFrameworkPX_Util_MixedCollectionTest

        $suite->addTestSuite('xFrameworkPX_Util_MixedCollectionTest');

        // }}}
        // {{{ SerializerTest

        $suite->addTestSuite('xFrameworkPX_Util_SerializerTest');

        // }}}
        // {{{ FormatTest

        $suite->addTestSuite('xFrameworkPX_Util_FormatTest');

        // }}}

        return $suite;
    }

    // }}}

}

// }}}
// {{{ xFrameworkPX_Util_AllTests::main

if (PHPUnit_MAIN_METHOD == 'xFrameworkPX_Util_AllTests::main') {
    xFrameworkPX_Util_AllTests::main();
}

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
