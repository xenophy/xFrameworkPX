<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * extenderTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: extenderTest.php 882 2009-12-23 09:36:22Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'xFrameworkPX/extender.php';

// }}}
// {{{ extenderTest

/**
 * extenderTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/?class=extenderTest
 */
class extenderTest extends PHPUnit_Framework_TestCase
{

    // {{{ props

    /**
     * ディレクトリパス
     *
     * @var string
     */
    protected $_path;

    // }}}
    // {{{ __construct

    public function __construct()
    {
        $this->_path = dirname(__FILE__) . '\_files';
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
    // {{{ testNormalize_path

    /**
     * パス正規化関数テストメソッド
     *
     * @return void
     */
    public function testNormalize_path()
    {

        // {{{ ローカル変数初期化

        $src = array(
            '///foo/bar',
            'C:\\webroot\\\\'
        );

        $dest = array(
            '\\foo\\bar',
            'C:\\webroot\\'
        );

        // }}}
        // {{{ パス正規化テスト

        // {{{ \で正規化する場合

        foreach ($src as $key => $value) {
            $this->assertEquals($dest[$key], normalize_path($value));
        }

        // }}}

        $dest = array(
            '/foo/bar',
            'C:/webroot/'
        );

        // {{{ /で正規化する場合

        foreach ($src as $key => $value) {
            $this->assertEquals($dest[$key], normalize_path($value, '/'));
        }

        // }}}

        // }}}

    }

    // }}}
    // {{{ testFile_forceput_contents

    /**
     * ファイル出力関数テストメソッド
     *
     * @return void
     */
    public function testFile_forceput_contents()
    {
        $ret = false;
        $filePath1 = $this->_path . '\put\test1.txt';
        $filePath2 = $this->_path . '\put\test2.txt';

        // {{{ ファイル新規作成テスト(ディレクトリなし)

        $ret = file_forceput_contents($filePath1, 'Test Data 1');

        $this->assertTrue((bool)$ret);
        $this->assertTrue(file_exists($filePath1));
        $this->assertEquals('Test Data 1', file_get_contents($filePath1));

        // }}}
        // {{{ ファイル新規作成テスト(ディレクトリあり)

        $ret = file_forceput_contents($filePath2, 'Test Data 2');

        $this->assertTrue((bool)$ret);
        $this->assertTrue(file_exists($filePath2));
        $this->assertEquals('Test Data 2', file_get_contents($filePath2));

        // }}}
        // {{{ ファイル追記テスト

        $ret = file_forceput_contents(
            $filePath1, PHP_EOL . 'Append Test Data 1', FILE_APPEND
        );
        $this->assertTrue((bool)$ret);
        $this->assertEquals(
            'Test Data 1' . PHP_EOL . 'Append Test Data 1',
            file_get_contents($filePath1)
        );

        // }}}

        removeDirectory($this->_path . '\put');

        // }}}
    }

    // }}}
    // {{{ testMakeDirectory

    /**
     * ディレクトリ作成関数テストメソッド
     *
     * @return void
     */
    public function testMakeDirectory()
    {

        // {{{ ローカル変数初期化

        $ret = false;
        $dirPath1 = $this->_path . '\\hoge\\foo\\';
        $dirPath2 = $this->_path . '\\hoge\\bar';
        $dirPath3 = $this->_path . '\\hoge\\foo\\bar';

        // }}}
        // {{{ ディレクトリ生成テスト

        $ret = makeDirectory($dirPath1);
        $this->assertTrue($ret);
        $this->assertTrue(is_dir($dirPath1));
        removeDirectory($dirPath1);

        $ret = makeDirectory($dirPath2);
        $this->assertTrue($ret);
        $this->assertTrue(is_dir($dirPath2));
        removeDirectory($dirPath2);

        $ret = makeDirectory($dirPath3);
        $this->assertTrue($ret);
        $this->assertTrue(is_dir($dirPath3));

        removeDirectory($this->_path . '\\hoge');

        makeDirectory($dirPath1);
        $ret = makeDirectory($dirPath3);

        $this->assertTrue($ret);
        $this->assertTrue(is_dir($dirPath3));
        removeDirectory($this->_path . '\\hoge');

        // }}}

    }

    // }}}
    // {{{ testRemoveDirectory

    /**
     * ディレクトリ削除関数テストメソッド
     *
     * @return void
     */
    public function testRemoveDirectory()
    {
        $dirPath1 = $this->_path . '\hoge\foo\\';
        $dirPath2 = $this->_path . '\hoge\bar';
        $dirPath3 = $this->_path . '\hoge\foo\bar';

        // {{{ ディレクトリ削除テスト

        makeDirectory($dirPath1);
        removeDirectory($dirPath1);
        $this->assertFalse(is_dir($dirPath1));

        makeDirectory($dirPath2);
        removeDirectory($dirPath2);
        $this->assertFalse(is_dir($dirPath2));

        makeDirectory($dirPath3);
        removeDirectory($dirPath3);
        $this->assertFalse(is_dir($dirPath3));

        makeDirectory($dirPath1);
        makeDirectory($dirPath3);
        file_put_contents($dirPath1 . '\test.txt', '');
        removeDirectory($dirPath1);
        $this->assertFalse(is_dir($dirPath3));
        removeDirectory($this->_path . '\hoge');
        $this->assertFalse(is_dir($this->_path . '\hoge'));

        makeDirectory($dirPath3);
        removeDirectory($this->_path . '\hoge');
        $this->assertFalse(is_dir($this->_path . '\hoge'));

        // }}}
        // {{{ 存在しないディレクトリテスト

        removeDirectory($this->_path . '\hoge');
        $this->assertFalse(is_dir($this->_path . '\hoge'));

        // }}}

    }

    // }}}
    // {{{ testFile_copy

    /**
     * ファイルコピー関数テストメソッド
     *
     * @return void
     */
    public function testFile_copy()
    {
        $ret = false;
        $srcPath = $this->_path . '\put\test1.txt';
        $destPath = $this->_path . '\put\copy\test1_copy.txt';

        // {{{ コピー元ファイル生成

        file_forceput_contents($srcPath, 'Test Data 1');

        // }}}
        // {{{ コピーテスト

        $ret = file_copy($destPath, $srcPath);
        $this->assertTrue((bool)$ret);
        $this->assertTrue(file_exists($destPath));
        $this->assertEquals(
            file_get_contents($srcPath),
            file_get_contents($destPath)
        );

        $ret = file_copy($this->_path . '\put\test2.txt', $destPath);
        $this->assertTrue((bool)$ret);
        $this->assertTrue(file_exists($destPath));
        $this->assertEquals(
            file_get_contents($destPath),
            file_get_contents($this->_path . '\put\test2.txt')
        );

        // }}}

        if (file_exists($this->_path . '\put')) {
            removeDirectory($this->_path . '\put');
        }

    }

    // }}}
    // {{{ testGet_filename

    /**
     * ファイル名取得関数テストメソッド
     *
     * @return void
     */
    public function testGet_filename()
    {
        // {{{ ファイル名取得テスト

        $this->assertEquals(
            get_filename('/home/xenophy/xFrameworkPX.php'),
            'xFrameworkPX'
        );

        // }}}
        // {{{ ファイル名取得テスト

        $this->assertEquals(
            get_filename('/home/xenophy/xFrameworkPX.class.php'),
            'xFrameworkPX.class'
        );

        // }}}
        // {{{ ファイル名取得テスト

        $this->assertEquals(
            get_filename('/home/xenophy/xFrameworkPX.php'),
            'xFrameworkPX'
        );

        // }}}
        // {{{ ファイル名取得テスト

        $this->assertEquals(
            get_filename('/home/xenophy/xFrameworkPX.class.php'),
            'xFrameworkPX.class'
        );

        // }}}
        // {{{ 空文字列テスト

        $this->assertEquals(get_filename(''), '');

        // }}}
    }

    // }}}
    // {{{ testGet_filelist

    /**
     * ファイルリスト取得関数テストメソッド
     *
     * @return void
     */
    public function testGet_filelist()
    {
        // {{{ ローカル変数初期化

        $ret = null;

        if (substr(PHP_OS, 0, 3) == 'WIN') {
            $to = 'sjis-win';
        }
        $fileName = mb_convert_encoding_deep('ファイル', $to);

        $filePath = array(
            $this->_path . '\put\test1.txt',
            $this->_path . '\put\test2.txt',
            $this->_path . '\put\hoge\test1.xml',
            $this->_path . '\put\hoge\test2.xml',
            $this->_path . '\put\foo\test1.txt',
            $this->_path . '\put\foo\test2.txt',
            $this->_path . '\put\foo\bar\test1.txt',
            $this->_path . '\put\foo\bar\test2.txt',

            $this->_path . '\put\foo\bar\.xfvcroot',
            $this->_path . '\put\foo\bar\filename',
            $this->_path . '\put\foo\bar\\'.$fileName.'.txt',
        );

        $dest = null;

        // }}}
        // {{{ ファイル生成

        foreach ($filePath as $path) {
            file_forceput_contents($path, '');
        }

        // }}}
        // {{{ ファイルリスト取得テスト

        $path = $this->_path . '\put';

        // {{{ フィルタなし

        $dest = array(
            $filePath[8],
            $filePath[9],
            $filePath[6],
            $filePath[7],
            $filePath[10],
            $filePath[4],
            $filePath[5],
            $filePath[2],
            $filePath[3],
            $filePath[0],
            $filePath[1],
        );

        $ret = get_filelist($path);

        foreach ($ret as $key => $file) {
            $this->assertEquals($dest[$key], $file);
        }

        // }}}
        // {{{ フィルタあり(ファイル名)

        $dest = array(
            $filePath[6],
            $filePath[4],
            $filePath[2],
            $filePath[0],
        );

        $ret = get_filelist($path, array('filename' => 'test1'));

        foreach ($ret as $key => $file) {
            $this->assertEquals($dest[$key], $file);
        }

        // }}}
        // {{{ フィルタあり(拡張子)

        $dest = array(
            $filePath[2],
            $filePath[3],
        );

        $ret = get_filelist($path, array('ext' => 'xml'));

        foreach ($ret as $key => $file) {
            $this->assertEquals($dest[$key], $file);
        }

        // }}}
        // {{{ フィルタあり(両方)

        $dest = array(
            $filePath[7],
            $filePath[5],
            $filePath[1],
        );

        $ret = get_filelist(
            $path, array('filename' => 'test2', 'ext' => 'txt')
        );

        foreach ($ret as $key => $file) {
            $this->assertEquals($dest[$key], $file);
        }

        // }}}

        removeDirectory($path);

        // }}}
        // {{{ ディレクトリが存在しないケース

        $this->assertEquals(
            get_filelist('_hoge'), array()
        );

        // }}}
    }

    // }}}
    // {{{ testGet_relative_url

    /**
     * 相対パス取得関数テストメソッド
     *
     * @return void
     */
    public function testGet_relative_url()
    {
        // {{{ ローカル変数初期化

        $ret = '';

        // }}}
        // {{{ 同階層テスト

        $base = 'http://example.com/test/hoge/foo/index.html';
        $target = 'http://example.com/test/hoge/foo/index.html';
        $ret = get_relative_url($base, $target);
        $this->assertEquals('', $ret);

        $base = './test/hoge/foo/index.html';
        $target = './test/hoge/foo/index.html';
        $ret = get_relative_url($base, $target);
        $this->assertEquals('', $ret);

        $base = './test/hoge/foo/';
        $target = './test/hoge/foo/';
        $ret = get_relative_url($base, $target);
        $this->assertEquals('', $ret);

        $base = './test/hoge/foo';
        $target = './test/hoge/foo';
        $ret = get_relative_url($base, $target);
        $this->assertEquals('', $ret);

        // }}}
        // {{{ 一段階上の階層

        $base = 'http://example.com/test/hoge/foo/index.html';
        $target = 'http://example.com/test/hoge/index.html';
        $ret = get_relative_url($base, $target);
        $this->assertEquals('../', $ret);

        $base = './test/hoge/foo/index.html';
        $target = './test/hoge/index.html';
        $ret = get_relative_url($base, $target);
        $this->assertEquals('../', $ret);

        $base = './test/hoge/foo/';
        $target = './test/hoge/';
        $ret = get_relative_url($base, $target);
        $this->assertEquals('../', $ret);

        $base = './test/hoge/foo';
        $target = './test/hoge';
        $ret = get_relative_url($base, $target);
        $this->assertEquals('../', $ret);

        // }}}
        // {{{ 一段階下の階層

        $base = 'http://example.com/test/hoge/foo/index.html';
        $target = 'http://example.com/test/hoge/foo/bar/index.html';
        $ret = get_relative_url($base, $target);
        $this->assertEquals('', $ret);

        $base = './test/hoge/foo/index.html';
        $target = './test/hoge/foo/bar/index.html';
        $ret = get_relative_url($base, $target);
        $this->assertEquals('', $ret);

        $base = './test/hoge/foo/';
        $target = './test/hoge/foo/bar/';
        $ret = get_relative_url($base, $target);
        $this->assertEquals('', $ret);

        $base = './test/hoge/foo';
        $target = './test/hoge/foo/bar';
        $ret = get_relative_url($base, $target);
        $this->assertEquals('', $ret);

        // }}}

    }

    // }}}
    // {{{ testStripslashes_deep

    /**
     * クォート削除関数テストメソッド
     *
     * @return void
     */
    public function testStripslashes_deep()
    {
        // {{{ ローカル変数初期化

        $ret = null;
        $src = null;
        $dest = null;


        // }}}
        // {{{ クォート削除テスト

        // {{{ 文字列の場合

        $src = 'hoge\\foo\\bar';
        $dest = 'hogefoobar';

        $ret = stripslashes_deep($src);
        $this->assertEquals($dest, $ret);

        // }}}
        // {{{ 配列の場合

        $src = array(
            'testkey' => '\'hoge\'',
            'teston' => '\\foo\\bar',
            'foo' => '\"bar\"'
        );

        $dest = array(
            "testkey" => "'hoge'",
            "teston" => "foobar",
            "foo" => '"bar"'
        );

        $ret = stripslashes_deep($src);
        $this->assertEquals($dest, $ret);

        $src = array(
            'testkey' => '\'hoge\'',
            'moge' => array(
                'teston' => '\\foo\\bar',
                'ton' => array(
                    'foo' => '\"bar\"'
                )
            )
        );

        $dest = array(
            "testkey" => "'hoge'",
            "moge" => array(
                "teston" => "foobar",
                "ton" => array(
                    "foo" => '"bar"'
                )
            )
        );

        $ret = stripslashes_deep($src);
        $this->assertEquals($dest, $ret);

        // }}}

        // }}}
    }

    // }}}
    // {{{ testSys_get_temp_dir

    /**
     * 一時ファイル用ディレクトリパス取得関数テストメソッド
     *
     * @return void
     */
    public function testSys_get_temp_dir()
    {

        // {{{ ローカル変数初期化

        $ret = null;

        // }}}
        // {{{ 一時ファイル用ディレクトリパス取得テスト

        $ret = sys_get_temp_dir();
        $this->assertTrue(is_dir($ret));

        // }}}

    }

    // }}}
    // {{{ testMb_convert_encoding_deep

    /**
     * 多階層mb_convert_encoding関数テストメソッド
     *
     * @return void
     */
    public function testMb_convert_encoding_deep()
    {

        // {{{ ローカル変数初期化

        $ret = null;
        $src = null;
        $to = 'ASCII';
        $from = null;

        // }}}
        // {{{ 多階層mb_convert_encodingテスト

        // {{{ 文字列の場合

        $src = 'テスト文字列';
        $from = mb_detect_encoding($src);
        $ret = mb_convert_encoding_deep($src, $to, $from);
        $this->assertEquals($to, mb_detect_encoding($ret));

        // }}}
        // {{{ 配列の場合

        $src = array(
            'テスト文字列1',
            'テスト文字列2',
            'hoge' => 'テスト文字列3'
        );
        $ret = mb_convert_encoding_deep($src, $to);

        foreach ($ret as $value) {
            $this->assertEquals($to, mb_detect_encoding($value));
        }

        $src = array(
            'テスト文字列1',
            'hoge' => array(
                'foo' => 'テスト文字列2',
                'bar' => array('テスト文字列3')
            )
        );
        $ret = mb_convert_encoding_deep($src, $to);
        $this->assertTrue($this->_checkEncodingDeep($ret, $to));

        // }}}

        // }}}

    }

    // }}}
    // {{{ testStartsWith

    /**
     * 先頭一致確認関数テストメソッド
     *
     * @return void
     */
    public function testStartsWith()
    {

        // {{{ ローカル変数初期化

        $src = 'testhogefoobar';

        // }}}
        // {{{ 先頭一致確認テスト

        $this->assertTrue(startsWith($src, 'test'));
        $this->assertFalse(startsWith($src, 'hoge'));
        $this->assertFalse(startsWith($src, 'bar'));
        $this->assertFalse(startsWith($src, 'TEST'));

        // }}}
        // {{{ マルチバイトテスト

        $src = 'あaいiうuえeお';

        $this->assertTrue(startsWith($src, 'あ'));
        $this->assertTrue(startsWith($src, 'あa'));
        $this->assertFalse(startsWith($src, 'aい'));
        $this->assertFalse(startsWith($src, 'う'));

        // }}}

    }

    // }}}
    // {{{ testEndsWith

    /**
     * 後方一致確認関数テストメソッド
     *
     * @return void
     */
    public function testEndsWith()
    {

        // {{{ ローカル変数初期化

        $src = 'testhogefoobar';

        // }}}
        // {{{ 後方一致確認テスト

        $this->assertTrue(endsWith($src, 'bar'));
        $this->assertFalse(endsWith($src, 'testhogehogefoobar'));
        $this->assertFalse(endsWith($src, 'test'));
        $this->assertFalse(endsWith($src, 'BAR'));
        $this->assertFalse(endsWith($src, 'hoge'));

        // }}}
        // {{{ マルチバイトテスト

        $src = 'あaいiうuえeお';

        $this->assertTrue(endsWith($src, 'お'));
        $this->assertTrue(endsWith($src, 'えeお'));
        $this->assertFalse(endsWith($src, 'の'));
        $this->assertFalse(endsWith($src, '亜'));

        // }}}

    }

    // }}}
    // {{{ testMatchesIn

    /**
     * 部分一致確認関数テストメソッド
     *
     * @return void
     */
    public function testMatchesIn()
    {

        // {{{ ローカル変数初期化

        $src = 'testhogefoobar';

        // }}}
        // {{{ 部分一致確認テスト

        $this->assertTrue(matchesIn($src, 'test'));
        $this->assertTrue(matchesIn($src, 'hoge'));
        $this->assertTrue(matchesIn($src, 'bar'));
        $this->assertFalse(matchesIn($src, 'teston'));
        $this->assertFalse(matchesIn($src, 'TEST'));
        $this->assertFalse(matchesIn($src, 'testhogehogefoobar'));

        // }}}
        // {{{ マルチバイトテスト

        $src = 'あaいiうuえeお';

        $this->assertTrue(matchesIn($src, 'お'));
        $this->assertTrue(matchesIn($src, 'iうu'));
        $this->assertFalse(matchesIn($src, 'A'));
        $this->assertFalse(matchesIn($src, '亜'));

        // }}}

    }

    // }}}
    // {{{ testLcfirst

    /**
     * 先頭文字を小文字にする関数テストメソッド
     *
     * @return void
     */
    public function testLcfirst()
    {

        // {{{ ローカル変数初期化

        $ret = null;

        // }}}
        // {{{ 先頭文字を小文字にするテスト

        $ret = lcfirst('Test');
        $this->assertEquals('test', $ret);

        $ret = lcfirst('test');
        $this->assertEquals('test', $ret);

        // }}}
        // {{{ マルチバイトテスト

        $ret = lcfirst('あいうえお');
        $this->assertEquals('あいうえお', $ret);

        // }}}
        // {{{ 半角数字記号テスト

        $ret = lcfirst('12345667&%$');
        $this->assertEquals('12345667&%$', $ret);

        $ret = lcfirst('&%$12345667');
        $this->assertEquals('&%$12345667', $ret);
        // }}}

    }

    // }}}
    // {{{ testGet_status_code

    /**
     * ステータスコード取得関数テストメソッド
     *
     * @return void
     */
    public function testGet_status_code()
    {
        // {{{ ローカル変数初期化

        $ret = null;

        // }}}
        // {{{ ステータスコード取得テスト

        $ret = get_status_code(100);
        $this->assertEquals('Continue', $ret);

        $ret = get_status_code(101);
        $this->assertEquals('Switching Protocols', $ret);

        $ret = get_status_code(200);
        $this->assertEquals('OK', $ret);

        $ret = get_status_code(201);
        $this->assertEquals('Created', $ret);

        $ret = get_status_code(202);
        $this->assertEquals('Accepted', $ret);

        $ret = get_status_code(203);
        $this->assertEquals('Non-Authoritative Information', $ret);

        $ret = get_status_code(204);
        $this->assertEquals('No Content', $ret);

        $ret = get_status_code(205);
        $this->assertEquals('Reset Content', $ret);

        $ret = get_status_code(206);
        $this->assertEquals('Partial Content', $ret);

        $ret = get_status_code(300);
        $this->assertEquals('Multiple Choices', $ret);

        $ret = get_status_code(301);
        $this->assertEquals('Moved Permanently', $ret);

        $ret = get_status_code(302);
        $this->assertEquals('Found', $ret);

        $ret = get_status_code(303);
        $this->assertEquals('See Other', $ret);

        $ret = get_status_code(304);
        $this->assertEquals('Not Modified', $ret);

        $ret = get_status_code(305);
        $this->assertEquals('Use Proxy', $ret);

        $ret = get_status_code(307);
        $this->assertEquals('Temporary Redirect', $ret);

        $ret = get_status_code(400);
        $this->assertEquals('Bad Request', $ret);

        $ret = get_status_code(401);
        $this->assertEquals('Unauthorized', $ret);

        $ret = get_status_code(402);
        $this->assertEquals('Payment Required', $ret);

        $ret = get_status_code(403);
        $this->assertEquals('Forbidden', $ret);

        $ret = get_status_code(404);
        $this->assertEquals('Not Found', $ret);

        $ret = get_status_code(405);
        $this->assertEquals('Method Not Allowed', $ret);

        $ret = get_status_code(406);
        $this->assertEquals('Not Acceptable', $ret);

        $ret = get_status_code(407);
        $this->assertEquals('Proxy Authentication Required', $ret);

        $ret = get_status_code(408);
        $this->assertEquals('Request Time-out', $ret);

        $ret = get_status_code(409);
        $this->assertEquals('Conflict', $ret);

        $ret = get_status_code(410);
        $this->assertEquals('Gone', $ret);

        $ret = get_status_code(411);
        $this->assertEquals('Length Required', $ret);

        $ret = get_status_code(412);
        $this->assertEquals('Precondition Failed', $ret);

        $ret = get_status_code(413);
        $this->assertEquals('Request Entity Too Large', $ret);

        $ret = get_status_code(414);
        $this->assertEquals('Request-URI Too Large', $ret);

        $ret = get_status_code(415);
        $this->assertEquals('Unsupported Media Type', $ret);

        $ret = get_status_code(416);
        $this->assertEquals('Requested range not satisfiable', $ret);

        $ret = get_status_code(417);
        $this->assertEquals('Expectation Failed', $ret);

        $ret = get_status_code(500);
        $this->assertEquals('Internal Server Error', $ret);

        $ret = get_status_code(501);
        $this->assertEquals('Not Implemented', $ret);

        $ret = get_status_code(502);
        $this->assertEquals('Bad Gateway', $ret);

        $ret = get_status_code(503);
        $this->assertEquals('Service Unavailable', $ret);

        $ret = get_status_code(504);
        $this->assertEquals('Gateway Time-out', $ret);

        $ret = get_status_code(505);
        $this->assertNull($ret);

        // }}}

    }

    // }}}
    // {{{ testIsSecure

    /**
     * SSL接続判定関数テストメソッド
     *
     * @return void
     */
    public function testIsSecure()
    {

        // {{{ 非SSL時のテスト

        $_SERVER['HTTPS'] = null;
        $this->assertFalse(is_secure());

        // }}}
        // {{{ SSL時のテスト

        $_SERVER['HTTPS'] = 'on';
        $this->assertTrue(is_secure());

        $_SERVER['HTTPS'] = null;
        $_SERVER['HTTP_VIA'] = 'on';
        $this->assertTrue(is_secure());

        // }}}

    }

    // }}}
    // {{{ testBaseName

    /**
     * SSL接続判定関数テストメソッド
     *
     * @return void
     */
    public function testBaseName()
    {

        $url = null;
        $_SERVER['SERVER_NAME'] = 'localhost';

        // {{{ xFrameworkPXが動作する場合はこのパターン

        $_SERVER['PHP_SELF'] = '/index.php';

        // }}}
        // {{{ 非SSL時のテスト

        $_SERVER['HTTPS'] = null;

        $this->assertEquals(base_name(), 'http://localhost/');

        // }}}
        // {{{ SSL時のテスト

        $_SERVER['HTTPS'] = 'on';
        $this->assertEquals(base_name(), 'https://localhost/');

        // }}}

    }

    // }}}
    // {{{ _checkEncodingDeep

    /**
     * 多階層エンコードチェックメソッド
     *
     * @param mixed $src チェック対象の文字列または配列
     * @param string $encode チェックする文字エンコード名
     * @return bool チェック結果
     */
    private function _checkEncodingDeep($src, $encode)
    {

        // {{{ ローカル変数初期化

        $ret = false;

        // }}}

        if (is_array($src)) {

            foreach ($src as $value) {
                $ret = $this->_checkEncodingDeep($value, $encode);

                if ($ret === false) {
                    break;
                }
            }

        } else {
            $ret = ($encode == mb_detect_encoding($src));
        }

        return $ret;
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
