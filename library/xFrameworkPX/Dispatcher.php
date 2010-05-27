<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Dispatcher Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Dispatcher.php 1475 2010-02-27 23:45:20Z kotsutsumi $
 */

// {{{ xFrameworkPX_Dispatcher

/**
 * xFrameworkPX_Dispatcher Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Dispatcher
 */
class xFrameworkPX_Dispatcher extends xFrameworkPX_Object
{
    // {{{ props

    /**
     * インスタンス変数
     *
     * @var xFrameworkPX_Dispatcher
     */
    private static $_instance = null;

    /**
     * xFrameworkPX動作設定
     *
     * @var array
     */
    private $_conf;

    // }}}
    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @return void
     */
    private function __construct()
    {
        // xFrameworkPX動作設定デフォルト設定
        $this->_conf = array(

            // デバッグモード設定
            'DEBUG' => false,

            // 強制コントローラー実行
            'FORCE_CONTROLLER_EXECUTE' => false,

            // タイムゾーン設定
            'TIMEZONE' => 'Asia/Tokyo',

            // ビヘイビアディレクトリパス
            'BEHAVIOR_DIR' => '../behaviors',

            // バインド転送ディレクトリパス
            'BINDTRANSFER_DIR' => '../bindtransfer',

            // キャッシュディレクトリパス
            'CACHE_DIR' => '../cache',

            // 共通設定ディレクトリパス
            'CONFIG_DIR' => '../configs',

            // コントローラーディレクトリパス
            'CONTROLLER_DIR' => '../controllers',

            // レイアウトディレクトリパス
            'LAYOUT_DIR' => '../layouts',

            // ライブラリディレクトリパス
            'LIB_DIR' => '../library',

            // xFrameworkPXライブラリディレクトリパス
            'PX_LIB_DIR' => dirname(__FILE__),

            // ログ出力ディレクトリパス
            'LOG_DIR' => '../logs',

            // モジュールディレクトリパス
            'MODULE_DIR' => '../modules',

            // テンプレートディレクトリパス
            'TEMPLATE_DIR' => '../templates',

            // Webルートディレクトリパス
            'WEBROOT_DIR' => '../webapp',

            // デフォルトアクション名
            'DEFAULT_ACTION' => 'index',

            // コントローラーファイル接頭辞
            'CONTROLLER_PREFIX' => '.',

            // コントローラーファイル拡張子
            'CONTROLLER_EXTENSION' => '.php',

            // コントローラクラス名接尾辞
            'CONTROLLER_CLASS_SUFFIX' => 'Action',

            // 設定ファイル接頭辞
            'CONFIG_PREFIX' => '',

            // 404エラーテンプレートファイル名
            'ERROR404' => 'Error404.php',

            // 403エラーテンプレートファイル名
            'ERROR403' => 'Error403.php',

            // コンテンツパスキー設定
            'CONTENT_PATH_KEY' => 'cp',

            // ファイル転送使用フラグ
            'USE_FILE_TRANSFER' => true,

            // 仮想スクリーン許可拡張子
            'ALLOW_EXT' => array(
                'html'
            ),

            // 設定ファイル名
            'CONFIG' => array(

                // データベース設定ファイル
                'DATABASE' => 'database.pxml',

                // ファイル転送設定ファイル
                'FILETRANSFER' => 'filetransfer.pxml',

                // グローバルアクションコントローラー設定ファイル
                'GLOBAL' => 'global.pxml',

                // ログ設定ファイル
                'LOG' => 'log.pxml',

                // サイト設定ファイル
                'SITE' => 'site.pxml',

                // スーパーアクションコントローラー設定ファイル
                'SUPER' => 'super.pxml'
            ),

            // セッション設定
            'SESSION' => array(

                // ID設定
                'ID' => 'PHPSESSID',

                // 自動スタート設定
                'AUTO_START' => true,

                // タイプ設定
                'TYPE' => 'Php',

                // タイムアウト設定
                'TIMEOUT' => null
            ),

            // ビュークラス
            'VIEW' => array(

                // クラス名
                'NAME' => 'Smarty',

                // デバッグ設定
                'DEBUGGING' => false,

                // キャッシュ設定
                'CACHING' => 0,

                // 強制コンパイル設定
                'FORCE_COMPILE' => false,

                // キャッシュサブディレクトリの使用
                'USE_SUB_DIRS' => true,

                // 左デリミタ設定
                'LEFT_DELIMITER' => '<!--{',

                // 右デリミタ設定
                'RIGHT_DELIMITER' => '}-->'
            ),

            // WiseTag設定
            'WISE_TAG' => array(

                // ビューへのアサイン名設定
                'assign_name' => 'wt',

                // セッション登録名設定
                'session_name' => 'WiseTagConfig'
            )
        );
    }

