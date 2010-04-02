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
    public function __construct($conf)
    {
        // スーパークラスメソッドコール
        parent::__construct($conf);
    }

    // }}}
    // {{{ condition

    /**
     * WHERE区生成メソッド
     *
     * @param string $search 検索条件
     * @param array $fields 検索対象フィールド
     * @param array $orders ソート対象列
     * @return array WHERE区配列
     */
    public function condition(
        $search = '',
        $fields = array(),
        $orders = array()
    )
    {
        $where = '';
        $binds = array();
        $fields = is_array($fields) ? $fields : array();
        $orders = is_array($orders) ? $orders : array();

        // WHERE句生成
        if (!empty($search) && !empty($fields)) {
            foreach ($fields as $key => $field) {
                if ($key === 0) {
                    $where = sprintf(
                        '%s.%s like :%s',
                        $this->usetable,
                        $field,
                        $field
                    );
                } else {
                    $where .= sprintf(
                        ' OR %s.%s like :%s',
                        $this->usetable,
                        $field,
                        $field
                    );
                }
                $binds[$field] = sprintf('%%%s%%', $search);
            }
            $where .= ' ';
        }

        // Order By句生成
        if (!empty($orders)) {
            $index = 0;
            foreach ($orders as $key => $order) {
                $order = strtoupper($order);
                $order = ($order == 'ASC' || $order == 'DESC')
                            ? ' ' . $order
                            : '';
                if ($index === 0) {
                    $where .= sprintf(
                        'ORDER BY %s.%s%s',
                        $this->usetable,
                        $key,
                        $order
                    );
                } else {
                    $where .= sprintf(
                        ', %s.%s%s',
                        $this->usetable,
                        $key,
                        $order
                    );
                }
            }
        }

        return array('where' => trim($where), 'bind' => $binds);
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
        $search = '',
        $searchFields = array(),
        $orders = array()
    )
    {
        $ret = array();
        $conds = array('where' => null, 'bind' => null);
        $pageNum = is_numeric($pageNum) ? (int)$pageNum : 0;
        $searchFields = is_array($searchFields)
                        ? $searchFields
                        : array();
        $orders = is_array($orders) ? $orders : array();

        // スキーマ取得
        $schemas = $this->schema();

        // フィールド一覧生成
        $fields = array();

        foreach ($schemas as $items) {
             $fields[] = $items['Field'];
        }

        $fields = implode(', ', $fields);

        // WHERE句生成
        $conds = $this->condition($search, $searchFields, $orders);

        // カウントクエリー取得
        $queryCount = $this->count($conds, true);

        // Limit句生成
        if (!is_null($count)) {

            $conds['where'] .= ' ';
            $conds['where'] .= $this->adapter->getQueryLimit(
                $count, $pageNum * $count
            );

            $this->_pageCount = $count;

        }

        // クエリー定義
        $query = implode(
            PHP_EOL,
            array(
                'SELECT',
                '    %s,',
                '    ( %s ) as count',
                'FROM',
                '    %s'
            )
        );

        // レコード取得
        $ret = $this->rowAll(
            array(
                'query' => sprintf(
                    $query,
                    $fields,
                    $queryCount,
                    $this->usetable
                ),
                'where' => $conds['where'],
                'bind' => $conds['bind']
            )
        );

        return $ret;
    }

    // }}}
    // {{{ add

    /**
     * レコード追加メソッド
     *
     * @param array $data 追加データ
     * @return void
     * @access public
     */
    public function add($data)
    {
        $fields = array();
        $values = array();
        $binds = array();

        // フィールド一覧生成
        foreach ( $this->schema() as $schemas ) {
            if ($schemas['Field'] == 'del') {
                $dels = $schemas;
            }
            $fields[] = $schemas[ 'Field' ];
        }

        // 値一覧生成
        foreach ($fields as $field) {
            $values[] = sprintf(':%s', $field);
        }

        // バインド一覧生成
        foreach ($fields as $field) {
            $binds[$field] = array_key_exists($field, $data)
                                    ? $data[$field ]
                                    : null;

        }

        if (array_search('created', $fields) !== false) {

            // 作成日時セット
            $binds['created'] = date('Y-m-d H:i:s');
        }

        if (array_search('modified', $fields) !== false) {

            // 更新日時セット
            $binds['modified'] = date('Y-m-d H:i:s');
        }

        if (array_search('del', $fields) !== false) {
            if (isset($dels['Default'])) {
                $binds['del'] = $dels['Default'];
            }
        }

        // インサート
        $this->insert(
            array('field' => $fields, 'value' => $values, 'bind' => $binds )
        );
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
    // {{{ find

    /**
     * レコード取得メソッド
     *
     * @param int $id 主キー
     * @param string $keyName 主キーのフィールド名
     * @return array レコード配列
     * @access public
     */
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

    // }}}
    // {{{ getInputType

    /**
     * インプットタイプ取得メソッド
     *
     * @param
     * @return
     */
    public function getInputType($schemas)
    {
        $ret = array();

        // インプットタイプの生成
        foreach ($schemas as $fields) {
            $key = $fields['Field'];
            $ret[ $key] = $this->adapter->getType($fields);
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
     * 日付セレクトボックス要素生成メソッド
     *
     * @param
     * @return
     * @access
     */
    public function getDateSelectItem($date, $minYear = 0, $maxYear = 0)
    {
        $ret = array();
        $temp = null;
        $year = 0;

        // セレクトボックス要素生成
        if (!is_null($date['year']) && ($date['year'] !== false)) {

            $year = $date['year'];
            $temp = array();

            $minYear = ($minYear === 0) ? $year - 10 : $minYear;
            $maxYear = ($maxYear === 0) ? date('Y') : $maxYear;

            for ($i = $minYear; $i <= $maxYear; $i++) {
                $temp[] = $i;
            }

            $ret['year'] = $temp;
        }

        if (!is_null($date['month']) && ($date['month'] !== false)) {
        
            $temp = array();

            for ($i = 1; $i < 13; $i++) {
                $temp[] = $i;
            }

            $ret['month'] = $temp;
        }

        if (!is_null($date['day']) && ($date['day'] !== false)) {
            $temp = array();

            for ($i = 1; $i < 32; $i++) {
                $temp[] = $i;
            }

            $ret['day'] = $temp;
        }

        if (isset($data['hour'])) {
            if (!is_null($date['hour']) && ($date['hour'] !== false)) {
                $temp = array();

                for ($i = 0; $i < 24; $i++) {
                    $temp[] = $i;
                }

                $ret['hour'] = $temp;
            }
        }

        if (isset($data['minute'])) {

            if (!is_null($date['minute']) && ($date['minute'] !== false)) {
                $temp = array();

                for ($i = 0; $i < 60; $i++) {
                    $temp[] = $i;
                }

                $ret['minute'] = $temp;
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
