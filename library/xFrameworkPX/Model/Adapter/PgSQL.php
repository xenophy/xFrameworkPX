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
 * @version    SVN $Id: PgSQL.php 616 2009-12-12 17:56:31Z  $
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
class xFrameworkPX_Model_Adapter_PgSQL extends xFrameworkPX_Model_Adapter
{
    // {{{ 


    /**
     * カラムデータ型抽象化リスト
     */
    public $dataTypeList = array(
        'num' => array(
            'int', 'int2', 'int4', 'int8',
            'float4', 'float8',
            'serial4', 'serial8'
        ),
        'date' => array(
            'date', 'time', 'timetz', 'timestamp', 'timestamptz'
        ),
        'char' => array(
            'char', 'varchar', 'text'
        )
    );

    /**
     * 関数名リスト
     *
     * @var array
     */
    public $functionList = array(
        'date' => array(
            'NOW()', 'CURRENT_DATE', 'CURRENT_TIME', 'CURRENT_TIMESTAMP'
        ),
        'group' => array(
            'COUNT()', 'AVG()', 'MAX()', 'MIN()', 'SUM()'
        ),
        'other' => array(
            'NEXTVAL()', 'CURRVAL()', 'LASTVAL()', 'SETVAL()'
        )
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
        return 'pgsql';
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
        $query = implode(' ', array(
            'SELECT',
            'att.attname AS "Field",',
            'typ.typname AS "Type",',
            'con.contype AS "Key",',
            'col_description(att.attrelid, att.attnum) AS "Comment"',
            'FROM pg_attribute AS att',
            'INNER JOIN pg_catalog.pg_class AS cls',
            'ON att.attrelid = cls.oid',
            'INNER JOIN pg_namespace AS ns',
            'ON cls.relnamespace = ns.oid',
            'INNER JOIN pg_type AS typ',
            'ON att.atttypid = typ.oid',
            'LEFT JOIN pg_constraint AS con',
            'ON cls.relnamespace = con.connamespace AND',
            'att.attrelid = con.conrelid AND',
            'att.attnum = ANY(con.conkey)',
            "WHERE cls.relkind IN ('r', 'v') AND",
            'ns.nspname = current_schema() AND',
            'att.attnum > 0 AND',
            "cls.relname = '%s'"

            /*
            'SELECT columns.column_name, udt_name, constraint_type',
            'FROM information_schema.columns',
            'LEFT JOIN information_schema.constraint_column_usage',
            'ON columns.table_schema = constraint_column_usage.table_schema',
            'AND columns.table_name = constraint_column_usage.table_name',
            'AND columns.column_name = constraint_column_usage.column_name',
            'LEFT JOIN information_schema.table_constraints',
            'ON constraint_column_usage.constraint_name = table_constraints.constraint_name',
            'WHERE columns.table_schema=current_schema()',
            "AND columns.table_name='%s'",
            'ORDER BY ordinal_position;'
            */
        ));
        $query = sprintf($query, $tableName);

        // PDOStatement取得
        $stmt = @$pdoObj->prepare($query);

        // クエリー実行
        $stmt->execute();

        // 単行取得
        $result = $stmt->fetchAll(PDO::FETCH_NAMED);

        if ($result) {
            $temp = array();

            foreach ($result as $field) {
                $tempField = array();
                $tempField['Field'] = $field['Field'];
                $tempField['Type'] = $field['Type'];

                if ($field['Key'] == 'p') {
                    $tempField['Key'] = 'PRI';
                } else {
                    $tempField['Key'] = '';
                }

                $tempField['Extra'] = '';
                $tempField['Comment'] = (isset($field['Comment'])) ? $field['Comment'] : '';
                $temp[] = $tempField;
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
    // {{{ getQuerySchema

    /**
     * スキーマ取得クエリー取得メソッド
     *
     * @param string $strTableName テーブル名
     * @return string SQLフラグメント
     * @access public
     */
    public function getQuerySchema()
    {
        $sql[] = 'SELECT';
        $sql[] = '    *';
        $sql[] = 'FROM';
        $sql[] = '    information_schema.columns';
        $sql[] = 'WHERE';
        $sql[] = '    table_schema=current_schema()';
        $sql[] = 'AND';
        $sql[] = '    table_name=\'%s\''; 
        $sql[] = 'ORDER BY';
        $sql[] = '    ordinal_position;';

        return join('', $sql);
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
        return 'SELECT lastval() AS last_id';
    }

    // }}}
    // {{{ getQueryTableInfo

    /**
     * テーブル情報取得クエリー取得メソッド
     */
    public function getQueryTableInfo($dbName, $tblName)
    {
        $ret = implode(' ', array(
            'SELECT columns.column_name, udt_name, constraint_type',
            'FROM information_schema.columns',
            'LEFT JOIN information_schema.constraint_column_usage',
            'ON columns.table_schema = constraint_column_usage.table_schema',
            'AND columns.table_name = constraint_column_usage.table_name',
            'AND columns.column_name = constraint_column_usage.column_name',
            'LEFT JOIN information_schema.table_constraints',
            'ON constraint_column_usage.constraint_name = table_constraints.constraint_name',
            'WHERE columns.table_schema=current_schema()',
            "AND columns.table_name='%s'",
            'ORDER BY ordinal_position;'
        ));

        return sprintf(
            $ret,
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
            $ret .= sprintf(' OFFSET %s LIMIT %s', $offset, $count);
        } else if (!is_null($count)) {
            $ret .= sprintf(' OFFSET 0 LIMIT %s', $count);
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