    // }}}
    // {{{ __destruct

    /**
     * デストラクタ
     *
     * @return void
     */
    public function __destruct()
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
        // Cloneの使用は、%sによって許可されていません。
        throw new RuntimeException(
            sprintf(PX_ERR90001, get_class($this))
        );
    }

    // }}}
    // {{{ getInstance

    /**
     * インスタンス取得メソッド
     *
     * @return xFrameworkPXインスタンス
     */
    public static function getInstance()
    {
        // インスタンス取得
        if (!isset(self::$_instance)) {
            self::$_instance = new xFrameworkPX_Dispatcher();
        }

        return self::$_instance;
    }

    // }}}
    // {{{ run

    /**
     * 実行メソッド
     *
     * @param array $conf xFrameworkPX動作設定
     * @return void
     */
    public function run($conf = array())
    {
        // xFrameworkPX動作設定適用
        $this->_conf = array_merge($this->_conf, $conf);

        // Auto設定適用
        xFrameworkPX_Loader_Auto::setConf($this->_conf);

        // デバッグレベル設定
        xFrameworkPX_Debug::getInstance()->level = $this->_conf['DEBUG'];

        // デバッグ用計測開始
        xFrameworkPX_Debug::getInstance()->startTime();

        // デバッグモードによるエラーレポーティング設定
        if ($this->_conf['DEBUG'] === 0) {
            error_reporting(0);
        } else {
            error_reporting(E_ALL);
        }

        // タイムゾーン設定
        date_default_timezone_set($this->_conf['TIMEZONE']);

        // バインドトランスファー設定
        $this->_conf['BINDTRANSFER'] = false;

        // デフォルトアクション名をObjectクラスに設定
        $this->_defaultaction = $this->_conf['DEFAULT_ACTION'];

        // コンテンツパスキーをObjectクラスに設定
        $this->_cp = $this->_conf['CONTENT_PATH_KEY'];

        // アクセス制御
        if ($this->htaccess() === false) {
            return;
        }

        // 正規化されたURLで無い場合は、正規化してリダイレクト
        if (isset($_SERVER['REQUEST_URI'])) {
            $url = parse_url($_SERVER['REQUEST_URI']);
            if (isset($url['path']) && $url['path'] !== normalize_path($url['path'], '/')) {
                $query = '';
                if (isset($url['query'])) {
                    $query = '?' . $url['query'];
                }
                $this->redirect(normalize_path($url['path'], '/') . $query);
            }
        }

        // ファイル転送
        $isTransfer = $this->transfer();
        if ($isTransfer === false) {
            return;
        }

        // バインドトランスファーエミュレーション判定
        if (is_array($isTransfer) && $isTransfer['emulation'] === true) {
            $this->_conf['BINDTRANSFER'] = true;
        }

        // 仮想スクリーン
        if (!$this->isVirtualScreen() && $this->_conf['FORCE_CONTROLLER_EXECUTE'] === false) {

            if (PHP_SAPI === 'cli') {

                $controllername = implode(
                    '',
                    array(
                        $this->_conf['CONTROLLER_PREFIX'],
                        $this->getActionName(),
                        $this->_conf['CONTROLLER_EXTENSION']
                    )
                );

                $controllerpath = normalize_path(
                    implode(
                        DS,
                        array(
                            $this->_conf['WEBROOT_DIR'],
                            $this->getContentPath()
                        )
                    )
                );

                printf(
                    '%1$s Controller was not found in %2$s',
                    $this->getActionName(),
                    $controllerpath.$controllername
                );

            } else {

                // パスの末尾が/でない場合で404エラーになる場合は、/を付加してリダイレクト
                if (
                    isset($url['path']) &&
                    pathinfo($url['path'], PATHINFO_EXTENSION) === '' &&
                    !endsWith($url['path'], '/')
                ) {

                    $query = '';
                    if (isset($url['query'])) {
                        $query = '?' . $url['query'];
                    }
                    $this->redirect($url['path'] . '/' . $query);
                }

                // 404エラー
                header(sprintf('HTTP/1.1 404 %s', get_status_code(404)));

                // テンプレート出力
                include implode(
                    DS,
                    array(
                        $this->_conf['TEMPLATE_DIR'],
                        $this->_conf['ERROR404']
                    )
                );
            }

            return false;
        }

        // ログファイル設定読み込み
        $logconf = xFrameworkPX_Config_Log::getInstance()->import(
            $this->mix(
                array(
                    'path' => $this->_conf['CONFIG_DIR'],
                    'filename' => $this->_conf['CONFIG_PREFIX'] .
                                  $this->_conf['CONFIG']['LOG'],
                    'cachepath' => $this->_conf['CACHE_DIR']
                )
            )
        );

        // データベース設定読み込み
        $dbconf = xFrameworkPX_Config_Database::getInstance()->import(
            $this->mix(
                array(
                    'path' => $this->_conf['CONFIG_DIR'],
                    'filename' => $this->_conf['CONFIG_PREFIX'] .
                                  $this->_conf['CONFIG']['DATABASE'],
                    'cachepath' => $this->_conf['CACHE_DIR']
                )
            )
        );

        // コントローラー実行
        try {
            $this->getController(
                $this->mix(
                    array(
                        'pxconf' => $this->_conf,
                        'logconf' => $logconf->logger,
                        'dbconf' => $dbconf->database,
                        'params' => $this->getParams()
                    )
                )
            )->invoke();
        } catch (xFrameworkPX_Exception $e) {
            exit($e->printStackTrace());
        } catch (Exception $e) {
            $clsName = get_class($e);
            $e = new xFrameworkPX_Exception($e->getMessage());
            exit($e->printStackTrace($clsName));
        }

    }

    // }}}
    // {{{ isVirtualScreen

    /**
     * 仮想スクリーン判定メソッド
     *
     * @return boolean true:仮想スクリーン,false:仮想スクリーン以外
     */
    public function isVirtualScreen()
    {
        $filename = $this->getAccessFileName();
        $fileext = pathinfo($filename, PATHINFO_EXTENSION);

        if ($filename === '') {
            $filename = $this->_conf['DEFAULT_ACTION'];
            $fileext = 'html';
            $filename .= '.' . $fileext;
        }

        $webrootKey = 'WEBROOT_DIR';
        if ($this->_conf['BINDTRANSFER'] === true) {
            $webrootKey = 'BINDTRANSFER_DIR';
        }

        $path = normalize_path(
            implode(
                DS,
                array(
                    $this->_conf[$webrootKey],
                    $this->getContentPath(),
                    $filename
                )
            )
        );

        $controllername = implode(
            '',
            array(
                $this->_conf['CONTROLLER_PREFIX'],
                $this->getActionName(),
                $this->_conf['CONTROLLER_EXTENSION']
            )
        );

        $controllerpath = normalize_path(
            implode(
                DS,
                array(
                    $this->_conf[$webrootKey],
                    $this->getContentPath()
                )
            )
        );

        if (
            (
                !file_exists($path) &&
                !file_exists($controllerpath . DS . $controllername)
            ) ||
            (in_array($fileext, $this->_conf['ALLOW_EXT']) == false)
        ) {
            return false;
        }

        return true;
    }

    // }}}
    // {{{ getController

    /**
     * コントローラー取得メソッド
     *
     * return コントローラーオブジェクト
     */
    public function getController($conf)
    {
        if (PHP_SAPI === 'cli') {
            return new xFrameworkPX_Controller_Console($conf);
        }

        return new xFrameworkPX_Controller_Web($conf);
    }

    // }}}
    // {{{ htaccess

    /**
     * アクセス制御
     *
     * @return boolean
     */
    public function htaccess()
    {
        $htaccessFiles = array();
        $htaccessFile = '';
        $order = array('allow', 'deny');
        $hosts = array('allow' => array(), 'deny' => array());
        $users = array();
        $authType = '';
        $authName = '';
        $authUserFile = '';

        // .htaccess存在確認
        $filePath = normalize_path(
            implode(
                DS,
                array(
                    $this->_conf['WEBROOT_DIR'],
                    $this->getContentPath()
                )
            )
        );

        $filePath = explode(DS, $filePath);

        // 階層を上ってスキャン
        while (count($filePath) > 1) {
            $htaccessFile = implode(DS, $filePath) . DS . '.htaccess';

            if (file_exists($htaccessFile)) {
                $htaccessFiles[] = $htaccessFile;
            }

            array_pop($filePath);
        }

        if (empty($htaccessFiles)) {
            return true;
        }

        $htaccessFiles = array_reverse($htaccessFiles);

        // ファイル解析
        foreach ($htaccessFiles as $index => $filePath) {
            $operator = array();
            $option = array();
            $htaccess = file_get_contents($filePath);
            $htaccess = str_replace("\r\n", "\n", $htaccess);
            $htaccess = explode("\n", $htaccess);

            foreach ($htaccess as $value) {
                $temp = explode(' ', $value);

                if (isset($temp[0]) && trim($temp[0]) !== '') {
                    $operator[] = strtolower($temp[0]);
                    unset($temp[0]);
                    $option[] = array_values($temp);
                }
            }

            // 設定をオーバーライドするため初期化
            if (in_array('allow', $operator) && count($hosts['allow']) > 0) {
                $hosts['allow'] = array();
            }

            if (in_array('deny', $operator) && count($hosts['deny']) > 0) {
                $hosts['deny'] = array();
            }

            if (
                in_array('authuserfile', $operator) &&
                count($users) > 0
            ) {
                $users = array();
            }

            foreach ($operator as $key => $value) {

                switch(strtolower($value)) {

                    case 'authtype':

                        if (
                            isset($option[$key][0]) &&
                            trim($option[$key][0]) !== ''
                        ) {
                            $authType = strtolower($option[$key][0]);
                        }

                        break;

                    case 'authname':

                        if (
                            isset($option[$key][0]) &&
                            trim($option[$key][0]) !== ''
                        ) {
                            $authName = $option[$key][0];
                        }

                        break;

                    case 'authuserfile':

                        if (
                            isset($option[$key][0]) &&
                            trim($option[$key][0]) !== ''
                        ) {
                            $authUserFile = $option[$key][0];
                            $authUserFile = normalize_path(
                                implode(
                                    DS,
                                    array(
                                        $this->_conf['WEBROOT_DIR'],
                                        $this->getContentPath(),
                                        $authUserFile
                                    )
                                )
                            );

                            if (file_exists($authUserFile)) {
                                $authUserFile = file(
                                    $authUserFile, FILE_IGNORE_NEW_LINES
                                );

                                foreach ($authUserFile as $line) {
                                    $temp = explode(':', $line);

                                    if (!isset($users[$temp[0]])) {
                                        $users[$temp[0]] = $temp[1];
                                    }

                                }

                            }
                        }

                    case 'order':

                        if (
                            isset($option[$key][0]) &&
                            trim($option[$key][0]) !== ''
                        ) {
                            $temp = $option[$key][0];
                            $temp = explode(',', $temp);

                            if (count($temp) === 2) {
                                $order[0] = strtolower($temp[0]);
                                $order[1] = strtolower($temp[1]);
                            }

                        }

                        break;

                    case 'allow':

                        if (
                            isset($option[$key][0]) &&
                            isset($option[$key][1]) &&
                            is_string($option[$key][0]) &&
                            is_string($option[$key][1]) &&
                            strtolower($option[$key][0]) === 'from'
                        ) {
                            $hosts['allow'][] = $option[$key][1];
                        }

                        break;

                    case 'deny':

                        if (
                            isset($option[$key][0]) &&
                            isset($option[$key][1]) &&
                            is_string($option[$key][0]) &&
                            is_string($option[$key][1]) &&
                            strtolower($option[$key][0]) === 'from'
                        ) {
                            $hosts['deny'][] = $option[$key][1];
                        }

                        break;
                }

            }
        }

        // ホストによるアクセス制御

        if (count($hosts['allow']) > 0 || count($hosts['deny']) > 0) {

            $ip = get_ip();

            if ($order[0] === 'allow') {

                if (!$this->_checkHosts($ip, $hosts['allow'])) {

                    // アクセス拒否(403エラー)
                    header(sprintf('HTTP/1.1 403 %s', get_status_code(403)));

                    // テンプレート出力
                    include implode(
                        DS,
                        array(
                            $this->_conf['TEMPLATE_DIR'],
                            $this->_conf['ERROR403']
                        )
                    );

                    return false;
                } else {

                    if ($this->_checkHosts($ip, $hosts['deny'])) {

                        // アクセス拒否(403エラー)
                        header(
                            sprintf(
                                'HTTP/1.1 403 %s', get_status_code(403)
                            )
                        );

                        // テンプレート出力
                        include implode(
                            DS,
                            array(
                                $this->_conf['TEMPLATE_DIR'],
                                $this->_conf['ERROR403']
                            )
                        );

                        return false;
                    }

                }

            } else if ($order[0] === 'deny') {

                if ($this->_checkHosts($ip, $hosts['deny'])) {

                    if (!$this->_checkHosts($ip, $hosts['allow'])) {
                        // アクセス拒否(403エラー)
                        header(
                            sprintf('HTTP/1.1 403 %s', get_status_code(403))
                        );

                        // テンプレート出力
                        include implode(
                            DS,
                            array(
                                $this->_conf['TEMPLATE_DIR'],
                                $this->_conf['ERROR403']
                            )
                        );

                        return false;
                    }

                }

            }

        }

        // BASIC認証
        if ($authType === 'basic') {

            if (
                isset($_SERVER['PHP_AUTH_USER']) &&
                $_SERVER['PHP_AUTH_USER'] !== '' &&
                isset($_SERVER['PHP_AUTH_PW']) &&
                $_SERVER['PHP_AUTH_PW'] !== ''
            ) {
                // 認証処理
                if (array_key_exists($_SERVER['PHP_AUTH_USER'], $users)) {

                    if (startsWith(strtolower(PHP_OS), 'win')) {
                        $salt = $users[$_SERVER['PHP_AUTH_USER']];
                        $salt = substr($salt, 6, 8);
                        $password = $this->_authCryptWin(
                            $_SERVER['PHP_AUTH_PW'], $salt
                        );
                    } else {
                        $password = $this->_authCryptOther(
                            $_SERVER['PHP_AUTH_PW'], $_SERVER['PHP_AUTH_USER']
                        );
                    }

                    if ($users[$_SERVER['PHP_AUTH_USER']] === $password) {
                        return true;
                    }

                }

            }

            header(sprintf('WWW-Authenticate: Basic realm="%s"', $authName));
            header(sprintf('HTTP/1.0 401 %s', get_status_code(401)));

            return false;
        }

        return true;
    }

    // }}}
    // {{{ transfer

    /**
     * ファイルトランスファー
     *
     * @return boolean
     */
    public function transfer()
    {
        // 設定読み込みパラメータ生成
        $param = $this->mix(
            array(
                'path' => $this->_conf['CONFIG_DIR'],
                'filename' => $this->_conf['CONFIG_PREFIX'] .
                              $this->_conf['CONFIG']['FILETRANSFER'],
                'cachepath' => $this->_conf['CACHE_DIR']
            )
        );

        // 設定読み込み
        $conf = xFrameworkPX_Config_FileTransfer::getInstance()->import(
            $param
        );

        // ファイル転送設定判定
        if ($this->_conf['USE_FILE_TRANSFER']) {

            // MIME Typeオブジェクト生成
            $contenttype = $this->mix();

            // MIME Typeオブジェクト設定
            foreach (
                $conf->filetransfer->mimetypes->mimetype as
                $xmlContentType
            ) {
                $contenttype->offsetset(
                    (string)$xmlContentType->ext,
                    (string)$xmlContentType->mime
                );
            }

            // パス情報取得
            $filepath    = normalize_path(
                $this->_conf['WEBROOT_DIR'] .
                DS .
                $this->getContentPath()
            );
            $filename    = $this->getAccessFileName();
            $fileext     = pathinfo(
                $this->getAccessFileName(),
                PATHINFO_EXTENSION
            );

            // バインドファイル判定
            $bind = false;
            $emulation = false;
            foreach ($conf->filetransfer->binds->bind as $xml) {

                $allowExts = array();

                if (isset($xml->exts->ext) !== false) {

                    foreach ($xml->exts->ext as $ext) {
                        $allowExts[] = (string)$ext;
                    }

                }

                $bindpath = normalize_path(
                    $this->_conf['BINDTRANSFER_DIR'] .
                    DS .
                    $this->getContentPath()
                );
                $bindbase = normalize_path(
                    $this->_conf['BINDTRANSFER_DIR'] .
                    DS .
                    (string)$xml->target
                );

                if (
                    startsWith($bindpath, $bindbase) &&
                    file_exists($bindpath . DS . $this->getAccessFileName()) &&
                    in_array($fileext, $allowExts)
                ) {
                    $bind = true;
                    $filepath = $bindpath;
                } else if (
                    startsWith($bindpath, $bindbase)
                ) {
                    if (strtolower((string)$xml->emulation) === 'true') {
                        $bind = true;
                        $filepath = $bindpath;
                        $emulation = true;
                    }
                }
            }

            if ($emulation === true) {
                $bind = true;
            }

            // 転送判定
            if (
                (
                    file_exists(normalize_path($filepath . DS . $filename)) &&
                    $contenttype->offsetExists($fileext)
                ) ||
                (
                    $bind === true
                )
            ) {
                if (
                    empty($fileext) ||
                    !isset($contenttype[$fileext]) ||
                    is_null($contenttype->{$fileext})
                ) {
                } else {

                    if (
                        $bind === true &&
                        !file_exists(normalize_path($filepath . DS . $filename))
                    ) {
                        return true;
                    }

                    // 転送ファイル情報取得
                    $filetype = $contenttype->{$fileext};

                    // If-Modified-Since 対応
                    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {

                        $httpdate = '';
                        $httpdate = preg_replace(
                            '/;.*/',
                            '',
                            $_SERVER['HTTP_IF_MODIFIED_SINCE']
                        );

                        if (!preg_match('/GMT/', $httpdate)) {
                            $httpdate = $httpdate . ' GMT';
                        }

                        if (
                            strtotime($httpdate) >= filemtime($filepath)
                        ) {

                            $etag = md5(
                                $_SERVER['REQUEST_URI'] .
                                @filemtime($filepath)
                            );

                            // IEキャッシュ対策
                            header(
                                'Expires: ' .
                                date(
                                    'D, d M Y H:i:s',
                                    filemtime($filepath)
                                ) .
                                ' GMT' .
                                ' env=VERSIONED_FILE'
                            );
                            header(
                                'Cache-Control: ' .
                                'max-age=315360000 ' .
                                'env=VERSIONED_FILE'
                            );
                            header('HTTP/1.1 304 Not Modified');
                            header("Etag: \"$etag\"");

                            exit(0);
                        }
                    }

                    // 出力
                    $transfile = normalize_path($filepath . DS . $filename);

                    $transfile = substr(
                        $transfile,
                        strlen($this->_conf['WEBROOT_DIR'] . DS)
                    );

                    $transrealfile = normalize_path(
                        $filepath . DS . $filename
                    );

                    mb_http_output('pass');
                    header(sprintf('Content-type: %s', $filetype));

                    header(
                        sprintf(
                            'Content-Disposition: inline; filename=%s' . "\n",
                            $transfile
                        )
                    );

                    header(
                        sprintf(
                            'Content-length: %s',
                            filesize($transrealfile)
                        )
                    );

                    header(
                        sprintf(
                            'Last-Modified: %s GMT',
                            gmdate(
                                'D, d M Y H:i:s',
                                filemtime($transrealfile)
                            )
                        )
                    );

                    readfile($transrealfile);
                    flush();

                    return false;
                }
            }
        }

        if ($emulation === true) {

            return array(
                'emulation' => true
            );
        }

        return true;
    }

    // }}}
    // {{{ _checkHosts

    /**
     * 指定されたホストの存在チェック
     *
     * @param string $host ホストのIPアドレス
     * @param array $hosts ホストリスト
     * @return bool
     */
    private function _checkHosts($ip, $hosts)
    {

        // ローカル変数初期化
        $ret = false;

        if (is_ipv6($ip)) {
            $ip = uncompress_ipv6($ip);
        }

        if (in_array('all', $hosts)) {

            // リストにallが含まれていた場合 true
            $ret = true;
        } else {

            foreach ($hosts as $host) {
                $hostIp = gethostbyname($host);

                if (is_ipv6($hostIp)) {
                    $hostIp = uncompress_ipv6($hostIp);

                    if ($ip === $hostIp) {
                        $ret = true;
                        break;
                    }

                } else {
                    if ($ip === '0:0:0:0:0:0:0:1') {
                        $ip = '127.0.0.1';
                    }

                    if ($ip === $hostIp) {
                        $ret = true;
                        break;
                    }

                }

            }

        }

        return $ret;
    }

    // }}}
    // {{{ _authCrypt

    /**
     * パスワード認証用暗号化メソッド (Win版 Apache用)
     *
     * @param string $plainPassword
     * @param string $salt
     * @return string 暗号化されたパスワード
     */
    private function _authCryptWin($plainpasswd, $salt)
    {

        // {{{ ローカル変数初期化

        $len = strlen($plainpasswd);
        $text = $plainpasswd . '$apr1$' . $salt;
        $bin = pack("H32", md5($plainpasswd . $salt . $plainpasswd));
        $tmp = null;

        // }}}

        for ($i = $len; $i > 0; $i -= 16) {
            $text .= substr($bin, 0, min(16, $i));
        }

        for ($i = $len; $i > 0; $i >>= 1) {
            $text .= ($i & 1) ? chr(0) : $plainpasswd{0};
        }

        $bin = pack("H32", md5($text));

        for ($i = 0; $i < 1000; $i++) {
            $new = ($i & 1) ? $plainpasswd : $bin;

            if ($i % 3) {
                $new .= $salt;
            }

            if ($i % 7) {
                $new .= $plainpasswd;
            }

            $new .= ($i & 1) ? $bin : $plainpasswd;
            $bin = pack("H32", md5($new));
        }

        for ($i = 0; $i < 5; $i++) {
            $k = $i + 6;
            $j = $i + 12;

            if ($j == 16) {
                $j = 5;
            }

            $tmp = $bin[$i] . $bin[$k] . $bin[$j] . $tmp;
        }

        $tmp = chr(0) . chr(0) . $bin[11] . $tmp;
        $tmp = strtr(
            strrev(substr(base64_encode($tmp), 2)),
            'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' .
            '0123456789+/',
            './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'
        );

        return sprintf('$apr1$%s$%s', $salt, $tmp);
    }

    // }}}
    // {{{ _authCryptOther

    /**
     * パスワード認証用暗号化メソッド (Win版以外 Apache用)
     *
     * @param string $plainPassword
     * @param string $salt
     * @return string 暗号化されたパスワード
     */
    private function _authCryptOther($plainPassword, $salt)
    {

        if (is_null($salt) || $salt === '') {
            return $plainPassword;
        }

        $salt = substr($salt, 0, 2);

        return crypt($plainPassword, $salt);
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
