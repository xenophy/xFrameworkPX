<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Validation_Phone Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Phone.php 1399 2010-01-20 01:28:59Z kotsutsumi $
 */

// {{{ xFrameworkPX_Validation_Phone

/**
 * xFrameworkPX_Validation_Phone Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Validation_Phone
 */
class xFrameworkPX_Validation_Phone
{

    // {{{ validate

    /**
     * validate
     *
     * 全角チェック
     *
     * @param mixed 検査データ
     * @return bool true:OK, false:NG
     * @access public
     */
    public function validate($target, $opt=array('mobile' => true))
    {
        // 空はチェックしない
        if (empty($target)) {
            return true;
        }

        if(is_numeric($target)) {
            return true;
        }

        $ret = false;
        $regexPhone = "/^(0(?:[1-9]|[1-9]{2}\d{0,2}))-([2-9]\d{0,3})-(\d{4})$/";
        $regexMobile = "/^0[57-9]0-\d{4}-\d{4}$/";

        $ret = (preg_match($regexPhone, $target) === 1);
        if($ret === false && $opt['mobile']) {
            $ret = (preg_match($regexMobile, $target) === 1);
        }

        return $ret;
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
