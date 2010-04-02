<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Model_Adapter_MySQLTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_Model_Adapter
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: MySQLTest.php 943 2009-12-25 07:12:54Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'xFrameworkPX/Object.php';
require_once 'xFrameworkPX/Util/Observable.php';
require_once 'xFrameworkPX/Util/MixedCollection.php';

require_once 'xFrameworkPX/Model/Adapter.php';
require_once 'xFrameworkPX/Model/Adapter/MySQL.php';

// }}}
// {{{ xFrameworkPX_Model_Adapter_MySQLTest

/**
 * xFrameworkPX_Model_Adapter_MySQLTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_Model_Adapter
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_Model_Adapter_MySQLTest extends PHPUnit_Framework_TestCase
{
    protected $_dbXml = null;
    protected $_db = null;
    protected $_table = null;

    // {{{ setUp

    /**
     * セットアップ
     *
     * @return void
     */
    protected function setUp()
    {

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

        $this->conf = new xFrameworkPX_Util_MixedCollection(
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
        $sql .= 'CREATE TABLE IF NOT EXISTS `'.$this->_table.'` (';
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

        $mixConf = new xFrameworkPX_Util_MixedCollection( 
                        array( 
                            'conn'=> 'default'
                        ) 
                    );

        // {{{ データベース設定格納

        $mixConf->database = $this->objDbXml;

        // }}}
        // {{{ データベース設定格納

        $mixConf->px = array(
            'BEHAVIOR_DIR' => '../behaviors',
            'PX_LIB_DIR'   => 'C:\UserDir\px\library\xFrameworkPX'
            // todo: 固定パスではなく、可変に
        );
        // {{{ モデル生成

//        $this->objRD = new article_test( $mixConf );
//        $this->objRD = new xFrameworkPX_Model_RapidDrive( $mixConf );
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
    // {{{ testGetQuerySchema

    /**
     * getQuerySchemaテスト
     *
     * @return void
     */
    public function testGetQuerySchema()
    {

        // {{{ インスタンス化

        $obj = new xFrameworkPX_Model_Adapter_MySQL();

        // }}}
        // {{{ メソッドコール

        $sql = $obj->getQuerySchema();

        // }}}
        // {{{ 返り値判定

        $this->assertEquals($sql, 'SHOW FULL COLUMNS FROM %s');

        // }}}
        // {{{ SQL生成

        $sql = sprintf($sql, $this->_table);

        // }}}
        // {{{ 生成クエリー判定

        $this->assertEquals($sql, 'SHOW FULL COLUMNS FROM tbl_article_test');

        // }}}

    }

    // }}}
    // {{{ testGetQueryLastId

    /**
     * getQueryLastIdテスト
     *
     * @return void
     */
    public function testGetQueryLastId()
    {

        // {{{ インスタンス化

        $obj = new xFrameworkPX_Model_Adapter_MySQL();

        // }}}
        // {{{ メソッドコール

        $sql = $obj->getQueryLastId();

        // }}}
        // {{{ 返り値判定

        $this->assertEquals($sql, 'SELECT last_insert_id() AS last_id;');

        // }}}

    }

    // }}}
    // {{{ testGetQueryLimit

    /**
     * getQueryLimitテスト
     *
     * @return void
     */
    public function testGetQueryLimit()
    {

        // {{{ インスタンス化

        $obj = new xFrameworkPX_Model_Adapter_MySQL();

        // }}}
        // {{{ メソッドコール

        $sql = $obj->getQueryLimit(5);

        // }}}
        // {{{ 返り値判定

        $this->assertEquals($sql, 'LIMIT 5');

        // }}}
        // {{{ メソッドコール

        $sql = $obj->getQueryLimit(2, 7);

        // }}}
        // {{{ 返り値判定

        $this->assertEquals($sql, 'LIMIT 7, 2');

        // }}}
        // {{{ メソッドコール

        $sql = $obj->getQueryLimit();

        // }}}
        // {{{ 返り値判定

        $this->assertEquals($sql, '');

        // }}}

    }

    // }}}
    // {{{ testGetType

    /**
     * getTypeテスト
     *
     * @return void
     */
    public function testGetType()
    {

        // {{{ インスタンス化

        $obj = new xFrameworkPX_Model_Adapter_MySQL();

        // }}}
        // {{{ テーブル情報サンプル

        $schema = array(
            array(
                'Field' => 'id',
                'Type' => 'int(11)',
                'Collation' => '',
                'Null' => 'NO',
                'Key' => 'PRI',
                'Default' => '',
                'Extra' => 'auto_increment',
                'Privileges' => 'select,insert,update,references',
                'Comment' => ''
            ),
            array(
                'Field' => 'title',
                'Type' => 'varchar(255)',
                'Collation' => 'utf8_general_ci',
                'Null' => 'YES',
                'Key' => '',
                'Default' => '',
                'Extra' => '',
                'Privileges' => 'select,insert,update,references',
                'Comment' => 'タイトル'
            ),
            array(
                'Field' => 'message',
                'Type' => 'text',
                'Collation' => 'utf8_general_ci',
                'Null' => 'YES',
                'Key' => '',
                'Default' => '',
                'Extra' => '',
                'Privileges' => 'select,insert,update,references',
                'Comment' => 'メッセージ'
            ),
            array(
                'Field' => 'created',
                'Type' => 'datetime',
                'Collation' => '',
                'Null' => 'YES',
                'Key' => '',
                'Default' => '',
                'Extra' => '',
                'Privileges' => 'select,insert,update,references',
                'Comment' => ''
            ),
            array(
                'Field' => 'modified',
                'Type' => 'datetime',
                'Collation' => '',
                'Null' => 'YES',
                'Key' => '',
                'Default' => '',
                'Extra' => '',
                'Privileges' => 'select,insert,update,references',
                'Comment' => ''
            )
        );

        // }}}
        // {{{ メソッドコール

        foreach ($schema as $test) {
            $key = $test['Field'];
            $ret[$key] = $obj->getType($test);

        }

        // }}}
        // {{{ 返り値判定

        $arr = array(
                'id' => array(
                    'type' => 'text'
                ),
                'title' => array(
                    'type' => 'text',
                    'length' => '255'
                ),
                'message' => array(
                    'type' => 'textarea'
                ),
                'created' => array(
                    'type' => 'select_datetime'
                ),
                'modified' => array(
                    'type' => 'select_datetime'
                )
            );

        $this->assertEquals($arr, $ret);

        // }}}
        // {{{ テーブル情報サンプル

        $schema = array(
            array(
                'Field' => 'id',
                'Type' => 'int(11)',
                'Collation' => '',
                'Null' => 'NO',
                'Key' => 'PRI',
                'Default' => '',
                'Extra' => 'auto_increment',
                'Privileges' => 'select,insert,update,references',
                'Comment' => ''
            ),
            array(
                'Field' => 'password',
                'Type' => 'varchar(20)',
                'Collation' => 'utf8_general_ci',
                'Null' => 'YES',
                'Key' => '',
                'Default' => '',
                'Extra' => '',
                'Privileges' => 'select,insert,update,references',
                'Comment' => 'タイトル'
            ),
            array(
                'Field' => 'del',
                'Type' => 'tinyint(1)',
                'Collation' => 'utf8_general_ci',
                'Null' => 'YES',
                'Key' => '',
                'Default' => '',
                'Extra' => '',
                'Privileges' => 'select,insert,update,references',
                'Comment' => 'メッセージ'
            ),
            array(
                'Field' => 'testtime',
                'Type' => 'time',
                'Collation' => '',
                'Null' => 'YES',
                'Key' => '',
                'Default' => '',
                'Extra' => '',
                'Privileges' => 'select,insert,update,references',
                'Comment' => ''
            ),
            array(
                'Field' => 'testdate',
                'Type' => 'date',
                'Collation' => '',
                'Null' => 'YES',
                'Key' => '',
                'Default' => '',
                'Extra' => '',
                'Privileges' => 'select,insert,update,references',
                'Comment' => ''
            )
        );

        // }}}
        // {{{ メソッドコール

        $ret = array();
        foreach ($schema as $test) {
            $key = $test['Field'];
            $ret[$key] = $obj->getType($test);

        }

        // }}}
        // {{{ 返り値判定

        $arr = array(
                'id' => array(
                    'type' => 'text'
                ),
                'password' => array(
                    'type' => 'password',
                    'length' => '20'
                ),
                'del' => array(
                    'type' => 'checkbox'
                ),
                'testtime' => array(
                    'type' => 'select_time'
                ),
                'testdate' => array(
                    'type' => 'select_date'
                )
            );

        $this->assertEquals($arr, $ret);

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
