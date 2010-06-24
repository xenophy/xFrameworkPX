<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Model_Adapter_MySQL Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Model_Adapter
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: MySQL.php 1369 2010-01-18 12:02:07Z kotsutsumi $
 */

// {{{ xFrameworkPX_Model_Adapter_MySQL

/**
 * xFrameworkPX_Model_Adapter_MySQL Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Model_Adapter
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Model_Adapter_MySQL
 */
class xFrameworkPX_Model_Adapter_MySQL extends xFrameworkPX_Model_Adapter
{

    // {{{ properties

    /**
     * カラムデータ型抽象化リスト
     */
    public $dataTypeList = array(
        'num' => array(
            'tinyint', 'smallint', 'mediumint', 'int', 'integer', 'bigint',
            'float', 'double', 'numeric', 'decimal', 'dec', 'fixed'
        ),
        'date' => array(
            'date', 'datetime', 'timestamp', 'time', 'year'
        ),
        'char' => array(
            'char', 'varchar', 'binary', 'varbinary', 'tinyblob', 'tinytext',
            'blob', 'text', 'mediumblob', 'mediumtext', 'longblob', 'longtext',
            'enum', 'set'
        )
    );

    /**
     * 関数名リスト
     *
     * @var array
     */
    public $functionList = array(
        'date' => array(
            'CURDATE()', 'CURRENT_DATE', 'CURRENT_DATE()',
            'CURTIME()', 'CURRENT_TIME', 'CURRENT_TIME()',
            'NOW()', 'CURRENT_TIMESTAMP', 'CURRENT_TIMESTAMP()', 'SYSDATE()'
        ),
        'group' => array('COUNT()', 'MIN()', 'MAX()', 'AVG()', 'SUM()'),
        'other' => array('MD5()')
    );

    // }}}
    // {{{ getRdbmsName

    /**
     * RDBMS名取得メソッド
     *
     * @return string
     */
    public function getRdbmsName()
    {
        return 'mysql';
    }

    // }}}
    // {{{ getSchema

    /**
     * スキーマ取得メソッド
     *
     * @return array スキーマ情報とクエリ
     */
    public function getSchema($pdoObj, $tableName)
    {
        $ret = array();
        $query = 'SHOW FULL COLUMNS FROM ' . $tableName;
        $temp = array();

        // PDOStatement取得
        $stmt = @$pdoObj->prepare($query);

        // クエリー実行
        $stmt->execute();

        // 単行取得
        $result = $stmt->fetchAll(PDO::FETCH_NAMED);

        if ($result) {
            foreach ($result as $field) {
                $temp[] = array(
                    'Field' => $field['Field'],
                    'Type' => $field['Type'],
                    'Key' => $field['Key'],
                    'Default' => $field['Default'],
                    'Extra' => $field['Extra'],
                    'Comment' => $field['Comment']
                );
            }

        }

        // 結果セット
        $ret['result'] = $temp;
        $ret['query'] = $query;

        // カーソルを閉じてステートメントを再実行できるようにする
        $stmt->closeCursor();

        // PDOStatement破棄
        unset($stmt);

        return $ret;
    }

    // }}}
    // {{{ getQueryLastId

    /**
     * LastId取得クエリー取得メソッド
     *
     * @return string SQLフラグメント
     */
    public function getQueryLastId($tblName = null, $colName = null)
    {
        return 'SELECT last_insert_id() AS last_id;';
    }

    // }}}
    // {{{ getQueryTableInfo

    /**
     * テーブル情報取得クエリー取得メソッド
     */
    public function getQueryTableInfo($dbName, $tblName)
    {
        return sprintf(
            "show table status from `%s` like '%s'",
            $dbName,
            $tblName
        );
    }

    // }}}
    // {{{ addQueryLimit

    /**
     * LIMIT節クエリー付加メソッド
     *
     * @param string $query 元クエリ
     * @param int $count 取得件数
     * @param int $offset オフセット値
     * @return string SQLフラグメント
     * @access public
     */
    public function addQueryLimit($query, $count = null, $offset = null)
    {
        $ret = $query;

        // 取得数設定
        if (!is_null($offset) && !is_null($count)) {
            $ret .= sprintf(' LIMIT %s, %s', $offset, $count);
        } else if (!is_null($count)) {
            $ret .= sprintf(' LIMIT %s', $count);
        }

        return $ret;
    }

    // }}}
    // {{{ getColTypeAbstract

    /**
     * カラムデータ型抽象化メソッド
     */
    public function getColTypeAbstract($type)
    {
        $ret = 'other';

        if (preg_match('/^([a-z]+)\(.+\)/i', $type, $matches)) {
            $type = $matches[1];
        }

        foreach ($this->dataTypeList as $abst => $types) {

            if (in_array($type, $types)) {
                $ret = $abst;
                break;
            }

        }

        return $ret;
    }

    // }}}
    // {{{ getType

    /**
     * タイプ取得メソッド
     *
     * @param array $fields フィールドのスキーマ情報
     * @return array インプットタイプの情報
     * @access public
     */
    public function getType($fields)
    {
        $ret = array();
        $matches = array();

        // インプットタイプの生成
        if (preg_match('/^.*text$/i', $fields['Type'])) {
            $ret[ 'type' ] = 'textarea';
        } else if (
            preg_match(
                '/^(?:var)?char\(([1-9]+[0-9]*)\)$/i',
                $fields['Type'],
                $matches
            )
        ) {
            if ( $fields['Field'] == 'password' ||
                 $fields['Field'] == 'passwd' ||
                 $fields['Field'] == 'pasword'
            ) {
                $ret['type'] = 'password';
            } else {
                $ret['type'] = 'text';
            }
            $ret['length' ] = $matches[1];
        } else if (
            preg_match('/^tinyint\(1\)$/i', $fields['Type']) ||
            preg_match('/^boolean$/i', $fields['Type'])
        ) {
            $ret['type'] = 'checkbox';
        } else if (preg_match('/^date$/i', $fields['Type'])) {
            $ret['type'] = 'select_date';
        } else if (
            preg_match('/^datetime$/i', $fields['Type']) ||
            preg_match('/^timestamp$/i', $fields['Type'])
        ) {
            $ret['type'] = 'select_datetime';
        } else if (preg_match('/^time$/i', $fields['Type'])) {
            $ret['type'] = 'select_time';
        } else {
            $ret['type'] = 'text';
        }

        return $ret;
    }

    // }}}
    // {{{ getLockQuery

    public function getLockQuery($tables)
    {
        $ret = 'LOCK TABLES';

        if (is_array($tables)) {
            $i = 0;
            foreach ($tables as $table) {

                if ($i > 0) {
                    $ret .= ',';
                }

                if (is_string($table)) {
                    $ret .= ' ' . $table . ' WRITE';
                } else if(is_array($table)) {
                    $ret .= ' ' . $table['name'] . ' ' . $table['mode'];
                }

                $i++;
            }

            return $ret . ';';
        }

        return null;
    }

    // }}}
    // {{{ getUnlockQuery

    public function getUnlockQuery()
    {
        return 'UNLOCK TABLES;';
    }

    // }}}
    // {{{ getTruncateQuery

    public function getTruncateQuery()
    {
        return 'TRUNCATE TABLE %s';
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
