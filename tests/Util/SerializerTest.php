<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Util_SerializerTest Class File
 *
 * PHP versions 5
 *
 * @category   Tests
 * @package    xFrameworkPX_Util
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: SerializerTest.php 1161 2010-01-05 01:32:30Z tamari $
 */

// {{{ requires

set_include_path(get_include_path() . PATH_SEPARATOR . '../../library');

/**
 * Require Files
 */
require_once 'PHPUnit/Framework.php';
require_once 'xFrameworkPX/Util/Serializer.php';
require_once 'xFrameworkPX/Exception.php';
require_once 'xFrameworkPX/Util/Exception.php';
require_once 'xFrameworkPX/Util/Serializer/Exception.php';

// }}}
// {{{ xFrameworkPX_Util_SerializerTest

/**
 * xFrameworkPX_Util_SerializerTest Class
 *
 * @category   Tests
 * @package    xFrameworkPX_Util
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: @package_version@
 * @link       http://www.xframeworkpx.com/api/
 */
class xFrameworkPX_Util_SerializerTest extends PHPUnit_Framework_TestCase
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
    // {{{ testSerialize

    /**
     * serializeテスト
     *
     * @return void
     */
    public function testSerialize()
    {

        // {{{ 配列テスト

        $array = array('test');
        $this->assertEquals(
            $array,
            xFrameworkPX_Util_Serializer::unserialize(
                xFrameworkPX_Util_Serializer::serialize($array)
            )
        );

        // }}}
        // {{{ 文字列テスト

        $string = 'xFrameworkPX';
        $this->assertEquals(
            $string,
            xFrameworkPX_Util_Serializer::unserialize(
                xFrameworkPX_Util_Serializer::serialize($string)
            )
        );

        // }}}
        // {{{ オブジェクトテスト

        $object = new stdClass();
        $object->id = 'xFrameworkPX';
        $this->assertEquals(
            $string,
            xFrameworkPX_Util_Serializer::unserialize(
                xFrameworkPX_Util_Serializer::serialize($string)
            )
        );

        // }}}
        // {{{ SimpleXMLElementテスト

        $xml  = '';
        $xml .= '<?xml version="1.0" encoding="utf-8"?>';
        $xml .= '<actionstack>';
        $xml .= '    <actions></actions>';
        $xml .= '    <validators></validators>';
        $xml .= '</actionstack>';

        $xml = simplexml_load_string($xml);
        $this->assertEquals(
            $xml,
            xFrameworkPX_Util_Serializer::unserialize(
                xFrameworkPX_Util_Serializer::serialize($xml)
            )
        );

        // }}}

    }

    // }}}
    // {{{ testUnserialize

    /**
     * unserializeテスト
     *
     * @return void
     */
    public function testUnserialize()
    {

        // {{{ 配列テスト

        $array = array('test');
        $this->assertEquals(
            $array,
            xFrameworkPX_Util_Serializer::unserialize(
                xFrameworkPX_Util_Serializer::serialize($array)
            )
        );

        // }}}
        // {{{ 文字列テスト

        $string = 'xFrameworkPX';
        $this->assertEquals(
            $string,
            xFrameworkPX_Util_Serializer::unserialize(
                xFrameworkPX_Util_Serializer::serialize($string)
            )
        );

        // }}}
        // {{{ オブジェクトテスト

        $object = new stdClass();
        $object->id = 'xFrameworkPX';
        $this->assertEquals(
            $string,
            xFrameworkPX_Util_Serializer::unserialize(
                xFrameworkPX_Util_Serializer::serialize($string)
            )
        );

        // }}}
        // {{{ SimpleXMLElementテスト

        $xml  = '';
        $xml .= '<?xml version="1.0" encoding="utf-8"?>';
        $xml .= '<actionstack>';
        $xml .= '    <actions></actions>';
        $xml .= '    <validators></validators>';
        $xml .= '</actionstack>';

        $xml = simplexml_load_string($xml);
        $this->assertEquals(
            $xml,
            xFrameworkPX_Util_Serializer::unserialize(
                xFrameworkPX_Util_Serializer::serialize($xml)
            )
        );

        // }}}
        // {{{ 真偽値テスト

        $this->assertFalse(
            xFrameworkPX_Util_Serializer::unserialize(
                xFrameworkPX_Util_Serializer::serialize(false)
            )
        );

        // }}}
        // {{{ シリアライズファイルテスト

        $file = file_get_contents(
            dirname(__FILE__) . '\_files\SerializeTestFile.txt'
        );

        try {
            xFrameworkPX_Util_Serializer::unserialize($file);
        } catch (xFrameworkPX_Util_Serializer_Exception $ex) {
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
