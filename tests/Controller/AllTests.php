<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Controller_AllTests Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_Controller
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: AllTests.php 951 2009-12-25 11:40:13Z tamari $
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'xFrameworkPX_Controller_AllTests::main');
}

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once 'tests/Controller/ActionTest.php';
require_once 'tests/Controller/ConsoleTest.php';
require_once 'tests/Controller/WebTest.php';

require_once 'tests/Controller/Component/AllTests.php';


// }}}
// {{{ xFrameworkPX_Controller_AllTests

/**
 * xFrameworkPX_Controller_AllTests Class
 *
 * @category   Tests
 * @package    xFrameworkPX_Controller
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_Controller_AllTests
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
            'xFrameworkPX , xFrameworkPX_Controller'
        );

        // {{{ xFrameworkPX_Controller_ActionTest

        $suite->addTestSuite('xFrameworkPX_Controller_ActionTest');

        // }}}
        // {{{ xFrameworkPX_Controller_ConsoleTest

        $suite->addTestSuite('xFrameworkPX_Controller_ConsoleTest');

        // }}}
        // {{{ xFrameworkPX_Controller_WebTest

        $suite->addTestSuite('xFrameworkPX_Controller_WebTest');

        // }}}
        // {{{ xFrameworkPX_Controller_Component_AllTests

        $suite->addTestSuite(
            xFrameworkPX_Controller_Component_AllTests::suite()
        );

        // }}}

        return $suite;
    }

}

// }}}
// {{{ xFrameworkPX_Controller_AllTests::main

if (PHPUnit_MAIN_METHOD == 'xFrameworkPX_Controller_AllTests::main') {
    xFrameworkPX_Controller_AllTests::main();
}

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
