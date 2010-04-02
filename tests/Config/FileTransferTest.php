<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Config_FileTransferTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_Config
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: FileTransferTest.php 931 2009-12-24 10:42:44Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';

require_once 'xFrameworkPX/Object.php';
require_once 'xFrameworkPX/ConfigInterface.php';
require_once 'xFrameworkPX/Config.php';
require_once 'xFrameworkPX/Exception.php';

require_once 'xFrameworkPX/Config/FileTransfer.php';
require_once 'xFrameworkPX/Config/Exception.php';

// }}}
// {{{ xFrameworkPX_Config_FileTransferTest

/**
 * xFrameworkPX_Config_FileTransferTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_Config
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_Config_FileTransferTest extends PHPUnit_Framework_TestCase
{

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

        // インスタンスが生成されていないとき
        $this->assertType(
            'xFrameworkPX_Config_FileTransfer',
            xFrameworkPX_Config_FileTransfer::getInstance()
        );

        // インスタンスが生成されているとき
        $this->assertType(
            'xFrameworkPX_Config_FileTransfer',
            xFrameworkPX_Config_FileTransfer::getInstance()
        );
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
