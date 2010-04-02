<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Util_ObservableTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_Util
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: ObservableTest.php 1161 2010-01-05 01:32:30Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'xFrameworkPX/Object.php';
require_once 'xFrameworkPX/Util/Serializer.php';
require_once 'xFrameworkPX/Util/Observable.php';
require_once 'xFrameworkPX/Exception.php';
require_once 'xFrameworkPX/Util/Exception.php';
require_once 'xFrameworkPX/Util/Observable/Exception.php';

// }}}
// {{{ xFrameworkPX_Util_ObservableTest

/**
 * xFrameworkPX_Util_ObservableTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_Util
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_Util_ObservableTest extends PHPUnit_Framework_TestCase
{
    protected $_object;

    // {{{ setUp

    /**
     * セットアップ
     *
     * @return void
     */
    protected function setUp()
    {

        // テスト用クラスファイル読み込み
        require_once dirname(__FILE__) . '/_files/TestAction.php';

        // {{{ xFrameworkPX_Util_Observable オブジェクト生成

        $this->_object = new xFrameworkPX_Util_Observable();

        // }}}

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
    // {{{ testAddEvents

    /**
     * addEventsテスト
     *
     * @return void
     */
    public function testAddEvents()
    {

        // {{{ Case1

        /**
         * イベントを一つ定義する
         *
         * イベント名を文字列リテラルで指定する
         */
        $this->assertTrue($this->_object->addEvents('TestEvent'));

        // }}}
        // {{{ Case2

        /**
         * 定義済みイベントを定義した場合、falseが返却されることを確認
         */
        $this->assertFalse($this->_object->addEvents('TestEvent'));

        // }}}
        // {{{ Case3

        /**
         * 複数のイベント定義
         *
         * イベント名を文字列リテラルで指定する
         */
        $this->assertTrue(
            $this->_object->addEvents(
                'TestEvent2-1',
                'TestEvent2-2',
                'TestEvent2-3'
            )
        );

        // }}}
        // {{{ Case4

        /**
         * 複数のイベント定義
         *
         * 複数定義時、一つだけ既に登録されているイベント名を定義すると
         * falseが返却されることを確認
         */
        $this->assertFalse(
            $this->_object->addEvents(
                'TestEvent',
                'TestEvent3-1',
                'TestEvent3-2',
                'TestEvent3-3'
            )
        );

        // }}}

    }

    // }}}
    // {{{ testAddListner

    /**
     * addListnerテスト
     *
     * @return void
     */
    public function testAddListener()
    {

        // {{{ イベント定義

        $this->_object->addEvents('TestEvent');

        // }}}
        // {{{ イベントリスナーを追加

        $this->_object->addListener(
            'TestEvent', array(new TestAction(), 'getMsg1')
        );

        // }}}
        // {{{ イベントリスナー存在確認

        $this->assertTrue($this->_object->hasListener('TestEvent'));

        // }}}
        // {{{ 存在しないイベントリスナー確認

        $this->assertFalse($this->_object->hasListener('TestEvent-NotDefine'));

        // }}}
        // {{{ 未登録イベントに対するイベントリスナー追加テスト

        try {

            $this->_object->addListener(
                'TestEvent-NotDefine', array(new TestAction(), 'getMsg1')
            );

        } catch (xFrameworkPX_Util_Observable_Exception $expected) {
            return;
        }

        $this->fail('期待通りの例外が発生しませんでした。');
    }

    // }}}
    // {{{ testOn

    /**
     * onテスト
     *
     * @return void
     */
    public function testOn()
    {

        // {{{ イベント定義

        $this->_object->addEvents('TestEvent');

        // }}}
        // {{{ イベントリスナーを追加

        $this->_object->on('TestEvent', array(new TestAction(), 'getMsg1'));

        // }}}
        // {{{ イベントリスナー存在確認

        $this->assertTrue($this->_object->hasListener('TestEvent'));

        // }}}
        // {{{ 存在しないイベントリスナー確認

        $this->assertFalse($this->_object->hasListener('TestEvent-NotDefine'));

        // }}}
        // {{{ 未登録イベントに対するイベントリスナー追加テスト

        try {

            $this->_object->on(
                'TestEvent-NotDefine', array(new TestAction(), 'getMsg1')
            );

        } catch (xFrameworkPX_Util_Observable_Exception $expected) {
            return;
        }

        $this->fail('期待通りの例外が発生しませんでした。');
    }

    // }}}
    // {{{ testDispatch

    /**
     * dispatchテスト
     *
     * @return void
     */
    public function testDispatch()
    {

        // {{{ 出力バッファリング開始

        ob_start();

        // }}}

        try {

            // {{{ イベント登録

            $this->_object->addEvents(
                'TEST',
                'test',
                'テスト',
                'てすと',
                'tesuto',
                'TEST2'
            );

            // }}}
            // {{{ リスナー追加

            $this->_object->addListener(
                'TEST', array(new TestAction(), 'getMsg1')
            );

            $this->_object->addListener('test', 'TestAction');

            $this->_object->addListener(
                'てすと', array('TestAction', 'getMsg')
            );

            $this->_object->addListener(
                'tesuto', array('TestAction', 'getMsg', 'setMsg')
            );

            $this->_object->addListener(
                'TEST2', array(new TestAction(), 'getMsg3')
            );

            // }}}

        } catch (xFrameworkPX_Util_Observable_Exception $ex) {

        }

        // {{{ ディスパッチャーテスト

        $result = $this->_object->dispatch('TEST');
        $this->assertEquals('TestActionMessage1', $result[0]);

        // }}}
        // {{{ 不正なリスナー追加テスト

        try {
            $this->_object->dispatch('test');
        } catch (xFrameworkPX_Util_Observable_Exception $ex) {
            $this->assertTrue(true);
        }

        // }}}
        // {{{ 不正なリスナー追加テスト

        try {
            $this->_object->dispatch('テスト');
        } catch (xFrameworkPX_Util_Observable_Exception $ex) {
            $this->assertTrue(true);
        }

        // }}}
        // {{{ 不正なリスナー追加テスト

        try {
            $this->_object->dispatch('てすと');
        } catch (xFrameworkPX_Util_Observable_Exception $ex) {
            $this->assertTrue(true);
        }

        // }}}
        // {{{ 不正なリスナー追加テスト

        try {
            $this->_object->dispatch('tesuto');
        } catch (xFrameworkPX_Util_Observable_Exception $ex) {
            $this->assertTrue(true);
        }

        // }}}
        // {{{ 不正なリスナー追加テスト

        $result = $this->_object->dispatch('TEST2');
        $this->assertFalse($result);

        // }}}
        // {{{ サスペンド/レジュームテスト

        $this->_object->suspendEvents(true);
        $result = $this->_object->dispatch('TEST');

        $this->assertEquals('', $result[0]);
        $this->_object->resumeEvents();

        // }}}
        // {{{ サスペンド/レジュームテスト

        $this->_object->suspendEvents(true);
        $this->_object->dispatch('TEST2');
        $this->_object->resumeEvents();

        // }}}
        // {{{ バッファリングクリア

        ob_end_clean();

        // }}}

    }

    // }}}
    // {{{ testHasListner

    /**
     * hasListenerテスト
     *
     * @return void
     */
    public function testHasListener()
    {

/*
        $this->object->addEvents( 'TEST' );
        $this->object->addListener( 'TEST', array( 'TestAction', 'getMsg1' ) );

        // リスナー存在チェックテスト
        $this->assertTrue( $this->object->hasListener('TEST') );
        $this->assertFalse( $this->object->hasListener('test') );
*/

    }

    // }}}
    // {{{ testRemoveListener

    /**
     * removeListenerテスト
     *
     * @return void
     */
    public function testRemoveListener()
    {

        try {
            $this->_object->removeListener(
                'テスト',
                array('TestAction', 'getMsg2')
            );

        } catch (xFrameworkPX_Util_Observable_Exception $ex) {
            $this->assertTrue(true);
        }

        // {{{ イベント登録

        $this->_object->addEvents('TEST', 'test', 'tesuto', 'テスト');

        // }}}
        // {{{ リスナー追加

        $this->_object->addListener('TEST', array('TestAction', 'getMsg1'));

        $this->_object->addListener('test', array('TestAction', 'getMsg2'));

        $this->_object->addListener('tesuto', array('TestAction', 'getMsg3'));

        // }}}
        // {{{ リスナー削除テスト

        $this->_object->removeListener('test', array('TestAction', 'getMsg2'));

        // }}}
        // {{{ リスナー登録確認

        $this->assertTrue($this->_object->hasListener('TEST'));
        $this->assertTrue($this->_object->hasListener('tesuto'));

        // }}}
        // {{{ 登録されていないイベントは空の配列を返す

        $this->assertEquals($this->_object->dispatch('test'), array());

        // }}}
        // {{{ 登録されていないイベントは空の配列を返す

        $this->_object->un('TEST', array('TestAction', 'getMsg1'));
        $this->assertEquals($this->_object->dispatch('test'), array());

        // }}}

    }

    // }}}
    // {{{ testPurgeListeners

    /**
     * purgeListenersテスト
     *
     * @return void
     */
    public function testPurgeListeners()
    {

        // {{{ イベント登録

        $this->_object->addEvents('TEST', 'test', 'tesuto', 'テスト');

        // }}}
        // {{{ リスナー追加

        $this->_object->addListener(
            'TEST', array(new TestAction(), 'getMsg1')
        );

        $this->_object->addListener(
            'test', array(new TestAction(), 'getMsg2')
        );

        $this->_object->addListener(
            'tesuto', array(new TestAction(), 'getMsg3')
        );

        $this->_object->addListener(
            'テスト', array(new TestAction(), 'getArgMsg')
        );

        // }}}
        // {{{ ディスパッチャーすることで存在を確認

        $result = $this->_object->dispatch('TEST');
        $this->assertEquals('TestActionMessage1', $result[0]);

        $result = $this->_object->dispatch('test');
        $this->assertEquals('TestActionMessage2', $result[0]);

        $result = $this->_object->dispatch('tesuto');
        $this->assertFalse($result);

        $result = $this->_object->dispatch('テスト', 'hoge');
        $this->assertEquals('hoge', $result[0]);

        // }}}
        // {{{ リスナー全削除テスト

        $this->_object->purgeListeners();
        $this->assertFalse($this->_object->hasListener('TEST'));

        // }}}
        // {{{ リスナー登録が削除されたことを確認

        $this->assertFalse($this->_object->hasListener('TEST'));
        $this->assertFalse($this->_object->hasListener('test'));
        $this->assertFalse($this->_object->hasListener('tesuto'));
        $this->assertFalse($this->_object->hasListener('テスト'));

        // }}}
 
    }

    // }}}
    // {{{ testExecuteAction

    /**
     * 連続イベント実行テスト
     *
     * @return void
     */
    public function testExecuteAction()
    {

/*
        // {{{ イベント登録

        $this->object->addEvents( 'Test1', 'Test2' );

        // }}}
        // {{{ イベントの複数実行(正常時)

        $this->object->addListener(
            'Test1',
            array( new TestAction(), 'getMsg1' )
        );

        $this->object->addListener(
            'Test1',
            array( new TestAction(), 'getMsg2' )
        );

        $this->object->addListener(
            'Test1',
            array( new TestAction(), 'getArgMsg' )
        );

        // }}}
        // {{{ バッファリング開始

        ob_start();

        // }}}
        // {{{ イベントディスパッチ

        $this->assertTrue( $this->object->dispatch( 'Test1', 'Test3' ) );

        $strOb = ob_get_contents();

        $strCompare = '';
        $strCompare .= 'Test1' . PHP_EOL;
        $strCompare .= 'Test2' . PHP_EOL;
        $strCompare .= 'Test3' . PHP_EOL;

        // }}}
        // {{{ イベントの複数実行(異常時)

        $this->object->addListener(
            'Test2',
            array( new TestAction(), 'getMsg1' )
        );

        $this->object->addListener(
            'Test2',
            array( new TestAction(), 'getMsg3' )
        );

        $this->object->addListener(
            'Test2',
            array( new TestAction(), 'getArgMsg' )
        );

        // }}}
        // {{{ バッファリングクリア

        ob_clean();

        // }}}
        // {{{ イベントディスパッチ

        $this->assertFalse(
            $this->object->dispatch( 'Test2', 'Runtime Error' )
        );

        $strOb = ob_get_contents();

        $strCompare = '';
        $strCompare .= 'Test1' . PHP_EOL;
        $strCompare .= 'False' . PHP_EOL;

        // }}}
        // {{{ バッファリングクリア

        ob_end_clean();

        // }}}
*/
    }

    // }}}

}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
