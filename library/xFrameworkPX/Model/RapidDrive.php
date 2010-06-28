<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Model_RapidDrive Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Model
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: RapidDrive.php 1423 2010-01-20 08:00:29Z kotsutsumi $
 */

// {{{ xFrameworkPX_Model_RapidDrive

/**
 * xFrameworkPX_Model_RapidDrive Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Model
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Model_RapidDrive
 */
class xFrameworkPX_Model_RapidDrive extends xFrameworkPX_Model
{
    // {{{ props

    /**
     * 表示ページ数
     *
     * @var int
     */
    protected $_pageCount = 5;

    // }}}
    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @param SimpleXMLElement $conf 設定オブジェクト
     */
    public function __construct($conf, $controller)
    {
        // スーパークラスメソッドコール
        parent::__construct($conf, $controller);
    }

    // }}}
    // {{{ condition

    /**
     * WHERE区生成メソッド
     *
     * @param array $fields 検索対象フィールド
     * @param string $search 検索条件
     * @return array conditions配列
     */
    public function condition($fields = array(), $search = null)
    {
        $cond = array();
        $fields = (is_array($fields)) ? $fields : array();
        $search = (is_array($search)) ? $search : array();

        foreach ($fields as $key => $field) {

            if (isset($field['field_type']) && $field['field_type'] == 'none') {

                if (isset($field['value'])) {
                    $search[$key] = $field['value'];
                }

            }

            if (!isset($field['cond']) || !isset($field['target'])) {
                continue;
            }

            if (is_numeric($key)) {

                if (is_string($field) && preg_match('/^(and|or)$/i', $field)) {
                    $cond[] = $field;
                }

            } else if (isset($search[$key])) {

                switch (1) {

                    case preg_match('/^=$/', $field['cond']):
                        $temp = $this->_createCondtion(
                            $key,
                            $field['target'],
                            $search,
                            '',
                            '%s%s',
                            $cond
                        );
                        $cond = $temp;
                        break;

                    case preg_match('/^between$/i', $field['cond']):
                    case preg_match('/^not between$/i', $field['cond']):

                        foreach ($field['target'] as $colName) {

                            if (
                                isset($search[$colName]) &&
                                is_array($search[$colName]) &&
                                count($search[$colName]) >= 2
                            ) {
                                $search[$colName] = sprintf(
                                    '%s AND %s',
                                    $search[$colName][0],
                                    $search[$colName][1]
                                );
                            } else {
                                unset($search[$colName]);
                            }

                        }

                    case preg_match('/^<>$/', $field['cond']):
                    case preg_match('/^<$/', $field['cond']):
                    case preg_match('/^<=$/', $field['cond']):
                    case preg_match('/^>$/', $field['cond']):
                    case preg_match('/^>=$/', $field['cond']):
                    case preg_match('/^is$/i', $field['cond']):
                    case preg_match('/^is not$/i', $field['cond']):
                        $temp = $this->_createCondtion(
                            $key,
                            $field['target'],
                            $search,
                            $field['cond'],
                            '%s %s',
                            $cond
                        );
                        $cond = $temp;
                        break;

                    // 部分一致
                    case preg_match('/like/i', $field['cond']):
                    case preg_match('/like part/i', $field['cond']):
                        $temp = $this->_createCondtion(
                            $key,
                            $field['target'],
                            $search,
                            'LIKE',
                            '%s %%%s%%',
                            $cond
                        );
                        $cond = $temp;
                        break;

                    // 前方一致
                    case preg_match('/like front/i', $field['cond']):
                        $temp = $this->_createCondtion(
                            $key,
                            $field['target'],
                            $search,
                            'LIKE',
                            '%s %s%%',
                            $cond
                        );
                        $cond = $temp;
                        break;

                    // 後方一致
                    case preg_match('/like back/i', $field['cond']):
                        $temp = $this->_createCondtion(
                            $key,
                            $field['target'],
                            $search,
                            'LIKE',
                            '%s %%%s',
                            $cond
                        );
                        $cond = $temp;
                        break;
                }

            }

        }

        // 先頭要素のチェック
        reset($cond);
        if (current($cond) == 'AND' || current($cond) == 'OR') {
            unset($cond[key($cond)]);
        }

        // 末尾要素のチェック
        end($cond);
        if (current($cond) == 'AND' || current($cond) == 'OR') {
            unset($cond[key($cond)]);
        }

        return $cond;
    }

