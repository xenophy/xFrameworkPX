<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_CodeGenerator_Php_ParamsTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: ParamsTest.php 882 2009-12-23 09:36:22Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Generator.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Params.php';

require_once 'xFrameworkPX/Util/MixedCollection.php';

require_once 'xFrameworkPX/Exception.php';
require_once 'xFrameworkPX/CodeGenerator/Exception.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Exception.php';

// }}}
// {{{ xFrameworkPX_CodeGenerator_Php_ParamsTest

/**
 * xFrameworkPX_CodeGenerator_Php_ParamsTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_CodeGenerator_Php_ParamsTest
extends PHPUnit_Framework_TestCase
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

        // {{{ コンストラクタテスト

        try {
            new xFrameworkPX_CodeGenerator_Php_Params();
        } catch (Exception $ex) {
            $this->assertTrue(true);
        }


        try {
            new xFrameworkPX_CodeGenerator_Php_Params( 
                new xFrameworkPX_Util_MixedCollection()
            );
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                'Params Name is Undefined.',
                $ex->getMessage()
            );
        }


        try {
            new xFrameworkPX_CodeGenerator_Php_Params(
                new xFrameworkPX_Util_MixedCollection(array(
                    'value' => 'testValue1'
                ))
            );
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                'Params Name is Undefined.',
                $ex->getMessage()
            );
        }


        try {
            new xFrameworkPX_CodeGenerator_Php_Params(
                new xFrameworkPX_Util_MixedCollection(array(
                    'paramName' => null,
                    'value' => 'testValue1'
                ))
            );
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                'Params Name is Undefined.',
                $ex->getMessage()
            );
        }


        try {
            new xFrameworkPX_CodeGenerator_Php_Params(
                new xFrameworkPX_Util_MixedCollection(array(
                    'paramName' => '',
                    'value' => 'testValue1'
                ))
            );
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                'Params Name is Undefined.',
                $ex->getMessage()
            );
        }


        $config = new xFrameworkPX_Util_MixedCollection(array(
            'paramName' => 'testParam1',
            'value' => 'testValue2'
        ));

        $params = new xFrameworkPX_CodeGenerator_Php_Params($config);

        $this->assertAttributeEquals($config->paramName, '_name', $params);

        $this->assertAttributeEquals($config->value, '_value', $params);


        $config = new xFrameworkPX_Util_MixedCollection(array(
            'paramName' => 'testParam1'
        ));

        $params = new xFrameworkPX_CodeGenerator_Php_Params($config);
        $this->assertAttributeEquals($config->paramName, '_name', $params);

        $this->assertAttributeEquals(new stdClass, '_value', $params);


        $config = new xFrameworkPX_Util_MixedCollection(array(
            'paramName' => 'testParam1',
            'value' => null
        ));

        $params = new xFrameworkPX_CodeGenerator_Php_Params($config);
        $this->assertAttributeEquals($config->paramName, '_name', $params);
        $this->assertAttributeEquals(null, '_value', $params);


        $config = new xFrameworkPX_Util_MixedCollection(array(
            'paramName' => 'testParam2',
            'value' => ''
        ));

        $params = new xFrameworkPX_CodeGenerator_Php_Params($config);

        $this->assertAttributeEquals($config->paramName, '_name', $params);
        
        $this->assertAttributeEquals('', '_value', $params);


        $config = new xFrameworkPX_Util_MixedCollection(array(
            'paramName' => 'testParam3',
            'value' => new stdClass
        ));

        $params = new xFrameworkPX_CodeGenerator_Php_Params($config);
        $this->assertAttributeEquals($config->paramName, '_name', $params);
        $this->assertAttributeEquals(new stdClass, '_value', $params);

        // }}}
    }

    // }}}
    // {{{ testRender

    /**
     * renderテスト
     *
     * @return void
     */
    public function testRender()
    {

        // {{{ ソースコードレンダリングテスト

        $config = new xFrameworkPX_Util_MixedCollection(array(
            'paramName' => 'testParam1',
            'value' => 'testValue2'
        ));

        $params = new xFrameworkPX_CodeGenerator_Php_Params($config);
        $code = $params->render();
        $this->assertEquals('testParam1 = testValue2', $code);


        $config = new xFrameworkPX_Util_MixedCollection(array(
            'paramName' => 'testParam2'
        ));

        $params = new xFrameworkPX_CodeGenerator_Php_Params($config);
        $code = $params->render();
        $this->assertEquals('testParam2', $code);


        $config = new xFrameworkPX_Util_MixedCollection(array(
            'paramName' => 'testParam3',
            'value' => null
        ));

        $params = new xFrameworkPX_CodeGenerator_Php_Params($config);
        $code = $params->render();
        $this->assertEquals('testParam3 = null', $code);


        $config = new xFrameworkPX_Util_MixedCollection(array(
            'paramName' => 'testParam4',
            'value' => ''
        ));

        $params = new xFrameworkPX_CodeGenerator_Php_Params($config);
        $code = $params->render();
        $this->assertEquals("testParam4 = ''", $code);


        $config = new xFrameworkPX_Util_MixedCollection(array(
            'paramName' => 'testParam5',
            'value' => new stdClass
        ));

        $params = new xFrameworkPX_CodeGenerator_Php_Params($config);
        $code = $params->render();
        $this->assertEquals('testParam5', $code);


        $config = new xFrameworkPX_Util_MixedCollection(array(
            'paramName' => 'testParam6',
            'value' => true
        ));

        $params = new xFrameworkPX_CodeGenerator_Php_Params($config);
        $code = $params->render();
        $this->assertEquals('testParam6 = true', $code);


        $config = new xFrameworkPX_Util_MixedCollection(array(
            'paramName' => 'testParam7',
            'value' => false
        ));

        $params = new xFrameworkPX_CodeGenerator_Php_Params($config);
        $code = $params->render();
        $this->assertEquals('testParam7 = false', $code);

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
