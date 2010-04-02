<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Validation_ZenkakuNumTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: ZenkakuNumTest.php 956 2009-12-25 14:46:27Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';

require_once 'xFrameworkPX/Validation/ZenkakuNum.php';


// }}}
// {{{ xFrameworkPX_Validation_ZenkakuNumTest

/**
 * xFrameworkPX_Validation_ZenkakuNumTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_Validation_ZenkakuNumTest
extends PHPUnit_Framework_TestCase
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
     * 全角数字のみ許可
     *
     * @return void
     */
    public function testValidate()
    {

        // {{{ --------------false--------------

        // {{{ 全角ひらがな混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuNum::validate('１２３４test')
        );

        // }}}
        // {{{ 半角カナ混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuNum::validate('ﾃｽﾄ１２３４')
        );

        // }}}
        // {{{ 半角数字混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuNum::validate('１２３４11')
        );

        // }}}
        // {{{ 半角記号混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuNum::validate('<１２３４>')
        );

        // }}}
        // {{{ 全角カナ混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuNum::validate('１２３４テスト')
        );

        // }}}
        // {{{ 全角英字混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuNum::validate('１２３４ｇｇｇ')
        );

        // }}}
        // {{{ 全角記号混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuNum::validate('１２３４＜＞？')
        );

        // }}}
        // {{{ 全角ひらがな混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuNum::validate('１２３４ん')
        );

        // }}}

        // }}}
        // {{{ --------------true--------------

        // {{{ 空文字(NotEmptyでチェックするため)

        $this->assertTrue(
            xFrameworkPX_Validation_ZenkakuNum::validate('')
        );

        // }}}
        // {{{ 全角ひらがなのみで入力

        $this->assertTrue(
            xFrameworkPX_Validation_ZenkakuNum::validate('１２３４')
        );

        // }}}

        // }}}
        // {{{ --------------check--------------

        // {{{ 半角スペースのみはfalse

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuNum::validate(' ')
        );

        // }}}
        // {{{ 全角スペースのみはfalse

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuNum::validate('　')
        );

        // }}}
        // {{{ 半角スペース混じりはfalse

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuNum::validate('１２３ ４')
        );

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuNum::validate(' １２３４')
        );

        // }}}
        // {{{ 全角スペース混じりはfalse

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuNum::validate('１２３　４')
        );

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuNum::validate('　１２３４')
        );

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
