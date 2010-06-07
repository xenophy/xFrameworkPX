<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Model Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Model.php 1465 2010-01-22 10:28:19Z kotsutsumi $
 */

// {{{ xFrameworkPX_Model

/**
 * xFrameworkPX_Model Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Model
 */
abstract class xFrameworkPX_Model extends xFrameworkPX_Util_Observable
{
    // {{{ props

    /**
     * プライマリーキー名
     *
     * @var string
     */
    public $primaryKey = null;

    /**
     * 変換キャラセット配列
     *
     * @var array
     */
    protected $_charasetmap = array (
        'sjis-win'  => 'cp932',
        'sjis_win'  => 'cp932',
        'shift-jis' => 'cp932',
        'shift_jis' => 'cp932',
        'shiftjis'  => 'cp932',
        'sjis'      => 'cp932',
        'eucjp'     => 'eucjpms',
        'euc-jp'    => 'eucjpms',
        'euc_jp'    => 'eucjpms',
        'eucjp-win' => 'eucjpms',
        'eucjp_win' => 'eucjpms',
        'utf_8'     => 'utf8',
        'utf-8'     => 'utf8',
        'utf8'      => 'utf8'
    );

    /**
     * ビヘイビア配列
     *
     * @var array
     */
    public $behaviors = array();

    /**
     * ビヘイビアオブジェクト
     *
     * @var xFrameworkPX_Util_MixedCollection
     */
    private $_behaviors;

    /**
     * 設定オブジェクト
     *
     * @var xFrameworkPX_Util_MixedCollection
     */
    public $conf;

    /**
     * 接続設定オブジェクト
     *
     * @var xFrameworkPX_Util_MixedCollection
     */
    public $conn;

    /**
     * フェッチモード
     *
     * @var int
     */
    public $fetch = PDO::FETCH_ASSOC;

    /**
     * アダプターオブジェクト
     *
     * @var xFrameworkPX_Model_Adapter
     */
    public $adapter;

    /**
     * PDOオブジェクト
     *
     * @var PDO
     */
    public $pdo;

    /**
     * 直前実行プリペアードステートメント
     *
     * @var PDOStatement
     */
    public $stmtBefore = null;

    /**
     * 直前実行SQL文字列
     *
     * @var string
     */
    public $beforeQuery = '';

    /**
     * テーブル名接頭辞
     *
     * @var mixed
     */
    public $tablePrefix = 'tbl_';

    /**
     * 使用テーブル名
     *
     * @var mixed
     */
    public $usetable;

    /**
     * モジュール一覧
     *
     * @var array
     */
    public $modules;

    /**
     * オートコネクション
     *
     * @var bool
     */
    public $autoConn = true;

    public $hasOne = array();
    public $belongsTo = array();
    public $hasMany = array();

    public $controller;

    // }}}
    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @param SimpleXMLElement $conf 設定オブジェクト
     */
    public function __construct($conf, $controller)
    {
        if ($conf['px']['DEBUG'] >= 2) {
            $startTime = microtime(true);
        }

        $this->controller = $controller;

        // プライマリーキー名設定
        if (is_null($this->primaryKey)) {
            $this->primaryKey = isset($conf->primaryKey)
                                ? $conf->primaryKey
                                : 'id';
        }

        // 設定オブジェクト格納
        $this->conf = $conf;

        // コネクション設定
        if ($this->usetable !== false && $this->autoConn) {
            $this->connection();
        }

        // ビヘイビア設定
        $this->behaviors = array_merge(
            $this->behaviors, array('LiveRecord')
        );

        // ビヘイビアバインド
        $this->_bindBehavior();

        if ($conf['px']['DEBUG'] >= 2) {
            xFrameworkPX_Debug::getInstance()->addProfileData(
                get_class($this),
                'xFrameworkPX_Model',
                '__construct',
                'Model',
                microtime(true) - $startTime
            );
        }
    }

    // }}}
    // {{{ connection