    // }}}
    // {{{ _createCondition

    /**
     * targetからconditions配列を生成するメソッド
     *  conditionメソッド内部で使用
     *
     * @param array $fields
     * @param array $values
     * @param string $cond
     * @param string $format,
     * @param array $toAdd
     * @return array
     */
    private function _createCondtion($field, $target, $values, $cond, $format, $toAdd = array())
    {
        $ret = $toAdd;

        foreach ($target as $colName) {

            if (preg_match('/^(and|or)$/i', $colName)) {
                $ret[] = strtoupper($colName);
            } else {

                if (isset($values[$field]) && $values[$field] !== '') {

                    if (isset($ret[$colName])) {
                        $first = $ret[$colName];
                        unset($ret[$colName]);
                        $ret[] = array($colName => $first);

                        $ret[] = array(
                            $colName => sprintf(
                                $format,
                                $cond,
                                $values[$key]
                            )
                        );
                    } else {
                        $ret[$colName] = sprintf(
                            $format,
                            $cond,
                            $values[$field]
                        );
                    }

                }

            }

        }

        return $ret;
    }

    // }}}
    // {{{ pager

    /**
     * ページャー生成メソッド
     *
     * @param int $count 1ページの表示件数
     * @param string $search 検索条件
     * @return array ページャー配列
     */
    public function pager($count, $page = 0, $search = '')
    {
        $page = is_numeric($page)
                 ? (int)$page
                 : 0;

        if (is_array($search)) {
            $temp = array();

            foreach ($search as $key => $value) {

                if (!is_null($value)) {

                    if (
                        $value instanceof xFrameworkPX_Util_MixedCollection ||
                        is_array($value)
                    ) {

                        foreach ($value as $val) {
                            $temp[] = sprintf('%%1$s[%s]=%s', $key, $val);
                        }

                    } else {
                        $temp[] = sprintf('%%1$s[%s]=%s', $key, $value);
                    }

                }

            }

            $search = implode('&amp;', $temp);
        }

        // ページャー生成
        $pager = array();
        for ($i = 0; $i < ($count / $this->_pageCount); ++$i) {
            $pager[$i] = array(
                'next' => (($count / $this->_pageCount) - 1 > $i),
                'current' => ($page === $i),
                'prev' => ($i > 0),
                'prevpage' => $page - 1,
                'nextpage' => $page + 1,
                'search' => $search
            );
        }

        return $pager;
    }

    // }}}
    // {{{ findAll

    /**
     * レコード取得メソッド
     *
     * @param int $count
     * @param int $page
     * @param string $search
     * @return array レコード配列
     */
    public function findAll(
        $count = null,
        $pageNum = 0,
        $searchFields = array(),
        $search = array(),
        $orders = array()
    )
    {
        $ret = array();
        $conds = array();
        $pageNum = is_numeric($pageNum) ? (int)$pageNum : 0;
        $searchFields = is_array($searchFields)
                        ? $searchFields
                        : array();
        $orders = is_array($orders) ? $orders : array();

        // スキーマ取得
        $schemas = $this->getAllSchema();

        // フィールド一覧生成
        $fields = array();

        foreach ($schemas as $tblName => $table) {

            foreach ($table as $field) {
                $fields[] = sprintf('%s.%s', $tblName, $field['Field']);
            }

        }

        // conditions生成
        $conds = $this->condition($searchFields, $search);

        $conf = array();

        $conf['fields'] = $fields;
        $conf['conditions'] = $conds;

        if ($orders) {
            $conf['order'] = $orders;
        }

        if (!is_null($count)) {
            $conf['limit'] = $count;
            $conf['page'] = $pageNum;
        }

        // レコード取得
        $ret['list'] = $this->get('all', $conf);

        // 件数取得
        $ret['count'] = $this->get('count', $conf);

        return $ret;
    }

    // }}}
    // {{{ find

