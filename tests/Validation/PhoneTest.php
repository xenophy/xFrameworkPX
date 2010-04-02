<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Validation_PhoneTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: PhoneTest.php 956 2009-12-25 14:46:27Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';

require_once 'xFrameworkPX/Validation/Phone.php';


// }}}
// {{{ xFrameworkPX_Validation_PhoneTest

/**
 * xFrameworkPX_Validation_PhoneTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_Validation_PhoneTest extends PHPUnit_Framework_TestCase
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
     * 電話番号のみ許可
     *
     * @return void
     */
    public function testValidate()
    {

        // {{{ --------------false--------------

        // {{{ 桁が10と11以外

        $this->assertFalse(
            xFrameworkPX_Validation_Phone::validate('036805291413')
        );
        $this->assertFalse(
            xFrameworkPX_Validation_Phone::validate('68052914')
        );

        // }}}
        // {{{ 0以外から開始

        $this->assertFalse(
            xFrameworkPX_Validation_Phone::validate('3680529141')
        );

        // }}}
        // {{{ 11桁の場合のハイフンの位置が変

        $this->assertFalse(
            xFrameworkPX_Validation_Phone::validate('0805-555-5555')
        );

        // }}}
        // {{{ 11桁の場合、3-4-4桁以外はfalse

        $this->assertFalse(
            xFrameworkPX_Validation_Phone::validate('080-555-55555')
        );

        // }}}
        // {{{ 10桁の場合、先頭が２桁に満たない場合はfalse

        $this->assertFalse(
            xFrameworkPX_Validation_Phone::validate('0-36805-2914')
        );

        // }}}

        // }}}
        // {{{ --------------true--------------

        // 関係ないものは全てtrue

        // {{{ 空文字(NotEmptyでチェックするため)

        $this->assertTrue(
            xFrameworkPX_Validation_Phone::validate('')
        );

        // }}}
        // {{{ 全角ひらがな混じりで入力

        $this->assertTrue(
            xFrameworkPX_Validation_Phone::validate('03-6805-291あ')
        );

        // }}}
        // {{{ 全角数字混じりで入力

        $this->assertTrue(
            xFrameworkPX_Validation_Phone::validate('03-6805-291４')
        );

        // }}}
        // {{{ 全角カナ混じりで入力

        $this->assertTrue(
            xFrameworkPX_Validation_Phone::validate('03-6805-2テスト')
        );

        // }}}
        // {{{ 半角カナ混じりで入力

        $this->assertTrue(
            xFrameworkPX_Validation_Phone::validate('03-6805-2ﾃｽﾄ')
        );

        // }}}
        // {{{ 半角英字混じりで入力

        $this->assertTrue(
            xFrameworkPX_Validation_Phone::validate('03-6805-test')
        );

        // }}}
        // {{{ 半角記号混じりで入力

        $this->assertTrue(
            xFrameworkPX_Validation_Phone::validate('<03-6805-291>')
        );

        // }}}

        // 正しい電話番号

        // {{{ 半角数字とハイフンのみで入力

        $this->assertTrue(
            xFrameworkPX_Validation_Phone::validate('03-6805-2914')
        );
        $this->assertTrue(
            xFrameworkPX_Validation_Phone::validate('080-5555-5555')
        );

        // }}}
        // {{{ 半角数字のみで入力

        $this->assertTrue(
            xFrameworkPX_Validation_Phone::validate('0368052914')
        );

        // }}}

        // }}}
        // {{{ --------------check--------------

        // {{{ 半角スペースのみはtrue

        $this->assertTrue(
            xFrameworkPX_Validation_Phone::validate(' ')
        );

        // }}}
        // {{{ 全角スペースのみはtrue

        $this->assertTrue(
            xFrameworkPX_Validation_Phone::validate('　')
        );

        // }}}
        // {{{ 半角スペース混じりはtrue

        $this->assertTrue(
            xFrameworkPX_Validation_Phone::validate('te st')
        );

        $this->assertTrue(
            xFrameworkPX_Validation_Phone::validate(' test')
        );

        // }}}
        // {{{ 全角スペース混じりはtrue

        $this->assertTrue(
            xFrameworkPX_Validation_Phone::validate('te　st')
        );

        $this->assertTrue(
            xFrameworkPX_Validation_Phone::validate('　test')
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