    /**
     * PDO接続メソッド
     *
     * @param SimpleXMLElement $conf 設定オブジェクト
     */
    public function connection($settings = null)
    {

        // コネクション設定オブジェクト生成
        $this->conn = $this->mix();

        // コネクション設定取得
        foreach ($this->conf->database->connection as $xmlConn) {

            if ((string)$xmlConn->driver == 'oci') {
                $this->conn->{
                    (string)$xmlConn[ 'name' ]
                } = new xFrameworkPX_Util_MixedCollection( array(
                    'charset' => (string)$xmlConn->charset,
                    'adapter' => (string)$xmlConn->adapter,
                    'driver' => (string)$xmlConn->driver,
                    'host' => (string)$xmlConn->host,
                    'user' => (string)$xmlConn->user,
                    'password' => (string)$xmlConn->password,
                    'database' => (string)$xmlConn->database,
                    'prefix' => (string)$xmlConn->prefix,
                    'port' => (string)$xmlConn->port,
                    'socket' => (string)$xmlConn->socket,
                    'nls' => (array)$xmlConn->nls
                ) );
            } else {
                $this->conn->{
                    (string)$xmlConn[ 'name' ]
                } = new xFrameworkPX_Util_MixedCollection( array(
                    'charset' => (string)$xmlConn->charset,
                    'adapter' => (string)$xmlConn->adapter,
                    'driver' => (string)$xmlConn->driver,
                    'host' => (string)$xmlConn->host,
                    'user' => (string)$xmlConn->user,
                    'password' => (string)$xmlConn->password,
                    'database' => (string)$xmlConn->database,
                    'prefix' => (string)$xmlConn->prefix,
                    'port' => (string)$xmlConn->port,
                    'socket' => (string)$xmlConn->socket
                ) );
            }

        }

        if (is_array($settings)) {
            $this->conn->{$this->conf->conn} = $this->mix(array(
                'charset' => '',
                'adapter' => '',
                'driver' => '',
                'host' => '',
                'user' => '',
                'password' => '',
                'database' => '',
                'prefix' => '',
                'port' => '',
                'socket' => ''
            ));

            foreach ($settings as $key => $value) {
                $this->conn->{$this->conf->conn}->$key = $value;
            }

        }

        // アダプター対応表作成
        $adaptermap = array(
            'mysql' => 'MySQL',
            'pgsql' => 'PgSQL',
            'postgresql' => 'PgSQL',
            'oracle' => 'Oracle'
        );

        // コネクション設定
        if ($this->usetable !== false) {

            // テーブル名が未指定の場合、クラス名を元に自動設定
            if (is_null($this->usetable)) {
                $this->usetable = $this->getTableName();
            }

            if (!isset($this->conn->{$this->conf->conn})) {
                throw new xFrameworkPX_Model_Exception(sprintf(
                    PX_ERR30002, $this->conf->conn
                ));
            }

            if (isset($adaptermap[$this->conn->{$this->conf->conn}->adapter])) {

                // アダプターオブジェクト生成
                $clsName = sprintf(
                    'xFrameworkPX_Model_Adapter_%s',
                    $adaptermap[$this->conn->{$this->conf->conn}->adapter]
                );
            } else {
                throw new xFrameworkPX_Model_Exception(sprintf(
                    PX_ERR30003, $this->conn->{$this->conf->conn}->adapter
                ));
            }

            $this->adapter = new $clsName();
            // PDOオブジェクト生成
            $this->pdo = @new PDO(

                // DSN設定
                $this->getDSN(
                    strtolower($this->conn->{$this->conf->conn}->driver),
                    $this->conn->{$this->conf->conn}->host,
                    $this->conn->{$this->conf->conn}->port,
                    $this->conn->{$this->conf->conn}->database,
                    $this->conn->{$this->conf->conn}->socket
                ),

                // ユーザー設定
                $this->conn->{$this->conf->conn}->user,

                // パスワード設定
                $this->conn->{$this->conf->conn}->password
            );
            $this->pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            // MySQLキャラセット設定
            if (strtolower($this->conn->{$this->conf->conn}->driver) === 'mysql') {
                $chaeset = strtolower((string)$xmlConn->charset);

                if (isset($this->_charasetmap[$chaeset])) {
                    $this->pdo->exec(
                        sprintf(
                            'SET NAMES %s',
                            $this->_charasetmap[$chaeset]
                        )
                    );
                }

            } else if (strtolower($this->conn->{$this->conf->conn}->driver) === 'oci') {

                if (isset($this->conn->{$this->conf->conn}->nls)) {

                    if (isset($this->conn->{$this->conf->conn}->nls->date_format)) {
                        $this->pdo->exec(
                            sprintf(
                                "ALTER SESSION SET NLS_DATE_FORMAT = '%s'",
                                $this->conn->{$this->conf->conn}->nls['date_format']
                            )
                        );
                    }

                    if (isset($this->conn->{$this->conf->conn}->nls->timestamp_format)) {
                        $this->pdo->exec(
                            sprintf(
                                "ALTER SESSION SET NLS_TIMESTAMP_FORMAT = '%s'",
                                $this->conn->{$this->conf->conn}->nls['timestamp_format']
                            )
                        );
                    }

                }

            }

        }

    }