    /**
     * レコード取得メソッド
     *
     * @param int $id 主キー
     * @param string $keyName 主キーのフィールド名
     * @return array レコード配列
     * @access public
     */
    /*
    public function find($id, $keyName = 'id')
    {
        $keyName = !is_null($keyName)
                    ? (string)$keyName
                    : 'id';
        $binds = array();
        $where = null;

        // スキーマ取得
        $schemas = $this->schema();

        // フィールド一覧生成
        $fields = array();

        foreach ($schemas as $items) {
             $fields[] = $items['Field'];
        }
        $fields = implode(', ', $fields);

        // WHERE句生成
        $where = sprintf(
            '%s = :%s ',
            $keyName,
            $keyName
        );

        $binds = array($keyName => $id);

        // クエリー定義
        $query = implode(
            PHP_EOL,
            array(
                'SELECT',
                '    %s',
                'FROM',
                '    %s'
            )
        );

        // レコード取得
        $ret = $this->row(
            array(
                'query' => sprintf(
                    $query,
                    $fields,
                    $this->usetable
                ),
                'where' => $where,
                'bind' => $binds
            )
        );

        return $ret;
    }
    */

    public function find($cond = array(), $filter = array())
    {
        if (!is_array($filter)) {
            $filter = array();
        }

        $schemas = $this->getAllSchema($filter);
        $fields = array();

        foreach ($schemas as $name => $table) {

            foreach ($table as $field) {
                $fields[] = sprintf('%s.%s', $name, $field['Field']);
            }

        }

        $conf = array();
        $conf['fields'] = $fields;
        $conf['conditions'] = $cond;

        return $this->get('first', $conf);
    }

    // }}}
    // {{{ save

    /**
     * レコード保存メソッド
     *
     * @param array $data 追加データ
     * @return void
     * @access public
     */
    public function save($data, $targetId = null, $trans = true, $lock = true)
    {
        $setData = array();
        $cond = array();

        // スキーマ情報取得
        $schemas = $this->schema();

        foreach ($schemas as $schema) {

            if ($schema['Field'] == 'created') {

                if (is_null($targetId)) {
                    $setData['created'] = (isset($data['created']))
                                        ? $data['created']
                                        : 'NOW()';
                }

            } else if ($schema['Field'] == 'modified') {

                if (is_null($targetId)) {
                    $setData['modified'] = (isset($data['modified']))
                                         ? $data['modified']
                                         : null;
                } else {
                    $setData['modified'] = (isset($data['modified']))
                                         ? $data['modified']
                                         : 'NOW()';
                }

            } else if ($schema['Field'] == 'del') {

                if (is_null($targetId)) {
                    $delDefault = (isset($schema['Default']) && $schema['Default'] !== '')
                                ? $schema['Default']
                                : 0;
                    $setData['del'] = (isset($data['del']))
                                    ? $data['del']
                                    : $delDefault;
                }

            } else {

                if (isset($data[$schema['Field']])) {

                    if (($data[$schema['Field']] instanceof xFrameworkPX_Util_MixedCollection)) {
                        $data[$schema['Field']] = implode(',', $data[$schema['Field']]->getArrayCopy());
                    }

                    $setData[$schema['Field']] = $data[$schema['Field']];
                }

            }

        }

        if (is_null($targetId)) {

            if (isset($setData[$this->primaryKey])) {
                $cnt = $this->count(array(
                    'where' => sprintf('%s = :id', $this->primaryKey),
                    'bind' => array('id' => $setData[$this->primaryKey])
                ));

                if ($cnt > 0) {
                    return false;
                }

            }

        } else {
            $cond[$this->primaryKey] = $targetId;
        }

        try {

            if ($trans) {
                $this->beginTrans();
            }
            $this->set($setData, $cond, $lock);

            if ($trans) {
                $this->commit();
            }

        } catch (Exception $ex) {

            if ($trans) {
                $this->rollback();
            }

            throw $ex;
            return false;
        }

        return true;
    }

    // }}}
    // {{{ edit

