<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Controller_Component_RapidDrive Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Controller_Component
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: RapidDrive.php 1424 2010-01-20 08:53:32Z kotsutsumi $
 */

// {{{ xFrameworkPX_Controller_Component_RapidDrive

/**
 * xFrameworkPX_Controller_Component_RapidDrive Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Controller_Component
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Controller_Component_RapidDrive
 */
class xFrameworkPX_Controller_Component_RapidDrive
extends xFrameworkPX_Controller_Component
{

    // {{{ props

    /**
     * モード一覧配列
     *
     * @var array
     */
    protected $_mode = array(
        'list',
        'detail',
        'add',
        'edit',
        'addVerify',
        'verify',
        'save',
        'fin'
    );

    /**
     * 入力最大ページ数
     *
     * @var array
     */
    protected $_maxPage = 0;

    /**
     * 入力複数画面接尾辞
     *
     * @var array
     */
    protected $_actionSuffix = '_p';

    /**
     * エラー時遷移先
     *
     * @var array
     */
    protected $_startPage = 'index.html';

    /**
     * 入力値保持セッション名
     *
     * @var string
     */
    protected $_inputSessionName = 'inputData';

    /**
     * リファラー名
     *
     * @var string
     */
    protected $_refeterName = 'px_ref';

    // }}}
    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @param array $_mode
     * @param xFrameworkPX_Util_MixedCollection $conf 設定オブジェクト
     * @return void
     */
    public function __construct($conf)
    {
        // モード確認
        if (!in_array($conf->rapid['mode'], $this->_mode)) {
            throw new xFrameworkPX_Controller_Component_Exception(
                sprintf(PX_ERR48000, $this->_mode)
            );
        }

        // イベント定義
        $this->addEvents($conf->rapid['mode']);

        // イベントリスナー追加
        $this->on(
            $conf->rapid['mode'],
            array($this, 'on' . ucfirst($conf->rapid['mode']))
        );

        // 入力値保持セッション名設定
        if ($this->getContentPath()) {
            $this->_inputSessionName = str_replace(
                '/', '_', $this->getContentPath()
            );
        }

    }

    // }}}
    // {{{ onList

    /**
     * リストモードイベントハンドラ
     *
     * @param array $conf リストモードの動作設定
     * @param xFrameworkPX_Model $module モジュールオブジェクト
     * @return xFrameworkPX_Util_MixedCollection 処理結果
     */
    public function onList($conf, $module)
    {
        $ret            = $this->mix();
        $count          = isset($conf['count']) ? $conf['count'] : null;
        $primaryKey     = $module->primaryKey;
        $pageNumKey     = isset($conf['page_num_key'])
                          ? $conf['page_num_key']
                          : 'p';
        $searchKey      = isset($conf['search_key'])
                          ? $conf['search_key']
                          : 'q';
        $searchFields   = isset($conf['search_field'])
                          ? $conf['search_field']
                          : array('title', 'message');
        $orderFields    = isset($conf['order_field'])
                          ? $conf['order_field']
                          : array($primaryKey => '');
        $fieldFilters   = isset($conf['field_filter'])
                          ? $conf['field_filter']
                          : array();
        $noItemMessage  = isset($conf['no_item_message'])
                          ? $conf['no_item_message']
                          : '';
        $nextAction     = isset($conf['next_action'])
                          ? $conf['next_action']
                          : 'edit';
        $pageNum        = isset($this->get[$pageNumKey])
                          ? $this->get[$pageNumKey]
                          : 0;
        $search         = isset($this->get[$searchKey])
                          ? $this->get[$searchKey]
                          : '';

        // スキーマ取得
        $schemas = $module->schema($fieldFilters);

        $temp = parse_url($this->env('REQUEST_URI'));
        $query = '';
        if (isset($temp['query'])) {
            $query = $temp['query'];
        }

        // リスト取得
        $ret->list = $this->mix();
        $ret->list->import(
            $module->findAll(
                $count,
                $pageNum,
                $search,
                $searchFields,
                $orderFields
            )
        );

        // ページャー情報取得
        if ($ret->list->first()) {
            $ret->pager = $module->pager(
                intval($ret->list->first()->count),
                $pageNum,
                $search
            );
        }

        // 出力整形
        $ret->outlist = array();
        $ret->outheader = array();

        if ($ret->list->count() !== 0) {

            // 検索結果セット
            foreach ($ret->list as $key => $item) {
                $list = array();
                $headers = array();

                foreach ($schemas as $field) {
                    $list[$field['Field']] = $item[$field['Field']];
                    $headers[$field['Field']] = $field['Comment'];
                }

                $ret->outlist[$ret->list[$key][$primaryKey]] = $list;
                $ret->outheader[] = $headers;
            }

            $ret->outheader = $ret->outheader[0];
        } else {

            // 検索結果なしのメッセージ設定
            $ret->listnomessage = $noItemMessage;
        }

        // 検索条件セット
        $ret->{$searchKey} = $search;

        // IDのキー名セット
        $ret->idkey = $primaryKey;

        // 遷移先アクションをセット
        $ret->nextaction = $nextAction;

        // refererModeをセッションに登録
        $this->Session->write('refererMode', 'list');

        // リファラー名を設定
        $ret->refererName = $this->_refeterName;

        // リファラーを設定
        $ret->{$this->_refeterName} = $this->env('REQUEST_URI');

        // クエリー設定
        $ret->{'query'} = $query;

        return $ret;
    }

    // }}}
    // {{{ onDetail

    /**
     * 詳細モードイベントハンドラ
     *
     * @param array $conf ディテールモードの動作設定
     * @param xFrameworkPX_Model $module モジュールオブジェクト
     * @return xFrameworkPX_Util_MixedCollection 処理結果
     */
    public function onDetail($conf, $module)
    {
        // ローカル変数初期化
        $ret = $this->mix();
        $idKey = $module->primaryKey;
        $pageNumKey     = isset($conf['page_num_key'])
                          ? $conf['page_num_key']
                          : 'p';
        $searchKey      = isset($conf['search_key'])
                          ? $conf['search_key']
                          : 'q';
        $fieldFilters = isset($conf['field_filter'])
                        ? $conf['field_filter']
                        : array($idKey);
        $id = null;
        if (isset($this->get->{$idKey})) {
            $id = $this->get->{$idKey};
        } else if (isset($this->post->{$idKey})) {
            $id = $this->post->{$idKey};
        }

        $data = null;

        // エラー時遷移先画面名
        $errorRedirect = 'index';

        if (isset($conf['errorRedirect'])) {
            $errorRedirect = $conf['errorRedirect'];
        }

        // データ取得
        if (isset($id)) {
            $data = $module->find($id, $idKey);
        }

        if (empty($data)) {

            // データが空の場合indexにリダイレクト
            $this->redirect(sprintf('%s.html', $errorRedirect));
        }

        // スキーマ取得
        $schemas = $module->schema($fieldFilters);

        // 出力整形
        $ret->label = array();
        $ret->outdata = array();

        foreach ($schemas as $field) {
            $key = $field['Field'];
            $ret->label[$key] = (!empty($field['Comment']))
                                        ? $field['Comment']
                                        : $key;

            $ret->outdata[$key] = $data[$key];
        }

        // refererModeをセッションに登録
        $this->Session->write('refererMode', 'detail');

        // リファラー名を設定
        $ret->refererName = $this->_refeterName;

        // リファラーを設定
        $ret->{$this->_refeterName} = $this->env('REQUEST_URI');


        $temp = parse_url($this->env('REQUEST_URI'));
        $query = '';
        if(isset($temp['query'])) {
            $query = '?';
            $temp = explode('&', $temp['query']);
            foreach ($temp as $i => $value) {
                $q = explode('=', $value);
                $key = '';
                if (isset($q[0])) {
                    $key = $q[0];
                }
                if (isset($q[1])) {
                    $value = $q[1];
                }

                if ($pageNumKey === $key) {
                    if ($i > 0) {
                        $query .= '&';
                    }
                    $query .= $pageNumKey . '=' . $value;
                } else if($searchKey === $key) {
                    if ($i > 0) {
                        $query .= '&';
                    }
                    $query .= $searchKey . '=' . $value;
                }
            }
        }

        $ret->{'listquery'} = $query;

        return $ret;
    }

    // }}}
    // {{{ onEdit

    /**
     * エディットモードイベントハンドラ
     *
     * @param array $conf エディットモードの動作設定
     * @param xFrameworkPX_Model $module モジュールオブジェクト
     * @return xFrameworkPX_Util_MixedCollection 処理結果
     */
    public function onEdit($conf, $module)
    {
        // ローカル変数初期化
        $ret = $this->mix();
        $idKey = $module->primaryKey;
        $fieldFilters = isset($conf['field_filter'])
                        ? $conf['field_filter']
                        : array($idKey, 'created', 'modified');

        $id = null;
        if (isset($this->get->{$idKey})) {
            $id = $this->get->{$idKey};
        } else if (isset($this->post->{$idKey})) {
            $id = $this->post->{$idKey};
        }

        // エラー時遷移先画面名
        $errorRedirect = 'index';

        if (isset($conf['errorRedirect'])) {
            $errorRedirect = $conf['errorRedirect'];
        }

        $nextAction = isset($conf['next_action'])
                        ? $conf['next_action']
                        : 'verify';

        $data = array();
        $inputData = null;
        $validErrors = null;

        $refererMode = $this->Session->read('refererMode');

        $requestURIPath = parse_url(
            $this->env('REQUEST_URI'),
            PHP_URL_PATH
        );
        $httpRefererPath = parse_url(
            $this->getReferer(),
            PHP_URL_PATH
        );

        if (
            !preg_match('/'.preg_quote($nextAction.'.html', '/').'$/', $httpRefererPath) &&
            $requestURIPath != $httpRefererPath
        ) {
            $this->Session->remove('refererMode');
            $refererMode = null;
        }

        if ($requestURIPath !== '') {
            $requestURIPath = pathinfo($requestURIPath, PATHINFO_DIRNAME);
        }

        if ($httpRefererPath !== '') {
            $httpRefererPath = pathinfo($httpRefererPath, PATHINFO_DIRNAME);
        }

        // 遷移外からのアクセス時に初期化
        if (
            is_null($this->getReferer()) ||
            $requestURIPath !== $httpRefererPath
        ) {
            $this->Session->remove('refererMode');
            $refererMode = null;
        }

        if (
            is_null($this->getReferer()) ||
            $requestURIPath !== $httpRefererPath ||
            ($refererMode !== 'edit' && $refererMode !== 'verify')
        ) {
            // セッション破棄
            $this->Session->remove($this->_inputSessionName);
            $this->Session->remove('validErrors');
            $this->Session->remove('refererMode');
        }

        // 入力値取得
        $actionName = $this->getActionName();

        $inputData = $this->Session->read($this->_inputSessionName);

        $inputData = !isset($inputData[$actionName])
                     ? null
                     : $inputData[$actionName][$module->toString()];

        // 入力エラー内容取得
        $validErrors = $this->Session->read('validErrors');
        if (is_null($validErrors)) {
            $validErrors = $this->mix();
        }

        // 保存チケット登録
        $this->Session->write($module->toString() . '.save', true);

        // スキーマ取得
        $schemas = $module->schema($fieldFilters);

        // データ取得
        if (is_null($inputData)) {
            $data = $module->find($id, $idKey);
        } else {
            $data[$idKey] = $inputData[$idKey];

            foreach ($schemas as $field) {
                $data[$field['Field']] = null;
            }

            foreach ($inputData as $key => $value) {
                if ($value instanceof xFrameworkPX_Util_MixedCollection) {
                    $data[$key] = $value->getArrayCopy();
                } else {
                    $data[$key] = $value;
                }
            }

        }

        if (empty($data)) {

            // データが空の場合indexにリダイレクト
            $this->redirect(sprintf('%s.html', $errorRedirect));
        }

        // 出力整形
        $ret->label = array();
        $ret->type = array();
        $ret->outdata = array();

        $ret->type = $module->getInputType($schemas);
        $ret->type[$idKey] = array('type' => 'hidden');
        $ret->outdata[$idKey] = $data[$idKey];
        $ret->moduleName = $conf['module'];

        foreach ($schemas as $field) {
            $key = $field['Field'];

            // ラベル生成
            $ret->label[$key] = (!empty($field['Comment']))
                                        ? $field['Comment']
                                        : $key;

            if (startsWith($ret->type[$key]['type'], 'select')) {

                // 日付データ整形
                if (is_string($data[$key])) {
                    $data[$key] = $module->dateParse($data[$key]);
                }

                $ret->outdata[$key] = $data[$key];
                // セレクトボックス要素生成
                $ret->type[$key]['item'] =
                    $module->getDateSelectItem($ret->outdata[$key]);

            } else {
                $ret->outdata[$key] = $data[$key];
            }
        }

        // 入力エラー設定
        $ret->validerror = $validErrors;

        // 遷移先アクションの設定
        $ret->nextaction = $nextAction;

        // セッションに登録された入力データの破棄
//        $this->Session->remove($this->_inputSessionName);

        // refererModeをセッションに登録
        $this->Session->write('refererMode', 'edit');

        // リファラー名を設定
        $ret->refererName = $this->_refeterName;

        // リファラーを設定
        $ret->{$this->_refeterName} = $this->env('REQUEST_URI');

        return $ret;

    }

    // }}}
    // {{{ onAdd

    /**
     * アッドモードイベントハンドラ
     *
     * @param array $conf アッドモードの動作設定
     * @param xFrameworkPX_Model $module モジュールオブジェクト
     * @return xFrameworkPX_Util_MixedCollection 処理結果
     */
    public function onAdd($conf, $module)
    {

        // ローカル変数初期化
        $ret = $this->mix();
        $idKey = $module->primaryKey;
        $fieldFilters = isset($conf['field_filter'])
                        ? $conf['field_filter']
                        : array($idKey, 'created', 'modified');
        $nextAction = isset($conf['next_action'])
                        ? $conf['next_action']
                        : 'verify';

        // エラー時遷移先画面名
        $errorRedirect = 'index';

        if (isset($conf['errorRedirect'])) {
            $errorRedirect = $conf['errorRedirect'];
        }

        $validErrors = null;

        $refererMode = $this->Session->read('refererMode');

        $requestURIPath = parse_url($this->env('REQUEST_URI'), PHP_URL_PATH);
        $httpRefererPath = parse_url($this->getReferer(), PHP_URL_PATH);

        if (
            !preg_match('/'.preg_quote($nextAction.'.html', '/').'$/', $httpRefererPath) &&
            $requestURIPath != $httpRefererPath
        ) {
            $this->Session->remove('refererMode');
            $refererMode = null;
        }

        if ($requestURIPath !== '') {
            $requestURIPath = pathinfo(
                $requestURIPath, PATHINFO_DIRNAME
            );
        }

        if ($httpRefererPath !== '') {
            $httpRefererPath = pathinfo(
                $httpRefererPath, PATHINFO_DIRNAME
            );
        }

        // 遷移外からのアクセス時に初期化
        if (
            is_null($this->getReferer()) ||
            $requestURIPath !== $httpRefererPath
        ) {
            $this->Session->remove('refererMode');
            $refererMode = null;
        }

        if ($refererMode != 'addVerify') {
            if (
                is_null($this->getReferer()) ||
                $requestURIPath !== $httpRefererPath ||
                ($refererMode !== 'add' && $refererMode !== 'verify')
            ) {

                // セッション破棄
                $this->Session->remove($this->_inputSessionName);
                $this->Session->remove('validErrors');
                $this->Session->remove('refererMode');
            }

        }

        // 入力値取得
        $inputData = $this->Session->read($this->_inputSessionName);

        if (isset($inputData[$this->getActionName()])) {
            $inputData = $inputData[$this->getActionName()][$module->toString()];
        } else if (isset($inputData[$module->toString()])) {
            $inputData = $inputData[$module->toString()];
        }

        // 入力エラー内容取得
        $validErrors = $this->Session->read('validErrors');

        if (is_null($validErrors)) {
            $validErrors = $this->mix();
        }

        // 保存チケット登録
        $this->Session->write($module->toString() . '.save', true);

        // スキーマ取得
        $schemas = $module->schema($fieldFilters);

        // 出力整形
        $ret->label = array();
        $ret->type = array();
        $ret->outdata = array();

        $ret->type = $module->getInputType($schemas);

        // モジュール名設定
        $ret->moduleName = $conf['module'];

        foreach ($schemas as $field) {
            $key = $field['Field'];
            $ret->label[$key] = (!empty( $field['Comment']))
                                        ? $field['Comment']
                                        : $key;
            if (startsWith($ret->type[$key]['type'], 'select')) {
                $date = array(
                    'year' => date('Y'),
                    'month' => date('m'),
                    'day' => date('d'),
                    'hour' => date('G'),
                    'minute' => date('i')
                );

                $ret->type[$key]['item'] =
                    $module->getDateSelectItem(
                        $date,
                        $date['year'] - 20
                    );

                $ret->outdata[$key] = $date;
            } else {
                $ret->outdata[$key] = '';
            }

        }

        // 入力値の設定
        if (!is_null($inputData)) {
            foreach ($inputData as $key => $value) {
                if (array_key_exists($key, $ret->outdata)) {
                    $ret->outdata[$key] = (
                         $value instanceof xFrameworkPX_Util_MixedCollection)
                        ? $value->getArrayCopy()
                        : $value;
                }
            }
        }

        // 入力エラー設定
        $ret->validerror = $validErrors;

        // 遷移先アクションの設定
        $ret->nextaction = $nextAction;

        // refererModeをセッションに登録
        $this->Session->write('refererMode', 'add');

        // リファラー名を設定
        $ret->refererName = $this->_refeterName;

        // リファラーを設定
        $ret->{$this->_refeterName} = $this->env('REQUEST_URI');

        return $ret;

    }

    // }}}
    // {{{ onVerify

    /**
     * ベリファイモードイベントハンドラ
     *
     * @param array $conf ベリファイモードの動作設定
     * @param xFrameworkPX_Model $module モジュールオブジェクト
     * @return xFrameworkPX_Util_MixedCollection 処理結果
     */
    public function onVerify($conf, $module)
    {
        // ローカル変数初期化
        $ret = $this->mix();
        $fieldFilters = isset($conf['field_filter'])
                        ? $conf['field_filter']
                        : array(
                            $module->primaryKey,
                            'created',
                            'modified'
                        );
        $moduleName = $module->toString();
        $nextAction = isset( $conf['next_action'])
                        ? $conf['next_action']
                        : 'save';

        // エラー時遷移先画面名
        $errorRedirect = 'index';

        if (isset($conf['errorRedirect'])) {
            $errorRedirect = $conf['errorRedirect'];
        }

        $primaryKey = $module->primaryKey;

        $refererActionName = $this->rapidRefererAction();

        $refererMode = $this->Session->read('refererMode');

        $this->Session->remove($this->_refeterName);

        $requestURIPath = parse_url(
            $this->env('REQUEST_URI'),
            PHP_URL_PATH
        );
        $httpRefererPath = parse_url(
            $this->getReferer(),
            PHP_URL_PATH
        );

        if ($requestURIPath !== '') {
            $requestURIPath = pathinfo($requestURIPath, PATHINFO_DIRNAME);
        }

        if ($httpRefererPath !== '') {
            $httpRefererPath = pathinfo($httpRefererPath, PATHINFO_DIRNAME);
        }

        $temp = $this->mix();

        // 遷移外からのアクセス時にリダイレクト
        if (
            is_null($this->getReferer()) ||
            $requestURIPath !== $httpRefererPath
        ) {
            $this->Session->remove($module->toString() . '.save');
            $this->Session->remove('refererMode');
            $refererMode = null;
        }

        if (
            is_null($this->getReferer()) ||
            (
                $refererMode !== 'add' && 
                $refererMode !== 'edit' && 
                $refererMode !== 'verify' && 
                $refererMode !== 'addVerify'
            ) ||
            $this->Session->read($module->toString() . '.save') !== true
        ) {
            // セッション破棄
            $this->Session->remove($this->_inputSessionName);
            $this->Session->remove('validErrors');
            $this->Session->remove('refererMode');

            // indexにリダイレクト
            $this->redirect(sprintf('%s.html', $errorRedirect));
        }

        // インプットタイプ取得
        $type = $module->getInputType($module->schema());

        // 入力データをセッションに保存
        $inData = $this->Session->read($this->_inputSessionName);

        // 入力データ取得
        $inData[$refererActionName] = $this->post;

        // 入力データをセッションに保存
        $this->Session->write($this->_inputSessionName, $inData);

        // 入力データ整形
        $temp[$moduleName] = $this->mix();

        foreach ($type as $key => $columns) {
            if (startsWith($columns['type'], 'select')) {
                if (
                    isset($inData[$refererActionName][$moduleName][$key])
                ) {
                    $temp[$moduleName][$key] = $module->getDateString(
                        $inData[$refererActionName][$moduleName][$key]
                    );
                }
            } else {
                if (isset($inData[$refererActionName][$moduleName][$key])) {

                    $temp[$moduleName][$key]
                        = $inData[$refererActionName][$moduleName][$key];
                }
            }
        }
        // テーブル名以外のバリデーション
        foreach ($inData[$refererActionName][$moduleName] as $key => $val) {
            if (!array_key_exists($key, $type)) {
                $temp[$moduleName][$key]
                    = $inData[$refererActionName][$moduleName][$key];
            }
        }
        // 入力チェック
        $validErrors = $module->validation($temp);

        // エラー処理
        if ($validErrors->count() > 0) {

            // エラー内容をセッションに保存
            $this->Session->write('validErrors', $validErrors);

            // 入力画面にリダイレクト
            if ($refererMode == 'edit') {

                // リファラー情報をセッションにセット
                $this->Session->write($this->_refeterName, $this->getReferer());

                if (
                    isset($conf['queryMethod']) &&
                    $conf['queryMethod'] == 'hidden'
                ) {

                    $this->redirect(sprintf('%s.html', $refererActionName));

                } else {

                    $this->redirect(
                        sprintf(
                            '%s.html?%s=%s',
                            $refererActionName,
                            $primaryKey,
                            $inData[
                                $refererActionName
                            ]->{$module->toString()}->$primaryKey
                        )
                    );
                }

            } else {

                // リファラー情報をセッションにセット
                $this->Session->write($this->_refeterName, $this->getReferer());

                $this->redirect(sprintf('%s.html', $refererActionName));
            }
        } else {

            // エラー内容をセッションから削除
            $this->Session->remove('validErrors');

            // スキーマ取得
            $schemas = $module->schema($fieldFilters);

            // 出力整形
            $ret->label = array();
            $ret->outdata = array();
            $ret->refereraction = $refererActionName;

            foreach ($schemas as $field) {
                $key = $field['Field'];
                $ret->label[$key] = !empty($field['Comment'])
                                            ? $field['Comment']
                                            : $key;
            }

            foreach ($inData as $datas) {
                if (
                    isset($datas[$moduleName]) &&
                    ($datas[$moduleName] instanceof 
                        xFrameworkPX_Util_MixedCollection)
                ) {
                    $ret->outdata = array_merge(
                        $ret->outdata,
                        $datas[$moduleName]->getArrayCopy()
                    );
                }
            }

            // 遷移先アクションの設定
            $ret->nextaction = $nextAction;

            // refererModeをセッションに登録
            $this->Session->write('refererMode', 'verify');

            // リファラー名を設定
            $ret->refererName = $this->_refeterName;

            // リファラーを設定
            $ret->{$this->_refeterName} = $this->env('REQUEST_URI');

            return $ret;
        }
    }

    // }}}
    // {{{ onAddVerify

    /**
     * アドベリファイ
     *
     * @param array $conf アッドモードの動作設定
     * @param xFrameworkPX_Model $module モジュールオブジェクト
     * @return xFrameworkPX_Util_MixedCollection 処理結果
     */
    public function onAddVerify($conf, $module)
    {

        // ローカル変数初期化
        $ret = $this->mix();
        $fieldFilters = isset($conf['field_filter'])
                        ? $conf['field_filter']
                        : array($module->primaryKey, 'created','modified');

        // デフォルト次画面取得
        $actionName = $this->getActionName();
        $defaultNextAction = '';
        preg_match('/_p[0-9]+$/', $actionName, $matches);
        if (isset($matches[0])) {
            $num = preg_replace('/_p/', '', $matches[0]);
            $baseName = preg_replace('/_p[0-9]+$/', '', $actionName);
            $defaultNextAction = '_p'.($num + 1);
            $defaultNextAction = $baseName . $defaultNextAction;
        } else {
            $defaultNextAction = 'verify';
        }

        $moduleName = $module->toString();

        // エラー時遷移先画面名
        $errorRedirect = 'index';

        if (isset($conf['errorRedirect'])) {
            $errorRedirect = $conf['errorRedirect'];
        }

        $nextAction = isset( $conf['next_action'])
                        ? $conf['next_action'] : $defaultNextAction;

        $primaryKey = $module->primaryKey;

        $refererActionName = $this->rapidRefererAction();

        $refererMode = $this->Session->read('refererMode');

        $requestURIPath = parse_url($this->env('REQUEST_URI'), PHP_URL_PATH);

        $httpRefererPath = parse_url($this->getReferer(), PHP_URL_PATH);

        if ($requestURIPath !== '') {
            $requestURIPathInfo = pathinfo($requestURIPath, PATHINFO_DIRNAME);
        }

        if ($httpRefererPath !== '') {
            $httpRefererPathInfo = pathinfo($httpRefererPath, PATHINFO_DIRNAME);
        }

        $temp = $this->mix();

        // 遷移外からのアクセス時にリダイレクト
        if (
            is_null($this->getReferer()) ||
            $requestURIPathInfo !== $httpRefererPathInfo
        ) {
            $this->Session->remove($module->toString() . '.save');
            $this->Session->remove('refererMode');
            $refererMode = null;
        }

        if (
            is_null($this->getReferer()) ||
            (
                $refererMode !== 'add' && 
                $refererMode !== 'edit' &&
                $refererMode !== 'verify' &&
                $refererMode !== 'addVerify'
            ) ||
            $this->Session->read($module->toString() . '.save') !== true
        ) {
            // セッション破棄
            $this->Session->remove($this->_inputSessionName);
            $this->Session->remove('validErrors');
            $this->Session->remove('refererMode');

            // indexにリダイレクト
            $this->redirect(sprintf('%s.html', $errorRedirect));
        }

        // エラーチェック
        if (
            $requestURIPath !== $httpRefererPath &&
            $refererActionName !== $nextAction
        ) {

            // インプットタイプ取得
            $type = $module->getInputType($module->schema());

            // 入力データ取得
            $inData = $this->Session->read($this->_inputSessionName);
            $inData[$refererActionName] = $this->post;

            // 入力データをセッションに保存
            $this->Session->write($this->_inputSessionName, $inData);

            // 入力データ整形
            $temp[$moduleName] = $this->mix();

            foreach ($type as $key => $columns) {
                if (startsWith($columns['type'], 'select')) {
                    if (
                        isset($inData[$refererActionName][$moduleName][$key])
                    ) {
                        $temp[$moduleName][$key] = $module->getDateString(
                            $inData[$refererActionName][$moduleName][$key]
                        );
                    }
                } else {
                    if (isset($inData[$refererActionName][$moduleName][$key])) {

                        $temp[$moduleName][$key]
                            = $inData[$refererActionName][$moduleName][$key];
                    }
                }
            }

            // テーブル名以外のバリデーション
            foreach ($inData[$refererActionName][$moduleName] as $key => $val) {
                if (!array_key_exists($key, $type)) {
                    $temp[$moduleName][$key]
                        = $inData[$refererActionName][$moduleName][$key];
                }
            }

            // 入力チェック
            $validErrors = $module->validation($temp);

            // エラー処理
            if ($validErrors->count() > 0) {

                // エラー内容をセッションに保存
                $this->Session->write('validErrors', $validErrors);

                // 入力画面にリダイレクト
                if ($refererMode == 'edit') {

                    // リファラー情報をセッションにセット
                    $this->Session->write($this->_refeterName, $this->getReferer());

                    $this->redirect(
                        sprintf(
                            '%s.html?%s=%s',
                            $refererActionName,
                            $primaryKey,
                            $inData->{$module->toString()}->$primaryKey
                        )
                    );
                } else {
                    // リファラー情報をセッションにセット
                    $this->Session->write($this->_refeterName, $this->getReferer());

                    $this->redirect(sprintf('%s.html', $refererActionName));
                }

            } else {

                // エラー内容をセッションから削除
                $this->Session->remove('validErrors');

            }

        }

        // 入力値取得
        $inputData = $this->Session->read($this->_inputSessionName);
        $inputData = !isset($inputData[$actionName])
                        ? null
                        : $inputData[$actionName][$module->toString()];

        // 入力エラー内容取得
        $validErrors = $this->Session->read('validErrors');
        if (is_null($validErrors)) {
            $validErrors = $this->mix();
        }

        // 保存チケット登録
        $this->Session->write($module->toString() . '.save', true);

        // スキーマ取得
        $schemas = $module->schema($fieldFilters);

        // 出力整形
        $ret->label = array();
        $ret->type = array();
        $ret->outdata = array();

        $ret->type = $module->getInputType($schemas);

        // モジュール名設定
        $ret->moduleName = $conf['module'];

        foreach ($schemas as $field) {
            $key = $field['Field'];
            $ret->label[$key] = (!empty( $field['Comment']))
                                        ? $field['Comment']
                                        : $key;
            if (startsWith($ret->type[$key]['type'], 'select')) {
                $date = array(
                    'year' => date('Y'),
                    'month' => date('m'),
                    'day' => date('d'),
                    'hour' => date('G'),
                    'minute' => date('i')
                );

                $ret->type[$key]['item'] =
                    $module->getDateSelectItem(
                        $date,
                        $date['year'] - 20
                    );

                $ret->outdata[$key] = $date;
            } else {
                $ret->outdata[$key] = '';
            }

        }

        // 入力値の設定
        if (!is_null($inputData)) {
            foreach ($inputData as $key => $value) {
                if (array_key_exists($key, $ret->outdata)) {
                    $ret->outdata[$key] = (
                         $value instanceof xFrameworkPX_Util_MixedCollection)
                        ? $value->getArrayCopy()
                        : $value;
                }
            }
        }

        // 入力エラー設定
        $ret->validerror = $validErrors;

        // 遷移先アクションの設定
        $ret->nextaction = $nextAction;

        // refererModeをセッションに登録
        $this->Session->write('refererMode', 'addVerify');

        // リファラー名を設定
        $ret->refererName = $this->_refeterName;

        // リファラーを設定
        $ret->{$this->_refeterName} = $this->env('REQUEST_URI');

        return $ret;

    }

    // }}}
    // {{{ onSave

    /**
     * セーブモードイベントハンドラ
     *
     * @param array $conf セーブモードの動作設定
     * @param xFrameworkPX_Model $module モジュールオブジェクト
     * @return xFrameworkPX_Util_MixedCollection 処理結果
     */
    public function onSave($conf, $module)
    {
        // ローカル変数初期化
        $ret = $this->mix();

        $nextAction = isset($conf['next_action'])
                        ? $conf['next_action']
                        : 'fin';

        $idKey = $module->primaryKey;
        $moduleName = $module->toString();
        $id = null;
        $inputData = null;
        $validErrors = null;
        $type = null;
        $addData = null;
        $editMode = false;
        // エラー時遷移先画面名
        $errorRedirect = 'index';

        if (isset($conf['errorRedirect'])) {
            $errorRedirect = $conf['errorRedirect'];
        }

        $refererMode = $this->Session->read('refererMode');
        $refererActionName = $this->rapidRefererAction();

        $requestURIPath = parse_url(
            $this->env('REQUEST_URI'),
            PHP_URL_PATH
        );
        $httpRefererPath = parse_url(
            $this->getReferer(),
            PHP_URL_PATH
        );

        if ($requestURIPath !== '') {
            $requestURIPath = pathinfo($requestURIPath, PATHINFO_DIRNAME);
        }

        if ($httpRefererPath !== '') {
            $httpRefererPath = pathinfo($httpRefererPath, PATHINFO_DIRNAME);
        }
        $temp = $this->mix();

        // 遷移外からのアクセス時にリダイレクト
        if (
            is_null($this->getReferer()) ||
            $requestURIPath !== $httpRefererPath
        ) {
            $this->Session->remove($module->toString() . '.save');
        }

        if (
            is_null($this->getReferer()) ||
            $this->Session->read($moduleName . '.save') !== true
        ) {
            // セッション破棄
            $this->Session->remove($this->_inputSessionName);
            $this->Session->remove('validErrors');
            $this->Session->remove('refererMode');

            // indexにリダイレクト
            $this->redirect(sprintf('%s.html', $errorRedirect));
        }

        // 入力データ取得
        $inputTemp = $this->Session->read($this->_inputSessionName);
        if (is_null($inputTemp)) {
            $inputTemp = $this->post;
        }

        if (!is_array($inputTemp) ) {
            // indexにリダイレクト
            $this->redirect(sprintf('%s.html', $errorRedirect));
        }

        $inputData = array();
        $inputData[$moduleName] = array();

        foreach ($inputTemp as $datas ) {

            if (isset($datas[$moduleName])) {
                $inputData[$moduleName] = array_merge(
                    $inputData[$moduleName],
                    $datas[$moduleName]->getArrayCopy()
                );
            }
        }

        // インプットタイプ取得
        $type = $module->getInputType($module->schema());

        // 登録データ生成
        $temp[$moduleName] = $this->mix();
        foreach ($type as $key => $columns) {

            if (startsWith($columns['type'], 'select')) {
                if (isset($inputData[$moduleName][$key])) {
                    $temp[$moduleName][$key] =
                        $module->getDateString($inputData[$moduleName][$key]);
                }
            } else {
                if (isset($inputData[$moduleName][$key])) {
                    $temp[$moduleName][$key] = $inputData[$moduleName][$key];
                }
            }

        }

        $addData = $temp[$moduleName]->getArrayCopy();

        // 入力チェック
        if ( $refererMode !== 'verify' ) {

            // 入力データをセッションに保存
            $this->Session->write($this->_inputSessionName, $inputData);

            $validErrors = $module->validation($temp);

            // エラー処理
            if ($validErrors->count() > 0) {

                // エラー内容をセッションに保存
                $this->Session->write('validErrors', $validErrors);

                // 入力画面にリダイレクト
                if ( $refererMode == 'edit' ) {
                    $this->redirect(
                        sprintf(
                            '%s.html?%s=%s',
                            $refererActionName,
                            $idKey,
                            $addData[ $idKey ]
                        )
                    );
                } else {
                    $this->redirect(
                        sprintf('%s.html', $refererActionName)
                    );
                }
            } else {
                $this->Session->remove('validErrors');
            }
        }

        // 編集モード判定
        if (isset($addData[$idKey])) {
            $id = $addData[$idKey];
            $data = $module->find($id, $idKey);
            $editMode = isset($data[$idKey]);
        }

        // データ登録/更新
        if ($editMode) {
            // レコード更新
            $module->edit($addData, $idKey);
        } else {
            // レコード追加
            $module->add($addData);
        }

        // 入力データの破棄
        $this->Session->remove($this->_inputSessionName);

        // 保存チケットの破棄
        $this->Session->remove($moduleName . '.save');

        // 指定した遷移先にリダイレクト
        $this->redirect(sprintf('%s.html', $nextAction));
    }

    // }}}
    // {{{ onFin

    /**
     * 終了モードイベントハンドラ
     *
     * @param array $conf フィンモードの動作設定
     * @param xFrameworkPX_Model $module モジュールオブジェクト
     * @return xFrameworkPX_Util_MixedCollection 処理結果
     */
    public function onFin($conf, $module)
    {
        return $this->mix();
    }

    // }}}
    // {{{ getReferer

    /**
     * リファラー取得メソッド
     *
     * @param $request 強制的に送信パラメータから取得するフラグ（default=false）
     * @return string リファラー
     */
    public function getReferer($request = false)
    {

        $ret = null;
        if ($this->env('HTTP_REFERER') && $request !== true) {

            // リファラーがあれば取得
            $ret = $this->env('HTTP_REFERER');
        } else {

            // 無い場合は各パラメータから取得
            if (isset($this->post[$this->_refeterName])) {
                // POST
                $ret = $this->post[$this->_refeterName];
            } else if (isset($this->get[$this->_refeterName])) {
                // GET
                $ret = $this->get[$this->_refeterName];
            } else if ($this->Session->read($this->_refeterName)) {
                // セッション
                $ret = $this->Session->read($this->_refeterName);
            }

        }

        if (!preg_match('/\.html/', $ret)) {
            $temps = explode('?', $ret);
            $ret = $temps[0].'index.html';
            if (isset($temps[1])) {
                $ret .= '?'.$temps[1];
            }
        }

        return $ret;

    }

    // }}}
    // {{{ rapidRefererAction

    /**
     * リファラーによるファイル名取得メソッド
     *
     * @param string $default デフォルトファイル名
     * @return ファイル名
     */
    public function rapidRefererAction($default = 'index.html')
    {

        // HTTP_REFERERがない場合はnullを返す
        if (is_null($this->getReferer())) {
            return null;
        }

        // HTTP_REFERERからファイルパス取得
        $referer = $this->getReferer();

        if (pathinfo($referer, PATHINFO_EXTENSION) === '') {
            $referer .= $default;
        }

        return get_filename($referer);

        // }}}

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