    // }}}
    // {{{ getDSN

    /**
     * DSN文字列取得メソッド
     *
     * @param $type
     * @param $host
     * @param $database
     * @param $unixScoket
     * @return
     */
    public function getDSN($type, $host, $port, $database, $unixScoket = null)
    {
        // デバッグ用計測開始
        if ($this->conf['px']['DEBUG'] >= 2) {
            $startTime = microtime(true);
        }

        switch ($type) {

            case 'mysql':
                $dsn = 'mysql:';

                if ($host) {
                    $temp[] = sprintf('host=%s', $host);

                    if ($port) {
                        $temp[] = sprintf('port=%s', $port);
                    }

                } else if ($unixSocket) {
                    $temp[] = sprintf('unix_socket=%s', $unixSocket);
                }

                $temp[] = sprintf('dbname=%s', $database);
                break;

            case 'oci':
                $dsn = 'oci:';

                if (!matchesIn($database, '/') && $host) {

                    if ($port) {
                        $host .= ':' . $port;
                    }

                    $database = sprintf('%s/%s', $host, $database);
                }

                $temp[] = sprintf('dbname=%s', $database);
                break;

            case 'pgsql':
                $dsn = 'pgsql:';

                if ($host) {
                    $temp[] = sprintf('host=%s', $host);
                }

                if ($port) {
                    $temp[] = sprintf('port=%s', $port);
                }

                if ($database) {
                    $temp[] = sprintf('dbname=%s', $database);
                }

                break;

            default:
                $dsn = null;
                break;
        }

        if ($dsn) {
            $dsn .= implode(';', $temp);
        }

        if ($this->conf['px']['DEBUG'] >= 2) {
            xFrameworkPX_Debug::getInstance()->addProfileData(
                get_class($this),
                'xFrameworkPX_Model',
                'getDSN',
                'Model',
                microtime(true) - $startTime
            );
        }

        return $dsn;
    }

    // }}}
    // {{{ getTableName

    /**
     * テーブル名取得メソッド
     *
     * @return string テーブル名
     */
    public function getTableName($onlyTable = false)
    {
        if ($this->conf['px']['DEBUG'] >= 2) {
            $startTime = microtime(true);
        }

        $temp = explode('_', $this->toString());

        if ($onlyTable) {
            return end($temp);
        }

        $ret = implode(
            '',
            array(
                $this->tablePrefix,
                end($temp)
            )
        );

        if ($this->conf['px']['DEBUG'] >= 2) {
            xFrameworkPX_Debug::getInstance()->addProfileData(
                get_class($this),
                'xFrameworkPX_Model',
                'getTableName',
                'Model',
                microtime(true) - $startTime
            );
        }

        return $ret;
    }

    // }}}
    // {{{ isValid
    
    /**
     * バリデーションメソッド
     *
     * 自クラスのバリデーションを実行します。
     *
     * @param xFrameworkPX_Util_MixedCollection $data 入力値
     */
    public function isValid($data)
    {
        return $this->validation($this->mix(
            array(
                $this->toString() => $data
            )
        ));

    }
    
    // }}}
    // {{{ validation

