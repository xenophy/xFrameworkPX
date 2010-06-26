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

    /**
     * 実行コマンド
     *
     * @var string
     */
    public $cmd = null;

    /**
     * セッション名
     *
     * @var string
     */
    public $sessName = null;

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

        // セッション名設定
        $this->sessName = sprintf(
            'rd%s',
            str_replace('/', '_', $this->getContentPath())
        );
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
    /*
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
    */

    public function onList($conf, $module)
    {

        $ret            = $this->mix();
        $list           = null;
        $pager          = null;

        // 1ページの表示件数
        $count          = isset($conf['count']) ? $conf['count'] : null;

        // 表示するページ番号のフィールド名
        $pageNumKey     = isset($conf['page_num_key'])
                          ? $conf['page_num_key']
                          : 'p';

        // 一覧を絞り込む条件のフィールド名
        $searchKey      = isset($conf['search_key'])
                        ? $conf['search_key']
                        : 'q';

        // ソートの対象となるフィールド名とソートの設定
        $orderFields    = isset($conf['order_field'])
                          ? $conf['order_field']
                          : array($module->primaryKey);

        // 非表示にするフィールド名
        $fieldFilters   = isset($conf['field_filter'])
                          ? $conf['field_filter']
                          : array();

        // 検索結果がなかった場合に表示するメッセージ
        $noItemMessage  = isset($conf['no_item_message'])
                          ? $conf['no_item_message']
                          : '';

        $search         = isset($conf['search'])
                        ? $conf['search']
                        : array();

        // 初回実行時に検索を行うかどうかのフラグ
        $init_search    = (isset($conf['init_search']) && $conf['init_search'] !== '')
                          ? (bool)$conf['init_search']
                          : false;

        // 次画面アクション名
        $nextAction = (isset($conf['nextAction']))
                    ? $conf['nextAction']
                    : 'detail';

        // セッション取得
        $sessTemp = $this->Session->read($this->sessName);

        if (is_null($sessTemp)) {
            $sessTemp = array();
        }

        if (!isset($sessTemp[$this->actionName])) {
            $sessTemp[$this->actionName] = array();
        }

        // ページ番号
        $pageNum = 0;

        if (isset($this->post[$pageNumKey])) {
            $pageNum = (int)$this->post[$pageNumKey];
        } else if (isset($this->get[$pageNumKey])) {
            $pageNum = (int)$this->get[$pageNumKey];
        } else {

            if (isset($sessTemp[$this->actionName]['search_cond'])) {
                unset($sessTemp[$this->actionName]['search_cond']);
            }

        }

        // 検索条件
        $condition = null;

        // 入力データ取得
        if (isset($this->post[$this->actionName])) {
            $condition = $this->post[$this->actionName];
        } else if (isset($this->get[$this->actionName])) {
            $condition = $this->get[$this->actionName];
        } else if (isset($sessTemp[$this->actionName]['search_cond'])) {
            $condition = $sessTemp[$this->actionName]['search_cond'];
        }

        if ($condition instanceof xFrameworkPX_Util_MixedCollection) {
            $condition = $condition->getArrayCopy();
        }

        // スキーマ取得
        $schemas = $module->getAllSchema($fieldFilters);

        // 検索条件設定の整形
        $temp = array();

        if (isset($conf['search_field']) && is_array($conf['search_field'])) {

            foreach ($conf['search_field'] as $key => $value) {

                if (is_array($value)) {
                    $temp[$key] = array();

                    // 入力フォームのフィールドタイプ設定
                    if (isset($value['field_type'])) {
                        $temp[$key]['field_type'] = ($value['field_type'] !== '')
                                                  ? $value['field_type']
                                                  : 'text';
                    } else {

                        // 入力フォーム無し
                        $temp[$key]['field_type'] = 'none';
                    }

                    // 入力フォームその他設定
                    if (isset($value['options'])) {
                        $temp[$key]['options'] = (is_array($value['options']))
                                               ? $value['options'] : array();
                    }

                    // 検索条件設定
                    $temp[$key]['cond'] = (isset($value['cond']) && $value['cond'] !== '')
                                        ? $value['cond'] : '=';

                    // 検索対象カラム設定
                    if (
                        isset($value['target']) &&
                        is_array($value['target']) &&
                        count($value['target']) > 0
                    ) {
                        $temp[$key]['target'] = array();

                        foreach ($value['target'] as $colName) {
                            $temp[$key]['target'][] = $colName;
                        }

                    } else {

                        if (isset($condition[$key])) {
                            unset($condition[$key]);
                        }

                    }

                }

            }

        }

        if (count($temp) <= 0) {
            $temp = array();
            $allSchema = $module->getAllSchema();

            foreach ($allSchema as $tblName => $fields) {

                foreach ($fields as $field) {
                    $name = sprintf('%s.%s', $tblName, $field['Field']);
                    $temp[str_replace('.', '_', $name)] = array(
                        'field_type' => 'text',
                        'options' => array(
                            'id' => $name,
                            'prelabel' => $name
                        ),
                        'cond' => '=',
                        'target' => array($name)
                    );
                }

            }

        }

        $searchFields = $temp;
        $sessTemp[$this->actionName]['field_conf'] = $temp;

        // セッションに検索条件セット
        if ($condition) {
            $sessTemp[$this->actionName]['search_cond'] = $condition;
        }

        $findCond = (!is_null($condition))
                  ? array_merge($condition, $search)
                  : $search;

        // 最終実行アクション設定
        $sessTemp['lastAction'] = sprintf(
            '%s/%s',
            $this->getContentPath(),
            $this->actionName
        );

        if ($this->cmd == 'init') {
            $find = null;

            if (is_null($condition)) {

                if (isset($sessTemp[$this->actionName])) {
                    $sessTemp[$this->actionName] = array();
                }

                if ($init_search) {

                    // リスト取得
                    $find = $module->findAll(
                        $count,
                        $pageNum,
                        $searchFields,
                        $findCond,
                        $orderFields
                    );
                }

            } else {

                // リスト取得
                $find = $module->findAll(
                    $count,
                    $pageNum,
                    $searchFields,
                    $findCond,
                    $orderFields
                );
            }

            if ($find['list']) {
                $list = $find['list'];

                // ページャー情報取得
                $pager = $module->pager(
                    intval($find['count']),
                    $pageNum,
                    $condition
                );

                foreach ($pager as $key => $item) {

                    if (isset($item['search']) && $item['search'] !== '') {
                        $item['search'] = sprintf(
                            $item['search'],
                            $this->actionName
                        );

                        $pager[$key] = $item;
                    }

                }

            }

        } else if ($this->cmd == 'back') {

        }

        if ($list) {

            // 出力整形
            $ret->outheader = null;
            $ret->outlist = array();

            foreach ($list as $index => $line) {
                $headerTemp = array();
                $listTemp = array();

                foreach ($schemas as $fields) {

                    foreach ($fields as $field) {
                        $headerTemp[$field['Field']] = ($field['Comment'])
                                                     ? $field['Comment']
                                                     : $field['Field'];
                        $listTemp[$field['Field']] = $line[$field['Field']];
                    }

                }

                if ($index == 0) {
                    $ret->outheader = $headerTemp;
                }

                $ret->outlist[$list[$index][$module->primaryKey]] = $listTemp;
            }

        } else {

            if (!is_null($condition)) {

                // 検索結果がない場合のエラーメッセージセット
                $ret->noItemMessage = $noItemMessage;
            }

        }

        if ($pager) {
            $ret->pager = $pager;
        }

        // WiseTag設定生成
        $formConfigs = $module->getWiseTagConf(
            $this->actionName,
            $searchFields,
            $condition
        );

        // 出力設定
        if ($formConfigs) {
            $ret->wiseTag = $formConfigs;
        }

        $this->Session->write($this->sessName, $sessTemp);

        return $ret;

        /*
        $ret            = $this->mix();
        $list           = null;
        $sessTemp       = array();

        // 1ページの表示件数
        $count          = isset($conf['count']) ? $conf['count'] : null;

        // 参照するテーブルの主キー
        $primaryKey     = $module->primaryKey;

        // 表示するページ番号のフィールド名
        $pageNumKey     = isset($conf['page_num_key'])
                          ? $conf['page_num_key']
                          : 'p';

        // ソートの対象となるフィールド名とソートの設定
        $orderFields    = isset($conf['order_field'])
                          ? $conf['order_field']
                          : array($primaryKey);

        // 非表示にするフィールド名
        $fieldFilters   = isset($conf['field_filter'])
                          ? $conf['field_filter']
                          : array();

        // 検索結果がなかった場合に表示するメッセージ
        $noItemMessage  = isset($conf['no_item_message'])
                          ? $conf['no_item_message']
                          : '';

        // 初回実行時に検索を行うかどうかのフラグ
        $init_search    = (isset($conf['init_search']) && $conf['init_search'] !== '')
                          ? (bool)$conf['init_search']
                          : false;
        $backCond = array();
        if (isset($this->get[$pageNumKey])) {
            $pageNum = ($this->get[$pageNumKey])
                     ? $this->get[$pageNumKey]
                     : 0;
            $backCond[$pageNumKey] = $pageNum;
        } else if (isset($this->post[$pageNumKey])) {
            $pageNum = ($this->post[$pageNumKey])
                     ? $this->post[$pageNumKey]
                     : 0;
            $backCond[$pageNumKey] = $pageNum;
        } else {
            $pageNum = 0;
        }

        // スキーマ取得
        $schemas = $module->getAllSchema($fieldFilters);

        // 検索条件設定の整形
        $temp = array();

        if (isset($conf['search_field']) && is_array($conf['search_field'])) {

            foreach ($conf['search_field'] as $key => $value) {

                if (is_array($value)) {
                    $temp[$key] = array();

                    // 入力フォームのフィールドタイプ設定
                    if (isset($value['field_type'])) {
                        $temp[$key]['field_type'] = ($value['field_type'] !== '')
                                                  ? $value['field_type']
                                                  : 'text';
                    } else {

                        // 入力フォーム無し
                        $temp[$key]['field_type'] = 'none';
                    }

                    // 入力フォームその他設定
                    if (isset($value['options'])) {
                        $temp[$key]['options'] = (is_array($value['options']))
                                               ? $value['options'] : array();
                    }

                    // 検索条件設定
                    $temp[$key]['cond'] = (isset($value['cond']) && $value['cond'] !== '')
                                        ? $value['cond'] : '=';

                    // 検索対象カラム設定
                    if (
                        isset($value['target']) &&
                        is_array($value['target']) &&
                        count($value['target']) > 0
                    ) {
                        $temp[$key]['target'] = array();

                        foreach ($value['target'] as $colName) {
                            $temp[$key]['target'][] = $colName;
                        }

                    }

                }

            }

        }

        if (count($temp) <= 0) {
            $temp = array();
            $allSchema = $module->getAllSchema();

            foreach ($allSchema as $tblName => $fields) {

                foreach ($fields as $field) {
                    $name = sprintf('%s.%s', $tblName, $field['Field']);
                    $temp[str_replace('.', '_', $name)] = array(
                        'field_type' => 'text',
                        'options' => array(
                            'id' => $name,
                            'prelabel' => $name
                        ),
                        'cond' => '=',
                        'target' => array($name)
                    );
                }

            }

            $name = 'btn_search';
            $temp[$name] = array(
                'field_type' => 'submit',
                'options' => array(
                    'id' => $name,
                    'value' => '検索'
                )
            );
        }

        $searchFields = $temp;

        // 検索値取得
        $search = array();

        foreach (array_keys($searchFields) as $keyVal) {
            $type = strtolower($searchFields[$keyVal]['field_type']);
            $keyVal = str_replace('.', '_', $keyVal);
            
            if (
                $type != 'submit' &&
                $type != 'reset' &&
                $type != 'button' &&
                $type != 'image'
            ) {

                if (isset($this->post[$keyVal])) {
                    $search[$keyVal] = $this->post->{$keyVal};
                    $backCond[$keyVal] = $this->post->{$keyVal};
                } else if (isset($this->get[$keyVal])) {
                    $search[$keyVal] = $this->get->{$keyVal};
                    $backCond[$keyVal] = $this->get->{$keyVal};
                }

            }

        }

        if ($this->cmd == 'init') {
            $this->Session->remove('rd');
            $ret->wiseTag = array(
                array(
                    'type' => 'form',
                    'action' => sprintf('./%s.html', $this->actionName),
                    'method' => 'post'
                )
            );

            // WiseTag設定生成
            $formConfigs = $module->getWiseTagConf($searchFields, $search);
        } else if ($this->cmd == 'exec' || $this->cmd == 'back') {
            $ret->wiseTag = array();

            // 検索条件バックアップ
            if ($backCond) {
                $sessTemp['backCond'] = $backCond;
            }

            // WiseTag設定生成
            $formConfigs = $module->getWiseTagConf($searchFields, $search);
        }

        // WiseTag設定登録
        foreach ($formConfigs as $formConfig) {
            $ret->wiseTag[] = $formConfig;
        }

        if ($this->cmd != 'init' || $init_search) {

            $findSearch = array_merge($search, $conf['search']);

            // リスト取得
            $find = $module->findAll(
                $count,
                $pageNum,
                $searchFields,
                $findSearch,
                $orderFields
            );

            if ($find['list']) {

                // ページャー情報取得
                $ret->pager = $module->pager(
                    intval($find['count']),
                    $pageNum,
                    $search
                );

                // 出力整形
                $ret->outheader = null;
                $ret->outlist = array();

                foreach ($find['list'] as $index => $line) {
                    $headerTemp = array();
                    $listTemp = array();

                    foreach ($schemas as $fields) {

                        foreach ($fields as $field) {
                            $headerTemp[$field['Field']] = ($field['Comment'])
                                                         ? $field['Comment']
                                                         : $field['Field'];
                            $listTemp[$field['Field']] = $line[$field['Field']];
                        }

                    }

                    if ($index == 0) {
                        $ret->outheader = $headerTemp;
                    }

                    $ret->outlist[$find['list'][$index][$primaryKey]] = $listTemp;
                }

            } else {

                // 検索結果なしのメッセージ設定
                $ret->noListMessage = $noItemMessage;
            }

        }

        // アクション名保存
        $sessTemp['prevAction'] = $this->actionName;

        // セッションに保存
        $this->Session->write('rd', $sessTemp);

        // IDのキー名セット
        $ret->idkey = $primaryKey;

        return $ret;
        */
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

    /*
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
    */

    public function onDetail($conf, $module)
    {

        // ローカル変数初期化
        $ret = $this->mix();

        // 主キーのカラム名
        $idKey = $module->primaryKey;

        // 非表示カラム名一覧
        $fieldFilters = isset($conf['field_filter'])
                        ? $conf['field_filter']
                        : array($idKey);

        $nodataMessage  = isset($conf['no_data_message'])
                          ? $conf['no_data_message']
                          : '';

        $id = null;

        $param = (isset($conf['param_name_id']) && $conf['param_name_id'] !== '')
                 ? $conf['param_name_id'] : $idKey;

        if (isset($this->get->{$param})) {
            $id = $this->get->{$param};
        } else if (isset($this->post->{$param})) {
            $id = $this->post->{$param};
        }

        $backCond = null;
        $param = (isset($conf['param_name_backCond']) && $conf['param_name_backCond'] !== '')
               ? $conf['param_name_backCond'] : 'bc';

        $sessData = $this->Session->read('rd');
        $backCond = (isset($sessData['backCond']))
                  ? $sessData['backCond'] : null;

        $cond = (isset($conf['search']) && is_array($conf['search']))
              ? $conf['search'] : array();
        $cond[$idKey] = $id;

        $data = null;

        if ($this->cmd == 'init' || $this->cmd == 'exec') {

            if (!is_null($backCond)) {
                $ret->{$param} = $backCond;
                unset($sessData['backCond']);
            }

            // データ取得
            $data = $module->find($cond, $fieldFilters);

            if ($data) {

                // スキーマ取得
                $schemas = $module->getAllSchema($fieldFilters);

                // 出力整形
                $headerTemp = array();
                $dataTemp = array();

                foreach ($schemas as $fields) {

                    foreach ($fields as $field) {
                        $headerTemp[$field['Field']] = ($field['Comment'])
                                                     ? $field['Comment']
                                                     : $field['Field'];
                        $dataTemp[$field['Field']] = $data[$field['Field']];
                    }

                }

                $ret->header = $headerTemp;
                $ret->data = $dataTemp;
            } else {

                // 検索結果なしのメッセージ設定
                $ret->noDataMessage = $nodataMessage;
            }

            $ret->wiseTag = array(
                array(
                    'type' => 'form',
                    'action' => sprintf('./%s.html', $sessData['prevAction']),
                    'method' => 'post'
                ),
                array(
                    'type' => 'submit',
                    'name' => 'btn_back',
                    'value' => '戻る'
                )
            );
        }

        // アクション名保存
        $sessTemp['prevAction'] = $this->actionName;

        // セッションに保存
        $this->Session->write('rd', $sessTemp);

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

        // フィールド表示フィルタ
        $fieldFilters = isset($conf['field_filter'])
                      ? $conf['field_filter']
                      : array(
                            $module->primaryKey, 'created', 'modified', 'del'
                        );

        // ID指定フィールド名
        $idKey = (isset($conf['idKey']) && $conf['idKey'] !== '')
               ? $conf['idKey']
               : $module->primaryKey;

        // 次画面アクション名
        $nextAction = (isset($conf['nextAction']))
                    ? $conf['nextAction']
                    : 'verify';

        // 前画面アクション名
        $prevAction = (isset($conf['prevAction']))
                    ? $conf['prevAction']
                    : 'index';

        // フィールド設定
        $fieldConf = null;

        // フィールドデータ
        $fieldData = null;

        // セッション情報取得
        $sessTemp = $this->Session->read($this->sessName);

        if (is_null($sessTemp)) {
            $sessTemp = array();
        }

        // 最終実行アクション設定
        $sessTemp['lastAction'] = sprintf(
            '%s/%s',
            $this->getContentPath(),
            $this->actionName
        );

        if (!isset($sessTemp[$this->actionName])) {
            $sessTemp[$this->actionName] = array();
        }

        // スキーマ取得
        $schemas = $module->schema($fieldFilters);

        if ($this->cmd == 'init') {
            $id = null;

            // ID取得
            if (isset($this->post[$idKey])) {
                $id = $this->post[$idKey];
            } else if (isset($this->get[$idKey])) {
                $id = $this->get[$idKey];
            }

            // データ取得フィールド設定生成
            $fields = array();

            foreach ($schemas as $schema) {
                $fields[] = $schema['Field'];
            }

            if (!is_null($id)) {
                $fieldData = $module->get(
                    'first',
                    array(
                        'fields' => $fields,
                        'conditions' => array($module->primaryKey => $id)
                    )
                );

                // 取得したIDをセッションに保存
                $sessTemp[$this->actionName]['targetId'] = $id;
            } else {

                /* IDが取得できなかった場合は前画面にリダイレクト */

                $firstUrl = sprintf('./%s.html', $prevAction);

                foreach ($sessTemp as $key => $value) {

                    if (is_array($value)) {
                        $this->Session->remove(sprintf(
                            '%s_%s', $this->sessName, $key
                        ));
                    }

                }

                // セッション初期化
                $this->Session->remove($this->sessName);

                // リダイレクト
                $this->redirect($firstUrl);
            }

            // 保存チケット発行
            $sessTemp['ticket'] = sprintf('%s.save', $this->sessName);

        } else if ($this->cmd == 'back') {

            // 入力データ取得
            $fieldData = (isset($sessTemp[$nextAction]['inputData']))
                       ? $sessTemp[$nextAction]['inputData']
                       : null;

            // バリデーションエラー取得
            $validError = null;

            if (
                isset($sessTemp[$nextAction]) &&
                isset($sessTemp[$nextAction]['validError'])
            ) {
                $validError = $sessTemp[$nextAction]['validError'];
            } else {
                $validError = array();
            }

            if ($validError) {

                // エラーメッセージ整形
                $tempError = array();

                foreach ($validError as $fieldName => $item) {
                    $tempError[$fieldName] = $item['messages'];
                }

                $ret->validError = $tempError;
            }

        }

        if (!isset($sessTemp[$this->actionName]['field_conf'])) {
            $fieldConf = array();

            if (isset($conf['input_field']) && is_array($conf['input_field'])) {

                foreach ($conf['input_field'] as $key => $value) {

                    if (!in_array($key, $fieldFilters) && is_array($value)) {
                        $fieldConf[$key] = array();

                        // 入力フォームのフィールドタイプ設定
                        if (
                            isset($value['field_type']) &&
                            $value['field_type'] !== ''
                        ) {
                            $fieldConf[$key]['field_type'] = $value['field_type'];
                        } else {

                            // 入力フォーム指定無し
                            $fieldConf[$key]['field_type'] = 'text';
                        }

                        // 入力フォームその他設定
                        if (isset($value['options'])) {
                            $fieldConf[$key]['options'] = (is_array($value['options']))
                                                   ? $value['options'] : array();
                        }

                    }

                }

            }

            // 入力フィールドの設定がなかった場合自動生成
            if (count($fieldConf) < 1) {
                $fieldConf[] = array(
                    'field_type' => 'form',
                    'options' => array(
                        'action' => sprintf('./%s.html', $nextAction),
                        'method' => 'post'
                    )
                );

                $temp = $module->getInputForm($schemas);

                foreach ($temp as $key => $item) {
                    $fieldConf[$key] = $item;
                }

                $fieldConf['btn_submit'] = array(
                    'field_type' => 'submit',
                    'options' => array(
                        'value' => '登録'
                    )
                );
            }

            $sessTemp[$this->actionName]['field_conf'] = $fieldConf;
        } else {

            // セッションから設定情報読込
            $fieldConf = $sessTemp[$this->actionName]['field_conf'];
        }

        // セッションデータ設定
        $this->Session->write($this->sessName, $sessTemp);

        // 戻り値設定
        if ($fieldConf) {
            $ret->wiseTag = $module->getWiseTagConf(
                $this->actionName,
                $fieldConf,
                $fieldData
            );
        }

        if ($nextAction) {
            $ret->nextAction = sprintf('./%s.html', $nextAction);
        }

        if ($prevAction) {
            $ret->prevAction = sprintf('./%s.html', $prevAction);
        }

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

        // フィールド表示フィルタ
        $fieldFilters = isset($conf['field_filter'])
                        ? $conf['field_filter']
                        : array(
                            $module->primaryKey, 'created', 'modified', 'del'
                          );

        // 入力フィールド情報
        $inputFields = null;

        // フィールド設定情報
        $fieldConf = null;

        // セッション情報取得
        $sessTemp = $this->Session->read($this->sessName);

        if (is_null($sessTemp)) {
            $sessTemp = array();
        }

        // 最終実行アクション設定
        $sessTemp['lastAction'] = sprintf(
            '%s/%s',
            $this->getContentPath(),
            $this->actionName
        );

        if (!isset($sessTemp[$this->actionName])) {
            $sessTemp[$this->actionName] = array();
        }

        // 次画面アクション名
        $nextAction = (isset($conf['nextAction']))
                    ? $conf['nextAction']
                    : 'verify';

        // 前画面アクション名
        $prevAction = (isset($conf['prevAction']))
                    ? $conf['prevAction']
                    : '';

        // フィールド設定存在チェック
        if (!isset($sessTemp[$this->actionName]['field_conf'])) {

            // スキーマ取得
            $schemas = $module->schema($fieldFilters);

            $fieldConf = array();

            if (isset($conf['input_field']) && is_array($conf['input_field'])) {

                foreach ($conf['input_field'] as $key => $value) {

                    if (!in_array($key, $fieldFilters) && is_array($value)) {
                        $fieldConf[$key] = array();

                        // 入力フォームのフィールドタイプ設定
                        if (
                            isset($value['field_type']) &&
                            $value['field_type'] !== ''
                        ) {
                            $fieldConf[$key]['field_type'] = $value['field_type'];
                        } else {

                            // 入力フォーム指定無し
                            $fieldConf[$key]['field_type'] = 'text';
                        }

                        // 入力フォームその他設定
                        if (isset($value['options'])) {
                            $fieldConf[$key]['options'] = (is_array($value['options']))
                                                   ? $value['options'] : array();
                        }

                    }

                }

            }

            // 入力フィールドの設定がなかった場合自動生成
            if (count($fieldConf) < 1) {
                $fieldConf[] = array(
                    'field_type' => 'form',
                    'options' => array(
                        'action' => sprintf('./%s.html', $nextAction),
                        'method' => 'post'
                    )
                );

                $temp = $module->getInputForm($schemas);

                foreach ($temp as $key => $item) {
                    $fieldConf[$key] = $item;
                }

                $fieldConf['btn_submit'] = array(
                    'field_type' => 'submit',
                    'options' => array(
                        'value' => '登録'
                    )
                );
            }

            $sessTemp[$this->actionName]['field_conf'] = $fieldConf;
        } else {

            // セッションから設定情報読込
            $fieldConf = $sessTemp[$this->actionName]['field_conf'];
        }

        if ($this->cmd == 'init') {

            // initモード時の処理

            $inputFields = $module->getWiseTagConf(
                $this->actionName, $fieldConf
            );

            // 保存チケット発行
            $sessTemp['ticket'] = sprintf('%s.save', $this->sessName);
        } else if ($this->cmd == 'back') {

            // backモード時の処理

            // バリデーションエラー取得
            if (
                isset($sessTemp[$nextAction]) &&
                isset($sessTemp[$nextAction]['validError'])
            ) {
                $validError = $sessTemp[$nextAction]['validError'];
            } else {
                $validError = array();
            }

            // 入力データ取得
            $inputData = (isset($sessTemp[$nextAction]['inputData']))
                       ? $sessTemp[$nextAction]['inputData']
                       : null;

            // 入力フォーム用WiseTag設定取得
            if (!is_null($inputData)) {
                $inputFields = $module->getWiseTagConf(
                    $this->actionName,
                    $fieldConf,
                    $inputData
                );
            }

            // バリデーションエラー存在チェック
            if ($validError) {

                // エラーメッセージ整形
                $tempError = array();

                foreach ($validError as $fieldName => $item) {
                    $tempError[$fieldName] = $item['messages'];
                }

                $ret->validError = $tempError;
            }
        }

        // セッション保存
        $this->Session->write($this->sessName, $sessTemp);

        // 戻り値設定
        if ($inputFields) {
            $ret->wiseTag = $inputFields;
        }

        if ($nextAction) {
            $ret->nextAction = sprintf('./%s.html', $nextAction);
        }

        if ($prevAction) {
            $ret->prevAction = sprintf('./%s.html', $prevAction);
        }

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

        // 次画面アクション名
        $nextAction = (isset($conf['nextAction']))
                    ? $conf['nextAction']
                    : 'save';

        // 前画面アクション名
        $prevAction = (isset($conf['prevAction']))
                    ? $conf['prevAction']
                    : '';

        // セッション情報取得
        $sessTemp = $this->Session->read($this->sessName);

        if (is_null($sessTemp)) {
            $sessTemp = array();
        }

        // 最終実行アクションパス
        $lastActionPath = (isset($sessTemp['lastAction']))
                        ? $sessTemp['lastAction']
                        : '';
        $sessTemp['lastAction'] = sprintf(
            '%s/%s',
            $this->getContentPath(),
            $this->actionName
        );

        // バリデーションエラー
        $validError = null;

        $prevActionPath = ($prevAction)
                        ? sprintf(
                            '%s/%s', $this->getContentPath(), $prevAction
                          )
                        : '';
        if (
            ($lastActionPath === '' || $prevActionPath === '') ||
            $lastActionPath != $prevActionPath  &&
            $lastActionPath != $sessTemp['lastAction']
        ) {
            $firstUrl = sprintf('./%s.html', $prevAction);

            foreach ($sessTemp as $key => $value) {

                if (is_array($value)) {
                    $this->Session->remove(sprintf(
                        '%s_%s', $this->sessName, $key
                    ));
                }

            }

            // セッション初期化
            $this->Session->remove($this->sessName);

            // リダイレクト
            $this->redirect($firstUrl);
        }

        $fieldConf = $sessTemp[$prevAction]['field_conf'];
        $postExist = false;
        $inputData = $this->mix();

        // 入力値存在チェック
        foreach ($fieldConf as $key => $value) {

            if (is_numeric($key)) {
                continue;
            }

            if (isset($this->post[$prevAction])) {

                if (
                    $value['field_type'] == 'submit' ||
                    $value['field_type'] == 'reset' ||
                    $value['field_type'] == 'button' ||
                    $value['field_type'] == 'image'
                ) {
                    continue;
                }

                $inputData[$key] = (isset($this->post[$prevAction][$key]))
                                 ? $this->post[$prevAction][$key]
                                 : '';
            } else {
                $postExist = false;
                break;
            }

            $postExist = true;
        }

        if (!$postExist) {
            $inputData = null;
            $firstUrl = sprintf('./%s.html', $prevAction);

            foreach ($sessTemp as $key => $value) {

                if (is_array($value)) {
                    $this->Session->remove(sprintf(
                        '%s_%s', $this->sessName, $key
                    ));
                }

            }

            // セッション初期化
            $this->Session->remove($this->sessName);

            // リダイレクト
            $this->redirect($firstUrl);
        }

        // バリデーション
        if ($inputData) {
            $sessTemp[$this->actionName]['inputData'] = $inputData;
            $target = $this->mix(array($module->toString() => $inputData));
            $validError = $module->validation($target);
        }

        if ($validError->count() > 0) {

            // バリデータエラーセット
            $sessTemp[$this->actionName]['validError'] = $validError;
            $this->Session->write($this->sessName, $sessTemp);

            // 入力画面にリダイレクト
            $this->redirect(sprintf('./%s.html', $prevAction));
        } else {

            if (isset($sessTemp[$this->actionName]['validError'])) {

                // バリデーターエラー消去
                unset($sessTemp[$this->actionName]['validError']);
            }

            if (isset($sessTemp[$prevAction]['targetId'])) {

                // targetIdセット
                $sessTemp[$this->actionName]['targetId'] = $sessTemp[$prevAction]['targetId'];
            }

            // セッション書込み
            $this->Session->write($this->sessName, $sessTemp);

            // 入力値セット
            $ret->inputData = $inputData;
        }

        // 戻り値セット
        if ($nextAction) {
            $ret->nextAction = sprintf('./%s.html', $nextAction);
        }

        if ($prevAction) {
            $ret->prevAction = sprintf('./%s.html', $prevAction);
        }

        return $ret;
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
    /*
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
    */

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
        $ret = $this->mix();
        $lock = (isset($conf['lock'])) ? $conf['lock'] : 'true';
        $trans = (isset($conf['transaction']))
               ? $conf['transaction']
               : 'true';

        // 次画面アクション名
        $nextAction = (isset($conf['nextAction']))
                        ? $conf['nextAction']
                        : 'fin';

        // 前画面アクション名
        $prevAction = (isset($conf['prevAction']))
                        ? $conf['prevAction']
                        : 'verify';

        // セッション情報取得
        $sessTemp = $this->Session->read($this->sessName);

        if (is_null($sessTemp)) {
            $sessTemp = array();
        }

        // 最終実行アクションパス
        $lastActionPath = (isset($sessTemp['lastAction']))
                        ? $sessTemp['lastAction']
                        : '';
        $sessTemp['lastAction'] = sprintf(
            '%s/%s',
            $this->getContentPath(),
            $this->actionName
        );

        $postExist = false;

        // 入力値存在チェック
        if (isset($this->post[$prevAction])) {
            $inputData = $this->post[$prevAction];
            $sessTemp[$this->actionName]['inputData'] = $inputData;
            $target = $this->mix(array($module->toString() => $inputData));
            $validError = $module->validation($target);

            if ($validError->count() > 0) {

                // バリデータエラーセット
                $sessTemp[$this->actionName]['validError'] = $validError;
                $this->Session->write($this->sessName, $sessTemp);

                // 入力画面にリダイレクト
                $this->redirect(sprintf('./%s.html', $prevAction));
            }

        } else if (isset($sessTemp[$prevAction]['inputData'])) {
            $inputData = $sessTemp[$prevAction]['inputData'];
        } else {
            $firstUrl = sprintf('./%s.html', $prevAction);

            foreach ($sessTemp as $key => $value) {

                if (is_array($value)) {
                    $this->Session->remove(sprintf(
                        '%s_%s', $this->sessName, $key
                    ));
                }

            }

            // セッション初期化
            $this->Session->remove($this->sessName);

            // リダイレクト
            $this->redirect($firstUrl);
        }

        // チケット確認
        if (
            isset($sessTemp['ticket']) &&
            $sessTemp['ticket'] == sprintf('%s.save', $this->sessName)
        ) {

            // チケット破棄
            unset($sessTemp['ticket']);

            // 編集対象条件取得
            $target = (isset($sessTemp[$prevAction]['targetId']))
                  ? $sessTemp[$prevAction]['targetId']
                  : null;

            // 登録処理
            $module->save($inputData, $target, $trans, $lock);

            $this->Session->write($this->sessName, $sessTemp);

            // 完了画面へリダイレクト
            $this->redirect(sprintf('./%s.html', $nextAction));
        } else {
            $inputData = null;
            $firstUrl = sprintf('./%s.html', $prevAction);

            foreach ($sessTemp as $key => $value) {

                if (is_array($value)) {
                    $this->Session->remove(sprintf(
                        '%s_%s', $this->sessName, $key
                    ));
                }

            }

            // セッション初期化
            $this->Session->remove($this->sessName);

            // リダイレクト
            $this->redirect($firstUrl);
        }

        return $ret;
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
        $ret = $this->mix();

        // 次画面アクション名
        $nextAction = (isset($conf['nextAction']))
                        ? $conf['nextAction']
                        : 'fin';

        // セッション取得
        $sessTemp['lastAction'] = sprintf(
            '%s/%s',
            $this->getContentPath(),
            $this->actionName
        );

        // セッション初期化処理
        foreach ($sessTemp as $key => $value) {

            if (is_array($value)) {
                $this->Session->remove(sprintf(
                    '%s_%s', $this->sessName, $key
                ));
            }

        }

        // RapidDriveセッション削除
        $this->Session->remove($this->sessName);

        // 戻り値セット
        if ($nextAction) {
            $ret->nextAction = sprintf('./%s.html', $nextAction);
        }

        return $ret;
    }

    // }}}
    // {{{ getReferer

    /**
     * リファラー取得メソッド
     *
     * @param $request 強制的に送信パラメータから取得するフラグ（default=false）
     * @return string リファラー
     */
    /*
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
    */

    // }}}
    // {{{ rapidRefererAction

    /**
     * リファラーによるファイル名取得メソッド
     *
     * @param string $default デフォルトファイル名
     * @return ファイル名
     */
    /*
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
    */
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
