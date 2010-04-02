<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_CodeGenerator_Php_PropsDocTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: PropsDocTest.php 882 2009-12-23 09:36:22Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Generator.php';
require_once 'xFrameworkPX/CodeGenerator/Php/Doc.php';
require_once 'xFrameworkPX/CodeGenerator/Php/PropsDoc.php';

require_once 'xFrameworkPX/Util/MixedCollection.php';

// }}}
// {{{ xFrameworkPX_CodeGenerator_Php_PropsDocTest

/**
 * xFrameworkPX_CodeGenerator_Php_PropsDocTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_CodeGenerator_Php
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_CodeGenerator_Php_PropsDocTest
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
            new xFrameworkPX_CodeGenerator_Php_PropsDoc();
        } catch (Exception $ex) {
            $this->assertTrue(true);
        }


        // 引数NULL
        $doc = new xFrameworkPX_CodeGenerator_Php_PropsDoc(null);

        $this->assertAttributeEquals('', '_shortDesc', $doc);
        $this->assertAttributeEquals('', '_longDesc', $doc);
        $tags = new xFrameworkPX_Util_MixedCollection(array(
            'var' => '',
            'access' => ''
        ));
        $this->assertAttributeEquals($tags, '_tags', $doc);


        // xFrameworkPX_Util_MixedCollectionの中が空
        $doc = new xFrameworkPX_CodeGenerator_Php_PropsDoc(
            new xFrameworkPX_Util_MixedCollection()
        );

        $this->assertAttributeEquals('', '_shortDesc', $doc);
        $this->assertAttributeEquals('', '_longDesc', $doc);
        $tags = new xFrameworkPX_Util_MixedCollection(array(
            'var' => '',
            'access' => ''
        ));
        $this->assertAttributeEquals($tags, '_tags', $doc);


        // xFrameworkPX_Util_MixedCollectionの各要素がnull
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'shortDesc' => null,
            'longDesc' => null,
            'tags' => null
        ));

        $doc = new xFrameworkPX_CodeGenerator_Php_PropsDoc($conf);

        $this->assertAttributeEquals('', '_shortDesc', $doc);
        $this->assertAttributeEquals('', '_longDesc', $doc);
        $tags = new xFrameworkPX_Util_MixedCollection(array(
            'var' => '',
            'access' => ''
        ));
        $this->assertAttributeEquals($tags, '_tags', $doc);


        // xFrameworkPX_Util_MixedCollectionの各要素が空文字
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'shortDesc' => '',
            'longDesc' => '',
            'tags' => ''
        ));

        $doc = new xFrameworkPX_CodeGenerator_Php_PropsDoc($conf);

        $this->assertAttributeEquals('', '_shortDesc', $doc);
        $this->assertAttributeEquals('', '_longDesc', $doc);
        $tags = new xFrameworkPX_Util_MixedCollection(array(
            'var' => '',
            'access' => ''
        ));
        $this->assertAttributeEquals($tags, '_tags', $doc);


        // 各プロパティ設定
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'shortDesc' => 'test prop',
            'longDesc' => 'this is test prop.',
            'tags' =>
            new xFrameworkPX_Util_MixedCollection(array(
                'var' => 'string',
                'access' => 'public'
            ))
        ));

        $doc = new xFrameworkPX_CodeGenerator_Php_PropsDoc($conf);

        $this->assertAttributeEquals($conf->shortDesc, '_shortDesc', $doc);
        $this->assertAttributeEquals($conf->longDesc, '_longDesc', $doc);
        $tags = new xFrameworkPX_Util_MixedCollection(array(
            'var' => 'string',
            'access' => 'public'
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
                'var' => null,
                'access' => null
            ))
        ));

        $doc = new xFrameworkPX_CodeGenerator_Php_PropsDoc($conf);
        $rend = $doc->render();

        $code = array(
            '    /**',
            '     *',
            '     *',
            '     * @var',
            '     * @access',
            '     */'
        );
        $this->assertEquals(implode("\n", $code) . "\n", $rend);

        // 全項目に空文字を設定
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'shortDesc' => '',
            'longDesc' => '',
            'tags' =>
            new xFrameworkPX_Util_MixedCollection(array(
                'var' => '',
                'access' => ''
            ))
        ));

        $doc = new xFrameworkPX_CodeGenerator_Php_PropsDoc($conf);
        $rend = $doc->render();

        $code = array(
            '    /**',
            '     *',
            '     *',
            '     * @var',
            '     * @access',
            '     */'
        );
        $this->assertEquals(implode("\n", $code) . "\n", $rend);


        // 全項目に設定
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'shortDesc' => 'test prop',
            'longDesc' => 'this is test prop.',
            'tags' =>
            new xFrameworkPX_Util_MixedCollection(array(
                'var' => 'Test',
                'access' => 'public',
                'var' => 'string',
                'param' => 'mixed testParam',
                'return' => 'boolean'
            ))
        ));

        $doc = new xFrameworkPX_CodeGenerator_Php_PropsDoc($conf);
        $rend = $doc->render();

        $code = array(
            "    /**",
            "     * test prop",
            "     *",
            "     * this is test prop.",
            "     *",
            "     * @var       string",
            "     * @access    public",
            "     */"
        );

        $this->assertEquals(implode("\n", $code) . "\n", $rend);


        // LongDescriptionに複数行設定
        $conf = new xFrameworkPX_Util_MixedCollection(array(
            'shortDesc' => 'test prop',
            'longDesc' => 'this is test prop.\nhoge hoge\n\nfoo bar',
            'tags' =>
            new xFrameworkPX_Util_MixedCollection(array(
                'var' => 'Test',
                'access' => 'protected',
                'var' => 'boolean',
                'param' => 'mixed testParam',
                'return' => 'boolean'
            ))
        ));

        $doc = new xFrameworkPX_CodeGenerator_Php_PropsDoc($conf);
        $rend = $doc->render();

        $code = array(
            '    /**',
            '     * test prop',
            '     *',
            '     * this is test prop.',
            '     * hoge hoge',
            '     *',
            '     * foo bar',
            '     *',
            '     * @var       boolean',
            '     * @access    protected',
            '     */'
        );

        $this->assertEquals(implode("\n", $code) . "\n", $rend);

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
