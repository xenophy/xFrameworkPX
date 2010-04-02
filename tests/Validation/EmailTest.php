<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Validation_EmailTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: EmailTest.php 956 2009-12-25 14:46:27Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';

require_once 'xFrameworkPX/Validation/Email.php';

// }}}
// {{{ xFrameworkPX_Validation_EmailTest

/**
 * xFrameworkPX_Validation_EmailTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_Validation_EmailTest extends PHPUnit_Framework_TestCase
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
     * メールアドレスのみ許可
     *
     * @return void
     */
    public function testValidate()
    {

        // {{{ --------------false--------------

        // {{{ 全角ひらがな混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_Email::validate('てすとtest')
        );

        // }}}
        // {{{ 全角数字混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_Email::validate('１test')
        );

        // }}}
        // {{{ 全角カナ混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_Email::validate('テストtest')
        );

        // }}}
        // {{{ 半角カナ混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_Email::validate('ﾃｽﾄtest')
        );

        // }}}
        // {{{ 半角数字混じりで入力

        $this->assertFalse(
            xFrameworkPX_Validation_Email::validate('11test')
        );

        // }}}
        // {{{ 半角英字のみで入力

        $this->assertFalse(
            xFrameworkPX_Validation_Email::validate('test')
        );

        // }}}

        // {{{ 間違ったメールアドレス

        $this->assertFalse(
            xFrameworkPX_Validation_Email::validate('asano@xenophy.c')
        );

        $this->assertFalse(
            xFrameworkPX_Validation_Email::validate('asano@xe$ophy.com')
        );

        $this->assertFalse(
            xFrameworkPX_Validation_Email::validate('asanoAtxenophy.com')
        );

        // }}}

        // }}}
        // {{{ --------------true--------------

        // {{{ 空文字(NotEmptyでチェックするため)

        $this->assertTrue(
            xFrameworkPX_Validation_Email::validate('')
        );

        // }}}
        // {{{ メールアドレス入力

        $this->assertTrue(
            xFrameworkPX_Validation_Email::validate('info@xenophy.com')
        );

        $this->assertTrue(
            xFrameworkPX_Validation_Email::validate('asano@xenophy.com')
        );

        $this->assertTrue(
            xFrameworkPX_Validation_Email::validate('!asano@xenophy.com')
        );

        $this->assertTrue(
            xFrameworkPX_Validation_Email::validate('____@xenophy.com')
        );

        // }}}

        // }}}
        // {{{ --------------check--------------

        // {{{ 半角スペースのみはfalse

        $this->assertFalse(
            xFrameworkPX_Validation_Email::validate(' ')
        );

        // }}}
        // {{{ 全角スペースのみはfalse

        $this->assertFalse(
            xFrameworkPX_Validation_Email::validate('　')
        );

        // }}}
        // {{{ 半角スペース混じりはfalse

        $this->assertFalse(
            xFrameworkPX_Validation_Email::validate('asan o@xenophy.com')
        );

        // -------------------------------------------------------------------
        //  半角スペース先頭はtrue（trimが効いている）
        // -------------------------------------------------------------------

        $this->assertTrue(
            xFrameworkPX_Validation_Email::validate(' asano@xenophy.com')
        );

        // -------------------------------------------------------------------

        // }}}
        // {{{ 全角スペース混じりはfalse

        $this->assertFalse(
            xFrameworkPX_Validation_Email::validate('asano@xe　nophy.com')
        );

        $this->assertFalse(
            xFrameworkPX_Validation_Email::validate('　asano@xenophy.com')
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
