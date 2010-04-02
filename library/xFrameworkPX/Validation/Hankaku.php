<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Validation_Hankaku Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Hankaku.php 1178 2010-01-05 15:13:08Z tamari $
 */

// {{{ xFrameworkPX_Validation_Hankaku

/**
 * xFrameworkPX_Validation_Hankaku Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Validation_Hankaku
 */
class xFrameworkPX_Validation_Hankaku
{

    // {{{ validate

    /**
     * validate
     *
     * 半角文字チェック（記号、半角スペースを含む半角英数）
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

        // チェックに使用する正規表現
        $reg = '/^[\w\d\s_\.\,\/\<\>\?\!\"\#\$\%\&\'\(\)'
                    .'\=\-\~\^\|\\\{\]\[\]\;\+\*\:]+$/';

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
