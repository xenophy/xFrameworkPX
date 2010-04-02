<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_CodeGenerator_Php_MethodTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: MethodTest.php 882 2009-12-23 09:36:22Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Generator.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Method.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Params.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Doc.php';
require_once 'xFrameworkPX/CodeGenerator/Php/MethodDoc.php';

require_once 'xFrameworkPX/Util/MixedCollection.php';

require_once 'xFrameworkPX/Exception.php';
require_once 'xFrameworkPX/CodeGenerator/Exception.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Exception.php';

// }}}
// {{{ xFrameworkPX_CodeGenerator_Php_MethodTest

/**
 * xFrameworkPX_CodeGenerator_Php_MethodTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_CodeGenerator_Php_MethodTest
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

        // 引数なし
        try {
            new xFrameworkPX_CodeGenerator_Php_Method();
        } catch (Exception $ex) {
            $this->assertTrue(true);
        }


        // 引数がNULL
        try {
            new xFrameworkPX_CodeGenerator_Php_Method(null);
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                'Method Name is Undefined.',
                $ex->getMessage()
            );
        }


        // 引数が中身が空の設定用の配列
        try {
            new xFrameworkPX_CodeGenerator_Php_Method(
                new xFrameworkPX_Util_MixedCollection()
            );
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                'Method Name is Undefined.', $ex->getMessage()
            );
        }


        // メソッド名がNULL
        try {
            new xFrameworkPX_CodeGenerator_Php_Method(
                new xFrameworkPX_Util_MixedCollection(array(
                    'methodName' => null
                ))
            );
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                'Method Name is Undefined.',
                $ex->getMessage()
            );
        }


        // メソッド名が空文字
        try {
            new xFrameworkPX_CodeGenerator_Php_Method(
                new xFrameworkPX_Util_MixedCollection(array(
                    'methodName' => ''
                ))
            );
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                'Method Name is Undefined.',
                $ex->getMessage()
            );
        }


        // アクセス修飾子が規定のもの以外
        try {
            new xFrameworkPX_CodeGenerator_Php_Method(
                new xFrameworkPX_Util_MixedCollection(array(
                    'methodName' => 'testMethod',
                    'access' => 'test'
                ))
            );
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                'Access "test" is invalid type.',
                $ex->getMessage()
            );
        }


        // アブストラクト設定がオンのときアクセス修飾子にprivateを設定
        try {
            new xFrameworkPX_CodeGenerator_Php_Method(
                new xFrameworkPX_Util_MixedCollection(array(
                    'methodName' => 'testMethod',
                    'access' => 'private',
                    'abstract' => true
                ))
            );
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                '"private" is invalid type when abstract config enabled',
                $ex->getMessage()
            );
        }


        // メソッド名以外の設定なし
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'methodName' => 'testMethod',
        ));

        $method = new xFrameworkPX_CodeGenerator_Php_Method($conf);

        $this->assertAttributeEquals($conf->methodName, '_name', $method);
        $this->assertAttributeEquals('public', '_access', $method);
        $this->assertAttributeEquals(false, '_static', $method);
        $this->assertAttributeEquals(false, '_abstract', $method);
        $this->assertAttributeEquals(false, '_final', $method);
        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection,
            '_params',
            $method
        );
        $this->assertAttributeEquals('', '_blockCode', $method);
        $this->assertAttributeEquals(false, '_parentCall', $method);
        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection,
            '_parentParams',
            $method
        );


        // メソッド名以外の設定NULL
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'methodName' => 'testMethod2',
            'access' => null,
            'static' => null,
            'abstract' => null,
            'final' => null,
            'params' => null,
            'blockCode' => null,
            'parentCall' => null,
            'parentCallParams' => null
        ));

        $method = new xFrameworkPX_CodeGenerator_Php_Method($conf);

        $this->assertAttributeEquals($conf->methodName, '_name', $method);
        $this->assertAttributeEquals('', '_access', $method);
        $this->assertAttributeEquals(false, '_static', $method);
        $this->assertAttributeEquals(false, '_abstract', $method);
        $this->assertAttributeEquals(false, '_final', $method);
        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection,
            '_params',
            $method
        );
        $this->assertAttributeEquals('', '_blockCode', $method);
        $this->assertAttributeEquals(false, '_parentCall', $method);
        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection,
            '_parentParams',
            $method
        );


        // メソッド名以外の設定空文字
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'methodName' => 'testMethod',
            'access' => '',
            'static' => '',
            'abstract' => '',
            'final' => '',
            'params' => '',
            'blockCode' => '',
            'parentCall' => '',
            'parentCallParams' => ''
        ));

        $method = new xFrameworkPX_CodeGenerator_Php_Method($conf);

        $this->assertAttributeEquals($conf->methodName, '_name', $method);
        $this->assertAttributeEquals('', '_access', $method);
        $this->assertAttributeEquals(false, '_static', $method);
        $this->assertAttributeEquals(false, '_abstract', $method);
        $this->assertAttributeEquals(false, '_final', $method);
        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection,
            '_params',
            $method
        );
        $this->assertAttributeEquals('', '_blockCode', $method);
        $this->assertAttributeEquals(false, '_parentCall', $method);
        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection,
            '_parentParams',
            $method
        );


        // メソッド名以外に規定値以外のものを設定
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'methodName' => 'testMethod',
            'access' => 'public',
            'static' => 'abc',
            'abstract' => 'def',
            'final' => 'ghi',
            'params' => 'params',
            'blockCode' => 1234,
            'parentCall' => 'jkl',
            'parentCallParams' => 'mno'
        ));

        $method = new xFrameworkPX_CodeGenerator_Php_Method($conf);

        $this->assertAttributeEquals($conf->methodName, '_name', $method);
        $this->assertAttributeEquals('public', '_access', $method);
        $this->assertAttributeEquals(false, '_static', $method);
        $this->assertAttributeEquals(false, '_abstract', $method);
        $this->assertAttributeEquals(false, '_final', $method);
        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection,
            '_params',
            $method
        );
        $this->assertAttributeEquals('1234', '_blockCode', $method);
        $this->assertAttributeEquals(false, '_parentCall', $method);
        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection,
            '_parentParams',
            $method
        );


        // 親メソッド呼出しfalse (親メソッドパラメーターあり)
        $paramsConf = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_Util_MixedCollection(array(
                'paramName' => 'param1'
            )),

            new xFrameworkPX_Util_MixedCollection(array(
                'paramName' => 'param2',
                'value' => 'default'
            ))
        ));

        $params = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_CodeGenerator_Php_Params($paramsConf->{0}),
            new xFrameworkPX_CodeGenerator_Php_Params($paramsConf->{1})
        ));

        $parentParamsConf = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_Util_MixedCollection(array(
                'paramName' => 'pParam1'
            )),

            new xFrameworkPX_Util_MixedCollection(array(
                'paramName' => 'pParam2',
                'value' => 'default'
            ))
        ));

        $parentParams = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_CodeGenerator_Php_Params($parentParamsConf->{0}),
            new xFrameworkPX_CodeGenerator_Php_Params($parentParamsConf->{1})
        ));

        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'methodName' => 'testMethod1',
            'access' => 'public',
            'static' => false,
            'abstract' => false,
            'final' => false,
            'params' => $paramsConf,
            'blockCode' => '',
            'parentCall' => false,
            'parentParams' => $parentParams
        ));

        $method = new xFrameworkPX_CodeGenerator_Php_Method($conf);

        $this->assertAttributeEquals($conf->methodName, '_name', $method);
        $this->assertAttributeEquals($conf->access, '_access', $method);
        $this->assertAttributeEquals($conf->static, '_static', $method);
        $this->assertAttributeEquals($conf->abstract, '_abstract', $method);
        $this->assertAttributeEquals($conf->final, '_final', $method);
        $this->assertAttributeEquals($params, '_params', $method);
        $this->assertAttributeEquals($conf->blockCode, '_blockCode', $method);
        $this->assertAttributeEquals(
            $conf->parentCall,
            '_parentCall',
            $method
        );
        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection,
            '_parentParams',
            $method
        );


        $paramsConf = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_Util_MixedCollection(array(
                'paramName' => 'param1'
            ))
        ));

        $params = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_CodeGenerator_Php_Params($paramsConf->{0})
        ));

        $parentParamsConf = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_Util_MixedCollection(array(
                'paramName' => 'pParam1'
            )),

            new xFrameworkPX_Util_MixedCollection(array(
                'paramName' => 'pParam2',
                'value' => 'default'
            ))
        ));

        $parentParams = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_CodeGenerator_Php_Params($parentParamsConf->{0}),
            new xFrameworkPX_CodeGenerator_Php_Params($parentParamsConf->{1})
        ));

        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'methodName' => 'testMethod2',
            'access' => 'protected',
            'static' => true,
            'abstract' => true,
            'final' => true,
            'params' => $paramsConf,
            'blockCode' => '',
            'parentCall' => true,
            'parentParams' => $parentParamsConf
        ));

        $method = new xFrameworkPX_CodeGenerator_Php_Method($conf);

        $this->assertAttributeEquals($conf->methodName, '_name', $method);
        $this->assertAttributeEquals($conf->access, '_access', $method);
        $this->assertAttributeEquals($conf->static, '_static', $method);
        $this->assertAttributeEquals($conf->abstract, '_abstract', $method);
        $this->assertAttributeEquals($conf->final, '_final', $method);
        $this->assertAttributeEquals($params, '_params', $method);
        $this->assertAttributeEquals($conf->blockCode, '_blockCode', $method);
        $this->assertAttributeEquals(
            $conf->parentCall,
            '_parentCall',
            $method
        );
        $this->assertAttributeEquals($parentParams, '_parentParams', $method);


        $paramsConf = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_Util_MixedCollection(array(
                'paramName' => 'param1'
            ))
        ));

        $params = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_CodeGenerator_Php_Params($paramsConf->{0})
        ));

        $parentParamsConf = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_Util_MixedCollection(array(
                'paramName' => 'pParam1'
            ))
        ));

        $parentParams = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_CodeGenerator_Php_Params($parentParamsConf->{0})
        ));

        $conf = new xFrameworkPX_Util_MixedCollection( array(
            'methodName' => 'testMethod2',
            'access' => 'protected',
            'static' => true,
            'abstract' => true,
            'final' => true,
            'params' => $paramsConf,
            'blockCode' => '',
            'parentCall' => true,
            'parentParams' => $parentParamsConf
        ) );

        $method = new xFrameworkPX_CodeGenerator_Php_Method( $conf );

        $this->assertAttributeEquals($conf->methodName, '_name', $method);
        $this->assertAttributeEquals($conf->access, '_access', $method);
        $this->assertAttributeEquals($conf->static, '_static', $method);
        $this->assertAttributeEquals($conf->abstract, '_abstract', $method);
        $this->assertAttributeEquals($conf->final, '_final', $method);
        $this->assertAttributeEquals($params, '_params', $method);
        $this->assertAttributeEquals($conf->blockCode, '_blockCode', $method);
        $this->assertAttributeEquals(
            $conf->parentCall,
            '_parentCall',
            $method
        );
        $this->assertAttributeEquals($parentParams, '_parentParams', $method);


        $parentParamsConf = new xFrameworkPX_Util_MixedCollection( array(
            new xFrameworkPX_Util_MixedCollection( array(
                'paramName' => 'pParam1'
            ) )
        ) );

        $parentParams = new xFrameworkPX_Util_MixedCollection( array(
            new xFrameworkPX_CodeGenerator_Php_Params(
                $parentParamsConf->{0}
            )
        ) );

        $conf = new xFrameworkPX_Util_MixedCollection( array(
            'methodName' => 'testMethod3',
            'access' => 'private',
            'static' => false,
            'abstract' => false,
            'final' => false,
            'params' => new xFrameworkPX_Util_MixedCollection(),
            'blockCode' => '',
            'parentCall' => true,
            'parentParams' => $parentParamsConf
        ) );

        $method = new xFrameworkPX_CodeGenerator_Php_Method($conf);

        $this->assertAttributeEquals($conf->methodName, '_name', $method);
        $this->assertAttributeEquals($conf->access, '_access', $method);
        $this->assertAttributeEquals($conf->static, '_static', $method);
        $this->assertAttributeEquals($conf->abstract, '_abstract', $method);
        $this->assertAttributeEquals($conf->final, '_final', $method);
        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection(),
            '_params',
            $method
        );
        $this->assertAttributeEquals($conf->blockCode, '_blockCode', $method);
        $this->assertAttributeEquals(
            $conf->parentCall,
            '_parentCall',
            $method
        );
        $this->assertAttributeEquals($parentParams, '_parentParams', $method);


        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'methodName' => 'testMethod3',
            'access' => 'private',
            'static' => false,
            'abstract' => false,
            'final' => false,
            'params' => new xFrameworkPX_Util_MixedCollection(),
            'blockCode' => '',
            'parentCall' => true,
            'parentParams' => new xFrameworkPX_Util_MixedCollection()
        ));

        $method = new xFrameworkPX_CodeGenerator_Php_Method($conf);

        $this->assertAttributeEquals($conf->methodName, '_name', $method);
        $this->assertAttributeEquals($conf->access, '_access', $method);
        $this->assertAttributeEquals($conf->static, '_static', $method);
        $this->assertAttributeEquals($conf->abstract, '_abstract', $method);
        $this->assertAttributeEquals($conf->final, '_final', $method);
        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection(),
            '_params',
            $method
        );
        $this->assertAttributeEquals($conf->blockCode, '_blockCode', $method);
        $this->assertAttributeEquals(
            $conf->parentCall,
            '_parentCall',
            $method
        );

        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection(),
            '_parentParams',
            $method
        );


        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'methodName' => 'testMethod3',
            'access' => 'private',
            'static' => false,
            'abstract' => false,
            'final' => false,
            'params' => new xFrameworkPX_Util_MixedCollection(),
            'blockCode' => '',
            'parentCall' => true
        ));

        $method = new xFrameworkPX_CodeGenerator_Php_Method($conf);

        $this->assertAttributeEquals($conf->methodName, '_name', $method);
        $this->assertAttributeEquals($conf->access, '_access', $method);
        $this->assertAttributeEquals($conf->static, '_static', $method);
        $this->assertAttributeEquals($conf->abstract, '_abstract', $method);
        $this->assertAttributeEquals($conf->final, '_final', $method);
        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection(),
            '_params',
            $method
        );
        $this->assertAttributeEquals($conf->blockCode, '_blockCode', $method);
        $this->assertAttributeEquals(
            $conf->parentCall,
            '_parentCall',
            $method
        );
        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection(),
            '_parentParams',
            $method
        );

        //}}}
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
        $paramsConf = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_Util_MixedCollection(array(
                'paramName' => 'param1'
            )),

            new xFrameworkPX_Util_MixedCollection(array(
                'paramName' => 'param2',
                'value' => 'default'
            ))
        ));

        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'methodName' => 'testMethod1',
            'access' => 'public',
            'static' => false,
            'abstract' => false,
            'final' => false,
            'params' => $paramsConf,
            'blockCode' => '',
            'parentCall' => false
        ));

        $method = new xFrameworkPX_CodeGenerator_Php_Method($conf);
        $render = $method->render();

        $code = array(
            '',
            '    /**',
            '     * testMethod1',
            '     *',
            '     * @param',
            '     * @return',
            '     * @access    public',
            '     */',
            '    public function testMethod1( param1, param2 = default ) {',
            '',
            '    }',
            ''
        );

        $this->assertEquals(implode("\n", $code), $render);


        $paramsConf = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_Util_MixedCollection(array(
                'paramName' => 'param1',
                'value' => null
            ))
        ));

        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'methodName' => 'testMethod2',
            'access' => 'protected',
            'static' => true,
            'abstract' => true,
            'final' => true,
            'params' => $paramsConf,
            'blockCode' => '',
            'parentCall' => true,
            'doc' => new xFrameworkPX_Util_MixedCollection(array(
                'shortDesc' => 'testMethod2',
                'longDesc' => 'this method is final, static',
                'tags' => new xFrameworkPX_Util_MixedCollection(array(
                    'param' => 'string $param1',
                    'return' => 'void',
                    'access' => 'protected'
                ))
            ))
        ));

        $method = new xFrameworkPX_CodeGenerator_Php_Method($conf);
        $render = $method->render();

        $code = array(
            '',
            '    /**',
            '     * testMethod2',
            '     *',
            '     * this method is final, static',
            '     *',
            '     * @param     string $param1',
            '     * @return    void',
            '     * @access    protected',
            '     */',
            '    final protected static function testMethod2( param1 = null )'
            . ' {',
            '',
            '        parent::testMethod2();',
            '',
            '    }',
            ''
        );

        $this->assertEquals(implode("\n", $code), $render);


        $paramsConf = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_Util_MixedCollection(array(
                'paramName' => 'param1',
                'value' => null
            ))
        ));

        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'methodName' => 'testMethod3',
            'access' => 'protected',
            'static' => true,
            'abstract' => true,
            'final' => false,
            'params' => $paramsConf,
            'blockCode' => '',
            'parentCall' => true,
            'parentParams' => null
        ));

        $method = new xFrameworkPX_CodeGenerator_Php_Method($conf);
        $render = $method->render();

        $code = array(
            '',
            '    /**',
            '     * testMethod3',
            '     *',
            '     * @param',
            '     * @return',
            '     * @access    protected',
            '     */',
            '    abstract protected function testMethod3( param1 = null );',
            ''
        );

        $this->assertEquals(implode("\n", $code), $render);


        $paramsConf = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_Util_MixedCollection(array(
                'paramName' => 'param1',
                'value' => null
            ))
        ));

        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'methodName' => 'testMethod4',
            'access' => 'protected',
            'static' => true,
            'abstract' => false,
            'final' => false,
            'params' => $paramsConf,
            'blockCode' => '',
            'parentCall' => true,
            'parentParams' => new xFrameworkPX_Util_MixedCollection()
        ));

        $method = new xFrameworkPX_CodeGenerator_Php_Method($conf);
        $render = $method->render();

        $code = array(
            '',
            '    /**',
            '     * testMethod4',
            '     *',
            '     * @param',
            '     * @return',
            '     * @access    protected',
            '     */',
            '    protected static function testMethod4( param1 = null ) {',
            '',
            '        parent::testMethod4();',
            '',
            '    }',
            ''
        );

        $this->assertEquals(implode("\n", $code), $render);


        $paramsConf = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_Util_MixedCollection(array(
                'paramName' => 'param1',
                'value' => null
            ))
        ));

        $parentParamsConf = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_Util_MixedCollection(array(
                'paramName' => 'pParam1',
                'value' => new stdClass
            )),

            new xFrameworkPX_Util_MixedCollection(array(
                'paramName' => 'pParam2',
                'value' => true
            ))
        ));

        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'methodName' => 'testMethod5',
            'access' => 'private',
            'static' => true,
            'abstract' => false,
            'final' => true,
            'params' => $paramsConf,
            'parentCall' => true,
            'parentParams' => $parentParamsConf
        ));

        $method = new xFrameworkPX_CodeGenerator_Php_Method($conf);
        $render = $method->render();

        $code = array(
            '',
            '    /**',
            '     * testMethod5',
            '     *',
            '     * @param',
            '     * @return',
            '     * @access    private',
            '     */',
            '    final private static function testMethod5( param1 = null ) {',
            '',
            '        parent::testMethod5( pParam1, pParam2 = true );',
            '',
            '    }',
            ''
        );

        $this->assertEquals(implode("\n", $code), $render);


        $paramsConf = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_Util_MixedCollection(array(
                'paramName' => 'param1',
                'value' => null
            ))
        ));

        $parentParamsConf = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_Util_MixedCollection(array(
                'paramName' => 'pParam1',
                'value' => new stdClass
            ))
        ));

        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'methodName' => 'testMethod6',
            'access' => '',
            'static' => false,
            'abstract' => true,
            'final' => true,
            'params' => $paramsConf,
            'blockCode' => null,
            'parentCall' => true,
            'parentParams' => $parentParamsConf
        ));

        $method = new xFrameworkPX_CodeGenerator_Php_Method($conf);
        $render = $method->render();

        $code = array(
            '',
            '    /**',
            '     * testMethod6',
            '     *',
            '     * @param',
            '     * @return',
            '     * @access',
            '     */',
            '    final function testMethod6( param1 = null ) {',
            '',
            '        parent::testMethod6( pParam1 );',
            '',
            '    }',
            ''
        );

        $this->assertEquals(implode("\n", $code), $render);


        $paramsConf = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_Util_MixedCollection(array(
                'paramName' => 'param1',
                'value' => null
            ))
        ));

        $parentParamsConf = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_Util_MixedCollection(array(
                'paramName' => 'pParam1',
                'value' => new stdClass
            )),

            new xFrameworkPX_Util_MixedCollection(array(
                'paramName' => 'pParam2',
                'value' => false
            ))
        ));

        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'methodName' => 'testMethod7',
            'access' => 'public',
            'static' => false,
            'abstract' => true,
            'final' => false,
            'params' => $paramsConf,
            'blockCode' => "test_code1\n    test_code2\n\ntest_code3",
            'parentCall' => false,
            'parentParams' => $parentParamsConf
        ));

        $method = new xFrameworkPX_CodeGenerator_Php_Method($conf);
        $render = $method->render();

        $code = array(
            '',
            '    /**',
            '     * testMethod7',
            '     *',
            '     * @param',
            '     * @return',
            '     * @access    public',
            '     */',
            '    abstract public function testMethod7( param1 = null );',
            ''
        );

        $this->assertEquals(implode("\n", $code), $render);


        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'methodName' => 'testMethod8',
            'access' => 'public',
            'static' => false,
            'abstract' => false,
            'final' => true,
            'params' => null,
            'blockCode' => "test_code1\n    test_code2\n\ntest_code3",
            'parentCall' => true,
            'parentParams' => new xFrameworkPX_Util_MixedCollection()
        ));

        $method = new xFrameworkPX_CodeGenerator_Php_Method($conf);
        $render = $method->render();

        $code = array(
            '',
            '    /**',
            '     * testMethod8',
            '     *',
            '     * @param',
            '     * @return',
            '     * @access    public',
            '     */',
            '    final public function testMethod8() {',
            '',
            '        parent::testMethod8();',
            '',
            '        test_code1',
            '            test_code2',
            '',
            '        test_code3',
            '',
            '    }',
            ''
        );

        $this->assertEquals(implode("\n", $code), $render);

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
