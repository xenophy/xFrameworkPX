<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Validation_Email Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Email.php 1178 2010-01-05 15:13:08Z tamari $
 */

// {{{ xFrameworkPX_Validation_Email

/**
 * xFrameworkPX_Validation_Email Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Validation_Email
 */
class xFrameworkPX_Validation_Email
{

    // {{{ validate

    /**
     * validate
     *
     * Emailアドレス妥当性チェック
     *
     * @param mixed 検査データ
     * @return bool true:OK, false:NG
     * @access public
     */
    public function validate($target)
    {

        // 空はチェックしない
        if (empty($target)) {
            return true;
        }

        // チェックに使用する正規表現
        $reg = '/^([*+!.&#$|\'\\%\/0-9a-z^_`{}=?~:-]+)'
                    .'@(([0-9a-z-]+\.)+[0-9a-z]{2,})$/i';

        // チェック、正しければtrueを返却
        return preg_match($reg, trim($target)) ? true : false;
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
