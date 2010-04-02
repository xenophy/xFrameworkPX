<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Loader_Core Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Loader
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Core.php 1436 2010-01-20 15:46:38Z kotsutsumi $
 */

if (!defined('PX_CACHE')) {
    define('PX_CACHE', false);
}

// {{{ xFrameworkPX_Loader_Core

/**
 * xFrameworkPX_Loader_Core Class
 *
 * xFrameworkPXコアクラスの読み込みを行います。
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Autoload
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Loader_Core
 */
class xFrameworkPX_Loader_Core
{
    // {{{ const

    /**
     * xFrameworkPXクラスライブラリキャッシュファイル名
     */
    const CACHE_NAME = 'xFrameworkPX-all.php';

    // }}}
    // {{{ props

    /**
     * 読み込みフラグ
     *
     * @var boolean
     */
    private static $_loaded = false;

    // }}}
    // {{{ load

    /**
     * 読み込みメソッド
     *
     * @param string $class クラス名
     * @return void
     */
    public static function load()
    {

        if (self::$_loaded === false) {

            if (PX_CACHE) {

                // キャッシュ読み込み
                include self::CACHE_NAME;

            } else {

                // コアクラス読み込み
                include 'xFrameworkPX/message.php';
                include 'xFrameworkPX/extender.php';
                include 'xFrameworkPX/Object.php';
                include 'xFrameworkPX/Exception.php';
                include 'xFrameworkPX/Util/Exception.php';
                include 'xFrameworkPX/Util/Format.php';
                include 'xFrameworkPX/Util/MixedCollection.php';
                include 'xFrameworkPX/Util/Observable.php';
                include 'xFrameworkPX/Util/Observable/Exception.php';
                include 'xFrameworkPX/Util/Serializer.php';
                include 'xFrameworkPX/Util/Serializer/Exception.php';
                include 'xFrameworkPX/CodeGenerator.php';
                include 'xFrameworkPX/ConfigInterface.php';
                include 'xFrameworkPX/Config.php';
                include 'xFrameworkPX/Config/Database.php';
                include 'xFrameworkPX/Config/Exception.php';
                include 'xFrameworkPX/Config/FileTransfer.php';
                include 'xFrameworkPX/Config/Global.php';
                include 'xFrameworkPX/Config/Log.php';
                include 'xFrameworkPX/Config/Site.php';
                include 'xFrameworkPX/Config/Super.php';
                include 'xFrameworkPX/Controller.php';
                include 'xFrameworkPX/Controller/Web.php';
                include 'xFrameworkPX/Controller/Action.php';
                include 'xFrameworkPX/Controller/Ajax.php';
                include 'xFrameworkPX/Controller/Component.php';
                include 'xFrameworkPX/Controller/Console.php';
                include 'xFrameworkPX/Controller/Exception.php';
                include 'xFrameworkPX/Controller/ExtDirect.php';
                include 'xFrameworkPX/Controller/Component/Exception.php';
                include 'xFrameworkPX/Controller/Component/Mail.php';
                include 'xFrameworkPX/Controller/Component/Session.php';
                include 'xFrameworkPX/Controller/Component/PhpSession.php';
                include 'xFrameworkPX/Controller/Component/RapidDrive.php';
                include 'xFrameworkPX/Dispatcher.php';
                include 'xFrameworkPX/Debug.php';
                include 'xFrameworkPX/Log.php';
                include 'xFrameworkPX/Log/LogBase.php';
                include 'xFrameworkPX/Mail.php';
                include 'xFrameworkPX/Mime.php';
                include 'xFrameworkPX/Mobile.php';
                include 'xFrameworkPX/Model.php';
                include 'xFrameworkPX/Model/Adapter.php';
                include 'xFrameworkPX/Model/Behavior.php';
                include 'xFrameworkPX/Model/Exception.php';
                include 'xFrameworkPX/Model/RapidDrive.php';
                include 'xFrameworkPX/Model/Adapter/MySQL.php';
                include 'xFrameworkPX/Model/Adapter/PgSQL.php';
                include 'xFrameworkPX/Model/Behavior/LiveRecord.php';
                include 'xFrameworkPX/Model/Behavior/WiseTag.php';
                include 'xFrameworkPX/Validation.php';
                include 'xFrameworkPX/Version.php';
                include 'xFrameworkPX/View.php';
                include 'xFrameworkPX/View/Exception.php';
                include 'xFrameworkPX/View/Smarty.php';
                include 'xFrameworkPX/Wiki.php';
                include 'xFrameworkPX/Yaml.php';
                include 'xFrameworkPX/Loader/Auto.php';

                // 自動読み込みクラス設定
                xFrameworkPX_Loader_Auto::register(
                    'CodeGenerator_Core.php',
                    'CodeGenerator/Core.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'CodeGenerator_Exception.php',
                    'CodeGenerator/Exception.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'CodeGenerator_Php.php',
                    'CodeGenerator/Php.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'CodeGenerator_Php_Class.php',
                    'CodeGenerator/Php/Class.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'CodeGenerator/Php/ClassDoc.php',
                    'CodeGenerator/Php/ClassDoc.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'CodeGenerator/Php/Doc.php',
                    'CodeGenerator/Php/Doc.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'CodeGenerator/Php/Exception.php',
                    'CodeGenerator/Php/Exception.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'CodeGenerator/Php/File.php',
                    'CodeGenerator/Php/File.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'CodeGenerator/Php/FileDoc.php',
                    'CodeGenerator/Php/FileDoc.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'CodeGenerator/Php/Generator.php',
                    'CodeGenerator/Php/Generator.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'CodeGenerator/Php/Method.php',
                    'CodeGenerator/Php/Method.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'CodeGenerator/Php/MethodDoc.php',
                    'CodeGenerator/Php/MethodDoc.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'CodeGenerator/Php/Params.php',
                    'CodeGenerator/Php/Params.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'CodeGenerator/Php/Props.php',
                    'CodeGenerator/Php/Props.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'CodeGenerator/Php/PropsDoc.php',
                    'CodeGenerator/Php/PropsDoc.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'Validation/Alpha.php',
                    'Validation/Alpha.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'Validation/AlphaNumeric.php',
                    'Validation/AlphaNumeric.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'Validation/BgColor.php',
                    'Validation/BgColor.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'Validation/Date.php',
                    'Validation/Date.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'Validation/Email.php',
                    'Validation/Email.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'Validation/Exception.php',
                    'Validation/Exception.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'Validation/Hankaku.php',
                    'Validation/Hankaku.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'Validation/HankakuKana.php',
                    'Validation/HankakuKana.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'Validation/NotEmpty.php',
                    'Validation/NotEmpty.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'Validation/Number.php',
                    'Validation/Number.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'Validation/Phone.php',
                    'Validation/Phone.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'Validation/TextLength.php',
                    'Validation/TextLength.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'Validation/Url.php',
                    'Validation/Url.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'Validation/Zenkaku.php',
                    'Validation/Zenkaku.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'Validation/ZenkakuHira.php',
                    'Validation/ZenkakuHira.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'Validation/ZenkakuKana.php',
                    'Validation/ZenkakuKana.php'
                );

                xFrameworkPX_Loader_Auto::register(
                    'Validation/ZenkakuNum.php',
                    'Validation/ZenkakuNum.php'
                );
            }

            // 読み込みフラグ更新
            self::$_loaded = true;
        }
    }

    // }}}

}

// }}}
// {{{ load core classes

xFrameworkPX_Loader_Core::load();

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
