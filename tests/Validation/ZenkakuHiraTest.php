<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Validation_ZenkakuHiraTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: ZenkakuHiraTest.php 956 2009-12-25 14:46:27Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';

require_once 'xFrameworkPX/Validation/ZenkakuHira.php';

// }}}
// {{{ xFrameworkPX_Validation_ZenkakuHiraTest

/**
 * xFrameworkPX_Validation_ZenkakuHiraTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_Validation_ZenkakuHiraTest
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
     * 全角ひらがなのみ許可
     *
     * @return void
     */
    public function testValidate()
    {

        // {{{ --------------false--------------

        // {{{ 全角ひらがな混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuHira::validate('てすとtest')
        );

        // }}}
        // {{{ 半角カナ混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuHira::validate('ﾃｽﾄてすと')
        );

        // }}}
        // {{{ 半角数字混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuHira::validate('てすと11')
        );

        // }}}
        // {{{ 半角記号混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuHira::validate('<てすと>')
        );

        // }}}
        // {{{ 全角数字混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuHira::validate('てすと１１１')
        );

        // }}}
        // {{{ 全角英字混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuHira::validate('てすとｇｇｇ')
        );

        // }}}
        // {{{ 全角記号混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuHira::validate('てすと＜＞？')
        );

        // }}}
        // {{{ 全角カナ混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuHira::validate('てすとテストん')
        );

        // }}}

        // }}}
        // {{{ --------------true--------------

        // {{{ 空文字(NotEmptyでチェックするため)

        $this->assertTrue(
            xFrameworkPX_Validation_ZenkakuHira::validate('')
        );

        // }}}
        // {{{ 全角ひらがなのみで入力

        $this->assertTrue(
            xFrameworkPX_Validation_ZenkakuHira::validate('てすと')
        );

        // }}}

        // }}}
        // {{{ --------------check--------------

        // {{{ 半角スペースのみはfalse

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuHira::validate(' ')
        );

        // }}}
        // {{{ 全角スペースのみはfalse

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuHira::validate('　')
        );

        // }}}
        // {{{ 半角スペース混じりはfalse

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuHira::validate('ほ げ')
        );

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuHira::validate(' ほげ')
        );

        // }}}
        // {{{ 全角スペース混じりはfalse

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuHira::validate('ほ　げ')
        );

        $this->assertFalse(
            xFrameworkPX_Validation_ZenkakuHira::validate('　ほげ')
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
