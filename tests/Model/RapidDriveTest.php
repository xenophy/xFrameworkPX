<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Model_RapidDriveTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_Model
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: RapidDriveTest.php 951 2009-12-25 11:40:13Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';

require_once 'xFrameworkPX/extender.php';
require_once 'xFrameworkPX/Object.php';
require_once 'xFrameworkPX/Util/Observable.php';
require_once 'xFrameworkPX/Util/MixedCollection.php';

require_once 'xFrameworkPX/Model.php';

require_once 'xFrameworkPX/Model/Adapter.php';
require_once 'xFrameworkPX/Model/Adapter/MySQL.php';
require_once 'xFrameworkPX/Model/Behavior.php';
require_once 'xFrameworkPX/Model/RapidDrive.php';

// }}}
// {{{ xFrameworkPX_Model_RapidDriveTest

/**
 * xFrameworkPX_Model_RapidDriveTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_Model
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_Model_RapidDriveTest extends PHPUnit_Framework_TestCase
{
    protected $_dbXml = null;
    protected $_db = null;
    protected $_table = null;
    protected $_rd = null;

    protected $_rdConf = null;


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

        $this->objDBConf = new xFrameworkPX_Util_MixedCollection(
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
        // {{{ 設定オブジェクト生成

        $conf = new xFrameworkPX_Util_MixedCollection(
            array('conn'=> 'default')
        );

        // {{{ データベース設定格納

        $conf->database = $this->_dbXml;
        $conf->data = new xFrameworkPX_Util_MixedCollection(
            array(
                'form'=> new xFrameworkPX_Util_MixedCollection(
                    array('_method'=> 'GET') 
                ),

                'url'=> new xFrameworkPX_Util_MixedCollection(
                    array('cp'=> '/article_rd/')
                )
            )
        );

        // }}}
        // {{{ データベース設定格納

        $conf->px = array(
            'BEHAVIOR_DIR' => '../behaviors',
            'PX_LIB_DIR'   => 'C:\UserDir\pxexamples\library\xFrameworkPX'
            // todo: 固定パスではなく、可変に
        );

        // }}}
        // {{{ モデル生成用オブジェクトを保存

        $this->_rdConf = $conf;

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
    // {{{ testCondition

    /**
     * conditionテスト
     *
     * @return void
     */
    public function testCondition()
    {

        // {{{ RapidDriveインスタンス生成

        $rd = new article_test_Model($this->_rdConf, $this->_table);

        // }}}
        // {{{ WHERE句などを返す

        $test = array('where' => '', 'bind' => array());

        $res = $rd->condition();

        $this->assertEquals($test, $res);

        // }}}
        // {{{ 検索されたとしてテスト

        $conf = $this->_rdConf;

        $conf->data = new xFrameworkPX_Util_MixedCollection( 
            array(
                'form'=> new xFrameworkPX_Util_MixedCollection(
                    array( 
                        '_method'=> 'GET'
                    )
                ),

                'url'=> new xFrameworkPX_Util_MixedCollection(
                    array(
                        'cp'=> '/article_rd/',
                        'q' => 'test'  // 検索する文字列
                    )
                )
            )
        );

        $rd = new article_test_Model($conf, $this->_table);

        $where = $this->_table . '.title';
        $where .= ' like :title OR ';
        $where .= $this->_table . '.message ';
        $where .= 'like :message ORDER BY ';
        $where .= $this->_table . '.id';

        $test = array(
            'where' => $strWhere,
            'bind' => array()
        );

        $res = $rd->condition();

        $this->assertEquals($test, $res);

        // }}}
        // {{{ 送信パラメータを変更してテスト

        $objConf->data = new xFrameworkPX_Util_MixedCollection(array(
            'form'=> new xFrameworkPX_Util_MixedCollection(
                array('_method'=> 'GET')
            ),

            'url'=> new xFrameworkPX_Util_MixedCollection(array(
                'cp'=> '/article_rd/',
                'hoge' => 'foo'  // 検索する文字列
            ))
        ));


        $rd = new article_test_Model($conf, $this->_table);

        $where = $this->_table . '.title';
        $where .= ' like :title OR ';
        $where .= $this->_table . '.message ';
        $where .= 'like :message ORDER BY ';
        $where .= $this->_table . '.id';

        $test = array(
            'where' => $strWhere,
            'bind' => array()
        );

        $res = $rd->condition('hoge');

        $this->assertEquals($test, $res);

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
