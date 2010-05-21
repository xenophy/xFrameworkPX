<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Model_Behavior_LiveRecord Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Model_Behavior
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: LiveRecord.php 1496 2010-04-01 12:27:22Z tamari $
 */

// {{{ xFrameworkPX_Model_Behavior_LiveRecord

/**
 * xFrameworkPX_Model_Behavior_LiveRecord Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Model_Behavior
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Model_Behavior_LiveRecord
 */
class xFrameworkPX_Model_Behavior_LiveRecord
extends xFrameworkPX_Model_Behavior
{

    // {{{ properties

    // バインド変数名用カウンター
    private $_bindCnt = 0;

    // }}}
    // {{{ getBindKey

    /**
     * バインド変数名取得メソッド
     */
    private function _getBindName()
    {

        $ret = 'bind_' . $this->_bindCnt;
        $this->_bindCnt++;

        return $ret;
    }

    // }}}
    // {{{ bindConnection

    /**
     * connectionバインドメソッド
     *
     * @return PDOオブジェクト
     */
    public function bindConnection()
    {
        return $this->pdo;
    }

    // }}}
    // {{{ bindDriver

    /**
     * driverバインドメソッド
     *
     * @return string PDOドライバー名
     */
    public function bindDriver()
    {
        return $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    }

    // }}}
    // {{{ bindDatabase

    /**
     * databaseバインドメソッド
     *
     * @return string データベース名
     * @access public
     */
    public function bindDatabase()
    {
        return $this->connconfig->{$this->config->conn}->database;
    }

    // }}}
    // {{{ bindSchema

    /**
     * schemaバインドメソッド
     *
     * @return array テーブル情報
     * @access public
     */
    public function bindSchema($filters = array())
    {
        $cacheDir = $this->config->px['CACHE_DIR'];
        $schemaFile = $cacheDir . DS . $this->usetable . '.schema';

        // スキーマキャッシュ生成
        if (!file_exists($schemaFile)) {

            // デバッグ用計測開始
            if ($this->module->conf['px']['DEBUG'] >= 2) {
                $startTime = microtime(true);
            }

            $result = $this->adapter->getSchema($this->pdo, $this->usetable);

            $query = $result['query'];
            $result = $result['result'];

            // デバッグ情報追加
            if ($this->module->conf['px']['DEBUG'] >= 2) {
                $traceQuery = $query;
                xFrameworkPX_Debug::getInstance()->addQuery(
                    $this->usetable,
                    get_class($this->module),
                    $traceQuery,
                    count($result),
                    microtime(true) - $startTime
                );
            }

            file_put_contents(
                $schemaFile,
                xFrameworkPX_Util_Serializer::serialize($result)
            );

        } else {

            // スキーマキャッシュ読み込み
            $result = xFrameworkPX_Util_Serializer::unserialize(
                file_get_contents($schemaFile)
            );
        }

        // フィルター登録されているフィールドを削除
        $schemas = array();
        foreach ( $result as $fields ) {
            if (!in_array($fields['Field'], $filters)) {
                $schemas[] = $fields;
            }
        }

        return $schemas;
    }
    // }}}
    // {{{ bindGetTableInfo
    
    public function bindGetTableInfo()
    {
        // クエリー生成
        $query = $this->adapter->getQueryTableInfo(
            $this->bindDatabase(), $this->usetable
        );

        // PDOStatement取得
        $stmt = @$this->pdo->prepare($query);

        // デバッグ用計測開始
        if ($this->module->conf['px']['DEBUG'] >= 2) {
            $startTime = microtime(true);
        }

        // クエリー実行
        $stmt->execute();

        // 単行取得
        $result = $stmt->fetchAll(PDO::FETCH_NAMED);

        // デバッグ情報追加

        if ($this->module->conf['px']['DEBUG'] >= 2) {
            $traceQuery = $query;
            xFrameworkPX_Debug::getInstance()->addQuery(
                $this->module->useTable,
                get_class($this->module),
                $traceQuery,
                count($result),
                microtime(true) - $startTime
            );
        }

        // カーソルを閉じてステートメントを再実行できるようにする
        $stmt->closeCursor();

        // PDOStatement破棄
        unset($stmt);

        return $result[0];

    }
    
    // }}}
    // {{{ bindExec

    /**
     * execバインドメソッド
     *
     * @param array $options オプション
     * @param bool $onlyQuery
     * @return array
     */
    public function bindExec($options, $onlyQuery = false)
    {
        // クエリー生成
        $query = isset($options['query']) ? $options['query'] : null;

        if ($onlyQuery === true) {
            $ret = $query;
        } else {

            // MySQL対応
            if (strtolower($this->bindDriver()) == 'mysql') {
                $this->pdo->setAttribute(
                    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,
                    true
                );
            }

            // デバッグ用計測開始
            if ($this->module->conf['px']['DEBUG'] >= 2) {
                $startTime = microtime(true);
            }

            // クエリ実行
            $ret = $this->pdo->exec($query);

            // デバッグ情報追加
            if ($this->module->conf['px']['DEBUG'] >= 2) {
                $traceQuery = $query;
                xFrameworkPX_Debug::getInstance()->addQuery(
                    $this->module->usetable,
                    get_class($this->module),
                    $traceQuery,
                    $ret,
                    microtime(true) - $startTime
                );
            }

        }

        return $ret;
    }

    // }}}
    // {{{ bindQuery

    /**
     * queryバインドメソッド
     *
     * @param array $options オプション
     * @param bool $onlyQuery
     * @return array
     */
    public function bindQuery($options, $onlyQuery = false)
    {
        // クエリー生成
        $query = isset($options['query']) ? $options['query'] : null;

        if ($onlyQuery === true) {
            $ret = $query;
        } else {

            // MySQL対応
            if (strtolower($this->bindDriver()) == 'mysql') {
                $this->pdo->setAttribute(
                    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,
                    true
                );
            }

            // デバッグ用計測開始
            if ($this->module->conf['px']['DEBUG'] >= 2) {
                $startTime = microtime(true);
            }

            // クエリ実行
            $ret = $this->pdo->query($query);

            // デバッグ情報追加
            if ($this->module->conf['px']['DEBUG'] >= 2) {
                $traceQuery = $query;
                $count = '';
                if (get_class($ret) === 'PDOStatement') {
                    $count = count($ret->fetchAll());
                }

                xFrameworkPX_Debug::getInstance()->addQuery(
                    $this->module->usetable,
                    get_class($this->module),
                    $traceQuery,
                    $count,
                    microtime(true) - $startTime
                );
            }
        }

        return $ret;
    }

    // }}}
    // {{{ bindExecute

    /**
     * executeバインドメソッド
     *
     * @param array $options オプション
     * @param bool $onlyQuery
     * @return array
     * @access public
     */
    public function bindExecute($options, $onlyQuery = false)
    {
        $query   = isset($options['query']) ? $options['query'] : null;
        $binds    = isset($options['bind']) ? $options['bind'] : array();
        $fetch     = isset($options['fetch'])
                      ? $options['fetch']
                      : $this->module->fetchmode;

        if ($onlyQuery === true) {
            $ret = $query;
        } else {

            // PDOStatement取得
            $stmt = @$this->pdo->prepare($query);

            // デバッグ用計測開始
            if ($this->module->conf['px']['DEBUG'] >= 2) {

                $startTime = microtime(true);

                // 最終実行クエリ設定
                xFrameworkPX_Debug::getInstance()->setLastQuery($query);

                // 最終バインド設定
                xFrameworkPX_Debug::getInstance()->setLastBinds($binds);
            }

            // クエリー実行
            $ret = $stmt->execute($binds);

            // デバッグ情報追加
            if ($this->module->conf['px']['DEBUG'] >= 2) {
                $traceQuery = $query;
                if (is_array($binds)) {
                    krsort($binds);
                    foreach ($binds as $key => $value) {
                        if (!is_numeric($value)) {
                            $value = "'" . $value . "'";
                        } else {
                            $value = $value;
                        }
                        $traceQuery = str_replace(
                            ':' . $key,
                            $value,
                            $traceQuery
                        );
                    }
                }

                xFrameworkPX_Debug::getInstance()->addQuery(
                    $this->module->usetable,
                    get_class($this->module),
                    $traceQuery,
                    count($ret),
                    microtime(true) - $startTime
                );
            }

            // カーソルを閉じてステートメントを再実行できるようにする
            $this->beforestmt = $stmt;

            // PDOStatement破棄
            unset($stmt);
        }

        return $ret;
    }

    // }}}
    // {{{ bindBeginTrans

    /**
     * beginTransバインドメソッド
     *
     * @return bool true:成功 false:失敗
     */
    public function bindBeginTrans()
    {
        return $this->pdo->beginTransaction();
    }

    // }}}
    // {{{ bindCommit

    /**
     * commitバインドメソッド
     *
     * @return bool true:成功 false:失敗
     */
    public function bindCommit()
    {
        return $this->pdo->commit();
    }

    // }}}
    // {{{ bindRollback

    /**
     * rollbackバインドメソッド
     *
     * @return bool true:成功 false:失敗
     */
    public function bindRollback()
    {
        return $this->pdo->rollback();
    }

    // }}}
    // {{{ bindInsert

    /**
     * insertバインドメソッド
     *
     * @param array $options
     * @param bool $onlyQuery
     * @return array
     */
    public function bindInsert($options, $onlyQuery = false)
    {
        $fields   = isset($options['field'])
                      ? $options['field']
                      : array();
        $values   = isset($options['value'])
                      ? $options['value']
                      : array();
        $binds    = isset($options['bind'])
                      ? $options['bind']
                      : array();
        $fetch     = isset($options['fetch'])
                      ? $options['fetch']
                      : $this->module->fetchmode;

        // クエリー生成
        $query = sprintf(
            'INSERT INTO %s ( %s ) VALUES ( %s )',
            $this->usetable,
            implode(',', $fields),
            implode(',', $values)
        );

        if ($onlyQuery === true) {
            $ret = $query;
        } else {

            // クエリ実行
            $ret = $this->bindExecute(
                array(
                    'query'=> $query,
                    'bind'=> $binds,
                    'fetch'=> $fetch
                )
            );
        }

        return $ret;
    }

    // }}}
    // {{{ bindUpdate

    /**
     * updateバインドメソッド
     *
     * @param array $options オプション
     * @param bool $onlyQuery
     * @return array
     * @access public
     */
    public function bindUpdate($options, $onlyQuery = false)
    {
        $clauses        = '/^WHERE\\x20|^GROUP\\x20BY\\x20|^HAVING\\x20|^ORDER\\x20BY\\x20/i';
        $clause         = ' WHERE ';
        $fields         = isset($options['field'])
                          ? $options['field']
                          : array();
        $values         = isset($options['value'])
                          ? $options['value']
                          : array();
        $binds          = isset($options['bind'])
                          ? $options['bind']
                          : array();
        $whereClause    = isset($options['where'])
                          ? $options['where']
                          : '';

        // WHERE句が配列の場合join
        $whereClause = $this->_getWhereClause($whereClause);

        // WHERE句が宣言されている場合と空の場合は、'WHERE'を付加しない
        if (
            (is_string($whereClause) &&
            preg_match($clauses, $whereClause, $matches)) ||
            $whereClause == ''
        ) {
            $clause = "\x20";
        }

        // SET節生成
        $set = urldecode(
            http_build_query(
                array_combine($fields, $values),
                '',
                ', '
            )
        );

        // クエリー生成
        $query = sprintf(
            'UPDATE %s SET %s %s',
            $this->usetable,
            $set,
            $clause . $whereClause
        );

        if ($onlyQuery === true) {
            $ret = $query;
        } else {

            // クエリ実行
            $ret = $this->bindExecute(
                array(
                    'query'=> $query,
                    'bind'=> $binds
                )
            );
        }

        return $ret;
    }

    // }}}
    // {{{ bindDelete

    /**
     * deleteバインドメソッド
     *
     * @param array $options オプション
     * @param bool $onlyQuery
     * @return array
     * @access public
     */
    public function bindDelete($options, $onlyQuery = false)
    {
        // ローカル変数初期化
        $clauses = '/^WHERE\\x20|^GROUP\\x20BY\\x20|^HAVING\\x20|^ORDER\\x20BY\\x20/i';
        $clause = ' WHERE ';
        $whereClause     = isset($options['where'])
                              ? $options['where']
                              : '';
        $binds            = isset($options['bind'])
                              ? $options['bind']
                              : array();

        // WHERE句が配列の場合join
        $whereClause = $this->_getWhereClause($whereClause);

        // WHERE句が宣言されている場合と空の場合は、'WHERE'を付加しない
        if (
            (is_string($whereClause) &&
            preg_match($clauses, $whereClause, $matches)) ||
            $whereClause == ''
        ) {
            $clause = "\x20";
        }

        // クエリー生成
        $query = implode(
            '',
            array(
                ' DELETE FROM ' . $this->usetable,
                $clause . $whereClause
            )
        );

        if ($onlyQuery === true) {
            $ret = $query;
        } else {

            // クエリ実行
            $ret = $this->bindExecute(
                array(
                    'query'=> $query,
                    'bind'=> $binds
                )
            );
        }

        return $ret;
    }

    // }}}
    // {{{ bindTruncate

    /**
     * truncateバインドメソッド
     *
     * @param bool $onlyQuery
     * @return void
     * @access public
     */
    public function bindTruncate($onlyQuery = false)
    {

        try {
            $query = sprintf(
                $this->module->adapter->getTruncateQuery(),
                $this->module->usetable
            );

            if ($onlyQuery) {
                return $query;
            }

            // テーブルトランケート
            $this->bindExec(array('query' => $query));

        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }

    }

    // }}}
    // {{{ bindLastId

    /**
     * lastId取得メソッド
     *
     * @param array $options オプション
     * @return mix
     * @access public
     */
    public function bindLastId()
    {
        // LAST_INSERT_IDクエリ取得
        $query = $this->module->adapter->getQueryLastId(
            $this->module->usetable, $this->module->primaryKey
        );
        $stmt = @$this->pdo->prepare($query);

        // デバッグ用計測開始
        if ($this->module->conf['px']['DEBUG'] >= 2) {
            $startTime = microtime(true);
        }

        // クエリー実行
        $stmt->execute(array());

        // 単行取得
        $ret = $stmt->fetch(PDO::FETCH_ASSOC);

        // デバッグ情報追加
        if ($this->module->conf['px']['DEBUG'] >= 2) {
            $traceQuery = $query;
            xFrameworkPX_Debug::getInstance()->addQuery(
                $this->module->usetable,
                get_class($this->module),
                $traceQuery,
                count($ret),
                microtime(true) - $startTime
            );
        }
        // カーソルを閉じてステートメントを再実行できるようにする
        $stmt->closeCursor();

        // PDOStatement破棄
        unset($stmt);

        return isset($ret['last_id'])? $ret['last_id'] : null;

    }

    // }}}
    // {{{ bindRow

    /**
     * rowバインドメソッド
     *
     * @param array $options オプション
     * @param bool $onlyQuery
     * @return array
     * @access public
     */
    public function bindRow($options, $onlyQuery = false)
    {
        $clauses = '/^WHERE\\x20|^GROUP\\x20BY\\x20|^HAVING\\x20|^ORDER\\x20BY\\x20/i';
        $clause = ' WHERE ';

        $query   = isset($options['query'])
                      ? $options['query']
                      : null;
        $binds    = isset($options['bind'])
                      ? $options['bind']
                      : array();
        $fetch     = isset($options['fetch'])
                      ? $options['fetch']
                      : $this->module->fetchmode;
        $whereClause = isset($options['where'])
                          ? $options['where']
                          : '';
        $groupClause = isset($options['group'])
                              ? $options['group']
                              : '';
        $havingClause = isset($options['having'])
                              ? $options['having']
                              : '';
        $orderClause     = isset($options['order'])
                              ? $options['order']
                              : '';

        // WHERE句が配列の場合join
        $whereClause = $this->_getWhereClause($whereClause);

        // WHERE句が宣言されている場合と空の場合は、'WHERE'を付加しない
        if (
            (is_string($whereClause) &&
            preg_match($clauses, $whereClause, $matches)) ||
            $whereClause == ''
        ) {
            $clause = "\x20";
        }

        // WHERE節付加
        if (!empty($whereClause)) {
            $query .= $clause . $whereClause;
        }

        // GROUP BY節付加
        if (
            (is_string($groupClause) &&
            preg_match($clauses, $groupClause, $matches)) ||
            $groupClause === ''
        ) {
            $query .= ' ' . $groupClause;
        } else {
            $query .= ' GROUP BY ' . $groupClause;
        }

        // HAVING句の指定が配列の場合文字列に変換
        $havingClause = $this->_getWhereClause($havingClause);

        // HAVING節付加
        if (
            (is_string($havingClause) &&
            preg_match($clauses, $havingClause, $matches)) ||
            $havingClause === ''
        ) {
            $query .= ' ' . $havingClause;
        } else {
            $query .= ' HAVING ' . $havingClause;
        }

        // ORDER BY節付加
        if (
            (is_string($orderClause) &&
            preg_match($clauses, $orderClause, $matches)) ||
            $orderClause === ''
        ) {
            $query .= ' ' . $orderClause;
        } else {
            $query .= ' ORDER BY ' . $orderClause;
        }

        // LIMIT節付加
        $query = rtrim($query);

        // LIMIT節チェック
        if (preg_match(
            '/limit\x20+[0-9]+\x20*(?:(?:,|\x20offset\x20)\x20*[0-9]+\x20*)?$/i',
            $query
        )) {
            throw new xFrameworkPX_Model_Exception(
                'LIMITは自動的に設定されるため、設定できません。'
            );
        }

        if (endsWith($query,';')) {
            $query = substr($query, 0, strlen($query) - 1);
        }

        // LIMIT句付加
        $query = $this->module->adapter->addQueryLimit($query, 1, 0);

        // クエリー取得の場合は、ここで終了
        if ($onlyQuery === true) {
            return $query;
        }

        // PDOStatement取得
        $stmt = @$this->pdo->prepare($query);

        // デバッグ用計測開始
        if ($this->module->conf['px']['DEBUG'] >= 2) {
            $startTime = microtime(true);
        }

        // クエリー実行
        $stmt->execute($binds);

        // 単行取得
        $result = $stmt->fetch($fetch);

        if ($result) {

            if ($this->module->adapter->getRdbmsName() == 'oracle') {
                array_pop($result);
            }

        }

        // デバッグ情報追加
        if ($this->module->conf['px']['DEBUG'] >= 2) {

            $traceQuery = $query;
            if (is_array($binds)) {
                krsort($binds);
                foreach ($binds as $key => $value) {
                    if (!is_numeric($value)) {
                        $value = "'" . $value . "'";
                    } else {
                        $value = $value;
                    }

                    if (!startsWith($key, ':')) {
                        $key = ':' . $key;
                    }

                    $traceQuery = str_replace(
                        $key,
                        $value,
                        $traceQuery
                    );
                }
            }

            xFrameworkPX_Debug::getInstance()->addQuery(
                $this->module->usetable,
                get_class($this->module),
                $traceQuery,
                count($result),
                microtime(true) - $startTime
            );
        }

        // カーソルを閉じてステートメントを再実行できるようにする
        $stmt->closeCursor();

        // PDOStatement破棄
        unset($stmt);

        return $result;

    }

    // }}}
    // {{{ bindRowAll

    /**
     * rowAllバインドメソッド
     *
     * @param array $options オプション
     * @param bool $onlyQuery
     * @return array
     * @access public
     */
    public function bindRowAll($options, $onlyQuery = false)
    {
        $clauses = '/^WHERE\\x20|^GROUP\\x20BY\\x20|^HAVING\\x20|^ORDER\\x20BY\\x20/i';
        $clause = ' WHERE ';

        $query   = isset($options['query'])
                      ? $options['query']
                      : null;
        $binds    = isset($options['bind'])
                      ? $options['bind']
                      : array();
        $fetch     = isset($options['fetch'])
                      ? $options['fetch']
                      : $this->module->fetchmode;
        $whereClause     = isset($options['where'])
                              ? $options['where']
                              : '';
        $groupClause = isset($options['group'])
                              ? $options['group']
                              : '';
        $havingClause = isset($options['having'])
                              ? $options['having']
                              : '';
        $orderClause     = isset($options['order'])
                              ? $options['order']
                              : '';
        $limitClause     = isset($options['limit'])
                              ? $options['limit']
                              : null;
        $pageClause     = isset($options['page'])
                              ? $options['page']
                              : 0;

        // WHERE句が配列の場合join
        $whereClause = $this->_getWhereClause($whereClause);

        // WHERE句が宣言されている場合と空の場合は、'WHERE'を付加しない
        if (
            (is_string($whereClause) &&
            preg_match($clauses, $whereClause, $matches)) ||
            $whereClause == ''
        ) {
            $clause = "\x20";
        }

        // GROUP BY節付加
        if (
            (is_string($groupClause) &&
            preg_match($clauses, $groupClause, $matches)) ||
            $groupClause === ''
        ) {
            $whereClause .= ' ' . $groupClause;
        } else {
            $whereClause .= ' GROUP BY ' . $groupClause;
        }

        // HAVING句の指定が配列の場合文字列に変換
        $havingClause = $this->_getWhereClause($havingClause);

        // HAVING節付加
        if (
            (is_string($havingClause) &&
            preg_match($clauses, $havingClause, $matches)) ||
            $havingClause === ''
        ) {
            $whereClause .= ' ' . $havingClause;
        } else {
            $whereClause .= ' HAVING ' . $havingClause;
        }

        // ORDER BY節付加
        if (
            (is_string($orderClause) &&
            preg_match($clauses, $orderClause, $matches)) ||
            $orderClause === ''
        ) {
            $whereClause .= ' ' . $orderClause;
        } else {
            $whereClause .= ' ORDER BY ' . $orderClause;
        }

        // query生成
        if (!empty($whereClause)) {
            $query = $query . $clause . $whereClause;
        }

        // LIMIT句付加
        if (!is_null($limitClause) && is_numeric($limitClause)) {
            $limitClause = intval($limitClause);
            $query = $this->module->adapter->addQueryLimit(
                $query,
                $limitClause,
                $pageClause * $limitClause
            );
        }

        if ($onlyQuery === true) {
            return query;
        } else {

            // 最終実行クエリ設定
            xFrameworkPX_Debug::getInstance()->setLastQuery($query);

            // PDOStatement取得
            $stmt = @$this->pdo->prepare($query);
        }

        // 最終バインド設定
        xFrameworkPX_Debug::getInstance()->setLastBinds($binds);

        // デバッグ用計測開始
        if ($this->module->conf['px']['DEBUG'] >= 2) {
            $startTime = microtime(true);
        }

        // クエリー実行
        $stmt->execute($binds);

        // 行取得
        $result = $stmt->fetchAll($fetch);

        if ($result) {

            if (!is_null($limitClause) && is_numeric($limitClause)) {

                if ($this->module->adapter->getRdbmsName() == 'oracle') {

                    foreach ($result as $key => $line) {
                        array_pop($line);
                        $result[$key] = $line;
                    }

                }

            }

        }

        // デバッグ情報追加
        if ($this->module->conf['px']['DEBUG'] >= 2) {

            $traceQuery = $query;
            if (is_array($binds)) {
                krsort($binds);
                foreach ($binds as $key => $value) {
                    if (!is_numeric($value)) {
                        $value = "'" . $value . "'";
                    } else {
                        $value = $value;
                    }

                    if (!startsWith($key, ':')) {
                        $key = ':' . $key;
                    }

                    $traceQuery = str_replace(
                        $key,
                        $value,
                        $traceQuery
                    );
                }
            }

            xFrameworkPX_Debug::getInstance()->addQuery(
                $this->module->usetable,
                get_class($this->module),
                $traceQuery,
                count($result),
                microtime(true) - $startTime
            );
        }

        // カーソルを閉じてステートメントを再実行できるようにする
        $stmt->closeCursor();

        // PDOStatement破棄
        unset($stmt);

        return $result;
    }

    // }}}
    // {{{ bindCount

    /**
     * countバインドメソッド
     *
     * @param array $options オプション
     * @param bool $onlyQuery
     * @return array
     * @access public
     */
    public function bindCount($options = array(), $onlyQuery = false)
    {
        $clauses = '/^WHERE\\x20|^GROUP\\x20BY\\x20|^HAVING\\x20|^ORDER\\x20BY\\x20/i';
        $clause = ' WHERE ';

        $selectClause    = isset($options['select'])
                              ? $options['select']
                              : 'COUNT(*)';
        $whereClause     = isset($options['where'])
                              ? $options['where']
                              : '';
        $binds            = isset($options['bind'])
                              ? $options['bind']
                              : array();
        $lf                = isset($options['lf'])
                              ? $options['lf']
                              : false;

        // SELECT句にAS句追加
        if (is_string($selectClause)) {

            if (!preg_match('/(AS|as) CNT$/', $selectClause)) {
                $selectClause .= 'AS "cnt"';
            }

        } else {
            $selectClause = 'COUNT(*) AS "cnt"';
        }


        // WHERE句が配列の場合join
        if (is_array($whereClause) && !empty($whereClause)) {
            $whereClause = implode(' AND ', $whereClause);
        }

        // WHERE句が宣言されている場合と空の場合は、'WHERE'を付加しない
        if (
            (is_string($whereClause) &&
            preg_match($clauses, $whereClause, $matches)) ||
            $whereClause == ''
        ) {

            $clause = "\x20";
        }

        // クエリー生成
        $query = implode(
            ($lf ? "\n" : ''),
            array(
                ' SELECT ' . $selectClause,
                ' FROM ' . $this->usetable,
                $clause . $whereClause
            )
        );

        // 件数取得
        $result = $this->bindRow(
            array('query'=> $query, 'bind'=> $binds),
            $onlyQuery
        );

        // onlyqueryがtrueの場合はクエリーを返す
        if (is_string($result)) {
            return $result;
        }

        return intval($result['cnt']);

    }

    // }}}
    // {{{ bindGet

    /**
     * getバインドメソッド
     *
     * @param string $type 取得タイプ
     * @return array
     * @access public
     */
    public function bindGet($type = 'all', $config = array())
    {
        $this->_bindCnt = 0;
        $where = null;
        $binds = null;
        $group = '';
        $having = '';
        $order = '';
        $limit = null;
        $page = 0;
        $fields = '*';

        // 取得タイプ取得
        $type = $this->_getBindGetType($type);

        // 検索条件取得
        if (isset($config['conditions']) && is_array($config['conditions'])) {
            $temp = $this->_getConditions($config['conditions']);
            $where = $temp['where'];
            $binds = $temp['bind'];
        }

        // GROUP BY句取得
        if (isset($config['group']) && is_array($config['group'])) {
            $group = 'GROUP BY ';
            $group .= implode(', ', $config['group']);
        }

        // HAVING句取得
        if (isset($config['having']) && is_array($config['having'])) {
            $temp = $this->_getHaving($config['having'], $binds);
            $having = $temp['having'];
            $binds = $temp['bind'];
        }

        // ORDER BY句取得
        if (isset($config['order']) && is_array($config['order'])) {
            $order = 'ORDER BY ';
            $order .= implode(', ', $config['order']);
        }

        // LIMIT句取得
        if (isset($config['limit']) && is_numeric($config['limit'])) {
            $limit = intval($config['limit']);
        }
        if (isset($config['page']) && is_numeric($config['page'])) {
            $page = intval($config['page']);
        }

        // 取得フィールド設定
        if (isset($config['fields']) && is_array($config['fields'])) {
            $fields = implode(',', $config['fields']);
        }

        if ($type == 'count') {

            if ($fields == '*' || count($config['fields']) <= 0) {
                $fields = 'COUNT(*) AS "cnt"';
            } else {
                $fields = sprintf('COUNT(%s) AS "cnt"', $config['fields'][0]);
            }

        }

        // アソシエーション別クエリ取得
        switch ($this->_getAssociationType()) {

            case 'hasOne':
                $query = $this->_getHasOneQuery($fields);
                break;

            case 'belongsTo':
                $query = $this->_getBelongsToQuery($fields);
                break;

            case 'hasMany':
                $temp = $this->_getHasManyQuery(
                    $type === 'first',
                    $order
                );
                $query = $temp['query'];
                $where = $temp['where'];
                $src = $temp['src'];
                $srctable = $temp['srctable'];
                $targettable = $temp['targettable'];
                $targetfield = $temp['targetfield'];
                $primaryKey = $temp['primaryKey'];
                $order = $temp['order'];
                break;

            default:

                // クエリー生成
                $query = implode(
                    PHP_EOL,
                    array(
                        'SELECT',
                        '    %2$s',
                        'FROM',
                        '    %1$s'
                    )
                );

                // クエリ合成
                $query = sprintf($query, $this->usetable, $fields);
                break;

        }

        switch ($type) {

            case 'first':

                if ($this->_getAssociationType() === 'hasMany') {

                    $ret = $this->bindRow(
                        array(
                            'query' => $query,
                            'where' => $where,
                            'bind' => $binds,
                            'group' => $group,
                            'having' => $having,
                            'order' => $order,
                            'limit' => $limit,
                            'page' => $page
                        )
                    );

                    $temp = $ret;
                    $ret = array();
                    foreach ($src as $value) {
                        $ret = array(
                            $srctable => $value,
                            $targettable => $temp
                        );
                    }

                } else {
                    $ret = $this->bindRow(
                        array(
                            'query' => $query,
                            'where' => $where,
                            'bind' => $binds,
                            'group' => $group,
                            'having' => $having,
                            'order' => $order,
                            'limit' => $limit,
                            'page' => $page
                        )
                    );
                }

                break;

            case 'all':
                $ret = $this->bindRowAll(
                    array(
                        'query' => $query,
                        'where' => $where,
                        'bind' => $binds,
                        'group' => $group,
                        'having' => $having,
                        'order' => $order,
                        'limit' => $limit,
                        'page' => $page
                    )
                );

                if ($this->_getAssociationType() === 'hasMany') {
                    $temp = $ret;
                    $ret = array();

                    foreach ($src as $value) {

                        $targetvalue = array();
                        foreach ($temp as $tvalue) {
                            if (
                                $value[$primaryKey] == $tvalue[$targetfield]
                            ) {
                                $targetvalue[] = $tvalue;
                            }
                        }

                        $ret[] = array(
                            $srctable => $value,
                            $targettable => $targetvalue
                        );
                    }
                }

                break;

            case 'list':
                break;

            case 'count':
                $temp = $this->bindRow(
                    array(
                        'query' => $query,
                        'where' => $where,
                        'bind' => $binds,
                        'group' => $group,
                        'having' => $having,
                        'order' => $order
                    )
                );
                $ret = ($temp) ? intval($temp['cnt']) : 0;
                break;
        }

        return $ret;
    }

    // }}}
    // {{{ bindGetJoinQuery

    public function bindGetJoinQuery()
    {
        $joinQuery = null;

        if (
            is_string($this->module->hasOne)
        ) {

        } else if (
            is_array($this->module->hasOne) &&
            count($this->module->hasOne) > 0
        ) {

            $joinQuery = '';
            foreach ($this->module->hasOne as $key => $value) {

                if (is_numeric($key)) {
                    $key = $value;
                    $value = array();
                }

            }

            if (isset($this->module->modules[$key])) {
                $joinModule = $this->module->modules[$key];
                $joinType = isset($value['type']) ? $value['type'] : null;
                $joinTable = $joinModule->usetable;
                $joinTableCore = $joinModule->getTableName(true);
                $joinPrimaryKey = $joinModule->primaryKey;
            } else {
                $joinType = isset($value['type']) ? $value['type'] : null;
                $joinTable = $key;
                $joinTableCore = end(explode('_', $joinTable));

                if (!isset($value['primaryKey'])) {
                    $joinPrimaryKey = 'id';
                } else {
                    $joinPrimaryKey = $value['primaryKey'];
                }
            }

            if (!isset($value['foreignKey'])) {
                $joinForeignKey = $joinTableCore . '_' . $this->primaryKey;
            } else {
                $joinForeignKey = $value['foreignKey'];
            }
            $joinQuery  = '';
            $joinQuery .= PHP_EOL;
            $joinQuery .= $this->_createJoinQuery(
                $joinType,
                $joinTable,
                sprintf(
                    '%2$s.%3$s = %1$s.%4$s',
                    $joinTable,
                    $this->usetable,
                    $joinForeignKey,
                    $joinPrimaryKey
                )
            );
        }

        return $joinQuery;
    }

    // }}}
    // {{{ _getAssociationType

    private function _getAssociationType()
    {
        $type = null;

        if (
            is_string($this->module->hasOne) ||
            (
                is_array($this->module->hasOne) &&
                count($this->module->hasOne) > 0
            )
        ) {

            $type = 'hasOne';

        } else if(
            is_string($this->module->belongsTo) ||
            (
                is_array($this->module->belongsTo) &&
                count($this->module->belongsTo) > 0
            )
        ) {

            $type = 'belongsTo';

        } else if(
            is_string($this->module->hasMany) ||
            (
                is_array($this->module->hasMany) &&
                count($this->module->hasMany) > 0
            )
        ) {

            $type = 'hasMany';

        }

        return $type;
    }

    // }}}
    // {{{ _getHasOneQuery

    private function _getHasOneQuery($fields)
    {

        if(is_string($this->module->hasOne)) {

            $joinType = 'INNER';
            $moduleKey = $this->module->hasOne;

            if (isset($this->module->modules[$moduleKey])) {

                $joinModule = $this->module->modules[$moduleKey];
                $joinTable = $joinModule->usetable;
                $joinTableCore = $joinModule->getTableName(true);
                $joinPrimaryKey = $joinModule->primaryKey;

            } else {

                $joinTable = $this->module->hasOne;
                $joinTableCore = end(explode('_', $joinTable));
                $joinPrimaryKey = 'id';

            }

            $leftPart = '%2$s.%3$s';
            $rightPart = '%1$s.%4$s';

            if (matchesIn($joinPrimaryKey,'.')) {
                $rightPart = '%4$s';
            }
            $joinQuery = '';
            $joinQuery .= PHP_EOL;
            $joinQuery = $this->_createJoinQuery(
                $joinType,
                $joinTable,
                sprintf(
                    $leftPart . ' = ' . $rightPart,
                    $joinTable,
                    $this->usetable,
                    $joinTableCore . '_' . $this->primaryKey,
                    $joinPrimaryKey
                )
            );

            // クエリー生成
            $query = implode(
                PHP_EOL,
                array(
                    'SELECT',
                    '    %2$s',
                    'FROM',
                    '    %1$s',
                    '%3$s'
                )
            );

            // クエリ合成
            $query = sprintf($query, $this->usetable, $fields, $joinQuery);

        } else {

            $joinQuery = '';
            foreach ($this->module->hasOne as $key => $value) {

                if (is_numeric($key)) {
                    $key = $value;
                    $value = array();
                }

                if (isset($this->module->modules[$key])) {
                    $joinModule = $this->module->modules[$key];
                    $joinType = isset($value['type']) ? $value['type'] : null;
                    $joinTable = $joinModule->usetable;
                    $joinTableCore = $joinModule->getTableName(true);
                    $joinPrimaryKey = $joinModule->primaryKey;
                } else {
                    $joinType = isset($value['type']) ? $value['type'] : null;
                    $joinTable = $key;
                    $joinTableCore = end(explode('_', $joinTable));

                    if (!isset($value['primaryKey'])) {
                        $joinPrimaryKey = 'id';
                    } else {
                        $joinPrimaryKey = $value['primaryKey'];
                    }
                }

                if (!isset($value['foreignKey'])) {
                    $joinForeignKey = $joinTableCore . '_' . $this->primaryKey;
                } else {
                    $joinForeignKey = $value['foreignKey'];
                }

                $leftPart = '%2$s.%3$s';
                $rightPart = '%1$s.%4$s';

                if (matchesIn($joinForeignKey,'.')) {
                    $leftPart = '%3$s';
                }
                if (matchesIn($joinPrimaryKey,'.')) {
                    $rightPart = '%4$s';
                }

                $joinQuery .= PHP_EOL;
                $joinQuery .= $this->_createJoinQuery(
                    $joinType,
                    $joinTable,
                    sprintf(
                        $leftPart . ' = ' . $rightPart,
                        $joinTable,
                        $this->usetable,
                        $joinForeignKey,
                        $joinPrimaryKey
                    )
                );
            }

            // クエリー生成
            $query = implode(
                PHP_EOL,
                array(
                    'SELECT',
                    '    %2$s',
                    'FROM',
                    '    %1$s',
                    '%3$s'
                )
            );

            // クエリ合成
            $query = sprintf($query, $this->usetable, $fields, $joinQuery);

        }

        return $query;

    }

    // }}}
    // {{{ _getBelongsToQuery

    private function _getBelongsToQuery($fields)
    {
        if(is_string($this->module->belongsTo)) {

            $joinType = 'INNER';
            $moduleKey = $this->module->belongsTo;

            if (isset($this->module->modules[$moduleKey])) {
                $joinModule = $this->module->modules[$moduleKey];
                $joinTable = $joinModule->usetable;
                $joinTableCore = $joinModule->getTableName(true);
                $joinPrimaryKey = $joinModule->primaryKey;
            } else {
                $joinTable = $this->module->belongsTo;
                $joinTableCore = end(explode('_', $joinTable));
                $joinPrimaryKey = 'id';
            }

            $useTableCode = end(explode('_', $this->usetable));

            $leftPart = '%2$s.%4$s';
            $rightPart = '%1$s.%3$s';

            if (matchesIn($joinPrimaryKey,'.')) {
                $rightPart = '%4$s';
            }
            $joinQuery = '';
            $joinQuery .= PHP_EOL;
            $joinQuery = $this->_createJoinQuery(
                $joinType,
                $joinTable,
                sprintf(
                    $leftPart . ' = ' . $rightPart,
                    $joinTable,
                    $this->usetable,
                    $useTableCode . '_' . $this->primaryKey,
                    $joinPrimaryKey
                )
            );
            // クエリー生成
            $query = implode(
                PHP_EOL,
                array(
                    'SELECT',
                    '    %2$s',
                    'FROM',
                    '    %1$s',
                    '%3$s'
                )
            );

            // クエリ合成
            $query = sprintf($query, $this->usetable, $fields, $joinQuery);

        } else {

            $joinQuery = '';
            foreach ($this->module->belongsTo as $key => $value) {

                if (is_numeric($key)) {
                    $key = $value;
                    $value = array();
                }

                if (isset($this->module->modules[$key])) {
                    $joinModule = $this->module->modules[$key];
                    $joinType = isset($value['type']) ? $value['type'] : null;
                    $joinTable = $joinModule->usetable;
                    $joinTableCore = $joinModule->getTableName(true);
                    $joinPrimaryKey = $joinModule->primaryKey;
                } else {
                    $joinType = isset($value['type']) ? $value['type'] : null;
                    $joinTable = $key;
                    $joinTableCore = end(explode('_', $joinTable));

                    if (!isset($value['foreignKey'])) {
                        $joinPrimaryKey = 'id';
                    } else {
                        $joinPrimaryKey = $value['foreignKey'];
                    }
                }

                $useTableCode = end(explode('_', $this->usetable));

                if (!isset($value['primaryKey'])) {
                    $joinForeignKey = $useTableCode . '_' . $joinPrimaryKey;
                } else {
                    $joinForeignKey = $value['primaryKey'];
                }

                $leftPart = '%2$s.%4$s';
                $rightPart = '%1$s.%3$s';

                if (matchesIn($joinForeignKey,'.')) {
                    $leftPart = '%3$s';
                }
                if (matchesIn($joinPrimaryKey,'.')) {
                    $rightPart = '%4$s';
                }

                $joinQuery .= PHP_EOL;
                $joinQuery .= $this->_createJoinQuery(
                    $joinType,
                    $joinTable,
                    sprintf(
                        $leftPart . ' = ' . $rightPart,
                        $joinTable,
                        $this->usetable,
                        $joinForeignKey,
                        $joinPrimaryKey
                    )
                );

            }

            // クエリー生成
            $query = implode(
                PHP_EOL,
                array(
                    'SELECT',
                    '    %2$s',
                    'FROM',
                    '    %1$s',
                    '%3$s'
                )
            );

            // クエリ合成
            $query = sprintf($query, $this->usetable, $fields, $joinQuery);

        }

        return $query;

    }

    // }}}
    // {{{ _getHasManyQuery

    private function _getHasManyQuery($first, $order)
    {
        if (is_string($this->module->hasMany)) {

            $primaryKey = 'id';
            $table = $this->module->usetable;
            $tableCore = end(explode('_', $table));
            $targetTable = $this->module->hasMany;
            $schema = $this->bindSchema();

            $fields = array();
            foreach ($schema as $value) {
                $fields[] = $table . '.' . $value['Field'];
            }

            $req = array();
            $req['query'] = implode(
                PHP_EOL,
                array(
                    'SELECT',
                    '    ' . implode(',', $fields),
                    'FROM',
                    '    ' . $table,
                )
            );

            if(!empty($order)) {
                $req['order'] = $order;
            }

            if($first) {

                $src = $this->bindRow($req);
                $src = array($src);

            } else {

                $src = $this->bindRowAll($req);
            }

            $in = array();
            foreach($src as $value) {
                $in[] = $value[$primaryKey];
            }

            $query = implode(
                PHP_EOL,
                array(
                    'SELECT',
                    '    *',
                    'FROM',
                    '    ' . $targetTable,
                )
            );

            $targetField = $tableCore . '_id';

            $where = sprintf(
                implode(
                    ' ',
                    array(
                        'WHERE ',
                        '    %1$s.%2$s',
                        'IN (',
                        '    %3$s',
                        ')'
                    )
                ),
                $targetTable,
                $targetField,
                implode(',', $in)
            );

            $order = '';

        } else {

            foreach ($this->module->hasMany as $key => $value) {

                if (is_numeric($key)) {
                    $key = $value;
                    $value = array();
                }

                if (isset($this->module->modules[$key])) {

                    $module = $this->module->modules[$key];

                    $primaryKey = 'id';
                    if(!empty($module->primaryKey)) {
                        $primaryKey = $module->primaryKey;
                    }

                    $table = $this->module->usetable;
                    $tableCore = end(explode('_', $table));
                    $targetTable = $module->usetable;
                    $schema = $this->bindSchema();
                    $fields = array();
                    foreach ($schema as $svalue) {
                        $fields[] = $table . '.' . $svalue['Field'];
                    }

                } else {
                    $primaryKey = 'id';

                    if(isset($value['primaryKey'])) {
                        $primaryKey = $value['primaryKey'];
                    }

                    $table = $this->module->usetable;
                    $tableCore = end(explode('_', $table));
                    $targetTable = $key;
                    $schema = $this->bindSchema();

                    $fields = array();
                    foreach ($schema as $svalue) {
                        $fields[] = $table . '.' . $svalue['Field'];
                    }
                }

                $req = array();
                $req['query'] = implode(
                    PHP_EOL,
                    array(
                        'SELECT',
                        '    ' . implode(',', $fields),
                        'FROM',
                        '    ' . $table,
                    )
                );

                if(!empty($order)) {
                    $req['order'] = $order;
                }

                if($first) {

                    $src = $this->bindRow($req);
                    $src = array($src);

                } else {

                    $src = $this->bindRowAll($req);
                }

                $in = array();
                foreach($src as $ivalue) {
                    $in[] = $ivalue[$primaryKey];
                }

                $query = implode(
                    PHP_EOL,
                    array(
                        'SELECT',
                        '    *',
                        'FROM',
                        '    ' . $targetTable,
                    )
                );

                $targetField = $tableCore . '_id';

                $where = sprintf(
                    implode(
                        ' ',
                        array(
                            'WHERE ',
                            '    %1$s.%2$s',
                            'IN (',
                            '    %3$s',
                            ')'
                        )
                    ),
                    $targetTable,
                    $targetField,
                    implode(',', $in)
                );

                if(isset($value['order']) && is_array($value['order'])) {
                    $order = 'ORDER BY ';
                    $order .= implode(', ', $value['order']);
                }
            }
        }

        return array(
            'query' => $query,
            'where' => $where,
            'src' => $src,
            'srctable' => $table,
            'targettable' => $targetTable,
            'targetfield' => $targetField,
            'primaryKey' => $primaryKey,
            'order' => $order
        );

    }

    // }}}
    // {{{ _getBindGetType

    private function _getBindGetType($type)
    {
        if (!is_string($type)) {
            $type = 'all';
        }

        $type = strtolower($type);

        if (!in_array($type, array('first', 'count', 'list'))) {
            $type = 'all';
        }

        return $type;
    }

    // }}}
    // {{{ _createJoinQuery

    private function _createJoinQuery($type, $table, $conditions)
    {
        $type = $this->_getJoinType($type);

        foreach ($this->module->modules as $module) {
            if (is_object($module)) {

                if(
                    $module->usetable === $table &&
                    !is_null($module->getJoinQuery())
                ) {
                    $table = sprintf(
                        '(%s %s)',
                        $table,
                        $module->getJoinQuery()
                    );
                }

            }
        }

        $query = sprintf(
            implode(
                PHP_EOL,
                array(
                    '%1$s JOIN',
                    '    %2$s'
                )
            ),
            $type,
            $table
        );

        // 文字列で条件が指定された場合は、そのまま設定
        if (is_string($conditions)) {

            $query = sprintf(
                implode(
                    PHP_EOL,
                    array(
                        $query,
                        'ON',
                        '    %s'
                    )
                ),
                $conditions
            );

        } else if (is_array($conditions) && count($conditions) > 0) {

            $reserveOperator = null;
            foreach ($conditions as $key => $cond) {

                $operator = '';
                if ($key > 0) {
                    $operator = ' AND';
                }
                if ($cond == 'AND' || $cond == 'OR') {
                    $reserveOperator = $cond;
                    continue;
                }
                if (!empty($reserveOperator)) {
                    $operator = ' ' . $reserveOperator;
                    $reserveOperator = null;
                }

                $query = sprintf(
                    implode(
                        PHP_EOL,
                        array(
                            $query . '%1$s',
                            'ON',
                            '    %2$s'
                        )
                    ),
                    $operator,
                    $cond
                );

            }


        } else {
            trigger_error('条件設定が不正です。', E_USER_ERROR);
        }

        return $query;

    }

    // }}}
    // {{{ _getJoinType

    private function _getJoinType($type)
    {
        if (!is_string($type)) {
            $type = 'INNER';
        }
        $type = strtoupper($type);
        if (!in_array($type, array('LEFT', 'RIGHT', 'INNER'))) {
            $type = 'INNER';
        }

        return $type;
    }

    // }}}
    // {{{ _hasOperator

    private function _hasOperator($text)
    {
        if (startsWith(trim($text), '<>')) {
            return '<>';
        } else if (startsWith(trim($text), '<=')) {
            return '<=';
        } else if (startsWith(trim($text), '>=')) {
            return '>=';
        } else if (startsWith(trim($text), '<')) {
            return '<';
        } else if (startsWith(trim($text), '>')) {
            return '>';
        } else if (startsWith(trim(strtoupper($text)), 'LIKE')) {
            return 'LIKE';
        } else if (startsWith(trim(strtoupper($text)), 'IS')) {
            return 'IS';
        } else if (startsWith(trim(strtoupper($text)), 'IS NOT')) {
            return 'IS NOT';
        } else if (startsWith(trim(strtoupper($text)), 'BETWEEN')) {
            return 'BETWEEN';
        } else if (startsWith(trim(strtoupper($text)), 'NOT BETWEEN')) {
            return 'NOT BETWEEN';
        } else if (startsWith(trim(strtoupper($text)), 'IN')) {
            return 'IN';
        } else if (startsWith(trim(strtoupper($text)), 'NOT IN')) {
            return 'NOT IN';
        }

        return false;
    }

    // }}}
    // {{{ _getWhereClause

    private function _getWhereClause($whereClause)
    {

        if (is_array($whereClause)) {
            $whereTemp = '';
            $prevOperator = false;

            foreach ($whereClause as $key => $value) {

                if ($value == 'AND' || $value == 'OR') {
                    $whereTemp .= ' ' . $value;
                    $prevOperator = true;
                } else {

                    if ($prevOperator || $key == 0) {
                        $whereTemp .= ' ' . $value;
                    } else {
                        $whereTemp .= ' AND ' . $value;
                    }

                    $prevOperator = false;
                }

            }

            $whereClause = $whereTemp;
        }

        return $whereClause;
    }

    // }}}
    // {{{ _getConditions

    private function _getConditions($conditions)
    {
        $where = array();
        $binds = array();

        foreach ($conditions as $key => $value) {

            if (is_numeric($key) && is_array($value)) {
                $temp = $this->_getConditions($value);
                $where[] = '(' . $this->_getWhereClause($temp['where']) . ')';

                foreach ($temp['bind'] as $bindKey => $bindValue) {
                    $binds[$bindKey] = $bindValue;
                }

            } else if (
                is_numeric($key) &&
                (
                    strtoupper(trim($value)) == 'AND' ||
                    strtoupper(trim($value)) == 'OR'
                )
            ) {
                $where[] = strtoupper(trim($value));
            } else {
                $func = false;
                $inVal = null;
                $isFunc = false;
                $type = '';

                // 演算子取得
                $hasOperator = $this->_hasOperator(trim($value));

                if (
                    $hasOperator == 'BETWEEN' || $hasOperator == 'NOT BETWEEN'
                ) {

                    if (preg_match(
                        '/^(?:between|not between)\x20+([^\x20]+)\x20+and\x20+([^\x20]+)/i',
                        $value,
                        $matches
                    )) {
                        $valTemp1 = $matches[1];
                        $valTemp2 = $matches[2];
                        $funcTemp1 = $this->_getFunction($valTemp1);
                        $funcTemp2 = $this->_getFunction($valTemp2);

                        if ($funcTemp1 || $funcTemp2) {
                            $func = array();

                            if ($funcTemp1) {
                                $func[0] = $funcTemp1;
                            } else {
                                $func[0] = $valTemp1;

                                // クォーテーション除去
                                if (preg_match(
                                    "/^(?:(\")|('))(.*)(?(1)\"|')$/",
                                    $func[0],
                                    $matches
                                )) {
                                    $func[0] = $matches[3];
                                }

                            }

                            if ($funcTemp2) {
                                $func[1] = $funcTemp2;
                            } else {
                                $func[1] = $valTemp2;

                                // クォーテーション除去
                                if (preg_match(
                                    "/^(?:(\")|('))(.*)(?(1)\"|')$/",
                                    $func[1],
                                    $matches
                                )) {
                                    $func[1] = $matches[3];
                                }

                            }

                        } else {
                            $value = array($valTemp1, $valTemp2);

                            // クォーテーション除去
                            if (preg_match(
                                "/^(?:(\")|('))(.*)(?(1)\"|')$/",
                                $value[0],
                                $matches
                            )) {
                                $value[0] = $matches[3];
                            }

                            if (preg_match(
                                "/^(?:(\")|('))(.*)(?(1)\"|')$/",
                                $value[1],
                                $matches
                            )) {
                                $value[1] = $matches[3];
                            }

                        }

                    }

                } else if ($hasOperator == 'IN' || $hasOperator == 'NOT IN') {

                    if (preg_match(
                        '/^(?:in|not in)\x20+(.+)/i', $value, $matches
                    )) {
                        $inVal = explode(',', $matches[1]);

                        if (count($inVal) > 0) {
                            $func = array();

                            foreach ($inVal as $index => $val) {
                                $tempFunc = $this->_getFunction(trim($val));

                                if ($tempFunc) {
                                    $inVal[$index] = $tempFunc;
                                    $func[$index] = $tempFunc;
                                } else {

                                    // クォーテーション除去
                                    if (preg_match(
                                        "/^(?:(\")|('))(.*)(?(1)\"|')$/",
                                        trim($val),
                                        $matches
                                    )) {
                                        $inVal[$index] = $matches[3];
                                    } else {
                                        $inVal[$index] = trim($val);
                                    }

                                }

                            }

                            if (empty($func)) {
                                $func = false;
                            }

                        }

                    }

                } else {

                    // 関数取得
                    $func = $this->_getFunction($value);
                }

                if (strpos($key, '.')) {
                    $colName = trim(substr($key, strpos($key, '.') + 1));
                } else {
                    $colName = trim($key);
                }

                // テーブル情報取得
                $schema = $this->bindSchema();

                foreach ($schema as $column) {

                    if ($column['Field'] == $colName) {
                        $type = $column['Type'];
                        break;
                    }

                }

                if (
                    ($hasOperator == 'BETWEEN' ||
                    $hasOperator == 'NOT BETWEEN') &&
                    $func
                ) {

                    foreach ($func as $val) {

                        if (is_string($val)) {
                            continue;
                        }

                        if ($val['type'] == 'date') {

                            if ($this->adapter->getColTypeAbstract($type) == 'date') {
                                $isFunc = true;
                            }

                        }

                    }

                } else if (
                    ($hasOperator == 'IN' ||
                    $hasOperator == 'NOT IN') &&
                    $func
                ) {

                    foreach ($func as $val) {

                        if (is_array($val)) {

                            if ($val['type'] == 'date') {

                                if ($this->adapter->getColTypeAbstract($type) == 'date') {
                                    $isFunc = true;
                                }

                            } else if ($val['type'] == 'other') {

                                if ($this->adapter->getColTypeAbstract($type) != 'date') {
                                    $isFunc = true;
                                }

                            }

                        }

                    }

                } else if ($func && $func['type'] == 'date') {

                    if ($this->adapter->getColTypeAbstract($type) == 'date') {
                        $isFunc = true;
                    }

                } else if ($func && $func['type'] == 'other') {

                    if ($this->adapter->getColTypeAbstract($type) != 'date') {
                        $isFunc = true;
                    }

                }

                if ($isFunc) {

                    if ($hasOperator !== false) {

                        if (
                            $hasOperator == 'BETWEEN' ||
                            $hasOperator == 'NOT BETWEEN'
                        ) {

                            if (is_string($func[0])) {
                                $from = $this->_getBindName();
                                $binds[$from] = $func[0];
                                $from = ':' . $from;
                            } else {
                                $from = $func[0]['src'];
                            }

                            if (is_string($func[1])) {
                                $to = $this->_getBindName();
                                $binds[$to] = $func[1];
                                $to = ':' . $to;
                            } else {
                                $to = $func[1]['src'];
                            }

                            $tempWhere = sprintf(
                                '%s %s %s AND %s',
                                $key,
                                $hasOperator,
                                $from,
                                $to
                            );

                        } else if (
                            $hasOperator == 'IN' || $hasOperator == 'NOT IN'
                        ) {
                            $inTemp = array();
                            $bindTemp = array();

                            foreach ($inVal as $index => $val) {

                                if (is_array($val)) {

                                    if (strtoupper($val['name']) == 'MD5()') {
                                        $bindKey = $this->_getBindName();
                                        $inTemp[$index] = sprintf(
                                            'MD5(:%s)',
                                            $bindKey
                                        );
                                        $binds[$bindKey] = $val['param'];
                                    } else {
                                        $inTemp[$index] = $val['src'];
                                    }

                                } else {
                                    $bindKey = $this->_getBindName();
                                    $inTemp[$index] = ':' . $bindKey;
                                    $binds[$bindKey] = $val;
                                }

                            }

                            $tempWhere = sprintf(
                                '%s %s (%s)',
                                $key,
                                $hasOperator,
                                implode(', ', $inTemp)
                            );
                        } else {
                            $tempWhere = sprintf(
                                '%s %s %s',
                                $key,
                                $hasOperator,
                                $func['src']
                            );
                        }

                    } else {

                        if (strtoupper($func['name']) == 'MD5()') {
                            $bindKey = $this->_getBindName();
                            $tempWhere = sprintf(
                                '%s = %s(:%s)',
                                $key,
                                substr($func['name'], 0, -2),
                                $bindKey
                            );
                            $binds[$bindKey] = $func['param'];
                        } else {
                            $tempWhere = sprintf(
                                '%s = %s',
                                $key,
                                $func['src']
                            );
                        }

                    }

                } else {

                    if ($hasOperator !== false) {

                        if (
                            $hasOperator == 'BETWEEN' ||
                            $hasOperator == 'NOT BETWEEN'
                        ) {
                            $from = $this->_getBindName();
                            $to = $this->_getBindName();
                            $tempWhere = sprintf(
                                '%s %s :%s AND :%s',
                                $key,
                                $hasOperator,
                                $from,
                                $to
                            );

                            if (
                                (isset($value[0]) && $value[0] !== '') &&
                                (isset($value[1]) && $value[1] !== '')
                            ) {
                                $binds[$from] = $value[0];
                                $binds[$to] = $value[1];
                            } else {
                                $binds[$from] = '';
                                $binds[$to] = '';
                            }

                        } else if (
                            $hasOperator == 'IN' ||
                            $hasOperator == 'NOT IN'
                        ) {
                            $inTemp = array();
                            $bindTemp = array();

                            foreach ($inVal as $index => $val) {
                                $bindKey = $this->_getBindName();
                                $inTemp[$index] = ':' . $bindKey;
                                $binds[$bindKey] = $val;
                            }

                            $tempWhere = sprintf(
                                '%s %s (%s)',
                                $key,
                                $hasOperator,
                                implode(', ', $inTemp)
                            );
                        } else if (
                            $hasOperator == 'IS' || $hasOperator == 'IS NOT'
                        ) {
                            $tempWhere = sprintf(
                                '%s %s %s',
                                $key,
                                $hasOperator,
                                trim(substr(
                                    trim($value), strlen($hasOperator)
                                ))
                            );
                        } else {
                            $bindKey = $this->_getBindName();
                            $tempWhere = implode(
                                '',
                                array(
                                    $key,
                                    ' ',
                                    $hasOperator,
                                    ' :',
                                    $bindKey
                                )
                            );

                            $value = trim(
                                substr(
                                    trim($value),
                                    strlen($hasOperator)
                                )
                            );
                            $binds[$bindKey] = $value;
                        }

                    } else {
                        $bindKey = $this->_getBindName();
                        $tempWhere = $key . ' = :' . $bindKey;
                        $binds[$bindKey] = $value;
                    }

                }

                $where[] = $tempWhere;
            }

        }

        return array(
            'where' => $where,
            'bind' => $binds
        );
    }

    // }}}
    // {{{ _getHaving

    private function _getHaving($havingCond, $binds, $dubCnt = 1)
    {
        $having = array();

        if (is_null($binds)) {
            $binds = array();
        }

        foreach ($havingCond as $key => $value) {

            $keyFunc = $this->_getFunction($key);

            if (is_numeric($key) && is_array($value)) {
                $temp = $this->_getHaving($value, array(), $dubCnt);
                $having[] = '(' . $this->_getWhereClause($temp['having']) . ')';

                foreach ($temp['bind'] as $bindKey => $bindValue) {
                    $binds[$bindKey] = $bindValue;
                }

            } else if (
                is_numeric($key) &&
                (
                    strtoupper(trim($value)) == 'AND' ||
                    strtoupper(trim($value)) == 'OR'
                )
            ) {
                $having[] = strtoupper(trim($value));
            } else {

                // 関数取得
                $func = $this->_getFunction($value);

                // テーブル情報取得
                $schema = $this->bindSchema();
                $type = '';

                if (strpos($key, '.')) {
                    $colName = trim(substr($key, strpos($key, '.') + 1));
                } else {
                    $colName = trim($key);
                }

                foreach ($schema as $column) {

                    if ($column['Field'] == $colName) {
                        $type = $column['Type'];
                        break;
                    }

                }

                $isFunc = false;

                if ($func && $func['type'] == 'date') {

                    if ($this->adapter->getColTypeAbstract($type) == 'date') {
                        $isFunc = true;
                    }

                } else if ($func && $func['type'] == 'group') {
                    $isFunc = true;
                } else if ($func && $func['type'] == 'other') {

                    if ($this->adapter->getColTypeAbstract($type) != 'date') {
                        $isFunc = true;
                    }
                }

                if ($isFunc) {

                    if ($this->_hasOperator($value) !== false) {

                        if (strtoupper($func['name']) == 'MD5()') {
                            $tempHaving = sprintf(
                                '%s %s %s(:%s)',
                                $key,
                                $this->_hasOperator(trim($value)),
                                substr($func['name'], 0, -2),
                                $this->_getBindName()
                            );
                        } else {
                            $tempHaving = sprintf(
                                '%s %s %s',
                                $key,
                                $this->_hasOperator(trim($value)),
                                $func['src']
                            );
                        }

                    } else {

                        if (strtoupper($func['name']) == 'MD5()') {
                            $tempHaving = sprintf(
                                '%s = %s(:%s)',
                                $key,
                                substr($func['name'], 0, -2),
                                $this->_getBindName()
                            );
                        } else {
                            $tempHaving = sprintf(
                                '%s = %s',
                                $key,
                                $func['src']
                            );
                        }
                    }

                } else {
                    $bindKey = $this->_getBindName();

                    if ($this->_hasOperator($value) !== false) {
                        $tempHaving = implode(
                            '',
                            array(
                                $key,
                                ' ',
                                $this->_hasOperator(trim($value)),
                                ' :',
                                $bindKey
                            )
                        );

                        $value = trim(
                            substr(
                                trim($value),
                                strlen($this->_hasOperator(trim($value)))
                            )
                        );
                    } else {
                        $tempHaving = $key . ' = :' . $bindKey;
                    }

                    $binds[$bindKey] = $value;
                }

                $having[] = $tempHaving;

            }

        }

        return array(
            'having' => $having,
            'bind' => $binds
        );
    }

    // }}}
    // {{{ _getFunction

    private function _getFunction($value)
    {
        $ret = false;

        if ($this->_hasOperator(trim($value))) {
            $temp = trim(
                substr(trim($value), strlen($this->_hasOperator(trim($value))))
            );
        } else {
            $temp = trim($value);
        }

        if (preg_match('/^([a-z0-9_]+)\x20*(?:\((.*)\))?/i', $temp, $matches)) {
            $name = isset($matches[2]) ? $matches[1] . '()'
                                       : $matches[1];

            foreach ($this->adapter->functionList as $type => $functions) {

                if (in_array(strtoupper($name), $functions)) {
                    $src = $matches[0];
                    $param = (isset($matches[2])) ? $matches[2] : '';

                    if (
                        preg_match(
                            "/^(?:(\")|('))(.*)(?(1)\"|')$/", $param, $matches
                        )
                    ) {
                        $param = $matches[3];
                    }

                    $ret = array(
                        'name' => $name,
                        'param' => $param,
                        'type' => $type,
                        'src' => $src
                    );
                    break;
                }

            }

        }

        return $ret;
    }

    // }}}
    // {{{ bindLock

    public function bindLock($tables)
    {
        $this->bindExec(
            array(
                'query' => $this->adapter->getLockQuery($tables)
            )
        );
    }

    // }}}
    // {{{ bindUnlock

    public function bindUnlock()
    {
        $this->bindExec(
            array(
                'query' => $this->adapter->getUnlockQuery()
            )
        );
    }

    // }}}
    // {{{ bindSet

    public function bindSet($data, $primaryCond = array(), $lock = true)
    {

        $tableName = $this->module->usetable;

        // テーブルロック
        if ($lock) {
            $this->bindLock(array($tableName));
        }

        $conditions = null;

        if (is_array($primaryCond) && count($primaryCond) > 0) {
            $conditions = $primaryCond;
        } else {

            if (isset($data[$this->primaryKey])) {
                $conditions = array(
                    $tableName . '.' . $this->primaryKey => $data[$this->primaryKey]
                );
            }

        }

        if (isset($conditions)) {
            $temp = $this->_getConditions($conditions);
            $result = $this->bindRow(array(
                'query' => 'SELECT COUNT(*) AS "cnt" FROM ' . $this->usetable,
                'where' => $temp['where'],
                'bind' => $temp['bind']
            ));
            $cnt = ($result) ? intval($result['cnt']) : 0;
        } else {
            $cnt = 0;
        }

        $this->_bindCnt = 0;
        $fields = array();
        $values = array();
        $binds = array();
        $colType = '';
        $schema = $this->bindSchema();

        if ($cnt > 0) {

            foreach ($data as $key => $value) {
                $bindKey = $this->_getBindName();

                if ($key !== $this->primaryKey) {

                    foreach ($schema as $column) {

                        if ($column['Field'] == $key) {
                            $colType = $column['Type'];
                            break;
                        }

                    }

                    // フィールド設定
                    $fields[] = $key;

                    // 関数判定
                    $func = $this->_getFunction($value);

                    if ($func) {

                        switch ($func['type']) {

                            case 'date':

                                if ($this->adapter->getColTypeAbstract($colType) == 'date') {
                                    $values[] = $func['src'];
                                } else {
                                    $values[] = ':' . $bindKey;
                                    $binds[$bindKey] = $func['src'];
                                }

                                break;

                            case 'other':

                                if ($this->adapter->getColTypeAbstract($colType) != 'date') {

                                    if ($func['param'] !== '') {
                                        $values[] = sprintf(
                                            '%s(:%s)',
                                            substr($func['name'], 0, -2),
                                            $bindKey
                                        );
                                        $binds[$bindKey] = $func['param'];
                                    } else {
                                        $values[] = $func['src'];
                                    }

                                } else {
                                    $values[] = ':' . $bindKey;
                                    $binds[$bindKey] = $func['src'];
                                }

                                break;

                            default:
                                $values[] = ':' . $bindKey;
                                $binds[$bindKey] = $func['src'];
                        }

                    } else {
                        $values[] = ':' . $bindKey;
                        $binds[$bindKey] = $value;
                    }

                }

            }

            // 検索条件取得
            $tempWhere = array();

            if (is_array($primaryCond) && count($primaryCond) > 0) {
                $temp = $this->_getConditions($primaryCond);
                $tempWhere = $temp['where'];

                foreach ($temp['bind'] as $bindKey => $bindVal) {
                    $binds[$bindKey] = $bindVal;
                }

            } else {
                $bindKey = $this->_getBindName();
                $tempWhere = sprintf(
                    '%s.%s = :%s', $tableName, $this->primaryKey, $bindKey
                );
                $binds[$bindKey] = $data[$this->primaryKey];
            }

            // UPDATE
            $this->bindUpdate(array(
                'field' => $fields,
                'value' => $values,
                'bind' => $binds,
                'where' => $tempWhere
            ));

        } else {

            // primaryCondの設定があれば終了
            if (is_array($primaryCond) && !empty($primaryCond)) {
                return;
            }

            // INSERT
            foreach ($data as $key => $value) {
                $bindKey = $this->_getBindName();

                foreach ($schema as $column) {

                    if ($column['Field'] == $key) {
                        $colType = $column['Type'];
                        break;
                    }

                }

                // フィールド設定
                $fields[] = $key;

                // 関数判定
                $func = $this->_getFunction($value);

                if ($func) {

                    switch ($func['type']) {

                        case 'date':

                            if ($this->adapter->getColTypeAbstract($colType) == 'date') {
                                $values[] = $func['src'];
                            } else {
                                $values[] = ':' . $bindKey;
                                $binds[$bindKey] = $func['src'];
                            }

                            break;

                        case 'other':

                            if ($this->adapter->getColTypeAbstract($colType) != 'date') {

                                if ($func['param'] !== '') {
                                    $values[] = sprintf(
                                        '%s(:%s)',
                                      substr($func['name'], 0, -2),
                                        $bindKey
                                    );
                                    $binds[$bindKey] = $func['param'];
                                } else {
                                    $values[] = $func['src'];
                                }

                            } else {
                                $values[] = ':' . $bindKey;
                                $binds[$bindKey] = $func['src'];
                            }

                            break;

                        default:
                            $values[] = ':' . $bindKey;
                            $binds[$bindKey] = $func['src'];
                    }

                } else {
                    $values[] = ':' . $bindKey;
                    $binds[$bindKey] = $value;
                }

            }

            if (!isset($data[$this->primaryKey])) {

                $tableinfo= $this->bindGetTableInfo();
                if (!isset($tableinfo['Auto_increment'])) {
                    $query = sprintf(
                            'SELECT MAX(%s) as "maxId" FROM %s',
                            $this->primaryKey,
                            $tableName
                    );
                    $ret = $this->bindRow(array(
                        'query' => $query
                    ));
                    $nextId = intval($ret['maxId']) + 1;

                    $fields[] = $this->primaryKey;
                    $bindKey = 'bind__' . $this->primaryKey;
                    $values[] = ':' . $bindKey;
                    $binds[$bindKey] = $nextId;
                }
            }

            $this->bindInsert(array(
                'field' => $fields,
                'value' => $values,
                'bind' => $binds,
            ));
        }

        // テーブルロック解除
        if ($lock) {
            $this->bindUnlock();
        }
    }

    // }}}
    // {{{ bindRemove

    public function bindRemove($cond)
    {
        $this->_bindCnt = 0;

        if (is_array($cond) && array_key_exists('conditions', $cond)) {
            $temp = $this->_getConditions($cond['conditions']);
        } else {
            $condTemp = array();

            foreach ($cond as $index => $id) {

                if ($index != 0) {
                    $condTemp[] = 'OR';
                }

                $condTemp[] = array($this->primaryKey => $id);
            }

            $temp = $this->_getConditions($condTemp);
        }

        // DELTE文実行
        $this->bindDelete(
            array('where' => $temp['where'], 'bind' => $temp['bind'])
        );
    }

    // }}}
    // {{{ bindBind

    public function bindBind($config)
    {

        if (isset($config['hasOne'])) {

            $this->module->hasOne = array_merge_recursive($this->module->hasOne, $config['hasOne']);

        }

        if (isset($config['hasMany'])) {

            $this->module->hasMany = array_merge_recursive($this->module->hasMany, $config['hasMany']);

        }

        if (isset($config['belongsTo'])) {

            $this->module->belongsTo = array_merge_recursive($this->module->belongsTo, $config['belongsTo']);

        }


    }

    // }}}
    // {{{ bindUnbind

    public function bindUnbind($config)
    {

        if (isset($config['hasOne'])) {
            foreach ($config['hasOne'] as $remove) {
                if (isset($this->module->hasOne[$remove])) {
                    unset($this->module->hasOne[$remove]);
                }
            }
        }

        if (isset($config['hasMany'])) {
            foreach ($config['hasMany'] as $remove) {
                if (isset($this->module->hasMany[$remove])) {
                    unset($this->module->hasMany[$remove]);
                }
            }
        }

        if (isset($config['belongsTo'])) {
            foreach ($config['belongsTo'] as $remove) {
                if (isset($this->module->belongsTo[$remove])) {
                    unset($this->module->belongsTo[$remove]);
                }
            }
        }

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
