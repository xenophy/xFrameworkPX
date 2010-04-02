<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * ObjectTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: ObjectTest.php 882 2009-12-23 09:36:22Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'xFrameworkPX/Object.php';
require_once 'xFrameworkPX/extender.php';
require_once '_files/TestObjectClass.php';
require_once 'xFrameworkPX/Util/MixedCollection.php';

// }}}
// {{{ ObjectTest

/**
 * ObjectTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX
 * @author     Akihiro Tamari <tamari@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_ObjectTest
 */
class xFrameworkPX_ObjectTest extends PHPUnit_Framework_TestCase
{
    // {{{ props

    /**
     * クラスオブジェクト
     *
     * @var object
     */
    protected $_classObject = null;

    // }}}
    // {{{ __construct

    public function __construct()
    {

        $this->_classObject = new TestObject();

    }

    // }}}
    // {{{ setUp

    /**
     * セットアップメソッド
     *
     * @return void
     */
    protected function setUp()
    {
    }

    // }}}
    // {{{ tearDown

    /**
     * 終了メソッド
     *
     * @return void
     */
    protected function tearDown()
    {
    }

    // }}}
    // {{{ testToString

    /**
     * クラス名取得メソッドテスト
     *
     * @return void
     */
    public function testToString()
    {
        // {{{ ローカル変数初期化

        $ret = null;

        // }}}
        // {{{ クラス名取得テスト

        $ret = $this->_classObject->toString();
        $this->assertEquals('TestObject', $ret);

        // }}}

    }

    // }}}
    // {{{ testEnv

    /**
     * サーバー変数取得メソッドテスト
     *
     * @return void
     */
    public function testEnv()
    {

        // {{{ ローカル変数初期化

        $ret = null;

        // }}}
        // {{{ サーバー変数取得テスト

        $ret = $this->_classObject->env('OS');
        $this->assertEquals($_SERVER['OS'], $ret);

        $ret = $this->_classObject->env('TEMP');
        $this->assertEquals($_SERVER['TEMP'], $ret);

        // }}}

    }

    // }}}
    // {{{ testRefererAction

    /**
     * リファラーによるファイル名取得メソッド
     *
     * @return void
     */
    public function testRefererAction()
    {

        // {{{ ローカル変数初期化

        $ret = null;

        // }}}
        // {{{ リファラーに値がない場合

        $ret = $this->_classObject->refererAction();
        $this->assertNull($ret);

        // }}}
        // {{{ リファラーに値がある場合

        $_SERVER['HTTP_REFERER'] = '/hoge/foo/';
        $ret = $this->_classObject->refererAction();
        $this->assertEquals('index', $ret);

        $_SERVER['HTTP_REFERER'] = '/hoge/foo/bar.html';
        $ret = $this->_classObject->refererAction();
        $this->assertEquals('bar', $ret);

        // }}}

    }

    // }}}
    // {{{ testMix

    /**
     * xFrameworkPX_Util_MixedCollection生成メソッドテスト
     *
     * @return void
     */
    public function testMix()
    {

        // {{{ ローカル変数初期化

        $ret = null;

        // }}}
        // {{{ 要素が空のMixedCollection生成

        $ret = $this->_classObject->mix();
        $this->assertType('xFrameworkPX_Util_MixedCollection', $ret);
        $this->assertEquals(0, $ret->count());

        // }}}
        // {{{ 要素が存在するMixedCollection生成

        $ret = $this->_classObject->mix(
            array(
                new TestObject(),
                'key1' => 'test1',
                'key2' => 'test2',
                3
            )
        );
        $this->assertType('xFrameworkPX_Util_MixedCollection', $ret);
        $this->assertEquals(4, $ret->count());
        $this->assertEquals(new TestObject(), $ret->{0});
        $this->assertEquals('test1', $ret->{'key1'});
        $this->assertEquals('test2', $ret->{'key2'});
        $this->assertEquals(3, $ret->{1});

        // }}}

    }

    // }}}
    // {{{ testGetAccessFileName