    /**
     * レコード更新メソッド
     *
     * @param array $data 更新データ
     * @param string $idKey 主キー名
     * @return void
     * @access public
     */
    public function edit($data, $idKey)
    {
        $fields = array();
        $values = array();
        $binds = array();
        $where = '';

        // フィールド一覧生成
        foreach ($this->schema() as $schemas) {
            $key = $schemas['Field'];
            if (
                $key != $idKey &&
                ($key == 'modified' || isset($data[$key]))
            ) {
                $fields[] = $schemas['Field'];
            }
        }

        // 値一覧生成
        foreach ($fields as $field) {
            $values[] = sprintf(':%s', $field);
        }

        // バインド一覧生成
        foreach ($fields as $field) {
            $binds[$field] = array_key_exists($field, $data)
                             ? $data[$field]
                             : null;

        }

        if (array_search('modified', $fields) !== false) {

            // 更新日時セット
            $binds['modified'] = date('Y-m-d H:i:s');
        }

        if (array_key_exists($idKey, $data)) {

            // 主キーセット
            $binds[$idKey] = $data[$idKey];
        }

        // Where生成
        $where = sprintf(
            'WHERE %s = :%s',
            $idKey,
            $idKey
        );

        // アップデート
        $this->update(
            array(
                'field' => $fields,
                'value' => $values,
                'bind' => $binds,
                'where' => $where
            )
        );
    }

    // }}}
    // {{{ getAllSchema()

    /**
     * アソシエーションも含めたすべてのテーブルスキーマ取得メソッド
     *  フィルターの指定は
     *      array(
     *          'テーブル名' => array('カラム名'),
     *          // または
     *          'テーブル名' => 'カラム名',
     *          ・・・,
     *      )
     *
     * @param array $filters
     * @return array
     */
    public function getAllSchema($filters = array())
    {
        $ret = array();

        // 使用するテーブル一覧生成
        $useTables = array($this->usetable);

        // hasOneで使用するテーブル取得
        if (is_string($this->hasOne)) {
            $useTables[] = $this->hasOne;
        } else {

            foreach ($this->hasOne as $key => $value) {

                if (is_numeric($key)) {
                    $useTables[] = $value;
                } else {
                    $useTables[] = $key;
                }

            }

        }

        // belongsToで使用するテーブル取得
        if (is_string($this->belongsTo)) {
            $useTables[] = $this->belongsTo;
        } else {

            foreach ($this->belongsTo as $key => $value) {

                if (is_numeric($key)) {
                    $useTables[] = $value;
                } else {
                    $useTables[] = $key;
                }

            }

        }

        // hasManyで使用するテーブル取得
        if (is_string($this->hasMany)) {
            $useTables[] = $this->hasMany;
        } else {

            foreach ($this->hasMany as $key => $value) {

                if (is_numeric($key)) {
                    $useTables[] = $value;
                } else {
                    $useTables[] = $key;
                }

            }

        }

        // スキーマのフィルター処理
        foreach ($useTables as $tblName) {

            if ($tblName == $this->usetable && !isset($filters[$tblName])) {
                $filters[$tblName] = $filters;
            }

            if (!isset($filters[$tblName])) {
                $filters[$tblName] = array();
            } else if (!is_array($filters[$tblName])) {
                $filters[$tblName] = array($filters[$tblName]);
            }

            $schema = $this->adapter->getSchema($this->pdo, $tblName);
            $ret[$tblName] = array();

            foreach ($schema['result'] as $value) {

                if (!in_array($value['Field'], $filters[$tblName])) {
                    $ret[$tblName][] = $value;
                }

            }

        }

        return $ret;
    }

    // }}}
    // {{{ getWiseTagConf

