<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_CodeGenerator_PhpTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_CodeGenerator
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: PhpTest.php 882 2009-12-23 09:36:22Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'xFrameworkPX/CodeGenerator/Core.php';
require_once 'xFrameworkPX/CodeGenerator/Php.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Generator.php';
require_once 'xFrameworkPX/CodeGenerator/Php/File.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Doc.php';
require_once 'xFrameworkPX/CodeGenerator/Php/FileDoc.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Class.php';
require_once 'xFrameworkPX/CodeGenerator/Php/ClassDoc.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Method.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Props.php';
require_once 'xFrameworkPX/CodeGenerator/Php/PropsDoc.php';

require_once 'xFrameworkPX/Util/MixedCollection.php';

require_once 'xFrameworkPX/Exception.php';
require_once 'xFrameworkPX/CodeGenerator/Exception.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Exception.php';

// }}}
// {{{ xFrameworkPX_CodeGenerator_PhpTest

/**
 * xFrameworkPX_CodeGenerator_PhpTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_CodeGenerator
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_CodeGenerator_PhpTest extends PHPUnit_Framework_TestCase
{

    // {{{ setUp

    /**
     * セットアップ
     *
     * @return void
     */
    public function setUp()
    {

    }

    // }}}
    // {{{ tearDown

    /**
     * 終了処理
     *
     * @return void
     */
    public function tearDown()
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

        $php = new xFrameworkPX_CodeGenerator_Php();

        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection(),
            '_files',
            $php
        );

        // }}}

    }

    // }}}
    // {{{ testAdd

    /**
     * addテスト
     *
     * @return void
     */
    public function testAdd()
    {
        $php = new xFrameworkPX_CodeGenerator_Php();

        $props = new xFrameworkPX_Util_MixedCollection(array(
            'testProp1' => new xFrameworkPX_Util_MixedCollection(array(
                'access' => 'public',
                'value' => 'testValue1'
            )),

            'testProp2' => new xFrameworkPX_Util_MixedCollection(array(
                'access' => 'protected',
                'value' => null
            ))
        ));

        $files = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_Util_MixedCollection(array(
                'type' => 'class',
                'clsName' => 'Test',
                'filename' => './_files/Test.class.php',
                'props' => $props
            ))
        ));

        // {{{ クラスジェネレータ登録テスト

        $php->add($files);

        $filesEx = new xFrameworkPX_Util_MixedCollection(array(
            'Test' =>
            new xFrameworkPX_CodeGenerator_Php_File($files->{0})
        ));

        $this->assertAttributeEquals($filesEx, '_files', $php);

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
        $php = new xFrameworkPX_CodeGenerator_Php();
        $props1 = new xFrameworkPX_Util_MixedCollection(array(
            'testProp1' => new xFrameworkPX_Util_MixedCollection(array(
                'access' => 'public',
                'value' => 'testValue1'
            )),

            'testProp2' => new xFrameworkPX_Util_MixedCollection(array(
                'access' => 'protected',
                'value' => null
            ))
        ) );

        $props2 = new xFrameworkPX_Util_MixedCollection(array(
            'testProp3' => new xFrameworkPX_Util_MixedCollection(array(
                'access' => 'public',
                'value' => 'testValue1'
            )),

            'testProp4' => new xFrameworkPX_Util_MixedCollection(array(
                'access' => 'private',
                'value' => "''"
            ))
        ));

        $files = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_Util_MixedCollection(array(
                'type' => 'class',
                'clsName' => 'Test1',
                'filename' => './_files/Test1.class.php',
                'props' => $props1
            )),

            new xFrameworkPX_Util_MixedCollection(array(
                'type' => 'class',
                'clsName' => 'Test2',
                'filename' => './_files/Test2.class.php',
                'props' => $props2
            ))
        ));

        $php->add($files);

        // {{{ ソースコードレンダリングテスト

        $php->render();

        $this->assertFileExists('./_files/Test1.class.php');
        $code = file_get_contents('./_files/Test1.class.php');
        $source = array(
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
            ' * Test1',
            ' *',
            ' * @copyright',
            ' * @link',
            ' * @package',
            ' * @since',
            ' * @version',
            ' * @license',
            ' */',
            'class Test1 {',
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

        $this->assertEquals(implode("\n", $source), $code);

        $this->assertFileExists('./_files/Test2.class.php');
        $code = file_get_contents('./_files/Test2.class.php');
        $source = array(
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
            ' * Test2',
            ' *',
            ' * @copyright',
            ' * @link',
            ' * @package',
            ' * @since',
            ' * @version',
            ' * @license',
            ' */',
            'class Test2 {',
            '',
            '    /**',
            '     * testProp3',
            '     *',
            '     * @var',
            '     * @access',
            '     */',
            '    public $testProp3 = testValue1;',
            '',
            '    /**',
            '     * testProp4',
            '     *',
            '     * @var',
            '     * @access',
            '     */',
            '    private $testProp4 = \'\';',
            '',
            '',
            '}',
            '',
            '?>',
            ''
        );
        $this->assertEquals(implode("\n", $source), $code);

        unlink('./_files/Test1.class.php');
        unlink('./_files/Test2.class.php');

        // }}}

    }

    // }}}
    // {{{ testGet

    /**
     * getテスト
     *
     * @return void
     */
    public function testGet()
    {
        $php = new xFrameworkPX_CodeGenerator_Php();

        $props1 = new xFrameworkPX_Util_MixedCollection(array(
            'testProp1' => new xFrameworkPX_Util_MixedCollection(array(
                'access' => 'public',
                'value' => 'testValue1'
            )),

            'testProp2' => new xFrameworkPX_Util_MixedCollection(array(
                'access' => 'protected',
                'value' => null
            ))
        ));

        $props2 = new xFrameworkPX_Util_MixedCollection(array(
            'testProp3' => new xFrameworkPX_Util_MixedCollection(array(
                'access' => 'public',
                'value' => 'testValue2'
            )),

            'testProp4' => new xFrameworkPX_Util_MixedCollection(array(
                'access' => 'private',
                'value' => "''"
            ))
        ) );

        $files = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_Util_MixedCollection(array(
                'type' => 'class',
                'clsName' => 'Test1',
                'filename' => './_files/Test.class.php',
                'props' => $props1
            )),

            new xFrameworkPX_Util_MixedCollection(array(
                'type' => 'class',
                'clsName' => 'Test2',
                'filename' => './_files/Test2.class.php',
                'props' => $props2
            ))
        ));

        // {{{ ライターオブジェクト取得テスト

        $php->add($files);

        $writer = $php->get('Test1');

        $this->assertAttributeEquals('Test1', '_name', $writer);
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
            new xFrameworkPX_Util_MixedCollection(),
            '_method',
            $writer
        );


        $writer = $php->get('Test2');

        $this->assertAttributeEquals('Test2', '_name', $writer);
        $this->assertAttributeEquals('', '_parentCls', $writer);

        $props = new xFrameworkPX_Util_MixedCollection(array(
            new xFrameworkPX_CodeGenerator_Php_Props(
                new xFrameworkPX_Util_MixedCollection(array(
                    'propsName' => 'testProp3',
                    'access' => 'public',
                    'value' => 'testValue2'
                ))
            ),

            new xFrameworkPX_CodeGenerator_Php_Props(
                new xFrameworkPX_Util_MixedCollection(array(
                    'propsName' => 'testProp4',
                    'access' => 'private',
                    'value' => "''"
                ))
            )
        ));
        $this->assertAttributeEquals($props, '_props', $writer);
        $this->assertAttributeEquals(
            new xFrameworkPX_Util_MixedCollection(),
            '_method',
            $writer
        );

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
