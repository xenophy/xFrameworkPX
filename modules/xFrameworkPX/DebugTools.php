<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_DebugTools Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_DebugTools
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: DebugTools.php 1342 2010-01-14 13:13:18Z kotsutsumi $
 */

// {{{ xFrameworkPX_DebugTools

/**
 * xFrameworkPX_DebugTools Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 1.0.0
 */
class xFrameworkPX_DebugTools extends xFrameworkPX_Model
{
    // {{{ props

    /**
     * 使用テーブル設定
     *
     * @var string
     */
    public $usetable = false;

    // }}}
    // {{{ clearCaches

    public function clearCaches($schema, $config, $template)
    {
        $dir = $this->conf->px['CACHE_DIR'];

        if ($schema) {
            foreach(get_filelist($dir, array('ext' => 'schema')) as $file) {
                unlink($file);
            }
        }

        if ($config) {
            foreach(get_filelist($dir, array('ext' => 'pxml')) as $file) {
                unlink($file);
            }
        }

        if ($template) {
            removeDirectory($dir . '/templates_c');
        }

    }

    // }}}
    // {{{ removeSession

    public function removeSession($key)
    {
        unset($_SESSION[$key]);
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
