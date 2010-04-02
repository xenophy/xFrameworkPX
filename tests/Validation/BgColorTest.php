<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Validation_BgColorTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: BgColorTest.php 956 2009-12-25 14:46:27Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';

require_once 'xFrameworkPX/Validation/BgColor.php';


// }}}
// {{{ xFrameworkPX_Validation_BgColorTest

/**
 * xFrameworkPX_Validation_BgColorTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_Validation_BgColorTest extends PHPUnit_Framework_TestCase
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
     * カラーコードのみ許可
     *
     * @return void
     */
    public function testValidate()
    {

        // {{{ --------------false--------------

        // {{{ 全角ひらがな混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_BgColor::validate('てすとtest')
        );

        // }}}
        // {{{ 全角数字混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_BgColor::validate('１test')
        );

        // }}}
        // {{{ 全角カナ混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_BgColor::validate('テストtest')
        );

        // }}}
        // {{{ 半角カナ混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_BgColor::validate('ﾃｽﾄtest')
        );

        // }}}
        // {{{ 半角数字混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_BgColor::validate('11test')
        );

        // }}}
        // {{{ 半角英字のみで入力

        $this->assertFalse(
            xFrameworkPX_Validation_BgColor::validate('test')
        );

        // }}}
        // {{{ 16進数以外の数字を入力

        $this->assertFalse(
            xFrameworkPX_Validation_BgColor::validate('#FGF8FF')
        );

        // }}}

        // }}}
        // {{{ --------------true--------------

        // {{{ 空文字(NotEmptyでチェックするため)

        $this->assertTrue(
            xFrameworkPX_Validation_BgColor::validate('')
        );

        // }}}
        // {{{ カラーコード入力

        $this->assertTrue(
            xFrameworkPX_Validation_BgColor::validate('#F0F8FF')
        );

        // }}}

        // }}}
        // {{{ --------------check--------------

        // {{{ 半角スペースのみはfalse

        $this->assertFalse(
            xFrameworkPX_Validation_BgColor::validate(' ')
        );

        // }}}
        // {{{ 全角スペースのみはfalse

        $this->assertFalse(
            xFrameworkPX_Validation_BgColor::validate('　')
        );

        // }}}
        // {{{ 半角スペース混じりはfalse

        $this->assertFalse(
            xFrameworkPX_Validation_BgColor::validate('#F 0F8FF')
        );

        $this->assertFalse(
            xFrameworkPX_Validation_BgColor::validate(' #F0F8FF')
        );

        // }}}
        // {{{ 全角スペース混じりはfalse

        $this->assertFalse(
            xFrameworkPX_Validation_BgColor::validate('#F0F　8FF')
        );

        $this->assertFalse(
            xFrameworkPX_Validation_BgColor::validate('　#F0F8FF')
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
