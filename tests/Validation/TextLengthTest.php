<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Validation_TextLengthTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: TextLengthTest.php 964 2009-12-25 17:23:11Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';

require_once 'xFrameworkPX/Validation/TextLength.php';

// }}}
// {{{ xFrameworkPX_Validation_TextLengthTest

/**
 * xFrameworkPX_Validation_TextLengthTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_Validation_TextLengthTest extends PHPUnit_Framework_TestCase
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
     * 指定した長さの文字列のみ許可
     *
     * @return void
     */
    public function testValidate()
    {

        $validation = new xFrameworkPX_Validation_TextLength();

        // {{{ --------------false--------------

        // {{{ 最大文字数よりも長い半角文字の場合false

        $this->assertFalse(
            $validation->validate(
                'testhogefoobar',
                array(
                    'maxlength' => 10,
                    'minlength' => 5
                )
            )
        );

        // }}}
        // {{{ 最大文字数よりも長い全角文字の場合false(エンコード指定なし)

        $this->assertFalse(
            $validation->validate(
                'てすとほげほげふうばあ',
                array(
                    'maxlength' => 10,
                    'minlength' =>5
                )
            )
        );

        // }}}
        // {{{ 最大文字数よりも長い全角文字の場合false(エンコード指定あり)

        $this->assertFalse(
            $validation->validate(
                mb_convert_encoding(
                    'テストホゲホゲフーバー', 'sjis', 'utf-8'
                ),
                array(
                    'encode' => 'sjis',
                    'maxlength' => 10,
                    'minlength' =>5
                )
            )
        );

        // }}}
        // {{{ 最小文字数よりも短い半角文字の場合false

        $this->assertFalse(
            $validation->validate(
                'hoge',
                array(
                    'maxlength' => 10,
                    'minlength' => 5
                )
            )
        );

        // }}}
        // {{{ 最小文字数よりも短い全角文字の場合false(エンコード指定なし)

        $this->assertFalse(
            $validation->validate(
                'ほげふう',
                array(
                    'maxlength' => 10,
                    'minlength' => 5
                )
            )
        );

        // }}}
        // {{{ 最小文字数よりも短い全角文字の場合false(エンコード指定あり)

        $this->assertFalse(
            $validation->validate(
                mb_convert_encoding('ホゲフー', 'sjis', 'utf-8'),
                array(
                    'encode' => 'sjis',
                    'maxlength' => 10,
                    'minlength' => 5
                )
            )
        );

        // }}}

        // }}}
        // {{{ --------------true--------------

        // {{{ 空文字の場合は無条件でtrue

        $this->assertTrue(
            $validation->validate(
                '',
                array(
                    'maxlength' => 10,
                    'minlength' => 5
                )
            )
        );

        // }}}
        // {{{ 条件の設定なしの場合はtrue

        $this->assertTrue(
            $validation->validate('', array())
        );

        // }}}
        // {{{ 条件に一致する半角文字列の場合はtrue

        $this->assertTrue(
            $validation->validate(
                'hogefoo',
                array(
                    'maxlength' => 10,
                    'minlength' => 5
                )
            )
        );

        // }}}
        // {{{ 条件に一致する全角文字列の場合はtrue(エンコード指定なし)

        $this->assertTrue(
            $validation->validate(
                'ほげふうばあ',
                array(
                    'maxlength' => 10,
                    'minlength' => 5
                )
            )
        );

        // }}}
        // {{{ 条件に一致する全角文字列の場合はtrue(エンコード指定あり)

        $this->assertTrue(
            $validation->validate(
                mb_convert_encoding('ホゲフーバー', 'sjis', 'utf-8'),
                array(
                    'encode' => 'sjis',
                    'maxlength' => 10,
                    'minlength' => 5
                )
            )
        );

        // }}}

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
