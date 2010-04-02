<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Controller_Component_Mail Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Controller_Component
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Mail.php 912 2009-12-23 20:00:22Z kotsutsumi $
 */

// {{{ xFrameworkPX_Controller_Component_Mail

/**
 * xFrameworkPX_Controller_Component_Mail Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Controller_Component
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Controller_Component_Mail
 */
class xFrameworkPX_Controller_Component_Mail
extends xFrameworkPX_Controller_Component
{
    // {{{ send

    /**
     * 送信メソッド
     *
     * @param xFrameworkPX_util_MixedCollection $conf 設定オブジェクト
     * @return void
     */
    public function send($conf)
    {
        $mail = new xFrameworkPX_Mail();
        $mail->send($conf);
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
