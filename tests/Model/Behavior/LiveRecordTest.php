<?php

// SVN $Id: LiveRecordTest.php 964 2009-12-25 17:23:11Z tamari $

/**
 * xFrameworkPX_Model_Behavior_LiveRecordTest Class File
 *
 * PHP versions 5
 *
 * xFrameworkPX : MVC Web application framework (http://px.xframework.net)
 * Copyright 2006 - 2009 Xenophy CO., LTD.(http://www.xenophy.com)
 *
 * Licensed under The MIT
 *
 * @filesource
 * @copyright     (c) 2006 - 2009 Xenophy CO., LTD.(http://www.xenophy.com)
 * @link          http://www.xframeworkpx.com xFrameworkPX
 * @package       Tests\Model\Behavior
 * @since         xFrameworkPX 3.5.0
 * @version       $Revision: 964 $
 * @license       http://www.opensource.org/licenses/mit-license.php
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';

require_once 'xFrameworkPX/extender.php';
require_once 'xFrameworkPX/Object.php';
require_once 'xFrameworkPX/Exception.php';
require_once 'xFrameworkPX/Util/Observable.php';
require_once 'xFrameworkPX/Util/MixedCollection.php';
require_once 'xFrameworkPX/Model.php';
require_once 'xFrameworkPX/Model/Adapter.php';
require_once 'xFrameworkPX/Model/Adapter/MySQL.php';
require_once 'xFrameworkPX/Model/RapidDrive.php';
require_once 'xFrameworkPX/Model/Behavior.php';
require_once 'xFrameworkPX/Model/Exception.php';
require_once 'xFrameworkPX/Model/Behavior/LiveRecord.php';
require_once 'xFrameworkPX/Util/Serializer.php';


// }}}
// {{{ xFrameworkPX_Model_Behavior_LiveRecordTest

/**
 * xFrameworkPX_Model_Behavior_LiveRecordTest Class
 *
 * @copyright     (c) 2006 - 2009 Xenophy CO., LTD.(http://www.xenophy.com)
 * @link          http://www.xframeworkpx.com xFrameworkPX
 * @package       Tests\Model\Behavior
 * @version       xFrameworkPX 3.5.0
 * @license       http://www.opensource.org/licenses/mit-license.php
 */
