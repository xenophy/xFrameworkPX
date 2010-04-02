<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_CodeGenerator_Php_FileTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: FileTest.php 936 2009-12-25 01:48:14Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Generator.php';
require_once 'xFrameworkPX/CodeGenerator/Php/File.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Class.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Doc.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Method.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Props.php';
require_once 'xFrameworkPX/CodeGenerator/Php/FileDoc.php';
require_once 'xFrameworkPX/CodeGenerator/Php/ClassDoc.php';
require_once 'xFrameworkPX/CodeGenerator/Php/MethodDoc.php';
require_once 'xFrameworkPX/CodeGenerator/Php/PropsDoc.php';

require_once 'xFrameworkPX/Util/MixedCollection.php';

require_once 'xFrameworkPX/Exception.php';
require_once 'xFrameworkPX/CodeGenerator/Exception.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Exception.php';

// }}}
// {{{ xFrameworkPX_CodeGenerator_Php_FileTest

/**
 * xFrameworkPX_CodeGenerator_Php_FileTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_CodeGenerator_Php_FileTest
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
            new xFrameworkPX_CodeGenerator_Php_File();
        } catch (Exception $ex) {
            $this->assertTrue(true);
        }


        // 引数にNULL
        try {
            new xFrameworkPX_CodeGenerator_Php_File(null);
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                'File Name is Undefined.',
                $ex->getMessage()
            );
        }


        // 引数に空文字
        try {
            new xFrameworkPX_CodeGenerator_Php_File('');
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                'File Name is Undefined.',
                $ex->getMessage()
            );
        }



        // 引数に空の設定オブジェクト
        try {
            new xFrameworkPX_CodeGenerator_Php_File(
                new xFrameworkPX_Util_MixedCollection()
            );
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                'File Name is Undefined.',
                $ex->getMessage()
            );
        }


        // ファイル名 NULL
        try {
            new xFrameworkPX_CodeGenerator_Php_File(
                new xFrameworkPX_Util_MixedCollection(array(
                    'clsName' => null
                ))
            );
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                'File Name is Undefined.',
                $ex->getMessage()
            );
        }


        // ファイル名 空文字
        try {
            new xFrameworkPX_CodeGenerator_Php_File(
                new xFrameworkPX_Util_MixedCollection(array(
                    'clsName' => ''
                ))
            );
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                'File Name is Undefined.',
                $ex->getMessage()
            );
        }


        // ファイル名以外の設定なし
        $conf = null;

        try {
            $conf = new xFrameworkPX_Util_MixedCollection(array(
                'filename' => 'File Name'
            ));
            new xFrameworkPX_CodeGenerator_Php_File($conf);
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                sprintf('Type "%s" is invalid type.', $conf->type),
                $ex->getMessage()
            );
        }


        // ファイル名以外の設定NULL
        try {
            $conf = new xFrameworkPX_Util_MixedCollection(array(
                'filename' => 'File Name',
                'clsName' => null,
                'type' => null,
                'doc' => null
            ));
            new xFrameworkPX_CodeGenerator_Php_File($conf);
        } catch ( xFrameworkPX_CodeGenerator_Php_Exception $ex ) {
            $this->assertEquals(
                sprintf('Type "%s" is invalid type.', $conf->type),
                $ex->getMessage()
            );
        }


        // ファイル名以外の設定空文字
        try {
            $conf = new xFrameworkPX_Util_MixedCollection(array(
                'filename' => 'File Name',
                'clsName' => '',
                'type' => '',
                'doc' => ''
            ));
            new xFrameworkPX_CodeGenerator_Php_File($conf);
        } catch ( xFrameworkPX_CodeGenerator_Php_Exception $ex ) {
            $this->assertEquals(
                sprintf('Type "%s" is invalid type.', $conf->type),
                $ex->getMessage()
            );
        }


        // ファイル名とクラス名以外の設定なし
        try {
            $conf = new xFrameworkPX_Util_MixedCollection(array(
                'filename' => 'File Name',
                'clsName' => 'Class Name'
            ));
            new xFrameworkPX_CodeGenerator_Php_File($conf);
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                sprintf('Type "%s" is invalid type.', $conf->type),
                $ex->getMessage()
            );
        }


        // ファイル名とクラス名以外の設定NULL
        try {
            $conf = new xFrameworkPX_Util_MixedCollection(array(
                'filename' => 'File Name',
                'clsName' => 'Class Name',
                'type' => null,
                'doc' => null
            ));
            new xFrameworkPX_CodeGenerator_Php_File($conf);
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                sprintf('Type "%s" is invalid type.', $conf->type),
                $ex->getMessage()
            );
        }


        // ファイル名とクラス名以外の設定空文字
        try {
            $conf = new xFrameworkPX_Util_MixedCollection(array(
                'filename' => 'File Name',
                'clsName' => 'Class Name',
                'type' => '',
                'doc' => ''
            ));
            new xFrameworkPX_CodeGenerator_Php_File($conf);
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                sprintf('Type "%s" is invalid type.', $conf->type),
                $ex->getMessage()
            );
        }


        // ファイル名とクラスタイプ以外の設定なし
        try {
            new xFrameworkPX_CodeGenerator_Php_File(
                new xFrameworkPX_Util_MixedCollection(array(
                    'filename' => 'File Name',
                    'type' => 'class'
                ))
            );
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                'Class Name is Undefined.',
                $ex->getMessage()
            );
        }


        // ファイル名とクラスタイプ以外の設定なし(タイプの設定が規定値以外)
        try {
            new xFrameworkPX_CodeGenerator_Php_File(
                new xFrameworkPX_Util_MixedCollection(array(
                    'filename' => 'File Name',
                    'type' => 'method',
                    'clsName' => '',
                    'doc' => ''
                ))
            );
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                'Type "method" is invalid type.',
                $ex->getMessage()
            );
        }


        // ファイル名とクラスタイプ以外の設定NULL
        try {
            new xFrameworkPX_CodeGenerator_Php_File(
                new xFrameworkPX_Util_MixedCollection(array(
                    'filename' => 'File Name',
                    'type' => 'class',
                    'clsName' => null,
                    'doc' => null
                ))
            );
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                'Class Name is Undefined.',
                $ex->getMessage()
            );
        }


        // ファイル名とクラスタイプ以外の設定空文字
        try {
            new xFrameworkPX_CodeGenerator_Php_File(
                new xFrameworkPX_Util_MixedCollection(array(
                    'filename' => 'File Name',
                    'type' => 'class',
                    'clsName' => '',
                    'doc' => ''
                ))
            );
        } catch (xFrameworkPX_CodeGenerator_Php_Exception $ex) {
            $this->assertEquals(
                'Class Name is Undefined.',
                $ex->getMessage()
            );
        }


        // ドキュメントの設定なし
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'filename' => 'File Name',
            'clsName' => 'Class Name',
            'type' => 'class'
        ));

        $file = new xFrameworkPX_CodeGenerator_Php_File($conf);

        $this->assertAttributeEquals($conf->filename, '_name', $file);

        $writer = new xFrameworkPX_CodeGenerator_Php_Class(
            new xFrameworkPX_Util_MixedCollection(array(
                'clsName' => $conf->clsName
            ))
        );
        $this->assertAttributeEquals($writer, '_writer', $file);

        $doc = new xFrameworkPX_CodeGenerator_Php_FileDoc(
            new xFrameworkPX_Util_MixedCollection()
        );
        $this->assertAttributeEquals($doc, '_doc', $file);


        // ドキュメントの設定null
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'filename' => 'File Name',
            'clsName' => 'Class Name',
            'type' => 'class',
            'doc' => null
        ));

        $file = new xFrameworkPX_CodeGenerator_Php_File($conf);

        $this->assertAttributeEquals($conf->filename, '_name', $file);

        $writer = new xFrameworkPX_CodeGenerator_Php_Class(
            new xFrameworkPX_Util_MixedCollection(array(
                'clsName' => $conf->clsName
            ))
        );
        $this->assertAttributeEquals($writer, '_writer', $file);

        $doc = new xFrameworkPX_CodeGenerator_Php_FileDoc(
            new xFrameworkPX_Util_MixedCollection()
        );
        $this->assertAttributeEquals($doc, '_doc', $file);


        // ドキュメントの設定空文字
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'filename' => 'File Name',
            'clsName' => 'Class Name',
            'type' => 'class',
            'doc' => null
        ));

        $file = new xFrameworkPX_CodeGenerator_Php_File($conf);

        $this->assertAttributeEquals($conf->filename, '_name', $file);

        $writer = new xFrameworkPX_CodeGenerator_Php_Class(
            new xFrameworkPX_Util_MixedCollection(array(
                'clsName' => $conf->clsName
            ))
        );
        $this->assertAttributeEquals($writer, '_writer', $file);

        $doc = new xFrameworkPX_CodeGenerator_Php_FileDoc(
            new xFrameworkPX_Util_MixedCollection()
        );
        $this->assertAttributeEquals($doc, '_doc', $file);


        // 全項目設定
        $propsConf = new xFrameworkPX_Util_MixedCollection(array(
            'testProp1' => new xFrameworkPX_Util_MixedCollection(array(
                'access' => 'public',
                'value' => 'testValue1'
            )),

            'testProp2' => new xFrameworkPX_Util_MixedCollection(array(
                'access' => 'protected',
                'value' => null
            )),
        ));

        $docConf = new xFrameworkPX_Util_MixedCollection(array(
            'shortDesc' => 'testName',
            'longDesc' => 'this file is "testName" class file.',
            'tags' => new xFrameworkPX_Util_MixedCollection()
        ));

        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'type' => 'class',
            'clsName' => 'testName',
            'filename' => dirname(__FILE__) . '/_files/testFileName.php',
            'props' => $propsConf,
            'doc' => $docConf
        ));

        $file = new xFrameworkPX_CodeGenerator_Php_File($conf);

        $this->assertAttributeEquals($conf->filename, '_name', $file);

        $class = new xFrameworkPX_CodeGenerator_Php_Class($conf);
        $this->assertAttributeEquals($class, '_writer', $file);

        $doc = new xFrameworkPX_CodeGenerator_Php_FileDoc($docConf);
        $this->assertAttributeEquals($doc, '_doc', $file);

        //}}}
    }

    // }}}
    // {{{ testGetWriter

    /**
     * getWriterテスト
     *
     * @return void
     */
    public function testGetWriter()
    {

        $props = new xFrameworkPX_Util_MixedCollection(array(
            'testProp1' => new xFrameworkPX_Util_MixedCollection(array(
                'access' => 'public',
                'value' => 'testValue1'
            )),

            'testProp2' => new xFrameworkPX_Util_MixedCollection(array(
                'access' => 'protected',
                'value' => null
            )),
        ));

        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'type' => 'class',
            'clsName' => 'testName',
            'filename' => dirname(__FILE__) . '/_files/testFileName.php',
            'props' => $props
        ));

        $file = new xFrameworkPX_CodeGenerator_Php_File($conf);

        // {{{ Writer取得テスト

        $writer = $file->getWriter();
        $this->assertAttributeEquals('testName', '_name', $writer);

        $this->assertAttributeEquals('', '_parentCls', $writer);

        $props = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_CodeGenerator_Php_Props(
                new xFrameworkPX_Util_MixedCollection(array(
                    'propsName' => 'testProp1',
                    'access' => 'public',
                    'value' => 'testValue1'
                ))
            ),

            new xFrameworkPX_CodeGenerator_Php_Props(
                new xFrameworkPX_Util_MixedCollection(array(
                    'propsName' => 'testProp2',
                    'access' => 'protected',
                    'value' => null
                ))
            )
        ));

        $this->assertAttributeEquals($props, '_props', $writer);

        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection,
            '_method',
            $writer
        );

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

        $props = new xFrameworkPX_Util_MixedCollection(array(
            'testProp1' => new xFrameworkPX_Util_MixedCollection(array(
                'access' => 'public',
                'value' => 'testValue1'
            )),

            'testProp2' => new xFrameworkPX_Util_MixedCollection(array(
                'access' => 'protected',
                'value' => null
            )),
        ));

        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'type' => 'class',
            'clsName' => 'testName',
            'filename' => dirname(__FILE__) . '/_files/testFileName.php',
            'props' => $props
        ));

        $file = new xFrameworkPX_CodeGenerator_Php_File($conf);

        $file->render();
        $this->assertFileExists(
            dirname(__FILE__) . '/_files/testFileName.php'
        );
        $render = file_get_contents(
            dirname(__FILE__) . '/_files/testFileName.php'
        );

        $code = array(
            '<?php',
            '',
            '/**',
            ' *',
            ' *',
            ' * @filesource',
            ' * @copyright',
            ' * @link',
            ' * @package',
            ' * @since',
            ' * @version',
            ' * @license',
            ' */',
            '',
            '/**',
            ' * testName',
            ' *',
            ' * @copyright',
            ' * @link',
            ' * @package',
            ' * @since',
            ' * @version',
            ' * @license',
            ' */',
            'class testName {',
            '',
            '    /**',
            '     * testProp1',
            '     *',
            '     * @var',
            '     * @access',
            '     */',
            '    public $testProp1 = testValue1;',
            '',
            '    /**',
            '     * testProp2',
            '     *',
            '     * @var',
            '     * @access',
            '     */',
            '    protected $testProp2 = null;',
            '',
            '',
            '}',
            '',
            '?>',
            ''
        );

        $this->assertEquals(implode("\n", $code), $render);

        unlink(dirname(__FILE__) . '/_files/testFileName.php');


        $props = new xFrameworkPX_Util_MixedCollection(array(
            'testProp1' => new xFrameworkPX_Util_MixedCollection(array(
                                'access' => 'public',
                                'value' => 'testValue1'
                            )),

            'testProp2' => new xFrameworkPX_Util_MixedCollection(array(
                                'access' => 'protected',
                                'value' => null
                            )),
        ));

        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'type' => 'class',
            'clsName' => 'testName',
            'filename' => dirname(__FILE__) . '/_files/testFileName.php',
            'props' => $props
        ) );

        $conf->clsName = 'SubTest';
        $file = new xFrameworkPX_CodeGenerator_Php_File($conf);
        $class = $file->getWriter();
        $class->setParent('Test');
        $class->setReflectionMember();

        $code = array(
            '<?php',
            '',
            '/**',
            ' *',
            ' *',
            ' * @filesource',
            ' * @copyright',
            ' * @link',
            ' * @package',
            ' * @since',
            ' * @version',
            ' * @license',
            ' */',
            '',
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
            '     * testProp1',
            '     *',
            '     * @var',
            '     * @access',
            '     */',
            '    public $testProp1 = testValue1;',
            '',
            '    /**',
            '     * testProp2',
            '     *',
            '     * @var',
            '     * @access',
            '     */',
            '    protected $testProp2 = null;',
            '',
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
            '?>',
            ''
        );

        $file->render();
        $this->assertFileExists(
            dirname(__FILE__) . '/_files/testFileName.php'
        );
        $render = file_get_contents(
            dirname(__FILE__) . '/_files/testFileName.php'
        );
        $this->assertEquals(implode("\n", $code), $render);

        unlink(dirname(__FILE__) . '/_files/testFileName.php');

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
