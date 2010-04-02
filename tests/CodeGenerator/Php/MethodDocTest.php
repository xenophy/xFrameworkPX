<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_CodeGenerator_Php_MethodDocTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: MethodDocTest.php 882 2009-12-23 09:36:22Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Generator.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Doc.php';
require_once 'xFrameworkPX/CodeGenerator/Php/MethodDoc.php';

require_once 'xFrameworkPX/Util/MixedCollection.php';

// }}}
// {{{ xFrameworkPX_CodeGenerator_Php_MethodDocTest

/**
 * xFrameworkPX_CodeGenerator_Php_MethodDocTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_CodeGenerator_Php_MethodDocTest
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
            new xFrameworkPX_CodeGenerator_Php_MethodDoc();
        } catch (Exception $ex) {
            $this->assertTrue(true);
        }


        // 引数NULL
        $doc = new xFrameworkPX_CodeGenerator_Php_MethodDoc(null);

        $this->assertAttributeEquals('', '_shortDesc', $doc);
        $this->assertAttributeEquals('', '_longDesc', $doc);
        $tags = new xFrameworkPX_Util_MixedCollection(array(
            'param' => '',
            'return' => '',
            'access' => ''
        ));
        $this->assertAttributeEquals($tags, '_tags', $doc);


        // xFrameworkPX_Util_MixedCollectionの中が空
        $doc = new xFrameworkPX_CodeGenerator_Php_MethodDoc(
            new xFrameworkPX_Util_MixedCollection()
        );

        $this->assertAttributeEquals('', '_shortDesc', $doc);
        $this->assertAttributeEquals('', '_longDesc', $doc);
        $tags = new xFrameworkPX_Util_MixedCollection(array(
            'param' => '',
            'return' => '',
            'access' => ''
        ));
        $this->assertAttributeEquals($tags, '_tags', $doc);


        // xFrameworkPX_Util_MixedCollectionの各要素がnull
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'shortDesc' => null,
            'longDesc' => null,
            'tags' => null
        ));

        $doc = new xFrameworkPX_CodeGenerator_Php_MethodDoc($conf);

        $this->assertAttributeEquals('', '_shortDesc', $doc);
        $this->assertAttributeEquals('', '_longDesc', $doc);
        $tags = new xFrameworkPX_Util_MixedCollection(array(
            'param' => '',
            'return' => '',
            'access' => ''
        ));
        $this->assertAttributeEquals($tags, '_tags', $doc);


        // xFrameworkPX_Util_MixedCollectionの各要素が空文字
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'shortDesc' => '',
            'longDesc' => '',
            'tags' => ''
        ));

        $doc = new xFrameworkPX_CodeGenerator_Php_MethodDoc($conf);

        $this->assertAttributeEquals('', '_shortDesc', $doc);
        $this->assertAttributeEquals('', '_longDesc', $doc);
        $tags = new xFrameworkPX_Util_MixedCollection(array(
            'param' => '',
            'return' => '',
            'access' => ''
        ));
        $this->assertAttributeEquals($tags, '_tags', $doc);


        // 各プロパティ設定
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'shortDesc' => 'test method',
            'longDesc' => 'this is test method.',
            'tags' =>
            new xFrameworkPX_Util_MixedCollection(array(
                'param' => 'string $parameter',
                'return' => 'void',
                'access' => 'private'
            ))
        ));

        $doc = new xFrameworkPX_CodeGenerator_Php_MethodDoc($conf);

        $this->assertAttributeEquals($conf->shortDesc, '_shortDesc', $doc);
        $this->assertAttributeEquals($conf->longDesc, '_longDesc', $doc);
        $tags = new xFrameworkPX_Util_MixedCollection(array(
            'param' => 'string $parameter',
            'return' => 'void',
            'access' => 'private'
        ));
        $this->assertAttributeEquals($tags, '_tags', $doc);


        // パラメータ複数設定
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'shortDesc' => 'test method',
            'longDesc' => 'this is test method.',
            'tags' =>
            new xFrameworkPX_Util_MixedCollection(array(
                'param' => 
                    new xFrameworkPX_Util_MixedCollection(array(
                        'string $param1',
                        'bool $param2'
                    )),
                'return' => 'void',
                'access' => 'private'
            ))
        ));

        $doc = new xFrameworkPX_CodeGenerator_Php_MethodDoc($conf);

        $this->assertAttributeEquals($conf->shortDesc, '_shortDesc', $doc);
        $this->assertAttributeEquals($conf->longDesc, '_longDesc', $doc);
        $tags = new xFrameworkPX_Util_MixedCollection(array(
            'param' =>
                new xFrameworkPX_Util_MixedCollection(array(
                    'string $param1',
                    'bool $param2'
                )),
            'return' => 'void',
            'access' => 'private'
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
                'param' => null,
                'return' => null,
                'access' => null
            ))
        ));

        $doc = new xFrameworkPX_CodeGenerator_Php_MethodDoc($conf);
        $render = $doc->render();

        $code = array(
            '    /**',
            '     *',
            '     *',
            '     * @param',
            '     * @return',
            '     * @access',
            '     */'
        );

        $this->assertEquals(implode("\n", $code) . "\n", $render);

        // 全項目に空文字を設定
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'shortDesc' => '',
            'longDesc' => '',
            'tags' =>
            new xFrameworkPX_Util_MixedCollection(array(
                'param' => null,
                'return' => null,
                'access' => null
            ))
        ));

        $doc = new xFrameworkPX_CodeGenerator_Php_MethodDoc($conf);
        $render = $doc->render();

        $code = array(
            '    /**',
            '     *',
            '     *',
            '     * @param',
            '     * @return',
            '     * @access',
            '     */'
        );

        $this->assertEquals(implode("\n", $code) . "\n", $render);


        // 全項目に設定
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'shortDesc' => 'test method',
            'longDesc' => 'this is test method.',
            'tags' =>
            new xFrameworkPX_Util_MixedCollection(array(
                'param' => 'string param',
                'return' => 'void',
                'access' => 'public'
            ))
        ));

        $doc = new xFrameworkPX_CodeGenerator_Php_MethodDoc($conf);
        $render = $doc->render();

        $code = array(
            '    /**',
            '     * test method',
            '     *',
            '     * this is test method.',
            '     *',
            '     * @param     string param',
            '     * @return    void',
            '     * @access    public',
            '     */'
        );

        $this->assertEquals(implode("\n", $code) . "\n", $render);


        // LongDescriptionに複数行設定 paramを複数設定
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'shortDesc' => 'test method',
            'longDesc' => 'this is test method.\nhoge hoge\n\nfoo bar',
            'tags' =>
            new xFrameworkPX_Util_MixedCollection(array(
                'filesource' => '',
                'version' => 'ver 1.0',
                'package' => 'xFrameworkPX\tests',
                'author' => 'test',
                'access' => 'public',
                'var' => 'string',
                'param' =>
                    new xFrameworkPX_Util_MixedCollection(array(
                        'integer param1',
                        'string param2'
                    )),
                'return' => 'boolean'
            ))
        ));

        $doc = new xFrameworkPX_CodeGenerator_Php_MethodDoc($conf);
        $render = $doc->render();

        $code = array(
            '    /**',
            '     * test method',
            '     *',
            '     * this is test method.',
            '     * hoge hoge',
            '     *',
            '     * foo bar',
            '     *',
            '     * @param     integer param1',
            '     * @param     string param2',
            '     * @return    boolean',
            '     * @access    public',
            '     */'
        );

        $this->assertEquals(implode("\n", $code) . "\n", $render);

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
