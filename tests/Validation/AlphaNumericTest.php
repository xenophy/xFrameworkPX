<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Validation_AlphaNumericTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: AlphaNumericTest.php 956 2009-12-25 14:46:27Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';

require_once 'xFrameworkPX/Validation/AlphaNumeric.php';

// }}}
// {{{ xFrameworkPX_Validation_AlphaNumericTest

/**
 * xFrameworkPX_Validation_AlphaNumericTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_Validation_AlphaNumericTest 
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
     * 半角英数のみ許可
     *
     * @return void
     */
    public function testValidate()
    {

        // {{{ --------------false--------------

        // {{{ 全角ひらがな混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_AlphaNumeric::validate('てすとtest55')
        );

        // }}}
        // {{{ 全角数字混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_AlphaNumeric::validate('１test55')
        );

        // }}}
        // {{{ 全角カナ混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_AlphaNumeric::validate('テストtest55')
        );

        // }}}
        // {{{ 半角カナ混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_AlphaNumeric::validate('ﾃｽﾄtest55')
        );

        // }}}
        // {{{ 半角記号混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_AlphaNumeric::validate('<test>55')
        );

        // }}}

        // }}}
        // {{{ --------------true--------------

        // {{{ 空文字(NotEmptyでチェックするため)

        $this->assertTrue(
            xFrameworkPX_Validation_AlphaNumeric::validate('')
        );

        // }}}
        // {{{ 半角数字混じりで入力

        $this->assertTrue(
            xFrameworkPX_Validation_AlphaNumeric::validate('11test')
        );

        // }}}
        // {{{ 半角英字のみで入力

        $this->assertTrue(
            xFrameworkPX_Validation_AlphaNumeric::validate('test')
        );

        // }}}

        // }}}
        // {{{ --------------check--------------

        // {{{ 半角スペースのみはfalse

        $this->assertFalse(
            xFrameworkPX_Validation_AlphaNumeric::validate(' ')
        );

        // }}}
        // {{{ 全角スペースのみはfalse

        $this->assertFalse(
            xFrameworkPX_Validation_AlphaNumeric::validate('　')
        );

        // }}}
        // {{{ 半角スペース混じりはfalse

        $this->assertFalse(
            xFrameworkPX_Validation_AlphaNumeric::validate('te st12')
        );

        $this->assertFalse(
            xFrameworkPX_Validation_AlphaNumeric::validate(' test12')
        );

        // }}}
        // {{{ 全角スペース混じりはfalse

        $this->assertFalse(
            xFrameworkPX_Validation_AlphaNumeric::validate('te　st12')
        );
        $this->assertFalse(
            xFrameworkPX_Validation_AlphaNumeric::validate('　test12')
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
