<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Validation_TextLength Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: TextLength.php 1178 2010-01-05 15:13:08Z tamari $
 */

// {{{ xFrameworkPX_Validation_TextLength

/**
 * xFrameworkPX_Validation_TextLength Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Validation
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Validation_TextLength
 */
class xFrameworkPX_Validation_TextLength
{

    // {{{ properties

    /**
     * エンコード種別
     */
    private $_encode = 'utf-8';

    // }}}
    // {{{ validate

    /**
     * validate
     *
     * 文字数チェックチェック
     *
     * @param mixed 検査データ
     * @return bool true:OK, false:NG
     * @access public
     */
    public function validate($target, $options)
    {

        // 空はチェックしない
        if ((!isset($target) || $target == "")) {
            return true;
        }

        // エンコード種別設定
        $this->_encode = (isset($options['encode']))
                        ? (string)$options['encode']
                        : 'utf-8';

        // 文字数チェック
        if (isset($options['maxlength']) &&
            mb_strlen(
                $target,
                $this->_encode
            ) > (int)$options['maxlength']
        ) {
            return false;
        }

        if (
            isset($options['minlength']) &&
            mb_strlen(
                $target,
                $this->_encode
            ) < (int)$options['minlength']
        ) {
            return false;
        }

        return true;
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
