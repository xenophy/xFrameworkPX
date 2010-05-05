<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Model_Adapter_Oracle Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Model_Adapter
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Oracle.php 1369 2010-01-18 12:02:07Z kotsutsumi $
 */
// {{{ xFrameworkPX_Model_Adapter_Oracle

/**

 * xFrameworkPX_Model_Adapter_Oracle Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Model_Adapter
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Model_Adapter_Oracle
 */
class xFrameworkPX_Model_Adapter_Oracle extends xFrameworkPX_Model_Adapter
{

    // {{{ properties

    /**
     * カラムデータ型抽象化リスト
     */
    public $dataTypeList = array(
        'num' => array(
            'number', 'binary_float', 'binary_double', 'float'
        ),
        'date' => array(
            'date', 'timestamp'
        ),
        'char' => array(
            'varchar', 'varchar2', 'nvarchar2', 'char', 'nchar',
            'clob', 'nclob', 'long'
        )
    );

    /**
     * 関数名リスト
     *
     * @var array
     */
    public $functionList = array(
        'date' => array(
            'SYSDATE', 'SYSTIMESTAMP', 'CURRENT_DATE',
            'CURRENT_TIMESTAMP', 'CURRENT_TIMESTAMP()',
            'TO_DATE()'
        ),
        'group' => array('COUNT()', 'MIN()', 'MAX()', 'AVG()', 'SUM()'),
        'other' => array()
    );

    // }}}
    // {{{ getDbName

    /**
     * RDBMS名取得メソッド
     *
     * @return string
     */
    public function getRdbmsName()
    {
        return 'oracle';
    }

    // }}}
    // {{{ getSchema

    public function getSchema($pdoObj, $tableName)
    {
        $ret = array();
        $temp = array();
        $query = sprintf(
            implode(' ', array(
                'SELECT a.COLUMN_NAME, a.DATA_TYPE, a.DATA_LENGTH,',
                'c.CONSTRAINT_TYPE, b.COMMENTS',
                'FROM ALL_TAB_COLUMNS a, ALL_COL_COMMENTS b,',
                '(SELECT c1.TABLE_NAME, c1.COLUMN_NAME, c2.CONSTRAINT_TYPE',
                'FROM ALL_CONS_COLUMNS c1, ALL_CONSTRAINTS c2',
                'WHERE c1.CONSTRAINT_NAME = c2.CONSTRAINT_NAME AND',
                'c1.TABLE_NAME = c2.TABLE_NAME(+) AND',
                "c2.STATUS = 'ENABLED' AND",
                "c2.CONSTRAINT_TYPE = 'P') c",
                'WHERE (a.TABLE_NAME = b.TABLE_NAME(+) AND',
                'a.TABLE_NAME = c.TABLE_NAME(+) AND',
                'a.COLUMN_NAME = b.COLUMN_NAME(+) AND',
                'a.COLUMN_NAME = c.COLUMN_NAME(+)) AND',
                "a.TABLE_NAME = '%s'",
                'ORDER BY a.COLUMN_ID ASC'
            )),
            $tableName
        );

        // PDOStatement取得
        $stmt = @$pdoObj->prepare($query);

        // クエリー実行
        $stmt->execute();

        // 単行取得
        $result = $stmt->fetchAll(PDO::FETCH_NAMED);

        if ($result) {

            foreach ($result as $field) {
                $constType = '';

                if (isset($field['CONSTRAINT_TYPE'])) {

                    switch ($field['CONSTRAINT_TYPE']) {

                        case 'P':
                            $constType = 'PRI';
                            break;
                    }

                }

                if (intval($field['DATA_LENGTH']) > 0) {
                    $fieldType = sprintf(
                        '%s(%s)',
                        $field['DATA_TYPE'],
                        $field['DATA_LENGTH']
                    );
                } else {
                    $fieldType = $field['DATA_TYPE'];
                }

                $temp[] = array(
                    'Field' => $field['COLUMN_NAME'],
                    'Type' => $fieldType,
                    'Key' => $constType,
                    'Extra' => '',
                    'Comment' => $field['COMMENTS']
                );
            }

        }

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
    public function getQueryLastId($tblName, $colName)
    {
        return sprintf(
            'SELECT MAX(%s.%s) AS "last_id" FROM %s',
            $tblName, $colName, $tblName
        );
    }

    // }}}
    // {{{ getQueryTableInfo

    /**
     * テーブル情報取得クエリー取得メソッド
     */
    public function getQueryTableInfo($dbName, $tblName)
    {
        $ret = implode(' ', array(
            'SELECT a.TABLE_NAME, a.OWNER, a.TABLESPACE_NAME, a.NUM_ROWS, b.COMMENTS',
            'FROM ALL_TABLES a, ALL_TAB_COMMENTS b',
            'WHERE a.TABLE_NAME = b.TABLE_NAME(+) AND',
            "a.TABLE_NAME = '%s'"
        ));

        return sprintf($ret, $tblName);
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

        // 取得数設定
        if (!is_null($count)) {

            $ret = sprintf(
                'SELECT * FROM (SELECT temp__cnt.*, ROWNUM AS "temp__rn" FROM (%s) temp__cnt)',
                rtrim($query, ';')
            );

            if (!is_null($offset)) {
                $ret .= sprintf(
                    ' WHERE "temp__rn" BETWEEN %s AND %s',
                    $offset + 1,
                    $offset + $count
                );
            } else {
                $ret .= sprintf(' WHERE "temp__rn" BETWEEN 1 AND %s', $count);
            }

        } else {
            $ret = $query;
        }

        return $ret;
    }

    // }}}
    // {{{ getColType

    /**
     * カラムデータ型抽象化メソッド
     */
    public function getColTypeAbstract($type)
    {
        $ret = 'other';
        $type = strtolower($type);

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
        $ret = 'LOCK TABLE ';

        if (is_array($tables)) {
            $i = 0;

            foreach ($tables as $table) {

                if ($i > 0) {
                    $ret .= ', ';
                }

                if (is_string($table)) {
                    $ret .= $table;
                } else if (is_array($table)) {
                    $ret .= $table['name'];
                }

                $i++;
            }

            $ret .= ' IN EXCLUSIVE MODE';

            return $ret;
        }

        return null;
    }

    // }}}
    // {{{ getUnlockQuery

    public function getUnlockQuery()
    {
        return 'COMMIT';
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