    /**
     * 入力フォーム WiseTag設定取得メソッド
     */
    public function getWiseTagConf($actionName, $fieldConf, $values = array())
    {
        $ret = array();
        $fieldConf = (is_array($fieldConf)) ? $fieldConf : array();

        foreach ($fieldConf as $name => $conf) {
            $tag = array();

            if (!is_numeric($name) && is_array($conf)) {
                $tag['type'] = $conf['field_type'];
                $tag['name'] = sprintf('%s[%s]', $actionName, $name);

                switch (strtolower($conf['field_type'])) {

                    case 'text':
                    case 'password':

                        if (isset($conf['options']) && is_array($conf['options'])) {

                            foreach ($conf['options'] as $key => $value) {
                                $tag[$key] = $value;
                            }

                        }

                        if (isset($values[$name])) {
                            $tag['value'] = $values[$name];
                        }

                        break;

                    case 'textarea':

                        if (isset($conf['options']) && is_array($conf['options'])) {

                            foreach ($conf['options'] as $key => $value) {
                                $tag[$key] = $value;
                            }

                        }

                        if (isset($values[$name])) {
                            $tag['intext'] = $values[$name];
                        }

                        break;

                    case 'hidden':
                    case 'submit':
                    case 'reset':
                    case 'button':
                    case 'image':

                        if (isset($conf['options']) && is_array($conf['options'])) {

                            foreach ($conf['options'] as $key => $value) {
                                $tag[$key] = $value;
                            }

                        }

                        if (isset($values[$name])) {
                            $tag['value'] = $values[$name];
                        }

                        break;

                    case 'checkbox':
                    case 'radio':

                        // フィールド要素単一フラグ
                        $hasOnly = true;

                        if (isset($conf['options']) && is_array($conf['options'])) {
                            $tags = array();

                            foreach ($conf['options'] as $index => $item) {

                                if (is_numeric($index)) {
                                    $temp = $tag;
                                    $hasOnly = false;

                                    foreach ($item as $key => $value) {

                                        if (!isset($values[$name]) || $key != 'checked') {
                                            $temp[$key] = $value;
                                        }

                                    }

                                    if (isset($temp['value']) && isset($values[$name])) {

                                        if ($values[$name] instanceof xFrameworkPX_Util_MixedCollection) {
                                            $values[$name] = $values[$name]->getArrayCopy();
                                        }

                                        if (is_array($values[$name])) {
                                            $temp['checked'] = (in_array($temp['value'], $values[$name]))
                                                             ? 'checked'
                                                             : null;
                                        } else {
                                            $temp['checked'] = ($temp['value'] === $values[$name])
                                                             ? 'checked'
                                                             : null;
                                        }

                                    }

                                    $tags[] = $temp;
                                } else {

                                    if (!isset($values[$name]) || $key != 'checked') {
                                        $tag[$index] = $item;
                                    }

                                }

                            }

                            $tag = $tags;
                        } else {
                            $tag['id'] = $name;
                            $tag['prelabel'] = $name;
                        }

                        if (count($tag) > 1) {

                            if (strtolower($conf['field_type']) == 'checkbox') {
                                foreach ($tag as $key => $value) {

                                    if (!endsWith($value['name'], '[]')) {
                                        $value['name'] = $value['name'] . '[]';
                                        $tag[$key] = $value;
                                    }

                                }

                            }

                        } else if (count($tag) == 1) {
                            $tag = $tag[0];
                            $hasOnly = true;
                        }

                        if ($hasOnly) {

                            if (
                                isset($tag['value']) &&
                                isset($values[$name]) &&
                                $tag['value'] === $values[$name]
                            ) {
                                $tag['checked'] = 'checked';
                            } else {
                                $tag['checked'] = null;
                            }

                        }

                        break;

                    case 'select':

                        if (isset($conf['options']) && is_array($conf['options'])) {

                            foreach ($conf['options'] as $key => $value) {

                                if ($key == 'options' && is_array($value)) {

                                    foreach ($value as $index => $item) {

                                        if (!isset($values[$name])) {
                                            $value[$index] = $item;
                                        } else {

                                            if (
                                                isset($item['value']) &&
                                                $item['value'] === $values[$name]
                                            ) {
                                                $item['selected'] = 'selected';
                                            } else {

                                                if (isset($item['selected'])) {
                                                    unset($item['selected']);
                                                }

                                            }

                                            $value[$index] = $item;
                                        }

                                    }

                                    $tag[$key] = $value;
                                } else {
                                    $tag[$key] = $value;
                                }

                            }

                        }

                        break;

                    default:
                        $tag = null;
                        break;
                }

            } else if (is_array($conf)) {
                $tag['type'] = (isset($conf['field_type']))
                             ? strtolower($conf['field_type'])
                             : '';

                if ($tag['type'] == 'form') {

                    if (isset($conf['options']) && is_array($conf['options'])) {

                        foreach ($conf['options'] as $key => $value) {
                            $tag[$key] = $value;
                        }

                    }

                } else {
                    unset($tag['type']);
                }

            }

            if ($tag) {
                $ret[] = $tag;
            }

        }

        return $ret;
    }