    /**
     * バリデーションメソッド
     *
     * @param xFrameworkPX_Util_MixedCollection $data 入力値
     * @return xFrameworkPX_Util_MixedCollection エラー配列
     */
    public function validation($data)
    {
        if ($this->conf['px']['DEBUG'] >= 2) {
            $startTime = microtime(true);
        }

        $ret = $this->mix();
        $validators = null;
        $events = array();
        $eventName = '';
        $options = array();
        $errorMessages = array();
        $target = null;
        $validateRet = true;
        $msg = null;

        // バリデーションイベント登録
        foreach ($this->validators as $field => $rules) {

            $events[$field] = array();
            $options[$field] = array();
            $messages[$field] = array();

            if (!is_int(key($rules))) {

                // バリデーション読み込み
                $events[$field][] = $this->_loadValidation($rules);

                // オプション読み込み
                $options[$field][] = (isset($rules['option']))
                                     ? $rules['option']
                                     : null;

            } else {

                // 複数バリデーション指定
                $validators = $rules;
                foreach ($validators as $rules) {

                    // バリデーション読み込み
                    $eventName = $this->_loadValidation($rules);

                    if (!in_array($eventName, $events[$field])) {

                        $events[$field][] = $eventName;

                        // オプション読み込み
                        $options[$field][] =
                            (isset($rules['option']))
                            ? $rules['option']
                            : null;
                    }
                }
            }
        }

        // イベントディスパッチ
        $eventSuspendOrg = $this->eventSuspend;
        $this->eventSuspend = false;

        foreach ($events as $field => $event) {

            $target = null;
            $errorMessages = array();

            if (
                isset($data->{$this->toString()}) &&
                isset($data->{$this->toString()}->{$field})
            ) {
                $target = $data->{$this->toString()}->{$field};

                foreach ($event as $key => $eventName) {

                    $validateRet = true;
                    $msg = null;
                    $option = $options[$field][$key];

                    if (is_null($option)) {
                        $validateRet = $this->dispatch(
                            $eventName, $target
                        );
                    } else {
                        $validateRet = $this->dispatch(
                            $eventName, $target, $option
                        );
                    }

                    if ($validateRet === false) {

                        $rules = $this->validators[$field];

                        if (!is_int(key($rules))) {
                            $msg = $rules['message'];
                        } else {
                            $msg = $rules[$key]['message'];
                        }

                        $errorMessages[] = $msg;
                    }
                }
            }

            if (count($errorMessages) > 0) {
                $ret->offsetSet(
                    $field,
                    $this->mix(
                        array(
                            'messages' => $errorMessages,
                            'target' => $target
                        )
                    )
                );
            }
        }

        $this->eventSuspend = $eventSuspendOrg;

        if ($this->conf['px']['DEBUG'] >= 2) {
            xFrameworkPX_Debug::getInstance()->addProfileData(
                get_class($this),
                'xFrameworkPX_Model',
                'validation',
                'Model',
                microtime(true) - $startTime
            );
        }

        return $ret;
    }

    // }}}
    // {{{ _bindBehavior

    /**
     * ビヘイビアバインドメソッド
     *
     * @return void
     */
    private function _bindBehavior()
    {
        $path = null;
        $clsName = null;
        $filename = null;

        // ビヘイビアオブジェクト生成
        $this->_behaviors = $this->mix();

        // ビヘイビア設定
        foreach ($this->behaviors as $behavior) {

            // xFrameworkPXディレクトリ走査
            $path = implode(
                DS,
                array(
                    $this->conf->px['PX_LIB_DIR'],
                    'Model/Behavior'
                )
            );

            $clsName = 'xFrameworkPX_Model_Behavior_' . $behavior;
            $filename = $behavior . '.php';

            $loadfile = normalize_path(
                implode(
                    DS,
                    array(
                        $path,
                        $filename
                    )
                )
            );

            if (file_exists($loadfile)) {
                if (!class_exists($clsName, false)) {
                    require_once $loadfile;
                }
                $this->_behaviors->{$behavior} = new $clsName($this);
            } else {

                // ユーザーディレクトリ走査
                $path = $this->conf->px['BEHAVIOR_DIR'];
                $clsName = $behavior;
                $filename = str_replace('_', DS, $behavior) . '.php';

                $loadfile = normalize_path(
                    implode(
                        DS,
                        array(
                            $path,
                            $filename
                        )
                    )
                );

                if (file_exists($loadfile)) {

                    if (!class_exists($clsName, false)) {
                        require_once $loadfile;
                    }

                    $clsName = str_replace('/', '_', $clsName);
                    $this->_behaviors->{$behavior} = new $clsName($this);

                } else {

                    // 指定したビヘイビアが存在しない場合は例外
                    throw new xFrameworkPX_Model_Exception(
                        PX_ERR30001,
                        $behavior
                    );
                }
            }
        }

        // バインド処理
        foreach ($this->_behaviors as $name => $behavior) {

            // メソッド一覧取得
            $methods = get_class_methods(get_class($behavior));

            // バインド設定
            foreach ($methods as $method) {

                // メソッド接頭辞が'bind'の場合リスナー登録
                if (startsWith($method, 'bind')) {

                    // バインド名設定
                    $name = lcfirst(substr($method, strlen('bind')));

                    // イベント名設定
                    $eventName = 'behavior.bind.' . $name;

                    // イベント追加
                    $this->addEvents($eventName);

                    // イベントリスナー追加
                    $this->on($eventName, array($behavior, $method));
                }
            }
        }
    }

