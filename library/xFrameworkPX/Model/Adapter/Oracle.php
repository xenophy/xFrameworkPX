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
     * 関数名リスト
     *
     * @var array
     */
    public $functionList = array(
        'date' => array(
            'SYSDATE', 'SYSTIMESTAMP', 'CURRENT_DATE',
            'CURRENT_TIMESTAMP', 'CURRENT_TIMESTAMP()'
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
    // {{{ getQuerySchema

    /**
     * スキーマ取得クエリー取得メソッド
     *
     * @return string SQLフラグメント
     */
    public function getQuerySchema()
    {
        $query = array();
        $query[] = 'SELECT a.column_id, a.column_name, a.data_type,';
        $query[] = '    a.data_precision, a.data_length, a.data_scale,';
        $query[] = '    a.nullable, a.data_default, b.comments';
        $query[] = 'FROM user_tab_columns a, user_col_comments b';
        $query[] = 'WHERE (a.table_name = b.table_name(+) AND';
        $query[] = '       a.column_name = b.column_name(+)) AND';
        $query[] = "(a.table_name = UPPER('%s'))";
        $query[] = 'ORDER BY a.column_id';
        /*
        $query[] = 'SELECT a.index_name, uniqueness';
        $query[] = 'FROM user_indexes a';
        $query[] = "WHERE a.table_name = '%s' AND";
        $query[] = '      NOT EXISTS (';
        $query[] = '          SELECT *';
        $query[] = '          FROM user_constraints b';
        $query[] = "          WHERE (b.constraint_type = 'P') AND";
        $query[] = '                (b.table_name = a.table_name AND';
        $query[] = '                    b.constraint_name = a.index_name)';
        $query[] = '      )';
        $query[] = 'ORDER BY a.index_name';
        */

        return implode(' ', $query);
    }

    // }}}
    // {{{ getQueryLastId

    /**
     * LastId取得クエリー取得メソッド
     *
     * @return string SQLフラグメント
     */
    public function getQueryLastId()
    {
        return 'SELECT last_insert_id() AS last_id;';
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

}

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
