<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_CodeGenerator_Php_ClassTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: ClassTest.php 936 2009-12-25 01:48:14Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Generator.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Class.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Doc.php';
require_once 'xFrameworkPX/CodeGenerator/Php/ClassDoc.php';
require_once 'xFrameworkPX/CodeGenerator/Php/PropsDoc.php';
require_once 'xFrameworkPX/CodeGenerator/Php/MethodDoc.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Method.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Params.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Props.php';

require_once 'xFrameworkPX/Util/MixedCollection.php';

require_once 'xFrameworkPX/Exception.php';
require_once 'xFrameworkPX/CodeGenerator/Exception.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Exception.php';

// }}}
// {{{ xFrameworkPX_CodeGenerator_Php_ClassTest

/**
 * xFrameworkPX_CodeGenerator_Php_ClassTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_CodeGenerator_Php_ClassTest
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

        // テストで使用するクラスファイル読み込み
        require_once dirname(__FILE__) . '/_files/Test.php';
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
            new xFrameworkPX_CodeGenerator_Php_Class();
        } catch (Exception $ex) {
            $this->assertTrue(true);
        }


        // 引数 NULL
        try {
            new xFrameworkPX_CodeGenerator_Php_Class(null);
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                'Class Name is Undefined.',
                $ex->getMessage()
            );
        }


        // 引数の配列が空
        try {
            new xFrameworkPX_CodeGenerator_Php_Class(
                new xFrameworkPX_Util_MixedCollection()
            );
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                'Class Name is Undefined.',
                $ex->getMessage()
            );
        }


        // クラス名の設定がNULL
        try {
            $conf = new xFrameworkPX_Util_MixedCollection(array(
                'clsName' => null
            ) );

            new xFrameworkPX_CodeGenerator_Php_Class( $conf );
        } catch ( xFrameworkPX_CodeGenerator_Php_Exception $ex ) {
            $this->assertEquals(
                'Class Name is Undefined.',
                $ex->getMessage()
            );
        }


        // クラス名の設定が空文字列
        try {
            $conf = new xFrameworkPX_Util_MixedCollection(array(
                'clsName' => '',
                'access' => 'public'
            ));

            new xFrameworkPX_CodeGenerator_Php_Class($conf);
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                'Class Name is Undefined.',
                $ex->getMessage()
            );
        }


        // クラス名以外の設定なし
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'clsName' => 'Class1'
        ));

        $class = new xFrameworkPX_CodeGenerator_Php_Class($conf);

        $this->assertAttributeEquals($conf->clsName, '_name', $class);

        $this->assertAttributeEquals(false, '_final', $class);

        $this->assertAttributeEquals(false, '_abstract', $class);

        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection(),
            '_props',
            $class
        );

        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection(),
            '_method',
            $class
        );


        // クラス名以外の設定がNULL
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'clsName' => 'Class1',
            'final' => null,
            'abstract' => null,
            'props' => null,
            'method' => null,
            'doc' => null
        ));

        $class = new xFrameworkPX_CodeGenerator_Php_Class($conf);

        $this->assertAttributeEquals($conf->clsName, '_name', $class);

        $this->assertAttributeEquals(false, '_final', $class);

        $this->assertAttributeEquals(false, '_abstract', $class);

        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection(),
            '_props',
            $class
        );

        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection(),
            '_method',
            $class
        );


        // クラス名以外の設定が空文字
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'clsName' => 'Class1',
            'final' => '',
            'abstract' => '',
            'props' => '',
            'method' => '',
            'doc' => ''
        ));

        $class = new xFrameworkPX_CodeGenerator_Php_Class($conf);

        $this->assertAttributeEquals($conf->clsName, '_name', $class);

        $this->assertAttributeEquals(false, '_final', $class);

        $this->assertAttributeEquals(false, '_abstract', $class);

        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection(),
            '_props',
            $class
        );

        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection(),
            '_method',
            $class
        );


        // 全設定あり
        $propsConf = new xFrameworkPX_Util_MixedCollection(array(
            'props1' =>
            new xFrameworkPX_Util_MixedCollection(array(
                'propsName' => 'props1',
                'access' => 'private',
                'value' => '"value1"'
            )),

            'props2' =>
            new xFrameworkPX_Util_MixedCollection(array(
                'propsName' => 'props2',
                'access' => 'public',
                'value' => new stdClass
            )),
        ));

        $methodConf = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_Util_MixedCollection(array(
                'methodName' => 'method1',
                'access' => 'public'
            )),

            new xFrameworkPX_Util_MixedCollection(array(
                'methodName' => 'method2',
                'access' => 'public',
                'params' =>
                new xFrameworkPX_Util_MixedCollection(array(
                    new xFrameworkPX_Util_MixedCollection(array(
                        'paramName' => 'param1',
                        'value' => new stdClass
                    )),

                    new xFrameworkPX_Util_MixedCollection(array(
                        'paramName' => 'param2',
                        'value' => false
                    ))
                ))
            ))
        ));

        $docConf = new xFrameworkPX_Util_MixedCollection(array(
            'shortDesc' => 'Class1 class',
            'longDesc' => 'this is "Class1" class',
            'tags' => new xFrameworkPX_Util_MixedCollection(array(
                'package' => 'xFrameworkPX\tests\CodeGenerator\Php'
            ))
        ));

        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'clsName' => 'Class1',
            'final' => true,
            'abstract' => true,
            'props' => $propsConf,
            'method' => $methodConf,
            'doc' => $docConf
        ));

        $class = new xFrameworkPX_CodeGenerator_Php_Class($conf);

        $this->assertAttributeEquals($conf->clsName, '_name', $class);

        $this->assertAttributeEquals($conf->final, '_final', $class);

        $this->assertAttributeEquals($conf->abstract, '_abstract', $class);

        $props = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_CodeGenerator_Php_Props($propsConf->props1),
            new xFrameworkPX_CodeGenerator_Php_Props($propsConf->props2)
        ));
        $this->assertAttributeEquals($props, '_props', $class);

        $method = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_CodeGenerator_Php_Method($methodConf->{0}),
            new xFrameworkPX_CodeGenerator_Php_Method($methodConf->{1})
        ));
        $this->assertAttributeEquals($method, '_method', $class);

        $doc = new xFrameworkPX_CodeGenerator_Php_ClassDoc($docConf);
        $this->assertAttributeEquals($doc, '_doc', $class);


        // ファイナルとアブストラクト設定 Boolean
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'clsName' => 'Class1',
            'final' => false,
            'abstract' => false,
        ));

        $class = new xFrameworkPX_CodeGenerator_Php_Class($conf);

        $this->assertAttributeEquals($conf->clsName, '_name', $class);

        $this->assertAttributeEquals($conf->final, '_final', $class);

        $this->assertAttributeEquals($conf->abstract, '_abstract', $class);

        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection(),
            '_props',
            $class
        );

        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection(),
            '_method',
            $class
        );

        $docConf = new xFrameworkPX_Util_MixedCollection(array(
            'shortDesc' => 'Class1',

            'longDesc' => '',

            'tags' => new xFrameworkPX_Util_MixedCollection(array(
                'copyright' => '',

                'link' => '',

                'package' => '',

                'since' => '',

                'version' => '',

                'license' => ''
            ))
        ));
        $this->assertAttributeEquals(
            new xFrameworkPX_CodeGenerator_Php_ClassDoc($docConf),
            '_doc',
            $class
        );


        // ファイナルとアブストラクト設定 Boolean以外
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'clsName' => 'Class1',
            'final' => 'true',
            'abstract' => 'true',
        ));

        $class = new xFrameworkPX_CodeGenerator_Php_Class($conf);

        $this->assertAttributeEquals($conf->clsName, '_name', $class);

        $this->assertAttributeEquals(false, '_final', $class);

        $this->assertAttributeEquals(false, '_abstract', $class);

        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection(),
            '_props',
            $class
        );

        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection(),
            '_method',
            $class
        );

        $docConf = new xFrameworkPX_Util_MixedCollection(array(
            'shortDesc' => 'Class1',

            'longDesc' => '',

            'tags' => new xFrameworkPX_Util_MixedCollection(array(
                'copyright' => '',

                'link' => '',

                'package' => '',

                'since' => '',

                'version' => '',

                'license' => ''
            ))
        ));

        $this->assertAttributeEquals(
            new xFrameworkPX_CodeGenerator_Php_ClassDoc($docConf),
            '_doc',
            $class
        );

        //}}}

    }

    // }}}
    // {{{ testSetParent

    /**
     * setParentテスト
     *
     * @return void
     */
    public function testSetParent()
    {

        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'clsName' => 'Class1',
        ));

        $class = new xFrameworkPX_CodeGenerator_Php_Class($conf);

        // {{{ 親クラス設定テスト

        $class->setParent('testParentName');
        $this->assertAttributeEquals('testParentName', '_parentCls', $class);

        $class->setParent('');
        $this->assertAttributeEquals('', '_parentCls', $class);

        // }}}

    }

    // }}}
    // {{{ testSetReflectionMember

    /**
     * setReflectionMemberテスト
     *
     * @return void
     */
    public function testSetReflectionMember()
    {

        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'clsName' => 'Class1',
        ));

        $class = new xFrameworkPX_CodeGenerator_Php_Class($conf);
        $class->setParent('Test');

        // {{{ 関係メンバー生成テスト

        $class->setReflectionMember();
        $this->assertAttributeEquals('Test', '_parentCls', $class);

        $method = new xFrameworkPX_Util_MixedCollection(array(

            'testMethod1' => new xFrameworkPX_CodeGenerator_Php_Method(
                new xFrameworkPX_Util_MixedCollection(array(
                    'methodName' => 'testMethod1',
                    'parentCall' => true
                ))
            ),

            'testMethod2' => new xFrameworkPX_CodeGenerator_Php_Method(
                new xFrameworkPX_Util_MixedCollection(array(
                    'methodName' => 'testMethod2',
                    'parentCall' => false
                ))
            )

        ) );

        $this->assertAttributeEquals($method, '_method', $class);

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

        // 通常クラス
        $propsConf = new xFrameworkPX_Util_MixedCollection(array(
            'props1' =>
            new xFrameworkPX_Util_MixedCollection(array(
                'propsName' => 'props1',
                'access' => 'private',
                'value' => '"value1"'
            )),

            'props2' =>
            new xFrameworkPX_Util_MixedCollection(array(
                'propsName' => 'props2',
                'access' => 'public',
                'value' => new stdClass
            ))
        ));

        $methodConf = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_Util_MixedCollection(array(
                'methodName' => 'method1',
                'access' => 'public'
            )),

            new xFrameworkPX_Util_MixedCollection(array(
                'methodName' => 'method2',
                'access' => 'public',
                'params' =>
                new xFrameworkPX_Util_MixedCollection(array(
                    new xFrameworkPX_Util_MixedCollection(array(
                        'paramName' => 'param1',
                        'value' => new stdClass
                    )),

                    new xFrameworkPX_Util_MixedCollection(array(
                        'paramName' => 'param2',
                        'value' => false
                    ))
                ))
            ))
        ));

        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'clsName' => 'Class1',
            'final' => false,
            'abstract' => false,
            'props' => $propsConf,
            'method' =>$methodConf
        ));

        $class = new xFrameworkPX_CodeGenerator_Php_Class($conf);

        $code = array(
            '/**',
            ' * Class1',
            ' *',
            ' * @copyright',
            ' * @link',
            ' * @package',
            ' * @since',
            ' * @version',
            ' * @license',
            ' */',
            'class Class1 {',
            '',
            '    /**',
            '     * props1',
            '     *',
            '     * @var',
            '     * @access',
            '     */',
            '    private $props1 = "value1";',
            '',
            '    /**',
            '     * props2',
            '     *',
            '     * @var',
            '     * @access',
            '     */',
            '    public $props2;',
            '',
            '',
            '    /**',
            '     * method1',
            '     *',
            '     * @param',
            '     * @return',
            '     * @access    public',
            '     */',
            '    public function method1() {',
            '',
            '    }',
            '',
            '    /**',
            '     * method2',
            '     *',
            '     * @param',
            '     * @return',
            '     * @access    public',
            '     */',
            '    public function method2( param1, param2 = false ) {',
            '',
            '    }',
            '',
            '}'
        );

        $rend = $class->render();

        $this->assertEquals(implode("\n", $code) . "\n", $rend);


        // 継承クラス
        $conf->clsName = 'SubTest';
        $class = new xFrameworkPX_CodeGenerator_Php_Class($conf);
        $class->setParent('Test');
        $class->setReflectionMember();

        $rend = $class->render();

        $code = array(
            '/**',
            ' * SubTest',
            ' *',
            ' * @copyright',
            ' * @link',
            ' * @package',
            ' * @since',
            ' * @version',
            ' * @license',
            ' */',
            'class SubTest extends Test {',
            '',
            '    /**',
            '     * props1',
            '     *',
            '     * @var',
            '     * @access',
            '     */',
            '    private $props1 = "value1";',
            '',
            '    /**',
            '     * props2',
            '     *',
            '     * @var',
            '     * @access',
            '     */',
            '    public $props2;',
            '',
            '',
            '    /**',
            '     * method1',
            '     *',
            '     * @param',
            '     * @return',
            '     * @access    public',
            '     */',
            '    public function method1() {',
            '',
            '    }',
            '',
            '    /**',
            '     * method2',
            '     *',
            '     * @param',
            '     * @return',
            '     * @access    public',
            '     */',
            '    public function method2( param1, param2 = false ) {',
            '',
            '    }',
            '',
            '    /**',
            '     * testMethod1',
            '     *',
            '     * @param',
            '     * @return',
            '     * @access    public',
            '     */',
            '    public function testMethod1() {',
            '',
            '        parent::testMethod1();',
            '',
            '    }',
            '',
            '    /**',
            '     * testMethod2',
            '     *',
            '     * @param',
            '     * @return',
            '     * @access    public',
            '     */',
            '    public function testMethod2() {',
            '',
            '    }',
            '',
            '}',
            '',
        );

        $this->assertEquals(implode("\n", $code), $rend);


        // finalクラス ( final:true & abstract:true )
        $propsConf = new xFrameworkPX_Util_MixedCollection(array(
            'props1' =>
            new xFrameworkPX_Util_MixedCollection(array(
                'propsName' => 'props1',
                'access' => 'private',
                'value' => '"value1"'
            )),

            'props2' =>
            new xFrameworkPX_Util_MixedCollection(array(
                'propsName' => 'props2',
                'access' => 'public',
                'value' => new stdClass
            ))
        ));

        $methodConf = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_Util_MixedCollection(array(
                'methodName' => 'method1',
                'access' => 'public'
            )),

            new xFrameworkPX_Util_MixedCollection(array(
                'methodName' => 'method2',
                'access' => 'public',
                'params' =>
                new xFrameworkPX_Util_MixedCollection(array(
                    new xFrameworkPX_Util_MixedCollection(array(
                        'paramName' => 'param1',
                        'value' => new stdClass
                    )),

                    new xFrameworkPX_Util_MixedCollection(array(
                        'paramName' => 'param2',
                        'value' => false
                    ))
                ))
            ))
        ));

        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'clsName' => 'Class1',
            'final' => true,
            'abstract' => true,
            'props' => $propsConf,
            'method' =>$methodConf
        ));

        $class = new xFrameworkPX_CodeGenerator_Php_Class($conf);

        $code = array(
            '/**',
            ' * Class1',
            ' *',
            ' * @copyright',
            ' * @link',
            ' * @package',
            ' * @since',
            ' * @version',
            ' * @license',
            ' */',
            'final class Class1 {',
            '',
            '    /**',
            '     * props1',
            '     *',
            '     * @var',
            '     * @access',
            '     */',
            '    private $props1 = "value1";',
            '',
            '    /**',
            '     * props2',
            '     *',
            '     * @var',
            '     * @access',
            '     */',
            '    public $props2;',
            '',
            '',
            '    /**',
            '     * method1',
            '     *',
            '     * @param',
            '     * @return',
            '     * @access    public',
            '     */',
            '    public function method1() {',
            '',
            '    }',
            '',
            '    /**',
            '     * method2',
            '     *',
            '     * @param',
            '     * @return',
            '     * @access    public',
            '     */',
            '    public function method2( param1, param2 = false ) {',
            '',
            '    }',
            '',
            '}'
        );

        $rend = $class->render();

        $this->assertEquals(implode("\n", $code) . "\n", $rend);


        // finalクラス ( final:true & abstract:false )
        $propsConf = new xFrameworkPX_Util_MixedCollection(array(
            'props1' =>
            new xFrameworkPX_Util_MixedCollection(array(
                'propsName' => 'props1',
                'access' => 'private',
                'value' => '"value1"'
            )),

            'props2' =>
            new xFrameworkPX_Util_MixedCollection(array(
                'propsName' => 'props2',
                'access' => 'public',
                'value' => new stdClass
            ))
        ));

        $methodConf = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_Util_MixedCollection(array(
                'methodName' => 'method1',
                'access' => 'public'
            )),

            new xFrameworkPX_Util_MixedCollection(array(
                'methodName' => 'method2',
                'access' => 'public',
                'params' =>
                new xFrameworkPX_Util_MixedCollection(array(
                    new xFrameworkPX_Util_MixedCollection(array(
                        'paramName' => 'param1',
                        'value' => new stdClass
                    )),

                    new xFrameworkPX_Util_MixedCollection(array(
                        'paramName' => 'param2',
                        'value' => false
                    ))
                ))
            ))
        ));

        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'clsName' => 'Class1',
            'final' => true,
            'abstract' => false,
            'props' => $propsConf,
            'method' =>$methodConf
        ));

        $class = new xFrameworkPX_CodeGenerator_Php_Class($conf);

        $code = array(
            '/**',
            ' * Class1',
            ' *',
            ' * @copyright',
            ' * @link',
            ' * @package',
            ' * @since',
            ' * @version',
            ' * @license',
            ' */',
            'final class Class1 {',
            '',
            '    /**',
            '     * props1',
            '     *',
            '     * @var',
            '     * @access',
            '     */',
            '    private $props1 = "value1";',
            '',
            '    /**',
            '     * props2',
            '     *',
            '     * @var',
            '     * @access',
            '     */',
            '    public $props2;',
            '',
            '',
            '    /**',
            '     * method1',
            '     *',
            '     * @param',
            '     * @return',
            '     * @access    public',
            '     */',
            '    public function method1() {',
            '',
            '    }',
            '',
            '    /**',
            '     * method2',
            '     *',
            '     * @param',
            '     * @return',
            '     * @access    public',
            '     */',
            '    public function method2( param1, param2 = false ) {',
            '',
            '    }',
            '',
            '}'
        );

        $rend = $class->render();

        $this->assertEquals(implode("\n", $code) . "\n", $rend);


        // abstractクラス ( final:false & abstract:true )
        $propsConf = new xFrameworkPX_Util_MixedCollection(array(
            'props1' =>
            new xFrameworkPX_Util_MixedCollection(array(
                'propsName' => 'props1',
                'access' => 'private',
                'value' => '"value1"'
            )),

            'props2' =>
            new xFrameworkPX_Util_MixedCollection(array(
                'propsName' => 'props2',
                'access' => 'public',
                'value' => new stdClass
            ))
        ));

        $methodConf = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_Util_MixedCollection(array(
                'methodName' => 'method1',
                'access' => 'public'
            )),

            new xFrameworkPX_Util_MixedCollection(array(
                'methodName' => 'method2',
                'access' => 'public',
                'params' =>
                new xFrameworkPX_Util_MixedCollection(array(
                    new xFrameworkPX_Util_MixedCollection(array(
                        'paramName' => 'param1',
                        'value' => new stdClass
                    )),

                    new xFrameworkPX_Util_MixedCollection(array(
                        'paramName' => 'param2',
                        'value' => false
                    ))
                ))
            ))
        ));

        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'clsName' => 'Class1',
            'final' => false,
            'abstract' => true,
            'props' => $propsConf,
            'method' =>$methodConf
        ));

        $class = new xFrameworkPX_CodeGenerator_Php_Class($conf);

        $code = array(
            '/**',
            ' * Class1',
            ' *',
            ' * @copyright',
            ' * @link',
            ' * @package',
            ' * @since',
            ' * @version',
            ' * @license',
            ' */',
            'abstract class Class1 {',
            '',
            '    /**',
            '     * props1',
            '     *',
            '     * @var',
            '     * @access',
            '     */',
            '    private $props1 = "value1";',
            '',
            '    /**',
            '     * props2',
            '     *',
            '     * @var',
            '     * @access',
            '     */',
            '    public $props2;',
            '',
            '',
            '    /**',
            '     * method1',
            '     *',
            '     * @param',
            '     * @return',
            '     * @access    public',
            '     */',
            '    public function method1() {',
            '',
            '    }',
            '',
            '    /**',
            '     * method2',
            '     *',
            '     * @param',
            '     * @return',
            '     * @access    public',
            '     */',
            '    public function method2( param1, param2 = false ) {',
            '',
            '    }',
            '',
            '}'
        );

        $rend = $class->render();

        $this->assertEquals(implode("\n", $code) . "\n", $rend);

        // }}}

    }

    // }}}
    // {{{ testGetDocComment

    /**
     *  getDocCommentテスト
     *
     * @return void
     */
    public function testGetDocComment()
    {

        $class = new xFrameworkPX_CodeGenerator_Php_Class(
            new xFrameworkPX_Util_MixedCollection(array(
                'clsName' => 'DocCommentTest'
            ))
        );

        $doc = array(
            '@tag hoge',
            '@px foo bar',
            'abcd',
        );
        $doc = implode("\n", $doc);

        $this->assertEquals($doc, $class->getDocComment($doc));

        $ret = array(
            array("@px foo bar\n"),
            array(" foo bar"),
            array("\n")
        );

        $this->assertEquals($ret, $class->getDocComment($doc, '@px'));

        $this->assertNull($class->getDocComment($doc, '@return'));
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
