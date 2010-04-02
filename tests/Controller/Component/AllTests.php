<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Controller_Component_AllTests Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_Controller_Component
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: AllTests.php 934 2009-12-24 15:20:52Z tamari $
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define(
        'PHPUnit_MAIN_METHOD',
        'xFrameworkPX_Controller_Component_AllTests::main'
    );
}

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../../');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once 'Tests/Controller/Component/PHPSessionTest.php';
require_once 'Tests/Controller/Component/RapidDriveTest.php';

// }}}
// {{{ xFrameworkPX_Controller_Component_AllTests

/**
 * xFrameworkPX_Controller_Component_AllTests Class
 *
 * @category   Tests
 * @package    xFrameworkPX_Controller_Component
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_Controller_Component_AllTests
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
            'xFrameworkPX , xFrameworkPX_Controller_Component'
        );

        // {{{ PHPSessionTest

        $suite->addTestSuite(
            'xFrameworkPX_Controller_Component_PHPSessionTest'
        );

        // }}}
        // {{{ RapidDriveTest

        $suite->addTestSuite(
            'xFrameworkPX_Controller_Component_RapidDriveTest'
        );

        // }}}

        return $suite;
    }

}

// }}}
// {{{ xFrameworkPX_Controller_Component_AllTests::main

if (
    PHPUnit_MAIN_METHOD == 'xFrameworkPX_Controller_Component_AllTests::main'
) {
    xFrameworkPX_Controller_Component_AllTests::main();
}

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
