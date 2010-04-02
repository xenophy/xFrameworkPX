<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_CodeGenerator_Php_FileDocTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: FileDocTest.php 882 2009-12-23 09:36:22Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Generator.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Doc.php';
require_once 'xFrameworkPX/CodeGenerator/Php/FileDoc.php';

require_once 'xFrameworkPX/Util/MixedCollection.php';

// }}}
// {{{ xFrameworkPX_CodeGenerator_Php_FileDocTest

/**
 * xFrameworkPX_CodeGenerator_Php_FileDocTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_CodeGenerator_Php_FileDocTest
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
            new xFrameworkPX_CodeGenerator_Php_FileDoc();
        } catch (Exception $ex) {
            $this->assertTrue(true);
        }


        // 引数NULL
        $doc = new xFrameworkPX_CodeGenerator_Php_FileDoc(null);

        $this->assertAttributeEquals('', '_shortDesc', $doc);
        $this->assertAttributeEquals('', '_longDesc', $doc);
        $tags = new xFrameworkPX_Util_MixedCollection(array(
            'filesource' => '',

            'copyright' => '',

            'link' => '',

            'package' => '',

            'since' => '',

            'version' => '',

            'license' => ''
        ));
        $this->assertAttributeEquals($tags, '_tags', $doc);


        // xFrameworkPX_Util_MixedCollectionの中が空
        $doc = new xFrameworkPX_CodeGenerator_Php_FileDoc(
            new xFrameworkPX_Util_MixedCollection()
        );

        $this->assertAttributeEquals('', '_shortDesc', $doc);
        $this->assertAttributeEquals('', '_longDesc', $doc);
        $tags = new xFrameworkPX_Util_MixedCollection(array(
            'filesource' => '',

            'copyright' => '',

            'link' => '',

            'package' => '',

            'since' => '',

            'version' => '',

            'license' => ''
        ));
        $this->assertAttributeEquals($tags, '_tags', $doc);


        // xFrameworkPX_Util_MixedCollectionの各要素がnull
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'shortDesc' => null,
            'longDesc' => null,
            'tags' => null
        ));

        $doc = new xFrameworkPX_CodeGenerator_Php_FileDoc($conf);

        $this->assertAttributeEquals('', '_shortDesc', $doc);
        $this->assertAttributeEquals('', '_longDesc', $doc);
        $tags = new xFrameworkPX_Util_MixedCollection(array(
            'filesource' => '',

            'copyright' => '',

            'link' => '',

            'package' => '',

            'since' => '',

            'version' => '',

            'license' => ''
        ));
        $this->assertAttributeEquals($tags, '_tags', $doc);


        // xFrameworkPX_Util_MixedCollectionの各要素が空文字
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'shortDesc' => '',
            'longDesc' => '',
            'tags' => ''
        ));

        $doc = new xFrameworkPX_CodeGenerator_Php_FileDoc($conf);

        $this->assertAttributeEquals('', '_shortDesc', $doc);
        $this->assertAttributeEquals('', '_longDesc', $doc);
        $tags = new xFrameworkPX_Util_MixedCollection(array(
            'filesource' => '',

            'copyright' => '',

            'link' => '',

            'package' => '',

            'since' => '',

            'version' => '',

            'license' => ''
        ));
        $this->assertAttributeEquals($tags, '_tags', $doc);


        // 各プロパティ設定
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'shortDesc' => 'test class',
            'longDesc' => 'this is test class.',
            'tags' =>
            new xFrameworkPX_Util_MixedCollection(array(
                'package' => 'xFrameworkPX\tests',
                'author' => 'test'
            ))
        ));

        $doc = new xFrameworkPX_CodeGenerator_Php_FileDoc($conf);

        $this->assertAttributeEquals(
            $conf->shortDesc,
            '_shortDesc',
            $doc
        );
        $this->assertAttributeEquals(
            $conf->longDesc,
            '_longDesc',
            $doc
        );
        $tags = new xFrameworkPX_Util_MixedCollection(array(
            'filesource' => '',

            'copyright' => '',

            'link' => '',

            'package' => 'xFrameworkPX\tests',

            'since' => '',

            'version' => '',

            'license' => '',

            'author' => 'test'
        ));
        $this->assertAttributeEquals($tags, '_tags', $doc);

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

        // 全項目にNULLを設定
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'shortDesc' => null,
            'longDesc' => null,
            'tags' =>
            new xFrameworkPX_Util_MixedCollection(array(
                'filesource' => null,
                'copyright' => null,
                'link' => null,
                'package' => null,
                'since' => null,
                'version' => null,
                'license' => null
            ))
        ));

        $doc = new xFrameworkPX_CodeGenerator_Php_FileDoc($conf);
        $render = $doc->render();

        $code = array(
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
            ''
        );

        $this->assertEquals(implode("\n", $code), $render);

        // 全項目に空文字を設定
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'shortDesc' => '',
            'longDesc' => '',
            'tags' =>
            new xFrameworkPX_Util_MixedCollection(array(
                'filesource' => '',
                'copyright' => '',
                'link' => '',
                'package' => '',
                'since' => '',
                'version' => '',
                'license' => ''
            ))
        ));

        $doc = new xFrameworkPX_CodeGenerator_Php_FileDoc($conf);
        $render = $doc->render();

        $code = array(
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
            ''
        );

        $this->assertEquals(implode("\n", $code), $render);


        // 全項目に設定
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'shortDesc' => 'test class',
            'longDesc' => 'this is test class.',
            'tags' =>
            new xFrameworkPX_Util_MixedCollection(array(
                'filesource' => 'php',
                'version' => 'ver 1.0',
                'package' => 'xFrameworkPX\tests',
                'author' => 'test',
                'access' => 'public',
                'copyright' =>
                '(c) 2006 - 2009 Xenophy CO., LTD.(http://www.xenophy.com)',
                'link' => 'http://www.xframeworkpx.com xFrameworkPX',
                'since' => 'xFrameworkPX 3.5.0',
                'return' => 'mixed testParam',
                'license' =>
                'http://www.opensource.org/licenses/mit-license.php'
            ))
        ));

        $doc = new xFrameworkPX_CodeGenerator_Php_FileDoc($conf);
        $render = $doc->render();

        $code = array(
            '/**',
            ' * test class',
            ' *',
            ' * this is test class.',
            ' *',
            ' * @filesource     php',
            ' * @copyright      '
            .'(c) 2006 - 2009 Xenophy CO., LTD.(http://www.xenophy.com)',
            ' * @link           http://www.xframeworkpx.com xFrameworkPX',
            ' * @package        xFrameworkPX\tests',
            ' * @since          xFrameworkPX 3.5.0',
            ' * @version        ver 1.0',
            ' * @license        '
            .'http://www.opensource.org/licenses/mit-license.php',
            ' */',
            ''
        );

        $this->assertEquals(implode("\n", $code), $render);


        // LongDescriptionに複数行設定
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'shortDesc' => 'test class',
            'longDesc' => 'this is test class.\nhoge hoge\n\nfoo bar',
            'tags' =>
            new xFrameworkPX_Util_MixedCollection(array(
                'filesource' => '',
                'version' => 'ver 1.0',
                'package' => 'xFrameworkPX\tests',
                'author' => 'test',
                'access' => 'public',
                'var' => 'string',
                'param' => 'mixed testParam',
                'return' => 'boolean'
            ))
        ));

        $doc = new xFrameworkPX_CodeGenerator_Php_FileDoc($conf);
        $render = $doc->render();

        $code = array(
            '/**',
            ' * test class',
            ' *',
            ' * this is test class.',
            ' * hoge hoge',
            ' *',
            ' * foo bar',
            ' *',
            ' * @filesource',
            ' * @copyright',
            ' * @link',
            ' * @package        xFrameworkPX\tests',
            ' * @since',
            ' * @version        ver 1.0',
            ' * @license',
            ' */',
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
