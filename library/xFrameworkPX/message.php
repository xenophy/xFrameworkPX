<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX Message File
 *
 * PHP versions 5
 *
 * xFrameworkPX内で使用する様々なメッセージを定義します。
 * このファイルを読み込む前に、同名で定義を行うことでロケール対応が行えます。
 *
 * PX_ERR10000 - PX_ERR10XXX : xFrameworkPX_Dispatcher
 * PX_ERR20000 - PX_ERR20XXX : xFrameworkPX_Util_Observable
 * PX_ERR30000 - PX_ERR30XXX : xFrameworkPX_Model
 * PX_ERR35000 - PX_ERR35XXX : xFrameworkPX_CodeGenerator
 * PX_ERR40000 - PX_ERR40XXX : xFrameworkPX_Controller
 * PX_ERR41000 - PX_ERR41XXX : xFrameworkPX_Controller_Console
 * PX_ERR42000 - PX_ERR42XXX : xFrameworkPX_Controller_Web
 * PX_ERR43000 - PX_ERR43XXX : xFrameworkPX_Controller_Action
 * PX_ERR44000 - PX_ERR44XXX : xFrameworkPX_Controller_Ajax
 * PX_ERR45000 - PX_ERR45XXX : xFrameworkPX_Controller_ExtDirect
 * PX_ERR46000 - PX_ERR46XXX : xFrameworkPX_Controller_Component_Mail
 * PX_ERR47000 - PX_ERR47XXX : xFrameworkPX_Controller_Component_PhpSession
 * PX_ERR48000 - PX_ERR48XXX : xFrameworkPX_Controller_Component_RapidDrive
 * PX_ERR49000 - PX_ERR49XXX : xFrameworkPX_Controller_Component_Session
 * PX_ERR50000 - PX_ERR50XXX : xFrameworkPX_Loader_Auto
 * PX_ERR51000 - PX_ERR51XXX : xFrameworkPX_Loader_Core
 * PX_ERR60000 - PX_ERR60XXX : xFrameworkPX_View
 * PX_ERR61000 - PX_ERR61XXX : xFrameworkPX_View_Smarty
 * PX_ERR70000 - PX_ERR70XXX : xFrameworkPX_Log
 * PX_ERR90000 - PX_ERR90XXX : Other
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: message.php 871 2009-12-23 05:12:52Z tamari $
 */

// {{{ メッセージキャラセット定義

if (!defined('PX_MSG_CHARSET')) {
    define('PX_MSG_CHARSET', 'UTF-8');
}

// }}}
// {{{ xFrameworkPX_Dispatcher


// }}}
// {{{ xFrameworkPX_Util_Observable

if (!defined('PX_ERR20001')) {
    define('PX_ERR20001', '%s event is not register.');
}

if (!defined('PX_ERR20002')) {
    define('PX_ERR20002', 'Callback setting failed.');
}

// }}}
// {{{ xFrameworkPX_Model

if (!defined('PX_ERR30001')) {
    define('PX_ERR30001', 'Specified %s doesn\'t exist.');
}

// }}}
// {{{ xFrameworkPX_CodeGenerator

if (!defined('PX_ERR35000')) {
    define('PX_ERR35000', '%s was not found.');
}

// }}}
// {{{ xFrameworkPX_Controller

if (!defined('PX_ERR40000')) {
    define('PX_ERR40000', '%2$s class was not found in %1$s');
}

// }}}
// {{{ xFrameworkPX_Controller_Web

if (!defined('PX_ERR41000')) {
    define('PX_ERR41000', '%1$s was not found.');
}

if (!defined('PX_ERR41001')) {
    define('PX_ERR41001', '%2$s class was not found in %1$s');
}

// }}}
// {{{ xFrameworkPX_Controller_Component_RapidDrive

if (!defined('PX_ERR48000')) {
    define('PX_ERR48000', '%1$s is unknown mode.');
}

// }}}
// {{{ xFrameworkPX_Loader_Auto


// }}}
// {{{ xFrameworkPX_Loader_Core

if (!defined('PX_ERR50000')) {
    define('PX_ERR50000', 'Class \'%s\' not found.');
}

// }}}
// {{{ xFrameworkPX_View_Smarty

if (!defined('PX_ERR61000')) {
    define('PX_ERR61000', 'LayoutFile( %s ) is not found.');
}

// }}}
// {{{ xFrameworkPX_Log

if (!defined('PX_ERR70000')) {
    define('PX_ERR70000', 'ClassFile( %s ) is not found.');
}

if (!defined('PX_ERR70001')) {
    define('PX_ERR70001', 'Class \'%s\' not found.');
}

// }}}
// {{{ Other

if (!defined('PX_ERR90001')) {
    define('PX_ERR90001', 'Clone is not allowed against %s.');
}

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
