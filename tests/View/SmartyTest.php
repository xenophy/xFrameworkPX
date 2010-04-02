<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_View_AllTests Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_View
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: SmartyTest.php 965 2009-12-26 05:24:33Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';

require_once 'xFrameworkPX/extender.php';
require_once 'xFrameworkPX/Object.php';
require_once 'xFrameworkPX/Util/Observable.php';
require_once 'xFrameworkPX/Util/MixedCollection.php';
require_once 'xFrameworkPX/View.php';

require_once 'xFrameworkPX/View/Smarty.php';

require_once 'xFrameworkPX/Exception.php';
require_once 'xFrameworkPX/View/Exception.php';

// }}}
// {{{ xFrameworkPX_View_SmartyTest

/**
 * xFrameworkPX_View_SmartyTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_View
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_View_SmartyTest extends PHPUnit_Framework_TestCase
{

    // {{{ props

    protected $_pxConf = array(
        'DEBUG' => 'false',
        'BEHAVIOR_DIR' => '../behaviors',
        'CACHE_DIR' => './_files/cache',
        'CONFIG_DIR' => '../configs',
        'CONTROLLER_DIR' => '../controllers',
        'LAYOUT_DIR' => '../layouts',
        'LIB_DIR' => '../library',
        'PX_LIB_DIR' => 'C:\UserDir\pxexamples\library\xFrameworkPX',
        'LOG_DIR' => '../logs',
        'MODULE_DIR' => '../modules',
        'TEMPLATE_DIR' => '../templates',
        'WEBROOT_DIR' => '../webapp',
        'DEFAULT_ACTION' => 'index',
        'CONTROLLER_PREFIX' => '.',
        'CONTROLLER_EXTENSION' => '.php',
        'CONFIG_PREFIX' => '_',
        'ERROR404' => 'Error404.php',
        'CONTENT_PATH_KEY' => 'cp',
        'ETM' => 'true',
        'COMPRESS_MODE' => 'false',
        'SAPI' => 'apache2handler',
        'USE_FILE_TRANSFER' => 'true',
        'ALLOW_EXT' => array( 'html' ),

        'CONFIG' => array(
            'DATABASE' => 'database.pxml',
            'FILETRANSFER' => 'filetransfer.pxml',
            'GLOBAL' => 'global.pxml',
            'SITE' => 'site.pxml',
            'SUPER' => 'super.pxml'
        ),

        'SESSION' => array(
            'ID' => 'PHPSESSID',
            'AUTO_START' => 'true',
            'TYPE' => 'Php',
            'TIMEOUT' => 'NULL'
        ),

        'VIEW' => array(
            'NAME' => 'Smarty',
            'DEBUGGING' => 'false',
            'CACHING' => '0',
            'FORCE_COMPILE' => 'false',
            'USE_SUB_DIRS' => 'true',
            'LEFT_DELIMITER' => '<!--{',
            'RIGHT_DELIMITER' => '}-->'
        ),

        '_XFRAMEWORKPX_CLASS_LIST' => array(
            'Version.class.php',
            'Exception.class.php',
            'Util/Exception.class.php',
            'Util/Observable/Exception.class.php',
            'Util/Observable.class.php',
            'Util/MixedCollection.class.php',
            'Util/Serializer/Exception.class.php',
            'Util/Serializer.class.php',
            'CodeGenerator.class.php',
            'CodeGenerator/Exception.class.php',
            'CodeGenerator/Core.class.php',
            'CodeGenerator/Php/Exception.class.php',
            'CodeGenerator/Php.class.php',
            'CodeGenerator/Php/Generator.class.php',
            'CodeGenerator/Php/Doc.class.php',
            'CodeGenerator/Php/Class.class.php',
            'CodeGenerator/Php/ClassDoc.class.php',
            'CodeGenerator/Php/File.class.php',
            'CodeGenerator/Php/FileDoc.class.php',
            'CodeGenerator/Php/Method.class.php',
            'CodeGenerator/Php/MethodDoc.class.php',
            'CodeGenerator/Php/Props.class.php',
            'CodeGenerator/Php/PropsDoc.class.php',
            'CodeGenerator/Php/Params.class.php',
            'Config/Exception.class.php',
            'Config.interface.php',
            'Config.class.php',
            'Config/Database.class.php',
            'Config/FileTransfer.class.php',
            'Config/Global.class.php',
            'Config/Site.class.php',
            'Config/Super.class.php',
            'Controller/Exception.class.php',
            'Controller.class.php',
            'Controller/Console.class.php',
            'Controller/Web.class.php',
            'Controller/Action.class.php',
            'Controller/Component.class.php',
            'Controller/Component/Exception.class.php',
            'Controller/Component/Session.class.php',
            'Controller/Component/RapidDrive.class.php',
            'Model/Exception.class.php',
            'Model.class.php',
            'Model/Behavior.class.php',
            'Model/Adapter.class.php',
            'Model/Adapter/MySQL.class.php',
            'Model/RapidDrive.class.php',
            'Validation.class.php',
            'Validation/Alpha.class.php',
            'Validation/AlphaNumeric.class.php',
            'Validation/BgColor.class.php',
            'Validation/Date.class.php',
            'Validation/Email.class.php',
            'Validation/Exception.class.php',
            'Validation/Hankaku.class.php',
            'Validation/HankakuKana.class.php',
            'Validation/NotEmpty.class.php',
            'Validation/Number.class.php',
            'Validation/Phone.class.php',
            'Validation/Url.class.php',
            'Validation/ZenkakuHira.class.php',
            'Validation/Zenkaku.class.php',
            'Validation/ZenkakuKana.class.php',
            'Validation/ZenkakuNum.class.php',
            'View.class.php',
            'View/Smarty.class.php',
            'View/Exception.class.php'
        )
    );

    // }}}
    // {{{ setUp

    /**
     * セットアップ
     *
     * @return void
     */
    protected function setUp()
    {

    }

    // }}}
    // {{{ tearDown

    /**
     * 終了処理
     *
     * @return void
     */
    protected function tearDown()
    {

    }

    // }}}
    // {{{ testGetInstance

    /**
     * getInstanceテスト
     *
     * @return void
     */
    public function testGetInstance()
    {

        if (file_exists($this->_pxConf['CACHE_DIR'] . '/cache')) {
            rmdir($this->_pxConf['CACHE_DIR'] . '/cache');
        }

        if (file_exists($this->_pxConf['CACHE_DIR'] . '/templates_c')) {
            rmdir($this->_pxConf['CACHE_DIR'] . '/templates_c');
        }

        // {{{ 引数生成

        $conf = new xFrameworkPX_Util_MixedCollection(
            array('pxconf' => $this->_pxConf)
        );

        // }}}
        // {{{ 引数なしはnullを返す

        $this->assertNull(xFrameworkPX_View_Smarty::getInstance());

        // }}}
        // {{{ インスタンス生成

        $this->assertType(
            'xFrameworkPX_View_Smarty',
            xFrameworkPX_View_Smarty::getInstance($conf)
        );

        // }}}
        // {{{ ２回目は再生成せずにインスタンスを返す(引数なしでも同様)

        $this->assertType(
            'xFrameworkPX_View_Smarty',
            xFrameworkPX_View_Smarty::getInstance()
        );

        // }}}

    }

    // }}}
    // {{{ testOnRender

    /**
     * onRenderテスト
     */
    public function testOnRender()
    {

        // {{{ インスタンス生成

        $conf = new xFrameworkPX_Util_MixedCollection(
            array(
                'file' => 'index.html',
                'path' => '../webapp/',
                'cp' => '_files',
                'relpath' => ''
            )
        );

        $view = xFrameworkPX_View_Smarty::getInstance($conf);

        // }}}
        // {{{ setUserDataメソッドコール

        $view->setUserData('smarty', 'hoge');

        // }}}
        // {{{ メソッドコール（例外発生）

        try {

            $view->onRender();

        } catch (xFrameworkPX_View_Exception $ex) {
            $this->assertTrue(true);
        }

        // }}}

    }

    // }}}

}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
