<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +-------------------------------------------------------------------------+
// | PHP version 5                                                           |
// +-------------------------------------------------------------------------+
// | Copyright (c) 2006 - 2006 Xenophy CO., LTD.                             |
// +-------------------------------------------------------------------------+
// | LICENSE: This source file is subject to xFramework software license.    |
// | that is available through the world-wide-web at the following URI:      |
// | http://www.xenophy.com/licanse/xFramework/license.txt.                  |
// +-------------------------------------------------------------------------+
// | Authors: Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>                   |
// +-------------------------------------------------------------------------+
//
// $Id: outputfilter.UTF82SJIS.php 337 2009-11-24 07:58:02Z kotsutsumi $

/**
 * Smarty Plugins
 *
 * Smarty preフィルタ( SJIS -> UTF8 )ファイル
 *
 * @package     xFramework
 * @author      Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @version     $Id: outputfilter.UTF82SJIS.php 337 2009-11-24 07:58:02Z kotsutsumi $
 */

// {{{ template_outputfilter_UTF82SJIS

/**
 * Smarty outputフィルタ( UTF8 -> SJIS )
 *
 * @category    Smarty Plugins
 * @package     View Plugins
 * @author      Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @version     $Id: outputfilter.UTF82SJIS.php 337 2009-11-24 07:58:02Z kotsutsumi $
 * @since       Function available since Release 2.0.0
 */
function smarty_outputfilter_UTF82SJIS($tpl_source, &$template_object) {

    return mb_convert_encoding( $tpl_source, 'SJIS-win', 'UTF-8' );
}

// }}}

/*
 * Local Variables:
 * mode: php
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 */
?>
