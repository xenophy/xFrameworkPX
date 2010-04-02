<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_CodeGeneratorTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: ConfigTest.php 882 2009-12-23 09:36:22Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'xFrameworkPX/Exception.php';
require_once 'xFrameworkPX/Config/Exception.php';
require_once 'xFrameworkPX/extender.php';
require_once 'xFrameworkPX/Object.php';
require_once 'xFrameworkPX/ConfigInterface.php';
require_once 'xFrameworkPX/Config.php';
require_once 'xFrameworkPX/Util/MixedCollection.php';
require_once 'xFrameworkPX/Util/Serializer.php';

// }}}
// {{{ defines

/**
 * Directory Separator Shorthand
 */
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

// }}}
// {{{ xFrameworkPX_ConfigTest

/**
 * xFrameworkPX_ConfigTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_ConfigTest extends PHPUnit_Framework_TestCase
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

        // 継承クラスファイル読み込み
        require_once dirname(__FILE__) . '/_files/Config_extends.php';

        // 次のテストのために、インスタンス破棄
        Config_extends::endTest();

    }

    // }}}
    // {{{ testConstruct

    /**
     * __constuctテスト
     *
     * @return void
     */
    public function testConstruct()
    {

    }

    // }}}
    // {{{ testClone

    /**
     * __cloneテスト
     *
     * @return void
     */
    public function testClone()
    {

        // 継承クラスファイル読み込み
        require_once dirname(__FILE__) . '/_files/Config_extends.php';

        // {{{ クローンテスト

        $this->setExpectedException('xFrameworkPX_Config_Exception');
        $disp = clone Config_extends::getInstance();

        // }}}
    }

    // }}}
    // {{{ testImport

    /**
     * importテスト
     * 
     * database.xml読み込みテスト
     *
     * @return void
     */
    public function testImport()
    {

        // 継承クラスファイル読み込み
        require_once dirname(__FILE__) . '/_files/Config_extends.php';

        // {{{ データベース設定オブジェクト設定

        $dbConf = Config_extends::getInstance()->import(
            new xFrameworkPX_Util_MixedCollection(array(

                // {{{ パス設定

                'path' => dirname(__FILE__) . '/_files/configs/',

                // }}}
                // {{{ ファイル名設定

                'filename' => '_database.pxml',

                // }}}
                // {{{ キャッシュパス

                'cachepath' => dirname(__FILE__) . '/_files/cache/'

                // }}}

            ))
        );

        // }}}
        // {{{ 戻ってきたオブジェクトを判定

        $this->assertType('SimpleXMLElement', $dbConf);
        $this->assertTrue(
            file_exists(
                dirname(__FILE__) . '/_files/cache/_database.pxml'
            )
        );
        // }}}
        // {{{ キャッシュから読み込みテスト

        $dbConf = Config_extends::getInstance()->import(
            new xFrameworkPX_Util_MixedCollection(array(

                // {{{ パス設定

                'path' => dirname(__FILE__) . '/_files/configs/',

                // }}}
                // {{{ ファイル名設定

                'filename' => '_database.pxml',

                // }}}
                // {{{ キャッシュパス

                'cachepath' => dirname(__FILE__) . '/_files/cache/'

                // }}}

            ))
        );

        // }}}
        // {{{ 戻ってきたオブジェクトを判定

        $this->assertType('SimpleXMLElement', $dbConf);
        $this->assertTrue(
            file_exists(
                dirname(__FILE__) . '/_files/cache/_database.pxml'
            )
        );

        // }}}
        // {{{ キャッシュファイル削除

        unlink(dirname(__FILE__) . '/_files/cache/_database.pxml');

        // }}}
        // {{{ 存在しないXMLファイルを指定して例外

        $this->setExpectedException('xFrameworkPX_Config_Exception');
        $dbConf = Config_extends::getInstance()->import(
            new xFrameworkPX_Util_MixedCollection(array(

                // {{{ パス設定

                'path' => dirname(__FILE__) . '/_files/configs/',

                // }}}
                // {{{ ファイル名設定

                'filename' => '_site.pxml',

                // }}}
                // {{{ キャッシュパス

                'cachepath' => dirname(__FILE__) . '/_files/cache/'

                // }}}

            ))
        );

        // }}}

    }

    // }}}
    // {{{ testSetGet

    /**
     * __setテスト、__getテスト
     * 
     * importメソッド使用
     *
     * @return void
     */
    public function testSetGet()
    {

        // 継承クラスファイル読み込み
        require_once dirname(__FILE__) . '/_files/Config_extends.php';

        // {{{ データベース設定オブジェクト設定

        $dbConf = Config_extends::getInstance()->import(
            new xFrameworkPX_Util_MixedCollection(array(

                // {{{ パス設定

                'path' => dirname(__FILE__) . '/_files/configs/',

                // }}}
                // {{{ ファイル名設定

                'filename' => '_database.pxml',

                // }}}
                // {{{ キャッシュパス

                'cachepath' => dirname(__FILE__) . '/_files/cache/'

                // }}}

            ))
        );

        // }}}
        // {{{ __getによって取得

        $confExtends = Config_extends::getInstance();
        $this->assertEquals(
            (string)$confExtends->database->connection->database, 'myapp'
        );

        // }}}
        // {{{ __setによって設定し、判定

        Config_extends::getInstance()->test = 'test';

        $this->assertEquals(
            (string)Config_extends::getInstance()->test, 'test'
        );

        // }}}
        // {{{ キャッシュファイル削除

        unlink(dirname(__FILE__) . '/_files/cache/_database.pxml');

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
