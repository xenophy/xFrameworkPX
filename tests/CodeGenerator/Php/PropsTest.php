<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_CodeGenerator_Php_PropsTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: PropsTest.php 882 2009-12-23 09:36:22Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Generator.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Props.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Doc.php';
require_once 'xFrameworkPX/CodeGenerator/Php/PropsDoc.php';

require_once 'xFrameworkPX/Util/MixedCollection.php';

require_once 'xFrameworkPX/Exception.php';
require_once 'xFrameworkPX/CodeGenerator/Exception.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Exception.php';

// }}}
// {{{ xFrameworkPX_CodeGenerator_Php_PropsTest

/**
 * xFrameworkPX_CodeGenerator_Php_PropsTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_CodeGenerator_Php_PropsTest
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
     * @retrun void
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
            new xFrameworkPX_CodeGenerator_Php_Props();
        } catch (Exception $ex) {
            $this->assertTrue(true);
        }


        try {
            new xFrameworkPX_CodeGenerator_Php_Props(
                new xFrameworkPX_Util_MixedCollection()
            );
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                'Property Name is Undefined.',
                $ex->getMessage()
            );
        }


        try {
            $conf = new xFrameworkPX_Util_MixedCollection(array( 
                'propsName' => ''
             ));
            new xFrameworkPX_CodeGenerator_Php_Props($conf);
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                'Property Name is Undefined.',
                $ex->getMessage()
            );
        }


        try {
            $conf = new xFrameworkPX_Util_MixedCollection(array( 
                'propsName' => null
             ));
            new xFrameworkPX_CodeGenerator_Php_Props($conf);
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                'Property Name is Undefined.',
                $ex->getMessage()
            );
        }


        try {
            $conf = new xFrameworkPX_Util_MixedCollection(array(
                'propsName' => 'testName',
                'value' => new stdClass,
                'access' => 'test'
            ));
            new xFrameworkPX_CodeGenerator_Php_Props($conf);
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                'Access "test" is invalid type.',
                $ex->getMessage()
            );
        }


        $conf = new xFrameworkPX_Util_MixedCollection(
            array('propsName' => 'testName')
        );

        $props = new xFrameworkPX_Codegenerator_Php_Props($conf);

        $this->assertAttributeEquals($conf->propsName, '_name', $props);
        $this->assertAttributeEquals('public', '_access', $props);
        $this->assertAttributeEquals(false, '_static', $props);
        $this->assertAttributeEquals($conf->value, '_value', $props);
        $this->assertAttributeEquals(false, '_const', $props);
        $this->assertAttributeEquals(true, '_constAutoUpper', $props);


        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'propsName' => 'testName',
            'access' => null,
            'static' => null,
            'value' => 'testValue',
            'const' => null,
            'doc' => null,
            'autoupper' => null
        ));

        $props = new xFrameworkPX_CodeGenerator_Php_Props($conf);

        $this->assertAttributeEquals($conf->propsName, '_name', $props);
        $this->assertAttributeEquals(null, '_access', $props);
        $this->assertAttributeEquals(false, '_static', $props);
        $this->assertAttributeEquals($conf->value, '_value', $props);
        $this->assertAttributeEquals(
            new xFrameworkPX_CodeGenerator_Php_PropsDoc(
                new xFrameworkPX_Util_MixedCollection(array(
                    'shortDesc' => 'testName'
                ))
            ),
            '_doc',
            $props
        );
        $this->assertAttributeEquals(false, '_const', $props);
        $this->assertAttributeEquals(true, '_constAutoUpper', $props);


        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'propsName' => 'testName',
            'access' => '',
            'static' => '',
            'value' => 'testValue',
            'const' => '',
            'autoupper' => ''
        ));

        $props = new xFrameworkPX_CodeGenerator_Php_Props($conf);

        $this->assertAttributeEquals($conf->propsName, '_name', $props);
        $this->assertAttributeEquals('', '_access', $props);
        $this->assertAttributeEquals(false, '_static', $props);
        $this->assertAttributeEquals($conf->value, '_value', $props);
        $this->assertAttributeEquals(false, '_const', $props);
        $this->assertAttributeEquals(true, '_constAutoUpper', $props);


        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'propsName' => 'testName',
            'access' => 'public',
            'static' => true,
            'value' => 'testValue',
            'const' => 'test',
            'autoupper' => 12345
        ));

        $props = new xFrameworkPX_CodeGenerator_Php_Props($conf);

        $this->assertAttributeEquals($conf->propsName, '_name', $props);
        $this->assertAttributeEquals($conf->access, '_access', $props);
        $this->assertAttributeEquals(true, '_static', $props);
        $this->assertAttributeEquals($conf->value, '_value', $props);
        $this->assertAttributeEquals(false, '_const', $props);
        $this->assertAttributeEquals(true, '_constAutoUpper', $props);


        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'propsName' => 'testName',
            'access' => 'protected',
            'static' => false,
            'value' => 'testValue',
            'const' => true,
            'autoupper' => true
        ));

        $props = new xFrameworkPX_CodeGenerator_Php_Props($conf);

        $this->assertAttributeEquals($conf->propsName, '_name', $props);
        $this->assertAttributeEquals($conf->access, '_access', $props);
        $this->assertAttributeEquals(false, '_static', $props);
        $this->assertAttributeEquals($conf->value, '_value', $props);
        $this->assertAttributeEquals(true, '_const', $props);
        $this->assertAttributeEquals(true, '_constAutoUpper', $props);


        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'propsName' => 'testName',
            'access' => 'private',
            'static' => false,
            'value' => 'testValue',
            'const' => false,
            'autoupper' => false
        ));

        $props = new xFrameworkPX_CodeGenerator_Php_Props($conf);

        $this->assertAttributeEquals($conf->propsName, '_name', $props);
        $this->assertAttributeEquals($conf->access, '_access', $props);
        $this->assertAttributeEquals($conf->value, '_value', $props);
        $this->assertAttributeEquals(false, '_const', $props);
        $this->assertAttributeEquals(false, '_constAutoUpper', $props);

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
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'propsName' => 'testName',
            'access' => 'public',
            'static' => false,
            'value' => 'testValue',
            'const' => false,
            'autoupper' => false
        ));

        $props = new xFrameworkPX_CodeGenerator_Php_Props($conf);

        $rend = $props->render();

        $code = array(
            '',
            '    /**',
            '     * testName',
            '     *',
            '     * @var',
            '     * @access',
            '     */',
            '    public $testName = testValue;',
            ''
        );

        $this->assertEquals(implode("\n", $code), $rend);

        $props = new xFrameworkPX_CodeGenerator_Php_Props(
            new xFrameworkPX_Util_MixedCollection( array(
                'propsName' => 'testName2',
                'access' => 'protected',
                'static' => false,
                'value' => "'testValue2'",
                'const' => true,
                'autoupper' => true
            ))
        );

        $rend = $props->render();

        $code = array(
            '',
            '    /**',
            '     * TESTNAME2',
            '     *',
            '     * @var',
            '     * @access',
            '     */',
            '    const TESTNAME2 = \'testValue2\';',
            ''
        );

        $this->assertEquals(implode("\n", $code), $rend);

        $props = new xFrameworkPX_CodeGenerator_Php_Props(
            new xFrameworkPX_Util_MixedCollection(array(
                'propsName' => 'testName3',
                'access' => 'private',
                'static' => true,
                'value' => null,
                'const' => true,
                'autoupper' => false
            ))
        );

        $rend = $props->render();

        $code = array(
            '',
            '    /**',
            '     * testName3',
            '     *',
            '     * @var',
            '     * @access',
            '     */',
            '    const testName3 = null;',
            ''
        );

        $this->assertEquals(implode("\n", $code), $rend);

        $props = new xFrameworkPX_CodeGenerator_Php_Props(
            new xFrameworkPX_Util_MixedCollection(array(
                'propsName' => 'testName4',
                'access' => 'private',
                'static' => false,
                'value' => '',
                'const' => true,
                'autoupper' => null
            ))
        );

        $rend = $props->render();

        $code = array(
            '',
            '    /**',
            '     * TESTNAME4',
            '     *',
            '     * @var',
            '     * @access',
            '     */',
            '    const TESTNAME4 = \'\';',
            ''
        );

        $this->assertEquals(implode("\n", $code), $rend);

        $props = new xFrameworkPX_CodeGenerator_Php_Props(
            new xFrameworkPX_Util_MixedCollection(array(
                'propsName' => 'testName5',
                'access' => '',
                'static' => false,
                'value' => new stdClass,
                'const' => true
            ))
        );

        $rend = $props->render();

        $code = array(
            '',
            '    /**',
            '     * TESTNAME5',
            '     *',
            '     * @var',
            '     * @access',
            '     */',
            '    const TESTNAME5;',
            ''
        );

        $this->assertEquals(implode("\n", $code), $rend);

        $props = new xFrameworkPX_CodeGenerator_Php_Props(
            new xFrameworkPX_Util_MixedCollection(array(
                'propsName' => 'testName6',
                'access' => 'public',
                'static' => true,
                'value' => true,
                'const' => false,
                'autoupper' => true,
                'doc' => new xFrameworkPX_Util_MixedCollection(array(
                    'shortDesc' => 'testName6',
                    'longDesc' => 'this props default value is boolean.',
                    'tags' => new xFrameworkPX_Util_MixedCollection(array(
                        'access' => 'public',
                        'var' => 'boolean'
                    ))
                ))
            ))
        );

        $rend = $props->render();

        $code = array(
            '',
            '    /**',
            '     * testName6',
            '     *',
            '     * this props default value is boolean.',
            '     *',
            '     * @var       boolean',
            '     * @access    public',
            '     */',
            '    public static $testName6 = true;',
            ''
        );

        $this->assertEquals(implode("\n", $code), $rend);

        $props = new xFrameworkPX_CodeGenerator_Php_Props(
            new xFrameworkPX_Util_MixedCollection(array(
                'propsName' => 'testName7',
                'access' => 'public',
                'static' => false,
                'value' => false,
                'const' => false,
                'autoupper' => true
            ))
        );

        $rend = $props->render();

        $code = array(
            '',
            '    /**',
            '     * testName7',
            '     *',
            '     * @var',
            '     * @access',
            '     */',
            '    public $testName7 = false;',
            ''
        );

        $this->assertEquals(implode("\n", $code), $rend);

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
