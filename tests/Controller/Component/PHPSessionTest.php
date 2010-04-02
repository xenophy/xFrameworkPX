<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Controller_Component_PHPSessionTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_Controller_Component
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: PHPSessionTest.php 934 2009-12-24 15:20:52Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';

require_once 'xFrameworkPX/extender.php';
require_once 'xFrameworkPX/Object.php';

require_once 'xFrameworkPX/Util/Observable.php';
require_once 'xFrameworkPX/Util/MixedCollection.php';

require_once 'xFrameworkPX/Controller/Component.php';

require_once 'xFrameworkPX/Controller/Component/Session.php';
require_once 'xFrameworkPX/Controller/Component/PHPSession.php';

// }}}
// {{{ xFrameworkPX_Controller_Component_PHPSessionTest

/**
 * xFrameworkPX_Controller_Component_PHPSessionTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_Controller_Component
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_Controller_Component_PHPSessionTest
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
        copy(
            dirname(__FILE__) . '/_files/sess_test',
            dirname(__FILE__) . '/_files/_Session/sess_test'
        );
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
    // {{{ testSession

    /**
     * Session混合テスト
     *
     * @return void
     */
    public function testSession()
    {

        // {{{ 引数生成

        $conf = new xFrameworkPX_Util_MixedCollection(
            array(
                'ID' => 'PHPSESSID',
                'AUTO_START' => true,
                'TYPE' => 'Php',
                'TIMEOUT' => 1
            ));

        // }}}
        // {{{ セッションクラスインスタンス化

        $ses = new xFrameworkPX_Controller_Component_PhpSession($conf);

        // }}}
        // {{{ セッション開始、PHPの内部でsession_save_pathがコールされている

        $success = $ses->sessionHandlerOpen(
            dirname(__FILE__) . '/_files/_Session/', '1'
        );

        $this->assertTrue($success);

        // }}}
        // {{{ セッション書き込み

        $success = $ses->sessionHandlerWrite('1', 'xFrameworkPX');
        $this->assertEquals($success, 12);  // 文字の長さを返す

        // }}}
        // {{{ セッション読み込み

        $read = $ses->sessionHandlerRead('1');

        $this->assertEquals($read, 'xFrameworkPX');

        // }}}
        // {{{ セッションファイル確認

        $this->assertTrue(
            file_exists(dirname(__FILE__) . '/_files/_Session/sess_1')
        );

        sleep(2); // タイムアウトによる削除をするため

        // }}}
        // {{{ セッション読み込み

        $read = $ses->sessionHandlerRead('1');

        $this->assertNotEquals($read, 'xFrameworkPX'); // 削除されている

        // }}}
        // {{{ セッションクローズ

        $success = $ses->sessionHandlerClose();

        $this->assertTrue($success);

        // }}}
        // {{{ 古いファイルを投入

        copy(
            dirname(__FILE__) . '/_files/sess_test',
            dirname(__FILE__) . '/_files/_Session/sess_test'
        );

        // }}}
        // {{{ セッションクリア

        $success = $ses->sessionHandlerClean(30); // 30秒後

        $this->assertTrue($success);

        // }}}
        // {{{ 古いファイルを投入

        copy(
            dirname(__FILE__).'/_files/sess_test',
            dirname(__FILE__).'/_files/_Session/sess_test'
        );
        usleep(1200000); // タイムアウトによる削除をするため

        // }}}
        // {{{ セッションクリア

        $success = $ses->sessionHandlerClean(0);  // 0秒後

        $this->assertTrue($success);

        // }}}
        // {{{ セッションファイル確認

        $this->assertFalse(
            file_exists(dirname(__FILE__) . '/_files/_Session/sess_1')
        );

        // }}}

    }

    // }}}
    // {{{ testSession2

    /**
     * Session混合テスト2
     *
     * @return void
     */
    public function testSession2()
    {

        // {{{ session_save_pathを先にコール

        session_save_path('');

        // }}}
        // {{{ 引数生成

        $conf = new xFrameworkPX_Util_MixedCollection(
            array(
                'ID' => 'PHPSESSID',
                'AUTO_START' => true,
                'TYPE' => 'Php',
                'TIMEOUT' => null
            )
        );

        // }}}
        // {{{ セッションクラスインスタンス化

        $ses = new xFrameworkPX_Controller_Component_PhpSession($conf);

        // }}}
        // {{{ セッション開始、PHPの内部でsession_save_pathがコールされている

        $success = $ses->sessionHandlerOpen('', '1');
        $this->assertTrue($success);

        // }}}
        // {{{ セッション書き込み

        $success = $ses->sessionHandlerWrite('1', 'xFrameworkPX');
        $this->assertEquals($success, 12);   // 文字の長さを返す

        // }}}
        // {{{ セッションファイル確認

        $this->assertTrue(file_exists(sys_get_temp_dir() . 'sess_1'));

        // }}}
        // {{{ セッション読み込み

        $read = $ses->sessionHandlerRead('1');

        $this->assertEquals($read, 'xFrameworkPX');

        // }}}
        // {{{ セッションクローズ

        $success = $ses->sessionHandlerClose();

        $this->assertTrue($success);

        // }}}
        // {{{ セッションクリア

        $success = $ses->sessionHandlerClean(30);   // 30秒後

        $this->assertTrue($success);

        // }}}
        // {{{ セッションクリア

        $success = $ses->sessionHandlerClean(0);    // 0秒後

        $this->assertTrue($success);

        // }}}
        // {{{ セッション破棄

        $success = $ses->sessionHandlerDestroy('1');

        $this->assertTrue($success);

        // }}}
        // {{{ セッションファイル確認

        $this->assertFalse(file_exists(sys_get_temp_dir() . 'sess_1'));

        // }}}

    }

    // }}}
    // {{{ testSession3

    /**
     * Session混合テスト3
     *
     * Sessionクラスのテスト
     *
     * @return void
     */
    public function testSession3()
    {

        // {{{ 引数生成

        $conf = new xFrameworkPX_Util_MixedCollection(
            array(
                'ID' => 'PHPSESSID',
                'AUTO_START' => true,
                'TYPE' => 'Php',
                'TIMEOUT' => null
            )
        );

        // }}}
        // {{{ セッションクラスインスタンス化

        $ses = new xFrameworkPX_Controller_Component_PhpSession($conf);

        // }}}
        // {{{ セッションに書き込み

        $testKey1 = 'test1';
        $testKey2 = 'test2';
        $testKey3 = 'test3';

        $testVal1 = 'testVal';
        $testVal2 = 'testVal';
        $testVal3 = array('test', 'hoge');

        $ses->write($testKey1, $testVal1);
        $ses->write($testKey2, $testVal2);
        $ses->write($testKey3, $testVal3);

        $this->assertEquals($testVal1, $_SESSION[$testKey1]);
        $this->assertEquals($testVal2, $_SESSION[$testKey2]);
        $this->assertEquals($testVal3, $_SESSION[$testKey3]);

        // }}}
        // {{{ セッションを読み込む

        $read1 = $ses->read($testKey1);
        $read2 = $ses->read($testKey2);
        $read3 = $ses->read($testKey3);

        $this->assertEquals($read1, $testVal1);
        $this->assertEquals($read2, $testVal2);
        $this->assertEquals($read3, $testVal3);

        // }}}
        // {{{ セッションを全て読み込む

        $seses = $ses->readall();

        $this->assertEquals($seses, $_SESSION);

        // }}}
        // {{{ セッションを削除して読み込む

        $remove1 = $ses->remove($testKey1);
        $read1 = $ses->read($testKey1);
        $read2 = $ses->read($testKey2);
        $read3 = $ses->read($testKey3);

        $this->assertEquals($remove1, $testVal1);
        $this->assertNotEquals($read1, $testVal1);
        $this->assertEquals($read2, $testVal2);
        $this->assertEquals($read3, $testVal3);

        // }}}
        // {{{ セッションをクリア

        $ses->clear($testKey1);

        $seses = $ses->readall();

        $this->assertEquals($seses, $_SESSION);
        $this->assertEquals(array(), $_SESSION);

        // }}}
        // {{{ セッションを破棄

        $ses->destroy();

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
