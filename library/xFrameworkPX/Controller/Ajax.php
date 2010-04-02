<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Controller_Ajax Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Controller
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Ajax.php 616 2009-12-12 17:56:31Z  $
 */

// {{{ xFrameworkPX_Controller_Ajax

/**
 * xFrameworkPX_Controller_Ajax Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Controller
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Controller_Ajax
 */
class xFrameworkPX_Controller_Ajax extends xFrameworkPX_Controller_Web
{
    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @param xFrameworkPX_Util_MixedCollection $conf 設定オブジェクト
     * @return void
     */
    public function __construct($conf)
    {
        // スーパークラスメソッドコール
        parent::__construct($conf);

        // ヘッダー送信
        header('Content-Type: text/javascript');
    }

    // }}}
    // {{{ setUp

    /**
     * 開始イベントハンドラ
     *
     * @return bool サスペンドフラグ
     */
    public function setUp()
    {

    }

    // }}}
    // {{{ tearDown

    /**
     * 終了イベントハンドラ
     *
     * @return bool サスペンドフラグ
     */
    public function tearDown()
    {
        // JSON出力
        if (
            !is_null(
                xFrameworkPX_View::getInstance()->getUserData('ajax')
            )
        ) {
            echo json_encode(
                xFrameworkPX_View::getInstance()->getUserData('ajax')
            );
        }
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
