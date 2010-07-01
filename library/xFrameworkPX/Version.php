<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Version Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Version.php 1337 2010-01-14 12:18:24Z kotsutsumi $
 */

// {{{ xFrameworkPX_Version

/**
 * xFrameworkPX_Version Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Version
 */
final class xFrameworkPX_Version
{

    // {{{ const

    const VERSION = '3.5.2';

    // }}}
    // {{{ compare

    /**
     * バージョン比較メソッド
     *
     * @param $strVersion
     * @return int 最初のバージョンが 2 番目のバージョンより小さい場合に -1、 
     *             同じ場合に 0、そして 2 番目のバージョンのほうが小さい場合に
     *              1
     */
    public static function compare($version)
    {
        return version_compare($version, self::VERSION);
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
