<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Validation_Date Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Date.php 1386 2010-01-18 19:47:33Z kotsutsumi $
 */

// {{{ xFrameworkPX_Validation_Date

/**
 * xFrameworkPX_Validation_Date Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Validation_Date
 */
class xFrameworkPX_Validation_Date
{

    // {{{ validate

    /**
     * validate
     *
     * 日付妥当性チェック
     *
     * @param mixed 検査データ
     * @return bool true:OK, false:NG
     */
    public function validate($target)
    {

        // 空はチェックしない
        if (empty($target)) {
            return true;
        }

        // /か-で区切って変数へ
        if (is_numeric($target) && strlen($target) === 8) {
            $year = substr($target, 0, 4);
            $month = substr($target, 4, 2);
            $day = substr($target, 6, 2);
        } else {
            $temp = preg_split('/[.\/-]+/', $target);
            if (count($temp) !== 3) {
                return false;
            }
            list($year, $month, $day) = $temp;
        }

        // 数値以外はfalse
        if (
            !is_numeric($year) ||
            !is_numeric($month) ||
            !is_numeric($day)
        ) {
            return false;
        }

        // 日付妥当性チェック
        if (checkdate($month, $day, $year)) {
            return true;
        }

        return false;
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