    // }}}
    // {{{ __call

    /**
     * メソッドオーバーロード
     *
     * @param string $name メソッド名
     * @param array $args 引数配列
     * @return mixed
     * @access public
     */
    public function __call($name, $args)
    {
        $ret = null;
        $bindEventName = 'behavior.bind.' . $name;

        // リスナー存在確認
        if ($this->hasListener($bindEventName)) {

            // 引数文字列生成
            $arg = '';
            foreach ($args as $key => $value) {

                if ($key > 0) {
                    $arg .= ', ';
                }

                $arg .= '$args[ ' . $key . ' ]';
            }

            // イベントディスパッチ
            if (count($args) === 0) {
                eval('$ret = $this->dispatch($bindEventName);');
            } else {

                eval(
                    sprintf(
                        '$ret = $this->dispatch($bindEventName, %s);',
                        $arg
                    )
                );
            }

        } else {

            // fatalエラー発生
            trigger_error(
                sprintf('Cannot find method %s', $name),
                E_USER_ERROR
            );
        }

        if (is_bool($ret)) {
            return $ret;
        }

        return isset($ret[0]) ? $ret[0] : null;
    }

    // }}}
    // {{{ __get

    /**
     * 読み出しオーバーロード
     *
     * @param string $name プロパティ名
     * @return mixed 対象オブジェクト
     * @access public
     */
    public function __get($name)
    {
        $ret = null;

        switch ($name) {

            case 'behavior':
                $ret = $this->_behaviors;
                break;

            case 'config':
                $ret = $this->conf;
                break;

            case 'connconfig':
                $ret = $this->conn;
                break;

            case 'fetchmode':
                $ret = $this->fetch;
                break;

            case 'adapter':
                $ret = $this->adapter;
                break;

            case 'connection':
            case 'pdo':
                $ret = $this->pdo;
                break;

            case 'beforestmt':
                $ret = $this->stmtBefore;
                break;

            case 'beforequery':
                $ret = $this->beforeQuery;
                break;

            case 'tablePrefix':
                $ret = $this->tablePrefix;
                break;

            case 'usetable':
                $ret = $this->usetable;
                break;

            case 'view':
                $ret = $this->controller->view;
                break;
        }

        return $ret;
    }

    // }}}
    // {{{ __set

    /**
     * 書き込みオーバーロード
     *
     * @param string $name プロパティ名
     * @param mixed $value 設定値
     * @return void
     */
    public function __set($name, $value)
    {
        switch ($name) {
            case 'beforestmt':
                $this->stmtBefore = $value;
                break;
        }
    }

    // }}}
    // {{{ _loadValidation

    /**
     * バリデーションクラス読み込みメソッド
     *
     * @param array $rules ルール配列
     * @return 登録イベント名
     */
    private function _loadValidation($rules)
    {
        $eventName = 'validate.' . $rules['rule'];

        // イベント定義
        $this->addEvents($eventName);

        // バリデーション設定
        if (!$this->hasListener($eventName)) {

            if (startsWith($rules['rule'], 'validate')) {

                // イベントリスナー追加
                if (method_exists($this, $rules['rule'])) {

                    // 自クラスメソッド設定
                    $this->on($eventName, array($this, $rules['rule']));

                } else {

                    // ビヘイビアメソッド走査
                    foreach ($this->_behaviors as $behavior) {

                        // メソッド存在確認
                        if (method_exists($behavior, 'bind' . ucfirst($rules['rule']))) {
                            $this->on(
                                $eventName,
                                array($behavior, 'bind' . ucfirst($rules['rule']))
                            );
                        }
                    }
                }
            } else {

                // ビルトインバリデーション設定
                $clsName = 'xFrameworkPX_Validation_' . $rules['rule'];
                $clsPath = normalize_path(
                    implode(
                        DS,
                        array(
                            dirname(__FILE__),
                            'Validation',
                            $rules[ 'rule' ] . '.php'
                        )
                    )
                );

                // バリデータークラス読み込み
                if (!class_exists($clsName, false)) {
                    require_once($clsPath);
                }

                // バリデータークラス生成
                $cls = new $clsName();

                // イベントリスナー追加
                $this->on($eventName, array($cls, 'validate'));
            }
        }

        return $eventName;
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
