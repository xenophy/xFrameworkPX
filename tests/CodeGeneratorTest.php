<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_CodeGeneratorTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: CodeGeneratorTest.php 882 2009-12-23 09:36:22Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'xFrameworkPX/CodeGenerator.php';
require_once 'xFrameworkPX/Codegenerator/Core.php';
require_once 'xFrameworkPX/CodeGenerator/Php.php';
require_once 'xFrameworkPX/Util/MixedCollection.php';

// }}}
// {{{ xFrameworkPX_CodeGeneratorTest

/**
 * xFrameworkPX_CodeGeneratorTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_CodeGeneratorTest extends PHPUnit_Framework_TestCase
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
    // {{{ testFactory

    /**
     * factoryテスト
     *
     * @return void
     */
    public function testFactory()
    {

        // {{{ factoryテスト

        $generator = xFrameworkPX_CodeGenerator::factory('php');
        $this->assertType('xFrameworkPX_CodeGenerator_Php', $generator);

        // }}}
    }

    // }}}

}

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
