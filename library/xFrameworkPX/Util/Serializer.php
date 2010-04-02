<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Util_Serializer Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Util
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Serializer.php 616 2009-12-12 17:56:31Z  $
 */

// {{{ xFrameworkPX_Util_Serializer

/**
 * xFrameworkPX_Util_Serializer Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Util
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Util_Serializer
 */
class xFrameworkPX_Util_Serializer
{

    // {{{ serialize

    /**
     * シリアライズメソッド
     *
     * @param mixed $serialize
     * @return string シリアライズ文字列
     */
    public static function serialize($serialize)
    {

        // シリアライズ文字列
        $ret = null;

        if (is_a($serialize, "SimpleXMLElement")) {
            $classObject = new stdClass();
            $classObject->type = get_class($serialize);
            $classObject->data = $serialize->asXml();

            $ret = @serialize($classObject);
        } else {
            $ret = @serialize($serialize);
        }

        return $ret;
    }

    // }}}
    // {{{ unserialize

    /**
     * アンシリアライズメソッド
     *
     * @param string $serialize シリアライズ済文字列
     * @return mixed 復号化オブジェクト
     */
    public static function unserialize($serialize)
    {

        $ret = @unserialize($serialize);

        if ($ret === false) {

            if ($serialize !== @serialize(false)) {
                throw new xFrameworkPX_Util_Serializer_Exception(
                    'Unserializing failed.'
                );
            }

        }

        if (is_a($ret, "stdClass")) {

            if ($ret->type == "SimpleXMLElement") {
                $ret = simplexml_load_string($ret->data);
            }

        }

        return $ret;
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
