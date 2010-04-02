<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Validation_ZenkakuTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: ZenkakuTest.php 956 2009-12-25 14:46:27Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';

require_once 'xFrameworkPX/Validation/Zenkaku.php';


// }}}
// {{{ xFrameworkPX_Validation_ZenkakuTest

/**
 * xFrameworkPX_Validation_ZenkakuTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_Validation_ZenkakuTest extends PHPUnit_Framework_TestCase
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
     * 全角文字のみ許可
     *
     * @return void
     */
    public function testValidate()
    {

        // {{{ --------------false--------------

        // {{{ 全角ひらがな混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_Zenkaku::validate('てすとtest')
        );

        // }}}
        // {{{ 半角カナ混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_Zenkaku::validate('ﾃｽﾄてすと')
        );

        // }}}
        // {{{ 半角数字混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_Zenkaku::validate('てすと11')
        );

        // }}}
        // {{{ 半角記号混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_Zenkaku::validate('<てすと>')
        );

        // }}}

        // }}}
        // {{{ --------------true--------------

        // {{{ 空文字(NotEmptyでチェックするため)

        $this->assertTrue(
            xFrameworkPX_Validation_Zenkaku::validate('')
        );

        // }}}
        // {{{ 全角のみで入力

        $this->assertTrue(
            xFrameworkPX_Validation_Zenkaku::validate('テスト')
        );
        $this->assertTrue(
            xFrameworkPX_Validation_Zenkaku::validate('ｆｆｆ')
        );
        $this->assertTrue(
            xFrameworkPX_Validation_Zenkaku::validate('１１１')
        );
        $this->assertTrue(
            xFrameworkPX_Validation_Zenkaku::validate('＠')
        );
        $this->assertTrue(
            xFrameworkPX_Validation_Zenkaku::validate('ほげ')
        );

        // }}}

        // }}}
        // {{{ --------------check--------------

        // {{{ 半角スペースのみはfalse

        $this->assertFalse(
            xFrameworkPX_Validation_Zenkaku::validate(' ')
        );

        // }}}
        // {{{ 全角スペースのみはtrue

        $this->assertTrue(
            xFrameworkPX_Validation_Zenkaku::validate('　')
        );

        // }}}
        // {{{ 半角スペース混じりはfalse

        $this->assertFalse(
            xFrameworkPX_Validation_Zenkaku::validate('ほ げ')
        );

        $this->assertFalse(
            xFrameworkPX_Validation_Zenkaku::validate(' ほげ')
        );

        // }}}
        // {{{ 全角スペース混じりはtrue

        $this->assertTrue(
            xFrameworkPX_Validation_Zenkaku::validate('ほ　げ')
        );

        $this->assertTrue(
            xFrameworkPX_Validation_Zenkaku::validate('　ほげ')
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
