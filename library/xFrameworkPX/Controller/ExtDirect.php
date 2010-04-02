<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Controller_ExtDirect Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Controller
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: ExtDirect.php 1471 2010-01-29 01:26:03Z kotsutsumi $
 */

// {{{ xFrameworkPX_Controller_ExtDirect

/**
 * xFrameworkPX_Controller_ExtDirect Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Controller
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Controller_ExtDirect
 */
class xFrameworkPX_Controller_ExtDirect
extends xFrameworkPX_Controller_Action
{
    // {{{ props

    /**
     * Ext.Direct設定
     *
     * @var array
     */
    public $direct = array();

    /**
     * フォームリクエストフラグ
     *
     * @var boolean
     */
    protected $_isForm = false;

    /**
     * アップロードフラグ
     *
     * @var boolean
     */
    protected $_isUpload = false;

    // }}}
    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @param xFrameworkPX_Util_MixedCollection $conf 設定オブジェクト
     * @return void
     */
    public function __construct($conf) {

        // ワークディレクトリ設定
        if (isset($_GET['wd'])) {
            if (isset($_GET['rp'])) {
                $this->direct['url'] = $_GET['rp'] . 'extdirect.html?wd=' . $_GET['wd'];
            } else {
                $this->direct['url'] = 'extdirect.html?wd=' . $_GET['wd'];
            }
        }

        // スーパークラスメソッドコール
        parent::__construct($conf);
    }

    // }}}
    // {{{ getRpcActions

    /**
     * RPCアクション一覧取得メソッド
     *
     * @return array
     */
    public function getRpcActions()
    {
        // メソッド一覧取得
        $actions = array();
        foreach ($this->modules as $clsName => $modules) {

            $methods = array_diff(
                get_class_methods($modules),
                get_class_methods('xFrameworkPX_Model')
            );

            $actions[$clsName] = array();

            foreach ($methods as $method) {

                $ref = new ReflectionMethod($clsName, $method);

                $count = count($ref->getParameters());
                $methods = array('name' => $method, 'len' => $count);

                if (
                    $ref->isPublic() &&
                    strlen($ref->getDocComment()) > 0
                ) {

                    $doc = $ref->getDocComment();

                    if (!!preg_match('/@formHandler/', $doc)) {
                        $methods['formHandler'] = true;
                    }

                }

                $actions[$clsName][] = $methods;
            }
        }

        return $actions;
    }

    // }}}
    // {{{ getApi

    public function getApi()
    {
        // API生成
        $ret = array(
            'url' => get_relative_url(
                parse_url($this->env('HTTP_REFERER'), PHP_URL_PATH),
                $this->env('REDIRECT_URL')
            ) . $this->direct['url'],
            'type' => $this->direct['type'],
            'actions' => $this->getRpcActions()
        );

        if ($this->direct['namespace'] !== false) {
            $ret['namespace'] = $this->direct['namespace'];
        }

        return $ret;
    }

    // }}}
    // {{{ getResponseData

    public function getResponseData()
    {
        // ローカル変数初期化
        $ret = array();

        // RAW POSTデータ取得
        $rawPostData = file_get_contents('php://input');

        // JSONデコード
        $data = json_decode($rawPostData);

        // リクエスト別処理
        if (!is_null($data)) {

            // RPC実行
            if (is_array($data)) {
                foreach ($data as $values) {
                    $ret[] = $this->rpc($values);
                }
            } else {
                $ret = $this->rpc($data);
            }

        } else if (isset($this->post->extAction)) {

            $temp = new stdClass();

            $this->isForm = true;
            if(
                isset($this->post->extUpload) &&
                $this->post->extUpload === 'true'
            ) {
                $this->isUpload = true;
            }

            $temp->action = $this->post->extAction;
            $temp->method = $this->post->extMethod;
            $temp->tid = isset($this->post->extTID)
                            ? $this->post->extTID
                            : null;
            $temp->data = array($_POST, $_FILES);
            $ret = $this->rpc($temp);

        } else {
            die('Invalid request.');
        }

        return $ret;
    }

    // }}}
    // {{{ rpc

    public function rpc($data)
    {
        $ret = array();

        try {

            $ret['type'] = 'rpc';
            $ret['tid'] = $data->tid;
            $ret['action'] = $data->action;
            $ret['method'] = $data->method;

            $clsName = $ret['action'];
            $method = $ret['method'];

            $arrParams = (isset($data->data) && is_array($data->data))
                         ? $data->data
                         : array();

            $arrParams['Session'] = $this->Session;

            $ret['result'] = call_user_func_array(
                array($this->modules[$clsName], $method),
                $arrParams
            );
            $ret['status'] = true;

        } catch( Exception $e ) {

            $ret['type'] = 'exception';
            $ret['message'] = $e->getMessage();
            $ret['where'] = $e->getTraceAsString();
        }

        return $ret;

    }

    // }}}
    // {{{ execute

    /**
     * コールバックメソッド
     *
     * @return bool サスペンドフラグ
     * @access public
     */
    public function execute()
    {
        $ret = 'Invalid request.';

        // 初期値設定
        if (!isset($this->direct['descriptor'])) {
            $this->direct['descriptor'] = 'Ext.app.REMOTING_API';
        }

        if (!isset($this->direct['type']) ) {
            $this->direct['type'] = 'remoting';
        }

        if (!isset($this->direct['namespace'])) {
            $this->direct['namespace'] = false;
        }

        if (!isset($this->direct['url'])) {
            $this->direct['url'] = 'extdirect.html';
        }

        // メソッド別レスポンス
        if (strtoupper($this->env('REQUEST_METHOD')) === 'POST') {

            // レスポンスデータ取得
            $res = $this->getResponseData();

            // レスポンス文字列生成
            if ($this->isForm && $this->isUpload) {

                $ret = sprintf(
                    '<html><body><textarea>%s</textarea></body></html>',
                    json_encode($res)
                );

            } else {

                // JSONエンコード
                $ret = json_encode($res);
            }
        } else {

            // API取得
            $ret = sprintf(
                'eval(%s = %s);',
                $this->direct['descriptor'],
                json_encode($this->getApi())
            );
        }

        // ヘッダー送信
        header('Content-Type: text/javascript');

        // 結果出力
        print $ret;

        exit();
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