class xFrameworkPX_Model_Behavior_LiveRecordTest
extends PHPUnit_Framework_TestCase
{

    protected $_dbXml = null;
    protected $_db = null;
    protected $_table = null;
    protected $_rd = null;


    // {{{ setUp

    /**
     * セットアップ
     *
     * @return void
     */
    protected function setUp()
    {

        // {{{ テストクラスファイル読み込み

        require_once dirname(__FILE__) . '/_files/articletest.php';

        // }}}
        // {{{ テスト用database.xml読み込み

        $this->_dbXml = simplexml_load_string(
            file_get_contents(dirname(__FILE__) . '/_files/database.xml')
        );

        $xml = $this->_dbXml->connection;

        // }}}
        // {{{ XMLのデータベース名とテーブル名をプロパティに保存

        $this->_table = (string)$xml->table;
        $this->_db = (string)$xml->database;

        // }}}
        // {{{ データベース接続設定オブジェクト生成、プロパティに保存

        $this->dbConf = new xFrameworkPX_Util_MixedCollection(
            array(
                'charset'  => (string)$xml->charset,
                'adapter'  => (string)$xml->adapter,
                'driver'   => (string)$xml->driver,
                'host'     => (string)$xml->host,
                'user'     => (string)$xml->user,
                'password' => (string)$xml->password,
                'database' => (string)$xml->database,
                'prefix'   => (string)$xml->prefix,
                'port'     => (string)$xml->port,
                'socket'   => (string)$xml->socket,
            )
        );

        // }}}
        // {{{ コネクション取得、失敗時はテスト中止

       $link = mysql_connect(
           (string)$xml->host,
           (string)$xml->user,
           (string)$xml->password
       ) or $this->fail();

        // }}}
        // {{{ データベースが存在しない場合は作成

        $sql = '';
        $sql .= 'CREATE DATABASE IF NOT EXISTS ';
        $sql .= $this->_db;
        $sql .= '';
        mysql_query($sql);

        // }}}
        // {{{ データベース選択

        mysql_select_db($this->_db, $link);

        // }}}
        // {{{ テーブルが存在しない場合は作成

        $sql = '';
        $sql .= 'CREATE TABLE IF NOT EXISTS `' . $this->_table . '` (';
        $sql .= '  `id` int(11) NOT NULL AUTO_INCREMENT,';
        $sql .= '  `title` varchar(255) ';
        $sql .= '  DEFAULT NULL COMMENT \'title\',';
        $sql .= '  `message` text COMMENT \'message\',';
        $sql .= '  `created` datetime DEFAULT NULL,';
        $sql .= '  `modified` datetime DEFAULT NULL,';
        $sql .= '  PRIMARY KEY (`id`)';
        $sql .= ') ENGINE=InnoDB DEFAULT CHARSET=latin1';
        mysql_query($sql);

        // }}}
        // {{{ 初期値作成
/*
        $strSql = '';
        $strSql .= 'INSERT INTO `'.$this->_table.'`';
        $strSql .= ' ( title,message ) ';
        $strSql .= 'VALUES ( ';
        $strSql .= 'Commit,';
        $strSql .= 'コミットテスト';
        $strSql .= ' )';
        mysql_query( $strSql );
*/
        // }}}
        // {{{ 設定オブジェクト生成

        $mixConf = new xFrameworkPX_Util_MixedCollection( 
            array('conn'=> 'default')
        );

        // {{{ データベース設定格納

        $mixConf->database = $this->_dbXml;

        // }}}
        // {{{ データベース設定格納

        $mixConf->px = array(
            'BEHAVIOR_DIR' => '../behaviors',
            'PX_LIB_DIR'   => 'C:\UserDir\px\library\xFrameworkPX',
            'CACHE_DIR'    => dirname(__FILE__) . '\_files\cache'
            // todo: 固定パスではなく、可変に
        );

        // }}}
        // {{{ モデル生成

        $this->_rd = new article_test($mixConf);
//        $this->_rd = new xFrameworkPX_Model_RapidDrive( $mixConf );
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

        // {{{ テーブル削除

        $sql = '';
        $sql .= 'DROP TABLE `' . $this->_table . '`';
        mysql_query($sql);

        // }}}

        @unlink(dirname(__FILE__) . '\_files\cache\tbl_article_test.schema');
    }

    // }}}
    // {{{ testBindRow

    /**
     * bindRowテスト
     *
     * @return void
     */
    public function testBindRow()
    {

        // {{{ LiveRecordインスタンス生成

        $liveRecord = new xFrameworkPX_Model_Behavior_LiveRecord($this->_rd);

        // }}}
        // {{{ 値を挿入してテスト準備

        $sql = '';
        $sql .= 'INSERT INTO `' . $this->_table . '`';
        $sql .= ' ( `title`,`message` ) ';
        $sql .= 'VALUES ( ';
        $sql .= '\'test\',';
        $sql .= '\'foo\'';
        $sql .= ' )';

        mysql_query($sql);

        $sql = '';
        $sql .= 'INSERT INTO `' . $this->_table . '`';
        $sql .= ' ( `title`,`message` ) ';
        $sql .= 'VALUES ( ';
        $sql .= '\'test\',';
        $sql .= '\'hoge\'';
        $sql .= ' )';

        mysql_query($sql);

        // }}}
        // {{{ 取得件数チェック

        $sqlCnt = '';
        $sqlCnt .= ' SELECT ';
        $sqlCnt .= '   COUNT(*) ';
        $sqlCnt .= ' FROM ';
        $sqlCnt .= '   ' . $this->_table . ' ';
        $sqlCnt .= ' WHERE  ';
        $sqlCnt .= '      ' . $this->_table . '.title = :title ';

        $bindCnt = array();
        $bindCnt[':title'] = 'test';

        $paramCnt = array();
        $paramCnt['query'] = $sqlCnt;
        $paramCnt['bind'] = $bindCnt;

        $resCnt = $liveRecord->bindRow($paramCnt);
        $this->assertTrue($resCnt['COUNT(*)'] == 2);

        // }}}
        // {{{ 取得件数チェック(Where句付き)

        $sqlCnt = '';
        $sqlCnt .= ' SELECT ';
        $sqlCnt .= '   COUNT(*) ';
        $sqlCnt .= ' FROM ';
        $sqlCnt .= '   ' . $this->_table . ' ';

        $bindCnt = array();
        $bindCnt[':title'] = 'test';
        $bindCnt[':message'] = 'hoge';

        $paramCnt = array();
        $paramCnt['query'] = $sqlCnt;
        $paramCnt['bind'] = $bindCnt;
        $paramCnt['where'] = array(
            $this->_table . '.title = :title ',
            $this->_table . '.message = :message '
        );

        $resCnt = $liveRecord->bindRow($paramCnt);
        $this->assertTrue($resCnt['COUNT(*)'] == 1);

        // }}}
        // {{{ クエリーを返す

        $sqlCnt = '';
        $sqlCnt .= ' SELECT ';
        $sqlCnt .= '   COUNT(*) ';
        $sqlCnt .= ' FROM ';
        $sqlCnt .= '   ' . $this->_table.' ';

        $bindCnt = array();
        $bindCnt[':title'] = 'test';
        $bindCnt[':message'] = 'hoge';


        $paramCnt = array();
        $paramCnt['query'] = $sqlCnt;
        $paramCnt['bind'] = $bindCnt;
        $paramCnt['onlyquery'] = true;
        $paramCnt['where'] = array(
            $this->_table . '.title = :title ',
            $this->_table . '.message = :message '
        );

        $resCnt = $liveRecord->bindRow($paramCnt, true);

        $test = '';
        $test .= ' SELECT    COUNT(*)  FROM    tbl_article_test  ';
        $test .= 'WHERE tbl_article_test.title = :title  ';
        $test .= 'AND tbl_article_test.message = :message LIMIT 0, 1';

        $this->assertEquals($test, $resCnt);

        // }}}
        // {{{ 例外処理

        try {
            $sqlCnt = '';
            $sqlCnt .= ' SELECT ';
            $sqlCnt .= '   COUNT(*) ';
            $sqlCnt .= ' FROM ';
            $sqlCnt .= '   ' . $this->_table . ' ';
            $sqlCnt .= ' WHERE  ';
            $sqlCnt .= '      ' . $this->_table . '.title = :title ';
            $sqlCnt .= ' LIMIT 0 5';

            $bindCnt = array();
            $bindCnt[':title'] = 'test';

            $paramCnt = array();
            $paramCnt['query'] = $sqlCnt;
            $paramCnt['bind'] = $bindCnt;

            $resCnt = $liveRecord->bindRow($paramCnt);

        } catch (xFrameworkPX_Model_Exception $ex) {
            $this->assertTrue(true);
        }

        // }}}

    }

    // }}}
    // {{{ testBindRowAll

    /**
     * bindRowAllテスト
     *
     * @return void
     */
    public function testBindRowAll()
    {

        // {{{ LiveRecordインスタンス生成

        $liveRecord = new xFrameworkPX_Model_Behavior_LiveRecord($this->_rd);

        // }}}
        // {{{ 値を挿入してテスト準備

        $sql = '';
        $sql .= 'INSERT INTO `' . $this->_table . '`';
        $sql .= ' ( `title`,`message` ) ';
        $sql .= 'VALUES ( ';
        $sql .= '\'test\',';
        $sql .= '\'hoge\'';
        $sql .= ' )';

        mysql_query($sql);

        $sql = '';
        $sql .= 'INSERT INTO `' . $this->_table . '`';
        $sql .= ' ( `title`,`message` ) ';
        $sql .= 'VALUES ( ';
        $sql .= '\'test\',';
        $sql .= '\'foo\'';
        $sql .= ' )';

        mysql_query($sql);

        // }}}
        // {{{ 取得テスト

        $sql = '';
        $sql .= ' SELECT ';
        $sql .= '   * ';
        $sql .= ' FROM ';
        $sql .= '   ' . $this->_table . ' ';

        $bind = array();
        $bind[':title'] = 'test';

        $param = array();
        $param['query'] = $sql;
        $param['bind'] = $bind;
        $param['where'] = 'WHERE ' . $this->_table . '.title = :title ';

        $res = $liveRecord->bindRowAll($param);

        $this->assertEquals($res[0]['title'], 'test');
        $this->assertEquals($res[0]['message'], 'hoge');
        $this->assertEquals($res[1]['title'], 'test');
        $this->assertEquals($res[1]['message'], 'foo');

        // WHEREが配列の場合
        $where = array(
            'title = :title',
            'message = :mes'
        );

        $bind = array();
        $bind[':title'] = 'test';
        $bind[':mes'] = 'foo';

        $param = array();
        $param['query'] = $sql;
        $param['bind'] = $bind;
        $param['where'] = $where;

        $res = $liveRecord->bindRowAll($param);

        $this->assertEquals($res[0]['title'], 'test');
        $this->assertEquals($res[0]['message'], 'foo');

        // WHERE句なし
        $bind = array();

        $param = array();
        $param['query'] = $sql;

        $res = $liveRecord->bindRowAll($param);

        $this->assertEquals($res[0]['title'], 'test');
        $this->assertEquals($res[0]['message'], 'hoge');
        $this->assertEquals($res[1]['title'], 'test');
        $this->assertEquals($res[1]['message'], 'foo');

        // }}}

    }

    // }}}
    // {{{ testBindCount

    /**
     * bindCountテスト
     *
     * @return void
     */
    public function testBindCount()
    {

        // {{{ LiveRecordインスタンス生成

        $liveRecord = new xFrameworkPX_Model_Behavior_LiveRecord($this->_rd);

        // }}}
        // {{{ 値を挿入してテスト準備

        $sql = '';
        $sql .= 'INSERT INTO `' . $this->_table . '`';
        $sql .= ' ( `title`,`message` ) ';
        $sql .= 'VALUES ( ';
        $sql .= '\'test\',';
        $sql .= '\'hoge\'';
        $sql .= ' )';

        mysql_query($sql);

        $sql = '';
        $sql .= 'INSERT INTO `' . $this->_table . '`';
        $sql .= ' ( `title`,`message` ) ';
        $sql .= 'VALUES ( ';
        $sql .= '\'test\',';
        $sql .= '\'foo\'';
        $sql .= ' )';

        mysql_query($sql);

        // }}}
        // {{{ 取得件数チェック

        // WHERE句が文字列の場合
        $bindCnt = array();
        $bindCnt[':title'] = 'test';

        $paramCnt = array();
        $paramCnt['query'] = '';
        $paramCnt['bind'] = $bindCnt;
        $paramCnt['where'] = 'WHERE ' . $this->_table . '.title = :title ';

        $resCnt = $liveRecord->bindCount($paramCnt);

        $this->assertTrue($resCnt == 2);

        // WHERE句が配列の場合
        $bindCnt = array();
        $bindCnt[':title'] = 'test';
        $bindCnt[':mes'] = 'foo';

        $where = array(
            'title = :title',
            'message = :mes'
        );

        $paramCnt = array();
        $paramCnt['where'] = $where;
        $paramCnt['bind'] = $bindCnt;

        $resCnt = $liveRecord->bindCount($paramCnt);
        $this->assertTrue($resCnt == 1);

        // WHERE句がない場合

        $paramCnt = array();
        $paramCnt['query'] = '';

        $resCnt = $liveRecord->bindCount($paramCnt);
        $this->assertTrue($resCnt == 2);

        // SELECT句を付けた場合

        $paramCnt = array();
        $paramCnt['select'] = '*';

        $resCnt = $liveRecord->bindCount($paramCnt);

        // SELECT * FROM tbl_article_testが実行されるが、intvalを通すため0になる
        $this->assertTrue($resCnt === 0);

        // onlyqueryをtrueに設定した場合はクエリーを返す

        $paramCnt = array();
        $paramCnt['select'] = '*';

        $resCnt = $liveRecord->bindCount($paramCnt, true);

        $this->assertEquals(
            ' SELECT * FROM tbl_article_test LIMIT 0, 1', $resCnt
        );

        // }}}

    }

    // }}}
    // {{{ testBindConnection

    /**
     * bindConnectionテスト
     *
     * @return void
     */
    public function testBindConnection()
    {

        // {{{ LiveRecordインスタンス生成

        $liveRecord = new xFrameworkPX_Model_Behavior_LiveRecord($this->_rd);

        // }}}
        // {{{ PDOオブジェクト取得

        $pdo = $liveRecord->bindConnection();

        // }}}
        // {{{ PDOオブジェクトの場合はtrue

        $this->assertType('PDO', $pdo);

        // }}}

    }

    // }}}
    // {{{ testBindDriver

    /**
     * bindDriverテスト
     *
     * @return void
     */
    public function testBindDriver()
    {

        // {{{ LiveRecordインスタンス生成

        $liveRecord = new xFrameworkPX_Model_Behavior_LiveRecord($this->_rd);

        // }}}
        // {{{ PDOドライバー取得

        $driver = $liveRecord->bindDriver();

        // }}}
        // {{{ ドライバーがmysqlの場合はtrue

        $this->assertEquals('mysql', $driver);

        // }}}

    }

    // }}}
    // {{{ testBindDatabase

    /**
     * bindDatabaseテスト
     *
     * @return void
     */
    public function testBindDatabase()
    {

        // {{{ LiveRecordインスタンス生成

        $liveRecord = new xFrameworkPX_Model_Behavior_LiveRecord($this->_rd);

        // }}}
        // {{{ データベース名取得

        $dbName = $liveRecord->bindDatabase();

        // }}}
        // {{{ データベース名が設定したものと同一の場合はtrue

        $this->assertEquals($this->_db, $dbName);

        // }}}

    }

    // }}}
    // {{{ testBindSchema

    /**
     * bindSchemaテスト
     *
     * @return void
     */
    public function testBindSchema()
    {

        // {{{ LiveRecordインスタンス生成

        $liveRecord = new xFrameworkPX_Model_Behavior_LiveRecord($this->_rd);

        // }}}
        // {{{ フィールド情報取得

        $fields = $liveRecord->bindSchema();

        // }}}
        // {{{ フィールド名が設定したものと同一の場合はtrue

        $this->assertEquals('id', $fields[0]['Field']);
        $this->assertEquals('title', $fields[1]['Field']);
        $this->assertEquals('message', $fields[2]['Field']);
        $this->assertEquals('created', $fields[3]['Field']);
        $this->assertEquals('modified', $fields[4]['Field']);

        // }}}
        // {{{ キャッシュファイル存在確認

        $this->assertTrue(
            file_exists(
                dirname(__FILE__) . '\_files\cache\tbl_article_test.schema'
            )
        );

        // }}}
        // {{{ フィールド情報取得（キャッシュから）

        $fields = $liveRecord->bindSchema();

        // }}}
        // {{{ フィールド名が設定したものと同一の場合はtrue

        $this->assertEquals('id', $fields[0]['Field']);
        $this->assertEquals('title', $fields[1]['Field']);
        $this->assertEquals('message', $fields[2]['Field']);
        $this->assertEquals('created', $fields[3]['Field']);
        $this->assertEquals('modified', $fields[4]['Field']);

        // }}}

    }

    // }}}
    // {{{ testBindExec

    /**
     * bindExecテスト
     *
     * @return void
     */
    public function testBindExec()
    {

        // {{{ LiveRecordインスタンス生成

        $liveRecord = new xFrameworkPX_Model_Behavior_LiveRecord($this->_rd);

        // }}}
        // {{{ SQL作成

        $sql = '';
        $sql .= 'CREATE TABLE IF NOT EXISTS `tbl_testtest` (';
        $sql .= '  `id` int(11) DEFAULT NULL,';
        $sql .= '  `title` varchar(255) ';
        $sql .= '  DEFAULT NULL COMMENT \'title\',';
        $sql .= '  `message` text COMMENT \'message\',';
        $sql .= '  `created` datetime DEFAULT NULL,';
        $sql .= '  `modified` datetime DEFAULT NULL';
        $sql .= ') ENGINE=InnoDB DEFAULT CHARSET=latin1';

        // }}}
        // {{{ bindExecコール

        $param = array();
        $param['query'] = $sql;

        $res = $liveRecord->bindExec($param);

        // }}}
        // {{{ int0を返せばtrue

        $this->assertTrue($res === 0);

        // }}}
        // {{{ SQL作成

        $sql = '';
        $sql .= 'DROP TABLE `tbl_testtest`';

        // }}}
        // {{{ bindExecコール

        $param = array();
        $param['query'] = $sql;

        $res = $liveRecord->bindExec($param);

        // }}}
        // {{{ int0を返せばtrue

        $this->assertTrue($res === 0);

        // }}}

    }

    // }}}
    // {{{ testBindExecute

    /**
     * bindExecuteテスト
     *
     * @return void
     */
    public function testBindExecute()
    {

        // {{{ LiveRecordインスタンス生成

        $liveRecord = new xFrameworkPX_Model_Behavior_LiveRecord($this->_rd);

        // }}}
        // {{{ SQL作成

        $sql .= 'INSERT INTO ';
        $sql .= '    ' . $this->_table . ' ';
        $sql .= '( ';
        $sql .= '    title, ';
        $sql .= '    message ';
        $sql .= ') VALUES ( ';
        $sql .= '   :title , ';
        $sql .= '   :mes ';
        $sql .= ') ';

        $bind = array();
        $bind[ ':title' ] = 'test';
        $bind[ ':mes' ] = 'testtest';



        $sql = sprintf($sql, 'test', 'testtest');

        // }}}
        // {{{ bindExecuteコール

        $param = array();
        $param['query'] = $sql;
        $param['bind'] = $bind;
        $param['fetch'] = '';

        $res = $liveRecord->bindExecute($param);

        // }}}
        // {{{ 実行結果判定

        $this->assertTrue($res);

        // }}}
    }

    // }}}
    // {{{ testBindBeginTrans

    /**
     * bindBeginTransテスト
     *
     * @return void
     */
    public function testBindBeginTrans()
    {

        // {{{ LiveRecordインスタンス生成

        $liveRecord = new xFrameworkPX_Model_Behavior_LiveRecord($this->_rd);

        // }}}
        // {{{ bindBeginTransコール

        $res = $liveRecord->bindBeginTrans();

        // }}}
        // {{{ 実行結果判定

        $this->assertTrue($res);

        // }}}
    }

    // }}}
    // {{{ testBindCommit

    /**
     * bindCommitテスト
     * 
     * 内部でbindRow、bindExecute使用
     *
     * @return void
     */
    public function testBindCommit()
    {

        // {{{ LiveRecordインスタンス生成

        $liveRecord = new xFrameworkPX_Model_Behavior_LiveRecord($this->_rd);

        // {{{ 取得件数チェック

        $sqlCnt = '';
        $sqlCnt .= ' SELECT ';
        $sqlCnt .= '   COUNT(*) ';
        $sqlCnt .= ' FROM ';
        $sqlCnt .= '   ' . $this->_table . ' ';
        $sqlCnt .= ' WHERE  ';
        $sqlCnt .= '      ' . $this->_table . '.title = :title ';

        $bindCnt = array();
        $bindCnt[':title'] = 'hogetest';

        $paramCnt = array();
        $paramCnt['query'] = $sqlCnt;
        $paramCnt['bind'] = $bindCnt;

        $resCnt = $liveRecord->bindRow($paramCnt);
        $this->assertTrue($resCnt['COUNT(*)'] == 0);

        // }}}
        // {{{ トランザクション開始

        $liveRecord->bindBeginTrans();

        // }}}
        // {{{ SQL作成

        $sql .= 'INSERT INTO ';
        $sql .= '    ' . $this->_table . ' ';
        $sql .= '( ';
        $sql .= '    title, ';
        $sql .= '    message ';
        $sql .= ') VALUES ( ';
        $sql .= '   :title , ';
        $sql .= '   :mes ';
        $sql .= ') ';

        $bind = array();
        $bind[':title'] = 'hogetest';
        $bind[':mes']   = 'hogehoge';

        // }}}
        // {{{ 値を挿入

        $param = array();
        $param['query'] = $sql;
        $param['bind']  = $bind;
        $param['fetch'] = '';

        $liveRecord->bindExecute($param);

        // }}}
        // {{{ 取得件数チェック

        $resCnt = $liveRecord->bindRow($paramCnt);
        $this->assertTrue($resCnt['COUNT(*)'] == 1);

        // }}}
        // {{{ bindCommitコール

        $res = $liveRecord->bindCommit();

        // }}}
        // {{{ 取得件数チェック

        $resCnt = $liveRecord->bindRow($paramCnt);
        $this->assertTrue($resCnt['COUNT(*)'] == 1);

        // }}}

    }

    // }}}
    // {{{ testBindRollback

    /**
     * bindRollbackテスト
     * 
     * 内部でbindRow、bindExecute使用
     *
     * @return void
     */
    public function testBindRollback()
    {

        // {{{ LiveRecordインスタンス生成

        $liveRecord = new xFrameworkPX_Model_Behavior_LiveRecord($this->_rd);

        // {{{ 取得件数チェック

        $sqlCnt = '';
        $sqlCnt .= ' SELECT ';
        $sqlCnt .= '   COUNT(*) ';
        $sqlCnt .= ' FROM ';
        $sqlCnt .= '   ' . $this->_table . ' ';
        $sqlCnt .= ' WHERE  ';
        $sqlCnt .= '      ' . $this->_table . '.title = :title ';

        $bindCnt = array();
        $bindCnt[':title'] = 'hogetest';

        $paramCnt = array();
        $paramCnt['query'] = $sqlCnt;
        $paramCnt['bind'] = $bindCnt;

        $resCnt = $liveRecord->bindRow($paramCnt);
        $this->assertTrue($resCnt['COUNT(*)'] == 0);

        // }}}
        // {{{ トランザクション開始

        $liveRecord->bindBeginTrans();

        // }}}
        // {{{ SQL作成

        $sql .= 'INSERT INTO ';
        $sql .= '    ' . $this->_table . ' ';
        $sql .= '( ';
        $sql .= '    title, ';
        $sql .= '    message ';
        $sql .= ') VALUES ( ';
        $sql .= '   :title , ';
        $sql .= '   :mes ';
        $sql .= ') ';

        $bind = array();
        $bind[':title'] = 'hogetest';
        $bind[':mes'] = 'hogehoge';

        // }}}
        // {{{ 値を挿入

        $param = array();
        $param['query'] = $sql;
        $param['bind']  = $bind;
        $param['fetch'] = '';

        $liveRecord->bindExecute($param);

        // }}}
        // {{{ 取得件数チェック

        $resCnt = $liveRecord->bindRow($paramCnt);
        $this->assertTrue($resCnt['COUNT(*)'] == 1);

        // }}}
        // {{{ bindRollbackコール

        $res = $liveRecord->bindRollback();

        // }}}
        // {{{ 取得件数チェック

        $resCnt = $liveRecord->bindRow($paramCnt);
        $this->assertTrue($resCnt['COUNT(*)'] == 0);

        // }}}

    }

    // }}}
    // {{{ testBindInsert

    /**
     * bindInsertテスト
     * 
     * 内部でbindRow使用
     *
     * @return void
     */
    public function testBindInsert()
    {

        // {{{ LiveRecordインスタンス生成

        $liveRecord = new xFrameworkPX_Model_Behavior_LiveRecord($this->_rd);

        // }}}
        // {{{ 取得件数チェック

        $sqlCnt = '';
        $sqlCnt .= ' SELECT ';
        $sqlCnt .= '   COUNT(*) ';
        $sqlCnt .= ' FROM ';
        $sqlCnt .= '   ' . $this->_table . ' ';
        $sqlCnt .= ' WHERE  ';
        $sqlCnt .= '      ' . $this->_table . '.title = :title ';

        $bindCnt = array();
        $bindCnt[':title'] = 'footest';

        $paramCnt = array();
        $paramCnt['query'] = $sqlCnt;
        $paramCnt['bind'] = $bindCnt;

        $resCnt = $liveRecord->bindRow($paramCnt);
        $this->assertTrue($resCnt['COUNT(*)'] == 0);

        // }}}
        // {{{ 値を準備

        $param = array();
        $param['field'] = array('title', 'message');
        $param['value']  = array(':title', ':mes');
        $param['bind'] = array(':title' => 'footest', ':mes' => 'foo');
        $param['fetch'] = '';

        // }}}
        // {{{ bindInsertコール

        $liveRecord->bindInsert($param);

        // }}}
        // {{{ 取得件数チェック

        $resCnt = $liveRecord->bindRow($paramCnt);
        $this->assertTrue($resCnt['COUNT(*)'] == 1);

        // }}}

    }

    // }}}
    // {{{ testBindUpdate

    /**
     * bindUpdateテスト
     * 
     * 内部でbindRow使用
     *
     * @return void
     */
    public function testBindUpdate()
    {

        // {{{ LiveRecordインスタンス生成

        $liveRecord = new xFrameworkPX_Model_Behavior_LiveRecord($this->_rd);

        // }}}
        // {{{ 値を挿入してテスト準備

        $sql = '';
        $sql .= 'INSERT INTO `' . $this->_table . '`';
        $sql .= ' ( `title`,`message` ) ';
        $sql .= 'VALUES ( ';
        $sql .= '\'footest\',';
        $sql .= '\'foo\'';
        $sql .= ' )';

        mysql_query($sql);

        // }}}
        // {{{ 更新を準備

        $param = array();
        $param['field'] = array('title', 'message');
        $param['value']  = array(':title', ':mes');
        $param['bind'] = array(':title' => 'bartest', ':mes' => 'bar');
        $param['where'] = 'WHERE title = \'footest\'';

        // }}}
        // {{{ bindUpdateコール

        $liveRecord->bindUpdate($param);

        // }}}
        // {{{ 取得件数チェック

        $sqlCnt = '';
        $sqlCnt .= ' SELECT ';
        $sqlCnt .= '   COUNT(*) ';
        $sqlCnt .= ' FROM ';
        $sqlCnt .= '   ' . $this->_table . ' ';
        $sqlCnt .= ' WHERE  ';
        $sqlCnt .= '      ' . $this->_table . '.title = :title ';

        // 元の値を確認
        $bindCnt = array();
        $bindCnt[':title'] = 'footest';

        $paramCnt = array();
        $paramCnt['query'] = $sqlCnt;
        $paramCnt['bind'] = $bindCnt;

        $resCnt = $liveRecord->bindRow($paramCnt);
        $this->assertTrue($resCnt['COUNT(*)'] == 0);

        // 新しい値を確認
        $bindCnt = array();
        $bindCnt[':title'] = 'bartest';

        $paramCnt = array();
        $paramCnt['query'] = $sqlCnt;
        $paramCnt['bind'] = $bindCnt;

        $resCnt = $liveRecord->bindRow($paramCnt);
        $this->assertTrue($resCnt['COUNT(*)'] == 1);

        // }}}
        // {{{ 値を準備

        $param = array();
        $param['field'] = array('title', 'message');
        $param['value']  = array(':title', ':mes');
        $param['bind'] = array(':title' => 'hogetest', ':mes' => 'hoge');
        $param['where'] = array('title = \'bartest\'', 'message = \'bar\'');

        // }}}
        // {{{ bindUpdateコール

        $liveRecord->bindUpdate($param);

        // }}}
        // {{{ 取得件数チェック

        // 元の値を確認
        $bindCnt = array();
        $bindCnt[':title'] = 'bartest';

        $paramCnt = array();
        $paramCnt['query'] = $sqlCnt;
        $paramCnt['bind'] = $bindCnt;

        $resCnt = $liveRecord->bindRow($paramCnt);
        $this->assertTrue($resCnt['COUNT(*)'] == 0);

        // 新しい値を確認
        $bindCnt = array();
        $bindCnt[':title'] = 'hogetest';

        $paramCnt = array();
        $paramCnt['query'] = $sqlCnt;
        $paramCnt['bind'] = $bindCnt;

        $resCnt = $liveRecord->bindRow($paramCnt);
        $this->assertTrue($resCnt['COUNT(*)'] == 1);

        // }}}

    }

    // }}}
    // {{{ testBindDelete

    /**
     * bindDeleteテスト
     * 
     * 内部でbindRow使用
     *
     * @return void
     */
    public function testBindDelete()
    {

        // {{{ LiveRecordインスタンス生成

        $liveRecord = new xFrameworkPX_Model_Behavior_LiveRecord($this->_rd);

        // }}}
        // {{{ 値を挿入してテスト準備

        $sql = '';
        $sql .= 'INSERT INTO `' . $this->_table . '`';
        $sql .= ' ( `title`,`message` ) ';
        $sql .= 'VALUES ( ';
        $sql .= '\'footest\',';
        $sql .= '\'foo\'';
        $sql .= ' )';

        mysql_query($sql);

        $sql = '';
        $sql .= 'INSERT INTO `' . $this->_table . '`';
        $sql .= ' ( `title`,`message` ) ';
        $sql .= 'VALUES ( ';
        $sql .= '\'bartest\',';
        $sql .= '\'bar\'';
        $sql .= ' )';

        mysql_query($sql);

        // }}}
        // {{{ 取得件数チェック

        $sqlCnt = '';
        $sqlCnt .= ' SELECT ';
        $sqlCnt .= '   COUNT(*) ';
        $sqlCnt .= ' FROM ';
        $sqlCnt .= '   ' . $this->_table . ' ';
        $sqlCnt .= ' WHERE  ';
        $sqlCnt .= '      ' . $this->_table . '.title = :title ';

        // 上
        $bindCnt = array();
        $bindCnt[':title'] = 'footest';

        $paramCnt = array();
        $paramCnt['query'] = $sqlCnt;
        $paramCnt['bind'] = $bindCnt;

        $resCnt = $liveRecord->bindRow($paramCnt);
        $this->assertTrue($resCnt['COUNT(*)'] == 1);

        // 下
        $bindCnt = array();
        $bindCnt[':title'] = 'footest';

        $paramCnt = array();
        $paramCnt['query'] = $sqlCnt;
        $paramCnt['bind'] = $bindCnt;

        $resCnt = $liveRecord->bindRow($paramCnt);
        $this->assertTrue($resCnt['COUNT(*)'] == 1);

        // }}}
        // {{{ 削除準備（上）

        $param = array();
        $param['field'] = array('title', 'message');
        $param['value']  = array(':title', ':mes');
        $param['bind'] = array(':title' => 'footest', ':mes' => 'foo');
        $param['where'] = array('title = :title', 'message = :mes');

        // }}}
        // {{{ bindDeleteコール（上）

        $liveRecord->bindDelete($param);

        // }}}
        // {{{ 削除準備（下）

        $param = array();
        $param['field'] = array('title', 'message');
        $param['value']  = array(':title', ':mes');
        $param['bind'] = array(':title' => 'bartest');
        $param['where'] = 'WHERE title = :title';

        // }}}
        // {{{ bindDeleteコール（下）

        $liveRecord->bindDelete($param);

        // }}}
        // {{{ 取得件数チェック

        // 上
        $bindCnt = array();
        $bindCnt[':title'] = 'bartest';

        $paramCnt = array();
        $paramCnt['query'] = $sqlCnt;
        $paramCnt['bind'] = $bindCnt;

        $resCnt = $liveRecord->bindRow($paramCnt);
        $this->assertTrue($resCnt['COUNT(*)'] == 0);

        // 下
        $bindCnt = array();
        $bindCnt[':title'] = 'footest';

        $paramCnt = array();
        $paramCnt['query'] = $sqlCnt;
        $paramCnt['bind'] = $bindCnt;

        $resCnt = $liveRecord->bindRow($paramCnt);
        $this->assertTrue($resCnt['COUNT(*)'] == 0);

        // }}}

    }

    // }}}
    // {{{ testBindLastId

    /**
     * bindLastIdテスト
     * 
     * 内部でbindRow、bindInsert使用
     *
     * @return void
     */
    public function testBindLastId()
    {

        // {{{ LiveRecordインスタンス生成

        $liveRecord = new xFrameworkPX_Model_Behavior_LiveRecord($this->_rd);

        // }}}
        // {{{ 値を挿入してテスト準備(べたSQLでは0になった)

        $param = array();
        $param['field'] = array('title', 'message');
        $param['value']  = array(':title', ':mes');
        $param['bind'] = array(':title' => 'footest', ':mes' => 'foo');
        $param['fetch'] = '';

        $liveRecord->bindInsert($param);


        $param = array();
        $param['field'] = array('title', 'message');
        $param['value']  = array(':title', ':mes');
        $param['bind'] = array(':title' => 'bartest', ':mes' => 'bar');
        $param['where'] = 'WHERE title = \'footest\'';

        $liveRecord->bindInsert($param);

        // }}}
        // {{{ bindUpdateコール

        $this->assertTrue($liveRecord->bindLastId($param) == 2);

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
