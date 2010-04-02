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
    // {{{ getQuerySchema

    /**
     * スキーマ取得クエリー取得メソッド
     *
     * @return string SQLフラグメント
     */
    public function getQuerySchema()
    {
        return 'SHOW FULL COLUMNS FROM %s';
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
    // {{{ getQueryLimit

    /**
     * LIMIT節クエリー取得メソッド
     *
     * @param int $count 取得件数
     * @param int $offset オフセット値
     * @return string SQLフラグメント
     * @access public
     */
    public function getQueryLimit($count = null, $offset = null)
    {
        $ret = '';

        // 取得数設定
        if (!is_null($offset) && !is_null($count)) {
            $ret = sprintf('LIMIT %s, %s', $offset, $count);
        } else if (!is_null($count)) {
            $ret = sprintf('LIMIT %s', $count);
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
