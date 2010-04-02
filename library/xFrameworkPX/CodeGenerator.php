<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_CodeGenerator Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: CodeGenerator.php 1181 2010-01-06 03:27:06Z tamari $
 */

// {{{ xFrameworkPX_CodeGenerator

/**
 * xFrameworkPX_CodeGenerator Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_CodeGenerator
 */
class xFrameworkPX_CodeGenerator
{

    // {{{ factory

    /**
     * ファクトリーメソッド
     *
     * @param string $type タイプ名
     * @return mixed クラスオブジェクト
     */
    public static function factory($type)
    {

        // クラス名生成
        $clsName = __CLASS__ . '_' . ucfirst(strtolower($type));

        return new $clsName();
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
