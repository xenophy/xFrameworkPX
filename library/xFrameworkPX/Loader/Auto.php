<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Loader_Auto Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Loader
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Auto.php 616 2009-12-12 17:56:31Z  $
 */

// {{{ xFrameworkPX_Loader_Auto

/**
 * xFrameworkPX_Loader_Auto Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Loader
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Autoload_Autoload
 */
class xFrameworkPX_Loader_Auto
{
    // {{{ props

    /**
     * 読み込みクラス配列
     *
     * @var array
     */
    public static $list = array();

    /**
     * コンフィグ配列
     *
     * @var array
     */
    public static $conf = array();

    // }}}
    // {{{ setConf

    /**
     * コンフィグ設定メソッド
     *
     * @param array $conf コンフィグ配列
     * @param string $path パス
     * @return void
     */
    public static function setConf($conf)
    {
        self::$conf = $conf;
    }

    // }}}
    // {{{ register

    /**
     * クラスファイル登録メソッド
     *
     * @param string $clsName クラス名
     * @param string $path パス
     * @return void
     */
    public static function register($clsName, $path)
    {
        self::$list[$clsName] = $path;
    }

    // }}}
    // {{{ load

    /**
     * 読み込みメソッド
     *
     * @param string $class クラス名
     * @return void
     */
    public static function load($class)
    {

/*
    // {{{ autoload

    /**
     * 自動読み込み
     */
     /*
    public function autoload( $class )
    {

        // {{{ コントローラー自動読み込み

        $path = normalize_path(
            $this->_conf['CONTROLLER_DIR'] .
            DS .
            $this->ajoin(
                array(
                    $this->_conf['CONTROLLER_PREFIX'],
                    $class,
                    $this->_conf['CONTROLLER_EXTENSION']
                )
            )
        );

        if (file_exists($path)) {
            include_once $path;
        }

        // }}}

    }
*/
    // }}}*/

        try {
            if (array_key_exists($class, self::$list)) {
                include self::$list[$class];
            } else {

                // コントローラー、モジュールから該当クラスを探し出す
                $exists = false;

                $controllerPath = '';
                $controllerPreffix = '';
                $controllerExtension = '';

                if (isset(self::$conf['CONTROLLER_DIR'])) {
                    $controllerPath = self::$conf['CONTROLLER_DIR'];
                }

                if (isset(self::$conf['CONTROLLER_PREFIX'])) {
                    $controllerPreffix = self::$conf['CONTROLLER_PREFIX'];
                }

                if (isset(self::$conf['CONTROLLER_EXTENSION'])) {
                    $controllerExtension = self::$conf['CONTROLLER_EXTENSION'];
                }

                $path = str_replace('_', DS, $class);
                $controllerPath = realpath($controllerPath);
                if ($controllerPath) {

                    $controllerPath = $controllerPath
                                    . DS
                                    . $controllerPreffix
                                    . $path
                                    . $controllerExtension;

                    if (file_exists($controllerPath)) {
                        include_once $controllerPath;
                        $exists = true;
                    }
                }

                $modulePath = '';
                $modulePreffix = '';
                $moduleExtension = '.php';

                if (isset(self::$conf['MODULE_DIR'])) {
                    $modulePath = self::$conf['MODULE_DIR'];
                }

                $modulePath = realpath($modulePath);
                if ($modulePath) {

                    $modulePath = $modulePath
                                . DS
                                . $modulePreffix
                                . $path
                                . $moduleExtension;
                    if (file_exists($modulePath)) {
                        include_once $modulePath;
                        $exists = true;
                    }
                }

                if (!$exists) {
                    throw new xFrameworkPX_Exception(sprintf(
                        PX_ERR50000,
                        $class
                    ));
                }
            }
        } catch (xFrameworkPX_Exception $e) {
            exit($e->printStackTrace());
        } catch (Exception $e) {
            $e = new xFrameworkPX_Exception($e->getMessage());
            exit($e->printStackTrace());
        }
    }

    // }}}

}

// }}}
// {{{ register autoload callback

spl_autoload_register(array('xFrameworkPX_Loader_Auto', 'load'));

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