    // }}}
    // {{{ getForm

    /**
     * 入力フォーム設定取得メソッド
     *    スキーマ情報を元に入力フォームの設定を取得するメソッド
     *
     * @param array $schema スキーマ情報
     * @return
     */
    public function getInputForm($schemas)
    {
        $ret = array();

        // インプットタイプの生成
        foreach ($schemas as $fields) {
            $key = $fields['Field'];
            $label = (isset($fields['Comment']) && $fields['Comment'] !== '')
                   ? $fields['Comment']
                   : $fields['Field'];
            $temp = $this->adapter->getType($fields);
            $type = $temp['type'];

            switch ($type) {

                // text, password
                case 'text':
                case 'password':
                    $ret[$key] = array(
                        'field_type' => $type,
                        'options' => array(
                            'id' => $key,
                            'prelabel' => $label
                        )
                    );

                    // 長さ設定
                    if (isset($temp['length'])) {
                        $ret[$key]['options']['length'] = $temp['length'];
                    }

                    break;

                // textarea
                case 'textarea':
                    $ret[$key] = array(
                        'field_type' => $type,
                        'options' => array(
                            'id' => $key,
                            'prelabel' => $label,
                            'cols' => '40',
                            'rows' => '5'
                        )
                    );
                    break;

                // checkbox
                case 'checkbox':
                    $ret[$key] = array(
                        'field_type' => $type,
                        'options' => array(
                            'id' => $key,
                            'prelabel' => $label,
                            'value' => '1'
                        )
                    );
                    break;

                // select_date, select_time, select_datetime
                case 'select_date':
                case 'select_datetime':
                case 'select_time':
                    /*
                    $selectType = '';

                    if ($type == 'select_date') {
                        $selectType = 'date';
                    } else if ($type == 'select_datetime') {
                        $selectType = 'datetime';
                    } else if ($type == 'select_time') {
                        $selectType = 'time';
                    }

                    $select = $this->getDateSelect($key, $selectType, $label);

                    foreach ($select as $fName => $conf) {
                        $ret[$fName] = $conf;
                    }
                    */

                    $conf = array();

                    if ($type == 'select_date') {
                        $conf['field_type'] = 'text';
                        $conf['options'] = array(
                            'id' => $key,
                            'prelabel' => $label,
                            'length' => 10
                        );
                    } else if ($type == 'select_datetime') {
                        $conf['field_type'] = 'text';
                        $conf['options'] = array(
                            'id' => $key,
                            'prelabel' => $label,
                            'length' => 19
                        );
                    } else if ($type == 'select_time') {
                        $conf['field_type'] = 'text';
                        $conf['options'] = array(
                            'id' => $key,
                            'prelabel' => $label,
                            'length' => 8
                        );
                    }

                    $ret[$key] = $conf;
                    break;
            }

        }

        return $ret;
    }

    // }}}
    // {{{ dateParse

    /**
     * 日付データ解析メソッド
     *
     * @param string $date 日付文字列
     * @return array 解析結果
     */
    public function dateParse($date)
    {
        $ret = array();

        // パース処理(date_parseの戻り値とおよそ同等)
        $unixtime = strtotime($date);

        if ($unixtime === false) {
            $ret = array(
                'year' => false,
                'month' => false,
                'day' => false,
                'hour' => false,
                'minute' => false,
                'second' => false,
            );
        } else {
            $ret = array(
                'year' => (int)date('Y', $unixtime),
                'month' => (int)date('m', $unixtime),
                'day' => (int)date('d', $unixtime),
                'hour' => (int)date('H', $unixtime),
                'minute' => (int)date('i', $unixtime),
                'second' => (int)date('s', $unixtime),
            );
        }

        return $ret;
    }

    // }}}
    // {{{ getDateString

