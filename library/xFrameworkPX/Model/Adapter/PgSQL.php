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
    public function getQueryLastId()
    {
        return 'SELECT lastval() AS last_id';
    }

    // }}}
    // {{{ getQueryLimit

    /**
     * LIMIT節クエリー取得メソッド
     *
     * @return string SQLフラグメント
     * @access public
     */
    public function getQueryLimit($count, $offset = null)
    {
        if (!is_null($offset)) {
            $ret = sprintf('offset %s limit %s', $offset, $count);
        } else {
            $ret = sprintf('offset 0 limit %s', $count);
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
