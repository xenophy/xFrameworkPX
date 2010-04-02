<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Debug Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Debug.php 1396 2010-01-19 07:00:14Z kotsutsumi $
 */

// {{{ xFrameworkPX_Debug

/**
 * xFrameworkPX_Debug Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Log
 */
class xFrameworkPX_Debug extends xFrameworkPX_Object
{
    // {{{ props

    /**
     * デバッグレベル
     */
    public $level = 0;

    /**
     * 開始時間
     *
     * @var float
     */
    protected $_startTime = 0;

    /**
     * ユーザー開始時間
     *
     * @var float
     */
    protected $_startUserTime = 0;

    /**
     * 終了時間
     *
     * @var float
     */
    protected $_endTime = 0;

    /**
     * ユーザー終了時間
     *
     * @var float
     */
    protected $_endUserTime = 0;

    /**
     * クエリー情報
     *
     * @var array
     */
    protected $_query = array();

    /**
     * ユーザーデータ情報
     *
     * @var array
     */
    protected $_userdata = array();

    /**
     * パラメーター情報
     *
     * @var array
     */
    protected $_parameter = array();

    /**
     * プロファイル情報
     *
     * @var array
     */
    protected $_profile = array();

    /**
     * トレース情報
     *
     * @var array
     */
    protected $_trace = array();

    /**
     * 最終実行クエリ－
     */
    protected $_lastQuery = null;

    /**
     * 最終実行バインド
     */
    protected $_lastBinds = null;

    /**
     * パラメータデータ設定フラグ
     */
    public $isParameterSetted = false;

    /**
     * xFrameworkPX設定オブジェクト
     *
     * @var array
     */
    protected $_pxconf;

    /**
     * インスタンス変数
     *
     * @var xFrameworkPX
     */
    protected static $_instance = null;

    // }}}
    // {{{ getInstance

    /**
     * インスタンス取得メソッド
     *
     * @return xFrameworkPXインスタンス
     */
    public static function getInstance($conf = null)
    {
        // インスタンス取得
        if (!isset(self::$_instance)) {
            self::$_instance = new xFrameworkPX_Debug();
        }

        return self::$_instance;
    }

    // }}}
    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @return void
     */
    protected function __construct()
    {
    }

    // }}}
    // {{{ __clone

    /**
     * インスタンス複製メソッド
     *
     * @return void
     */
    public final function __clone()
    {
        throw new xFrameworkPX_Config_Exception(
            sprintf(PX_ERR90001, get_class($this))
        );
    }

    // }}}
    // {{{ startTime

    public function startTime()
    {
        if ($this->level >= 2) {
            $this->_startTime = microtime(true);
        }
    }

    // }}}
    // {{{ time

    public function time()
    {
        if ($this->level >= 2) {
            $this->_startUserTime = microtime(true);
        }
    }

    // }}}
    // {{{ endTime

    public function endTime()
    {
        if ($this->level >= 2) {
            $this->_endTime = microtime(true);
        }
    }

    // }}}
    // {{{ timeEnd

    public function timeEnd()
    {
        if ($this->level >= 2) {

            $this->_endUserTime = microtime(true);

            $bt = debug_backtrace();
            if (isset($bt[1])) {
                $this->addProfileData(
                    $bt[1]['class'],
                    $bt[1]['class'],
                    $bt[1]['function'],
                    'User',
                    $this->_endUserTime - $this->_startUserTime
                );
            }
        }
    }

    // }}}
    // {{{ getProcessingTime

    public function getProcessingTime()
    {
        if ($this->level >= 2) {
            return $this->_endTime - $this->_startTime;
        }

        return null;
    }

    // }}}
    // {{{ addProfileData

    public function addProfileData($instance, $cls, $method, $type, $time)
    {
        if ($this->level >= 2) {
            $this->_profile[] = array(
                count($this->_profile) + 1,
                $instance,
                $cls,
                $method,
                $type,
                $time
            );
        }
    }

    // }}}
    // {{{ addUserData

    public function addUserData($name, $value)
    {
        if ($this->level >= 2) {
            ob_get_clean();
            ob_start();
            print_r($value);
            $temp = ob_get_clean();
            $bool = is_bool($value);
            $orgVal = $value;

            if (is_string($temp)) {
                $short = explode("\n", $temp);
                $short = $short[0];
            } else {
                $short = $temp;
            }
            $value = $temp;

            $short = mb_strimwidth($short, 0, 50, '...');
            if ($bool) {
                if ($orgVal === true){
                    $short = 'true';
                } else {
                    $short = 'false';
                }
            }
            $temp = str_replace('    ', '&nbsp;&nbsp;&nbsp;&nbsp;', nl2br($temp));
            if ($bool) {
                if ($orgVal === true){
                    $temp = 'true';
                } else {
                    $temp = 'false';
                }
            }
            $this->_userdata[] = array(
                count($this->_userdata) + 1,
                $name,
                $short,
                $temp
            );
        }
    }

