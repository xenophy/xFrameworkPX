<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Examples Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    Examples
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: .wiki.php 991 2009-12-26 14:02:21Z kotsutsumi $
 */

// {{{ Examples

/**
 * Examples Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 1.0.0
 */
class wiki extends xFrameworkPX_Controller_Action
{
    // {{{ execute

    /**
     * コールバックメソッド
     *
     * @return bool サスペンドフラグ
     */
    public function execute()
    {
        $id = null;

        if (isset($this->get->id)) {
            $id = $this->get->id;
        }

        $path = implode(
            DS,
            array(
                $this->conf->execpath,
                'wiki',
                $id . '.wiki'
            )
        );
        $path = str_replace('_', DS, $path);

        if (file_exists($path)) {
            $wiki = new xFrameworkPX_Wiki();
            exit($wiki->wiki(file_get_contents($path)));
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
