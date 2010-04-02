<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Validation_DateTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: DateTest.php 956 2009-12-25 14:46:27Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';

require_once 'xFrameworkPX/Validation/Date.php';

// }}}
// {{{ xFrameworkPX_Validation_DateTest

/**
 * xFrameworkPX_Validation_DateTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_Validation_DateTest extends PHPUnit_Framework_TestCase
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
    // {{{ testValidate

    /**
     * validateテスト
     *
     * 日付のみ許可
     *
     * @return void
     */
    public function testValidate()
    {

        // {{{ --------------false--------------

        // {{{ 全角ひらがな混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_Date::validate('てすとtest')
        );

        // }}}
        // {{{ 全角数字混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_Date::validate('１test')
        );

        // }}}
        // {{{ 全角カナ混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_Date::validate('テストtest')
        );

        // }}}
        // {{{ 半角カナ混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_Date::validate('ﾃｽﾄtest')
        );

        // }}}
        // {{{ 半角数字混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_Date::validate('11test')
        );

        // }}}
        // {{{ 半角英字のみで入力

        $this->assertFalse(
            xFrameworkPX_Validation_Date::validate('test')
        );

        // }}}
        // {{{ 存在しない日付入力

        $this->assertFalse(
            xFrameworkPX_Validation_Date::validate('2009-02-29')
        );

        $this->assertFalse(
            xFrameworkPX_Validation_Date::validate('2009/02/30')
        );

        // }}}
        // {{{ 区切りなし６桁

        $this->assertFalse(
            xFrameworkPX_Validation_Date::validate('090123')
        );

        // }}}

        // }}}
        // {{{ --------------true--------------

        // {{{ 空文字(NotEmptyでチェックするため)

        $this->assertTrue(
            xFrameworkPX_Validation_Date::validate('')
        );

        // }}}
        // {{{ 日付入力

        $this->assertTrue(
            xFrameworkPX_Validation_Date::validate('2009/11/11')
        );
        $this->assertTrue(
            xFrameworkPX_Validation_Date::validate('2009-11-11')
        );
        $this->assertTrue(
            xFrameworkPX_Validation_Date::validate('2009-1-13')
        );

        // }}}
        // {{{ 年が２桁

        $this->assertTrue(
            xFrameworkPX_Validation_Date::validate('09-02-28')
        );

        // }}}
        // {{{ 区切りなし8桁

        $this->assertTrue(
            xFrameworkPX_Validation_Date::validate('20090123')
        );

        // }}}

        // }}}
        // {{{ --------------check--------------

        // {{{ 半角スペースのみはfalse

        $this->assertFalse(
            xFrameworkPX_Validation_Date::validate(' ')
        );

        // }}}
        // {{{ 全角スペースのみはfalse

        $this->assertFalse(
            xFrameworkPX_Validation_Date::validate('　')
        );

        // }}}
        // {{{ 半角スペース混じりはtrue

        $this->assertTrue(
            xFrameworkPX_Validation_Date::validate('2009/ 11/11')
        );

        $this->assertTrue(
            xFrameworkPX_Validation_Date::validate(' 2009/11/11')
        );

        // }}}
        // {{{ 全角スペース混じりはfalse

        $this->assertFalse(
            xFrameworkPX_Validation_Date::validate('2009/　11/11')
        );

        $this->assertFalse(
            xFrameworkPX_Validation_Date::validate('　2009/11/11')
        );

        // }}}

    }

    // }}}

}

// }}}

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