    // }}}
    // {{{ addParameter

    public function addParameter($name, $value, $type)
    {
        if ($this->level >= 2) {
            ob_get_clean();
            ob_start();
            print_r($value);
            $temp = ob_get_clean();

            if (is_string($temp)) {
                $short = explode("\n", $temp);
                $short = $short[0];
            } else {
                $short = $temp;
            }
            $value = $temp;

            $this->_parameter[] = array(
                count($this->_parameter) + 1,
                $name,
                mb_strimwidth($short, 0, 50, '...'),
                str_replace('    ', '&nbsp;&nbsp;&nbsp;&nbsp;', nl2br($temp)),
                $type
            );
        }
    }

    // }}}
    // {{{ addQuery

    public function addQuery($table, $module, $query, $rows, $time)
    {
        if ($this->level >= 2) {
            $short = explode("\n", $query);
            $short = $short[0];

            $query = xFrameworkPX_Util_Format::formatSQL(
                $query,
                true,
                true
            );

            $this->_query[] = array(
                count($this->_query) + 1,
                $table,
                $module,
                mb_strimwidth($short, 0, 50, '...'),
                $rows,
                $time,
                $query
            );
        }
    }

    // }}}
    // {{{ addTrace

    public function addTrace($cls, $method, $line, $var, $tag)
    {
        if ($this->level >= 2) {
            ob_get_clean();
            ob_start();
            print_r($var);
            $temp = ob_get_clean();

            if (is_string($temp)) {
                $short = explode("\n", $temp);
                $short = $short[0];
            } else {
                $short = $temp;
            }
            $var = $temp;

            $this->_trace[] = array(
                count($this->_trace) + 1,
                $cls,
                $method,
                $line,
                $tag,
                mb_strimwidth($short, 0, 50, '...'),
                str_replace('    ', '&nbsp;&nbsp;&nbsp;&nbsp;', nl2br($var))
            );
        }
    }

    // }}}
    // {{{ getResultData

    public function getResultData()
    {
        if ($this->level >= 2) {

            // セッションデータ作成
            $session = array();
            $i = 1;
            foreach ($_SESSION as $key => $value) {

                ob_get_clean();
                ob_start();
                print_r($value);
                $temp = ob_get_clean();

                if (is_string($temp)) {
                    $short = explode("\n", $temp);
                    $short = $short[0];
                } else {
                    $short = $temp;
                }

                $value = $temp;

                $session[] = array(
                    $i,
                    $key,
                    str_replace('    ', '&nbsp;&nbsp;&nbsp;&nbsp;', nl2br($value)),
                    mb_strimwidth($short, 0, 50, '...'),
                );
                $i++;
            }

            // COOKIEデータ作成
            $cookie = array();
            $i = 1;
            foreach ($_COOKIE as $key => $value) {

                ob_get_clean();
                ob_start();
                print_r($value);
                $temp = ob_get_clean();

                if (is_string($temp)) {
                    $short = explode("\n", $temp);
                    $short = $short[0];
                } else {
                    $short = $temp;
                }
                $value = $temp;
                $cookie[] = array(
                    $i,
                    $key,
                    str_replace('    ', '&nbsp;&nbsp;&nbsp;&nbsp;', nl2br($value)),
                    mb_strimwidth($short, 0, 50, '...'),
                );
                $i++;
            }

            $ret = array(
                'trace' => $this->_trace,
                'session' => $session,
                'cookie' => $cookie,
                'query' => $this->_query,
                'parameter' => $this->_parameter,
                'userdata' => $this->_userdata,
                'profiler' => $this->_profile
            );

            return json_encode($ret);
        }

        return null;
    }

    // }}}
    // {{{ setLastQuery

    public function setLastQuery($query)
    {
        if ($this->level >= 1) {
            $this->_lasyQuery = $query;
        }
    }

    // }}}
    // {{{ setLastBinds

    public function setLastBinds($binds)
    {
        if ($this->level >= 1) {
            $this->_lasyBinds = $binds;
        }
    }

    // }}}
    // {{{ getLastQuery

    public function getLastQuery()
    {
        if ($this->level >= 1) {
            return $this->_lasyQuery;
        }
        return null;
    }

    // }}}
    // {{{ getLastBinds

    public function getLastBinds()
    {
        if ($this->level >= 1) {
            return $this->_lasyBinds;
        }
        return null;
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
