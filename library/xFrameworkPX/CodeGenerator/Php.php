<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_CodeGenerator_Php Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_CodeGenerator
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Php.php 1172 2010-01-05 14:02:02Z tamari $
 */

// {{{ xFrameworkPX_CodeGenerator_Php

/**
 * xFrameworkPX_CodeGenerator_Php Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_CodeGenerator
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_CodeGenerator_Php
 */
class xFrameworkPX_CodeGenerator_Php extends xFrameworkPX_CodeGenerator_Core
{

    // {{{ add

    /**
     * クラス定義追加メソッド
     *
     * @param xFrameworkPX_Util_MixedCollection $conf ファイル情報
     * @return xFrameworkPX_CodeGenerator_Php_File
     */
    public function add($conf)
    {

        foreach ($conf as $file) {

            // xFrameworkPX_CodeGenerator_Php_File生成
            $this->_files->offsetset(
                $file->clsName,
                new xFrameworkPX_CodeGenerator_Php_File($file)
            );
        }

        return $this->_files;
    }

    // }}}
    // {{{ render

    public function render()
    {

        foreach ($this->_files as $file) {
            $file->render();
        }

    }

    // }}}
    // {{{ get

    public function get($strName)
    {
        return $this->_files->{ $strName }->getWriter();
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
