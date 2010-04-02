<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Util_MixedCollectionTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_Util
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: MixedCollectionTest.php 951 2009-12-25 11:40:13Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'xFrameworkPX/Exception.php';
require_once 'xFrameworkPX/Util/MixedCollection.php';

// }}}
// {{{ xFrameworkPX_Util_MixedCollectionTest

/**
 * xFrameworkPX_Util_MixedCollectionTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_Util
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_Util_MixedCollectionTest extends PHPUnit_Framework_TestCase
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
    // {{{ test__Construct

    /**
     * __constuctテスト
     *
     * @return void
     */
    public function test__Construct()
    {

        // {{{ 配列登録

        $test = new xFrameworkPX_Util_MixedCollection(array(
            'data0',
            'data1',
            'data2',
            'key0' => 'keyData0',
            'key1' => 'keyData1',
            'key2' => 'keyData2'
        ));

        $this->assertEquals('data0', $test[0]);
        $this->assertEquals('data1', $test[1]);
        $this->assertEquals('data2', $test[2]);
        $this->assertEquals('keyData0', $test->key0);
        $this->assertEquals('keyData1', $test->key1);
        $this->assertEquals('keyData2', $test->key2);

        // }}}
        // {{{ オブジェクト登録

        $obj = new stdClass();
        $obj->key0 = 'keyData0';
        $obj->key1 = 'keyData1';
        $obj->key2 = 'keyData2';

        $test = new xFrameworkPX_Util_MixedCollection($obj);

        $this->assertEquals('keyData0', $test->key0);
        $this->assertEquals('keyData1', $test->key1);
        $this->assertEquals('keyData2', $test->key2);

        // }}}

    }

    // }}}
    // {{{ testOffsetSetAll

    /**
     * offsetSetAllテスト
     *
     * @return void
     */
    public function testOffsetSetAll()
    {

        $test = new xFrameworkPX_Util_MixedCollection();

        // {{{ データ一括登録

        $test->offsetSetAll(
            array(
                'key0' => 'keyData0',
                'key1' => 'keyData1',
                'key2' => 'keyData2',
                'data0',
                'data1',
                'data2'
            )
        );

        $this->assertEquals('keyData0', $test->key0);
        $this->assertEquals('keyData1', $test->key1);
        $this->assertEquals('keyData2', $test->key2);
        $this->assertEquals('data0', $test->{0});
        $this->assertEquals('data1', $test->{1});
        $this->assertEquals('data2', $test->{2});

        // }}}

    }

    // }}}
    // {{{ testImport

    /**
     * importテスト
     *
     * @return void
     */
    public function testImport()
    {

        $test = new xFrameworkPX_Util_MixedCollection();

        // {{{ データ一括登録

        $import = $test->import(
            array(
                'key0' => 'keyData0',
                'key1' => 'keyData1',
                'key2' => 'keyData2',
                'data0',
                'data1',
                'data2'
            )
        );

        $this->assertEquals('keyData0', $import->key0);
        $this->assertEquals('keyData1', $import->key1);
        $this->assertEquals('keyData2', $import->key2);
        $this->assertEquals('data0', $import->{0});
        $this->assertEquals('data1', $import->{1});
        $this->assertEquals('data2', $import->{2});

        // }}}
        // {{{ データ一括登録

        $import = $test->import(
            array(
                'key0' => array(
                    'deep1' => 'testDeep1',
                    'deep2' => array('deepdeep' => 'testDeep2')
                ),
                'key1' => 'keyData1',
                'key2' => 'keyData2',
                'data0',
                'data1',
                'data2'
            )
        );

        $this->assertEquals('testDeep1', $import->key0->deep1);
        $this->assertEquals('testDeep2', $import->key0->deep2->deepdeep);
        $this->assertEquals('keyData1', $import->key1);
        $this->assertEquals('keyData2', $import->key2);
        $this->assertEquals('data0', $import->{0});
        $this->assertEquals('data1', $import->{1});
        $this->assertEquals('data2', $import->{2});

        // }}}

    }

    // }}}
    // {{{ testFirst

    /**
     * firstテスト
     *
     * @return void
     */
    public function testFirst()
    {

        // {{{ 先頭キーデータ取得

        $test = new xFrameworkPX_Util_MixedCollection(array(
            'key0' => 'keyData0',
            'key1' => 'keyData1',
            'key2' => 'keyData2',
            'data0',
            'data1',
            'data2',
        ));

        $this->assertEquals('keyData0', $test->first());

        // }}}
        // {{{ 先頭データ取得

        $test = new xFrameworkPX_Util_MixedCollection(array(
            'data0',
            'data1',
            'data2',
            'key0' => 'keyData0',
            'key1' => 'keyData1',
            'key2' => 'keyData2'
        ));

        $this->assertEquals('data0', $test->first());

        // }}}

    }

    // }}}
    // {{{ testLast

    /**
     * lastテスト
     *
     * @return void
     */
    public function testLast()
    {

        // {{{ 末尾データ取得

        $test = new xFrameworkPX_Util_MixedCollection(array(
            'key0' => 'keyData0',
            'key1' => 'keyData1',
            'key2' => 'keyData2',
            'data0',
            'data1',
            'data2',
        ));

        $this->assertEquals('data2', $test->last());

        // }}}
        // {{{ 末尾キーデータ取得

        $test = new xFrameworkPX_Util_MixedCollection(array(
            'data0',
            'data1',
            'data2',
            'key0' => 'keyData0',
            'key1' => 'keyData1',
            'key2' => 'keyData2'
        ));

        $this->assertEquals('keyData2', $test->last());

        // }}}

    }

    // }}}
    // {{{ testPop

    /**
     * popテスト
     *
     * @return void
     */
    public function testPop()
    {

        // {{{ 末尾のデータ取得と取得したデータの除去

        $test = new xFrameworkPX_Util_MixedCollection(array(
            'data0',
            'data1',
            'data2',
            'key0' => 'keyData0',
            'key1' => 'keyData1',
            'key2' => 'keyData2'
        ));

        $this->assertEquals(6, $test->count());
        $this->assertEquals('keyData2', $test->pop());
        $this->assertEquals(5, $test->count());
        $this->assertEquals('keyData1', $test->pop());
        $this->assertEquals(4, $test->count());
        $this->assertEquals('keyData0', $test->pop());
        $this->assertEquals(3, $test->count());
        $this->assertEquals('data2', $test->pop());
        $this->assertEquals(2, $test->count());
        $this->assertEquals('data1', $test->pop());
        $this->assertEquals(1, $test->count());
        $this->assertEquals('data0', $test->pop());
        $this->assertEquals(0, $test->count());

        // }}}

    }

    // }}}
    // {{{ testPush

    /**
     * pushテスト
     *
     * @return void
     */
    public function testPush()
    {

        // {{{ 末尾にデータを追加

        $test = new xFrameworkPX_Util_MixedCollection();

        $this->assertEquals(0, $test->count());
        $retTest = $test->push('data0', 'data1', 'data2');
        $this->assertEquals(3, $test->count());
        $this->assertEquals(3, $retTest->count());
        $this->assertEquals($test, $retTest);
        $this->assertEquals('data0', $test->{0});
        $this->assertEquals('data1', $test->{1});
        $this->assertEquals('data2', $test->{2});

        // }}}
        // {{{ 末尾に配列データを追加

        $retTest = $test->push(
            array(
                'key0' => 'keyData0',
                'key1' => 'keyData1',
                'key2' => 'keyData2',
                'data0',
                'data1',
                'data2'
            )
        );
        $this->assertEquals(4, $test->count());
        $this->assertEquals(4, $retTest->count());
        $this->assertEquals($test, $retTest);
        $this->assertEquals('data0', $test->{0});
        $this->assertEquals('data1', $test->{1});
        $this->assertEquals('data2', $test->{2});
        $this->assertEquals(
            array(
                'key0' => 'keyData0',
                'key1' => 'keyData1',
                'key2' => 'keyData2',
                'data0',
                'data1',
                'data2'
            ),
            $test->{3}
        );
        
        // }}}

    }

    // }}}
    // {{{ testShift

    /**
     * shiftテスト
     *
     * @return void
     */
    public function testShift()
    {

        // {{{ 先頭のデータ取得と取得したデータの除去

        $test = new xFrameworkPX_Util_MixedCollection(array(
            'data0',
            'data1',
            'data2',
            'key0' => 'keyData0',
            'key1' => 'keyData1',
            'key2' => 'keyData2'
        ));

        $this->assertEquals(6, $test->count());
        $this->assertEquals('data0', $test->shift());
        $this->assertEquals(5, $test->count());
        $this->assertEquals('data1', $test->shift());
        $this->assertEquals(4, $test->count());
        $this->assertEquals('data2', $test->shift());
        $this->assertEquals(3, $test->count());
        $this->assertEquals('keyData0', $test->shift());
        $this->assertEquals(2, $test->count());
        $this->assertEquals('keyData1', $test->shift());
        $this->assertEquals(1, $test->count());
        $this->assertEquals('keyData2', $test->shift());
        $this->assertEquals(0, $test->count());

        // }}}

    }

    // }}}
    // {{{ testUnshift

    /**
     * unshiftテスト
     *
     * @return void
     */
    public function testUnshift()
    {

        // {{{ 先頭にデータを追加

        $test = new xFrameworkPX_Util_MixedCollection();

        $this->assertEquals(0, $test->count());
        $retTest = $test->unshift('data0', 'data1', 'data2');
        $this->assertEquals(3, $test->count());
        $this->assertEquals(3, $retTest->count());
        $this->assertEquals($test, $retTest);
        $this->assertEquals('data2', $test->{0});
        $this->assertEquals('data1', $test->{1});
        $this->assertEquals('data0', $test->{2});

        $retTest = $test->unshift(
            array(
                'key0' => 'keyData0',
                'key1' => 'keyData1',
                'key2' => 'keyData2',
                'data0',
                'data1',
                'data2'
            )
        );
        $this->assertEquals(4, $test->count());
        $this->assertEquals(4, $retTest->count());
        $this->assertEquals($test, $retTest);
        $this->assertEquals(
            array(
                'key0' => 'keyData0',
                'key1' => 'keyData1',
                'key2' => 'keyData2',
                'data0',
                'data1',
                'data2'
            ),
            $test->{0}
        );
        $this->assertEquals('data2', $test->{1});
        $this->assertEquals('data1', $test->{2});
        $this->assertEquals('data0', $test->{3});

        // }}}

    }

    // }}}
    // {{{ testReverse

    /**
     * reverseテスト
     */
    public function testReverse()
    {

        // {{{ 要素の順序を逆にする

        $test = new xFrameworkPX_Util_MixedCollection(array(
            'data0',
            'data1',
            'data2',
            'key0' => 'keyData0',
            'key1' => 'keyData1',
            'key2' => 'keyData2'
        ));

        $retTest = $test->reverse();
        $this->assertEquals($test, $retTest);
        $this->assertEquals('keyData2', $test->shift());
        $this->assertEquals('keyData1', $test->shift());
        $this->assertEquals('keyData0', $test->shift());
        $this->assertEquals('data2', $test->shift());
        $this->assertEquals('data1', $test->shift());
        $this->assertEquals('data0', $test->shift());

        // }}}
    }

    // }}}
    // {{{ testShuffle

    /**
     * shuffleテスト
     */
    public function testShuffle()
    {

        // {{{ 要素の順序をランダムに入れ替える

        $test = new xFrameworkPX_Util_MixedCollection(array(
            'data0',
            'data1',
            'data2',
            'key0' => 'keyData0',
            'key1' => 'keyData1',
            'key2' => 'keyData2'
        ));

        $testArray = $test->getArrayCopy();
        $retTest = $test->shuffle();
        $retArray = $retTest->getArrayCopy();
        $this->assertEquals($test, $retTest);
        $this->assertNotEquals($testArray, $retArray);

        // }}}

    }

    // }}}
    // {{{ testIn

    /**
     * inテスト
     */
    public function testIn()
    {

        // {{{ データ存在チェック

        $test = new xFrameworkPX_Util_MixedCollection(array(
            'data0',
            'data1',
            'data2',
            1,
            'key0' => 'keyData0',
            'key1' => 'keyData1',
            'key2' => 'keyData2'
        ));

        $this->assertTrue($test->in('data0'));
        $this->assertTrue($test->in('keyData0'));
        $this->assertFalse($test->in('data3'));
        $this->assertTrue($test->in('1'));
        $this->assertFalse($test->in('1', true));

        // }}}
    }

    // }}}
    // {{{ testKey_Exists 

    /**
     * key_existsテスト
     */
    public function testKey_exists()
    {

        // {{{ キー存在チェック

        $test = new xFrameworkPX_Util_MixedCollection(array(
            'data0',
            'data1',
            'data2',
            'key0' => 'keyData0',
            'key1' => 'keyData1',
            'key2' => 'keyData2'
        ));

        $this->assertTrue($test->key_exists(0));
        $this->assertTrue($test->key_exists('key0'));
        $this->assertFalse($test->key_exists(3));
        $this->assertFalse($test->key_exists('key3'));

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