    /**
     * アクセスファイル名取得メソッドテスト
     *
     * @return void
     */
    public function testGetAccessFileName()
    {

        // {{{ ローカル変数初期化

        $ret = null;

        // }}}
        // {{{ アクセスファイル名取得メソッドテスト

        $_GET['cp'] = '/hoge/foo/bar.html';
        $ret = $this->_classObject->getAccessFileName();
        $this->assertEquals('bar.html', $ret);

        $_GET['cp'] = '';
        $ret = $this->_classObject->getAccessFileName();
        $this->assertEquals('', $ret);

        $_GET['cp'] = '/hoge/foo/bar';
        $ret = $this->_classObject->getAccessFileName();
        $this->assertEquals('bar', $ret);

        $_GET['cp'] = '/hoge.html';
        $ret = $this->_classObject->getAccessFileName();
        $this->assertEquals('hoge.html', $ret);

        // }}}

    }

    // }}}
    // {{{ testGetActionName

    /**
     * アクション名取得メソッドテスト
     *
     * @return void
     */
    public function testGetActionName()
    {

        // {{{ ローカル変数初期化

        $ret = null;

        // }}}
        // {{{ アクション名取得メソッドテスト

        $_GET['cp'] = '/hoge/foo/';
        $ret = $this->_classObject->getActionName();
        $this->assertEquals('index', $ret);

        $_GET['cp'] = '/hoge/foo/bar.html';
        $ret = $this->_classObject->getActionName();
        $this->assertEquals('bar', $ret);

        $_GET['cp'] = '';
        $ret = $this->_classObject->getActionName();
        $this->assertEquals('index', $ret);
/*
        $_GET['cp'] = '/hoge/foo/';
        $ret = $this->_classObject->getActionName('bar');
        $this->assertEquals('bar', $ret);
*/
        // }}}

    }

    // }}}
    // {{{ testGetContentPath

    /**
     * コンテンツパス取得メソッドテスト
     *
     * @return void
     */
    public function testGetContentPath()
    {

        // {{{ ローカル変数初期化

        $ret = null;

        // }}}
        // {{{ コンテンツパス取得メソッドテスト

        $_GET['cp'] = '/hoge/foo/bar.html';
        $ret = $this->_classObject->getContentPath();
        $this->assertEquals('/hoge/foo', $ret);

        $_GET['cp'] = '/hoge.html';
        $ret = $this->_classObject->getContentPath();
        $this->assertEquals('', $ret);

        $_GET['cp'] = '';
        $ret = $this->_classObject->getContentPath();
        $this->assertEquals('', $ret);

        // }}}

    }

    // }}}
    // {{{ testGetParams

    /**
     * パラメータ取得メソッドテスト
     *
     * @return void
     */
    public function testGetParams()
    {

        // {{{ ローカル変数初期化

        $ret = null;
        $dest = new xFrameworkPX_Util_MixedCollection(
            array(
                'form' => new xFrameworkPX_Util_MixedCollection(),
                'url' => new xFrameworkPX_Util_MixedCollection(),
                'files' => new xFrameworkPX_Util_MixedCollection(),
                'args' => new xFrameworkPX_Util_MixedCollection()
            )
        );

        // }}}
        // {{{ パラメータ取得テスト

        $_SERVER['argv'] = array(
            'testMethod',
            '--test1',
            '--test2=foo',
            '-hoge',
            '-h=bar',
            'test5'
        );

        $dest->args->offsetSetAll(
            array(
                'test1' => true,
                'test2' => 'foo',
                'h' => 'bar',
                'o' => true,
                'g' => true,
                'e' => true,
                'test5'
            )
        );

        $ret = $this->_classObject->getParams();
        $this->assertEquals($dest, $ret);

        // }}}

    }

    // }}}
    // {{{ testGetRelativePath

    /**
     * アクセス相対位置取得メソッドテスト
     *
     * @return void
     */
    public function testGetRelativePath()
    {

        // {{{ ローカル変数初期化

        $ret = null;

        // }}}
        // {{{ アクセス相対位置取得テスト

        $_GET['cp'] = '/hoge/foo/bar.html';
        $ret = $this->_classObject->getRelativePath();
        $this->assertEquals('../../', $ret);

        $_GET['cp'] = '/bar.html';
        $ret = $this->_classObject->getRelativePath();
        $this->assertEquals('', $ret);

        $_GET['cp'] = '';
        $ret = $this->_classObject->getRelativePath();
        $this->assertEquals('', $ret);

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
