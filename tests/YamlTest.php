<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_YamlTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: YamlTest.php 882 2009-12-23 09:36:22Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'xFrameworkPX/Yaml.php';
require_once 'xFrameworkPX/Exception.php';

// }}}
// {{{ xFrameworkPX_YamlTest

/**
 * xFrameworkPX_YamlTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_YamlTest extends PHPUnit_Framework_TestCase
{

    // {{{ props

    private $_yaml = array();
    private $_file = null;

    // }}}
    // {{{ setUp

    /**
     * セットアップ
     *
     * @return void
     */
    protected function setUp()
    {

        if (file_exists('_files/test1.yaml')) {
            $this->_file = file('./_files/test1.yaml');

            foreach ($this->_file as $key => $line) {
                $this->_file[ $key ] = mb_convert_encoding(
                    $line, 'sjis-win', 'utf-8'
                );
            }
        } else {
            throw new RuntimeException(
                'This Test use file( \'test1\' ) not exists.'
            );
        }

        if (file_exists('_files/test2.yaml')) {
            $this->_yaml = xFrameworkPX_Yaml::decode('_files/test2.yaml');
        } else {
            throw new RuntimeException(
                'This Test use file( \'test2\' ) not exists.'
            );
        }

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
    // {{{ testDecode

    /**
     * testDecode
     *
     * @return void
     */
    public function testDecode()
    {

        $ret = xFrameworkPX_Yaml::decode($this->_file);
        /*
        echo "\n\n========== RESULT ==========\n\n";
        var_dump( $arrRet );
        echo "\n============================\n";
        */

        $ret = xFrameworkPX_Yaml::decode('./_files/test2.yaml');
        /*
        echo "\n\n========== RESULT ==========\n\n";
        var_dump( $arrRet );
        echo "\n============================\n";
        */

        
    }

    // }}}
    // {{{ testEncode

    /**
     * testEncode
     *
     * @return void
     */
    public function testEncode()
    {

        $code = xFrameworkPX_Yaml::encode($this->_yaml);
        /*
        echo "\n\n========== RESULT ==========\n\n";
        var_dump( $strCode );
        echo "\n============================\n";
        */
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