    /**
     * 日付文字列取得メソッド
     *
     * @param array $date
     * @return string
     */
    public function getDateString($date)
    {
        $ret = '';

        if (
            isset($date['year']) &&
            isset($date['month']) &&
            isset($date['day']) 
        ) {

            if (
                $date['year'] !== false &&
                $date['month'] !== false &&
                $date['day'] !== false
            ) {
                $ret .= sprintf(
                    '%s-%02s-%02s',
                    $date['year'],
                    $date['month'],
                    $date['day']
                );
            }

        }

        if (
            isset($date['hour']) &&
            isset($date['minute']) 
        ) {

            if (
                $date['hour'] !== false &&
                $date['minute'] !== false
            ) {
                $ret .= ($ret !== '') ? ' ' : '';
                $ret .= sprintf(
                    '%02s:%02s',
                    $date['hour'],
                    $date['minute']
                );

                $ret .= (isset($date['second']) && 
                            $date['second'] !== false)
                            ? sprintf(':%02s', $date['second'])
                            : '';
            }

        }

        return $ret;
    }

    // }}}
    // {{{ getDateSelectItem

    /**
     * 日付セレクトボックス生成メソッド
     *
     * @param
     * @return
     * @access
     */
    public function getDateSelect($fieldName, $type, $label)
    {
        $ret = array();

        if ($type == 'datetime' || $type == 'date') {

            // 年
            $nameTemp = sprintf('%s_year', $fieldName);
            $ret[$nameTemp] = array(
                'field_type' => 'text',
                'options' => array(
                    'id' => $nameTemp,
                    'prelabel' => $label,
                    'label' => '年',
                    'size' => 4,
                    'length' => 4
                )
            );

            // 月
            $nameTemp = sprintf('%s_month', $fieldName);
            $ret[$nameTemp] = array(
                'field_type' => 'select',
                'options' => array(
                    'id' => $nameTemp,
                    'label' => '月',
                    'options' => array(
                        array(
                            'value' => '',
                            'intext' => '--'
                        )
                    )
                )
            );

            for ($i = 1; $i <= 12; $i++) {
                $ret[$nameTemp]['options']['options'][] = array(
                    'value' => $i,
                    'intext' => sprintf('%02d', $i)
                );
            }

            // 日
            $nameTemp = sprintf('%s_day', $fieldName);
            $ret[$nameTemp] = array(
                'field_type' => 'select',
                'options' => array(
                    'id' => $nameTemp,
                    'label' => '日',
                    'options' => array(
                        array(
                            'value' => '',
                            'intext' => '--'
                        )
                    )
                )
            );

            for ($i = 1; $i <= 31; $i++) {
                $ret[$nameTemp]['options']['options'][] = array(
                    'value' => $i,
                    'intext' => sprintf('%02d', $i)
                );
            }

        }

        if ($type == 'datetime' || $type == 'time') {

            // 時
            $nameTemp = sprintf('%s_hour', $fieldName);
            $ret[$nameTemp] = array(
                'field_type' => 'select',
                'options' => array(
                    'id' => $nameTemp,
                    'label' => '時',
                    'options' => array(
                        array(
                            'value' => '',
                            'intext' => '--'
                        )
                    )
                )
            );

            for ($i = 0; $i <= 23; $i++) {
                $ret[$nameTemp]['options']['options'][] = array(
                    'value' => $i,
                    'intext' => sprintf('%02d', $i)
                );
            }


            // 分
            $nameTemp = sprintf('%s_minute', $fieldName);
            $ret[$nameTemp] = array(
                'field_type' => 'select',
                'options' => array(
                    'id' => $nameTemp,
                    'label' => '分',
                    'options' => array(
                        array(
                            'value' => '',
                            'intext' => '--'
                        )
                    )
                )
            );

            for ($i = 0; $i <= 59; $i++) {
                $ret[$nameTemp]['options']['options'][] = array(
                    'value' => $i,
                    'intext' => sprintf('%02d', $i)
                );
            }

            // 秒
            $nameTemp = sprintf('%s_second', $fieldName);
            $ret[$nameTemp] = array(
                'field_type' => 'select',
                'options' => array(
                    'id' => $nameTemp,
                    'label' => '秒',
                    'options' => array(
                        array(
                            'value' => '',
                            'intext' => '--'
                        )
                    )
                )
            );

            for ($i = 0; $i <= 59; $i++) {
                $ret[$nameTemp]['options']['options'][] = array(
                    'value' => $i,
                    'intext' => sprintf('%02d', $i)
                );
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
