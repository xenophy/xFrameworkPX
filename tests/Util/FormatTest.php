<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Util_FormatTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_Util
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: FormatTest.php 1167 2010-01-05 11:26:57Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'xFrameworkPX/Util/Format.php';

// }}}
// {{{ xFrameworkPX_Util_FormatTest

/**
 * xFrameworkPX_Util_FormatTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_Util
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_Util_FormatTest extends PHPUnit_Framework_TestCase
{
    // {{{ setUp

    /**
     * セットアップ
     *
     * @return void
     */
    protected function setUp()
    {

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
    // {{{ test_formatSelect

    /**
     * _formatSelectテストメソッド
     *
     * @return void
     */

    public function test_formatSelect()
    {

        // {{{ ローカル変数初期化

        $sql = '';
        $expSql = '';
        $retSql = '';

        // }}}
        // {{{ select distinct, select

        $sql .= 'select distinct * from tbl_test;';
        $sql .= 'select * from tbl_test;';

        // $expSql .= "select distinct\n";
        // $expSql .= '    *\n';


        $retSql = xFrameworkPX_Util_Format::formatSQL($sql);

        echo "\n ========== Test 'SELECT' ========== \n";
        var_dump($retSql);
        echo "\n =================================== \n";
        // $this->assertEquals($expSql, $retSql);

        // }}}
        // {{{ select where

        $sql = '';
        $sql .= "SELECT col1, col2, col3 FROM tbl_test ";
        $sql .= "WHERE col1 = 'abc' AND col2 = 10;";
        $sql .= "select col1, col2, col3 FROM tbl_test ";
        $sql .= "where col1 = 'abc' or col2 = 10;";

        // $expSql .= "";


        $retSql = xFrameworkPX_Util_Format::formatSQL($sql);

        echo "\n ========== Test 'SELECT' ========== \n";
        var_dump($retSql);
        echo "\n =================================== \n";
        // $this->assertEquals($expSql, $retSql);

        // }}}
        // {{{ select where between

        $sql = '';
        $sql .= "SELECT col1, col2, col3 FROM tbl_test ";
        $sql .= "WHERE col1 BETWEEN 10 AND 30;";

        // $expSql .= "";


        $retSql = xFrameworkPX_Util_Format::formatSQL($sql);

        echo "\n ========== Test 'SELECT' ========== \n";
        var_dump($retSql);
        echo "\n =================================== \n";
        // $this->assertEquals($expSql, $retSql);

        // }}}

    }

    // }}}
    // {{{ test_formatInsert

    /**
     * _formatInsertテストメソッド
     *
     * @return void
     */
    public function test_formatInsert()
    {

        // {{{ ローカル変数初期化

        $sql = '';
        $expSql = '';
        $retSql = '';

        // }}}
        // {{{ insert

        $sql .= 'insert into tbl_test (col1, col2, col3) ';
        $sql .= 'values (\'value1\', \'value2\', \'value3\');';

        // $expSql .= "select distinct\n";
        // $expSql .= '    *\n';

        $retSql = xFrameworkPX_Util_Format::formatSQL($sql);

        echo "\n ========== Test 'INSERT' ========== \n";
        var_dump($retSql);
        echo "\n =================================== \n";

        // $this->assertEquals($expSql, $retSql);

        // }}}
        // {{{ insert select

        $sql = '';
        $sql .= "INSERT INTO tbl_test1 (col1, col2, col3)\n";
        $sql .= "SELECT col1, col2, col3\n";
        $sql .= "FROM tbl_test2\n";
        $sql .= "WHERE Year(col3) = 1998;";

        /*
        $expSql .= "select distinct\n";
        $expSql .= '    *\n';
        */

        $retSql = xFrameworkPX_Util_Format::formatSQL($sql);

        echo "\n ========== Test 'INSERT' ========== \n";
        var_dump($retSql);
        echo "\n =================================== \n";

        // $this->assertEquals($expSql, $retSql);

        // }}}

    }

    // }}}
    // {{{ test_formatUpdate

    /**
     * _formatUpdateテストメソッド
     *
     * @return void
     */
    public function test_formatUpdate()
    {

        // {{{ ローカル変数初期化

        $sql = '';
        $expSql = '';
        $retSql = '';

        // }}}
        // {{{ insert

        $sql .= "update tbl_test ";
        $sql .= "set col1 = 500 ";
        $sql .= "where col1 > 500;";
        $sql .= "UPDATE tbl_test\n";
        $sql .= "SET col1 = 500\n";
        $sql .= "WHERE col2 = 'Los Angeles' AND col3 = 'Jan-08-1999';\n";
        $sql .= "update tbl_test\n";
        $sql .= "set col1 = 500\n";
        $sql .= "where col2 = 'Los Angeles' or col3 = 'Jan-08-1999';\n";

        // $expSql .= "select distinct\n";
        // $expSql .= '    *\n';

        $retSql = xFrameworkPX_Util_Format::formatSQL($sql);

        echo "\n ========== Test 'UPDATE' ========== \n";
        var_dump($retSql);
        echo "\n =================================== \n";

        // $this->assertEquals($expSql, $retSql);

        // }}}

    }

    // }}}
    // {{{ test_formatDelete

    /**
     * _formatDeleteテストメソッド
     *
     * @return void
     */
    public function test_formatDelete()
    {

        // {{{ ローカル変数初期化

        $sql = '';
        $expSql = '';
        $retSql = '';

        // }}}
        // {{{ delete

        $sql .= "delete from tbl_test\n";
        $sql .= "where col1 > 1000;";
        $sql .= "DELETE FROM tbl_test ";
        $sql .= "WHERE col2 = 'Los Angeles' AND col3 = 'Jan-08-1999';";
        $sql .= "delete from tbl_test ";
        $sql .= "where col2 = 'Los Angeles' or col3 = 'Jan-08-1999';";

        // $expSql .= "select distinct\n";
        // $expSql .= '    *\n';

        $retSql = xFrameworkPX_Util_Format::formatSQL($sql);

        echo "\n ========== Test 'DELETE' ========== \n";
        var_dump($retSql);
        echo "\n =================================== \n";

        // $this->assertEquals($expSql, $retSql);

        // }}}

    }

    // }}}
    // {{{ test_formatReplace

    /**
     * _formatReplaceテストメソッド
     *
     * @return void
     */
    public function test_formatReplace()
    {

        // {{{ ローカル変数初期化

        $sql = '';
        $expSql = '';
        $retSql = '';

        // }}}
        // {{{ insert

        $sql .= 'replace into tbl_test (col1, col2, col3) ';
        $sql .= 'values (\'value1\', \'value2\', \'value3\');';

        // $expSql .= "select distinct\n";
        // $expSql .= '    *\n';

        $retSql = xFrameworkPX_Util_Format::formatSQL($sql);

        echo "\n ========== Test 'REPLACE' ========== \n";
        var_dump($retSql);
        echo "\n ==================================== \n";

        // $this->assertEquals($expSql, $retSql);

        // }}}
        // {{{ insert select

        $sql = '';
        $sql .= "REPLACE INTO tbl_test1 (col1, col2, col3)\n";
        $sql .= "SELECT col1, col2, col3\n";
        $sql .= "FROM tbl_test2\n";
        $sql .= "WHERE Year(col3) = 1998;";

        /*
        $expSql .= "select distinct\n";
        $expSql .= '    *\n';
        */

        $retSql = xFrameworkPX_Util_Format::formatSQL($sql);

        echo "\n ========== Test 'REPLACE' ========== \n";
        var_dump($retSql);
        echo "\n ==================================== \n";

        // $this->assertEquals($expSql, $retSql);

        // }}}

    }

    // }}}

}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
