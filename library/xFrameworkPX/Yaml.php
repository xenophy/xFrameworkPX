<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Yaml Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Yaml.php 1181 2010-01-06 03:27:06Z tamari $
 */

// {{{ xFrameworkPX_Yaml

/**
 * xFrameworkPX_Yaml Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Yaml
 */
class xFrameworkPX_Yaml
{

    // {{{ props

    /**
     * YAMLのネストレベル保持配列
     *
     * @var array
     */
    private $_path = array();


    /**
     * リテラルブロックのプレースホルダー文字列
     *
     * @var string
     */
    private $_placeHolder = '___YAML_Literal_Block___';


    /**
     * アンカー名
     *
     * @var string
     */
    private $_groupAnchor = '';


    /**
     * エイリアス名
     *
     * @var string
     */
    private $_groupAlias = '';

    /**
     * グループ保持用配列
     *
     * @var array
     */
    private $_savedGroups = array();

    /**
     * グループ保持用配列
     *
     * @var array
     */
    private $_delayedPath = array();

    /**
     * インデント幅
     *
     * @var int
     */
    private $_indent = 2;

    // }}}
    // {{{ decode

    /**
     * YAMLデコードメソッド
     *
     * @param mixed $input YAMLファイルのパスまたはYAMLコード(文字列 or 配列)
     * @return mixed YAMLのデコード結果
     */
    public static function decode($input)
    {
        $yamlObject = null;
        $code = array();
        $ret = false;

        if (is_string($input)) {

            if (file_exists($input)) {
                $code = file($input);
            } else {
                $code[] = $input;
            }

        } else if (is_array($input)) {
            $code = $input;
        }

        if (!empty($code)) {
            $yamlObject = new xFrameworkPX_Yaml();
            $ret = $yamlObject->_decode($code);
        }

        return $ret;
    }

    // }}}
    // {{{ encode

    /**
     * YAMLエンコードメソッド
     *
     * 配列をYAMLドキュメントに変換する
     *
     * @param array $src エンコード対象配列
     * @param int $indent インデント幅 (default: 2)
     * @return string YAMLドキュメント文字列
     */
    public static function encode($src, $indent = 2)
    {
        $yamlObject = new xFrameworkPX_Yaml();
        $yaml = '';

        // インデント幅初期化
        if (is_numeric($indent)) {
            $yamlObject->_indent = $indent;
        }

        $yaml .= "---\n";

        if (is_array($src)) {

            $firstKey = key($src);
            $prevKey = -1;

            foreach ($src as $key => $value) {
                $yaml .= $yamlObject->_yamlize(
                    $key, $value, 0, $prevKey, $firstKey
                );

                $prevKey = $key;
            }

        }

        return $yaml;
    }

    // }}}
    // {{{ _decode

    /**
     * デコードメソッド
     *
     * @param arr $code YAMLコード配列
     * @return array 配列化されたYAMLのデータ
     */
    private function _decode($code)
    {

        if (!empty($code)) {

            $ret = array();
            $line = array();
            $indent = 0;
            $lastChar = '';
            $literalBlock = '';

            $this->_path = array();

            $cnt = count($code);

            for ($i = 0; $i < $cnt; $i++) {
                $codeLine = $code[$i];

                if (trim($codeLine) == '' || $this->_isComment($codeLine)) {

                    // 空白行もしくはコメント行
                    continue;
                }

                // インデント幅計算
                $indent = $this->_getIndentLength($codeLine);

                // インデント幅から親要素のパスを取得
                $this->_path = $this->_getParentPath($indent);

                // インデントの除去
                $codeLine = ltrim($codeLine);

                // リテラルブロック生成
                if (
                    preg_match('/.*\|$|.*>$/', $codeLine) &&
                    preg_match('/<.*>$/', $codeLine) === 0
                ) {

                    $lastChar = substr(trim($codeLine), -1);
                    $codeLine = rtrim($codeLine, $lastChar . " \n");
                    $codeLine .= $this->_placeHolder;
                    $literalBlock = '';

                    while (++$i < $cnt) {
                        $nextLine = $code[ $i ];
                        $nextIndent = $this->_getIndentLength($nextLine);

                        if (trim($nextLine) != '' && $nextIndent <= $indent) {
                            break;
                        }

                        $nextLine = ltrim($nextLine);
                        $nextLine = rtrim($nextLine, "\r\n\t ") . "\n";

                        if ($lastChar == '|') {
                            $literalBlock .= $nextLine;
                        } else if ($lastChar == '>') {
                            $literalBlock = sprintf(
                                '%s %s',
                                rtrim($literalBlock),
                                $nextLine
                            );
                        }
                    }

                    $i -= 1;
                }

                // 改行つきフロースタイルの改行除去
                while (++$i < $cnt) {

                    if ($this->_isSeveralInline($codeLine)) {
                        $codeLine = rtrim($codeLine, " \r\n\t") . ' ';
                        $codeLine .= ltrim($code[$i], " \t");
                    } else {
                        break;
                    }
                }

                $i -= 1;

                // アンカー/エイリアス保持処理
                if (!empty($codeLine) && is_string($codeLine)) {
                    $codeLine = trim($codeLine);

                    if (!empty($codeLine)) {

                        $matches = array();

                        if (preg_match(
                            '/^(&[A-z0-9_\-]+)/',
                            $codeLine,
                            $matches
                        )) {
                            $this->_groupAnchor = substr($matches[ 1 ], 1);
                            $codeLine = trim(
                                str_replace($matches[ 1 ], '', $codeLine)
                            );
                        } else if (preg_match(
                            '/(&[A-z0-9_\-]+)$/',
                            $codeLine,
                            $matches
                        )) {
                            $this->_groupAnchor = substr($matches[ 1 ], 1);
                            $codeLine = trim(
                                str_replace($matches[ 1 ], '', $codeLine)
                            );
                        } else if (preg_match(
                            '/^(\*[A-z0-9_\-]+)/',
                            $codeLine,
                            $matches
                        )) {
                            $this->_groupAlias = substr($matches[1], 1);
                            $codeLine = trim(
                                str_replace($matches[ 1 ], '', $codeLine)
                            );
                        } else if ( preg_match(
                            '/(\*[A-z0-9_\-]+)$/',
                            $codeLine,
                            $matches
                        )) {
                            $this->_groupAlias = substr($matches[1], 1);
                            $codeLine = trim(
                                str_replace($matches[1], '', $codeLine)
                            );
                        }

                    }

                }

                // YAMLコードの配列変換
                $line = $this->_unyamlize($codeLine, $indent);

                // リテラルブロックを配列に変換
                if (!empty($lastChar)) {
                    $line = $this->_addLiteralBlock($line, $literalBlock);
                }

                // 結果配列の追加
                $ret = $this->_addArray($line, $indent, $ret);

                foreach ($this->_delayedPath as $key => $value) {
                    $this->_path[ $key ] = $value;
                }

                $this->_delayedPath = array();

            }
        }

        return $ret;
    }

    // }}}
    // {{{ _yamlize

    /**
     * YAML変換メソッド
     *
     * @param string $key キー名
     * @param mixed $value 値
     * @param int $indentCnt インデント幅
     * @param int $prevKey 前要素のキー ( default: -1 )
     * @param int $firstKey 先頭要素のキー ( default: 0 )
     * @param bool $stringQuote
     * @return string
     */
    private function _yamlize(
        $key,
        $value,
        $indentCnt,
        $prevKey = -1,
        $firstKey = 0,
        $stringQuote = true
    )
    {
        $ret = '';
        $indent = '';
        $quoteMode = is_bool($stringQuote) ? $stringQuote : true;

        // インデントの生成
        $indent = str_repeat(' ', $indentCnt);

        if (is_numeric($key) && ($key - 1) == $prevKey && $firstKey === 0) {

            // YAML 配列
            $ret = $indent . '- ';
        } else {

            if ($firstKey === 0) {
                return;
            }

            // YAML ハッシュ
            if (strpos($key, ":") !== false) {

                // キーにコロンが入っていた場合
                $key = sprintf('\'%s\'', $key);
            }

            $ret = $indent . sprintf('%s: ', $key);

        }

        if (is_array($value)) {

            // 値が配列
            if (empty($value)) {
                $ret .= "[ ]\n";
            } else {
                $ret = $this->_yamlize(
                    $key, '', $indentCnt, $prevKey, $firstKey, false
                );
                $indentCnt += $this->_indent;
                $prevKey = -1;
                $firstKey = key($value);

                foreach ($value as $index => $item) {
                    $ret .= $this->_yamlize(
                        $index, $item, $indentCnt, $prevKey, $firstKey
                    );

                    $prevKey = $index;
                }

            }

        } else {

            // 値が配列以外
            $ret .= $this->_yamlizeValue($value, $indentCnt, $quoteMode);
            $ret .= "\n";
        }

        return $ret;

    }

    // }}}
    // {{{ _unyamlize

    /**
     * YAMLコード行の配列変換メソッド
     *
     * @param string $line YAMLコード行
     * @param int $indentCnt インデント数
     * @return array YAMLコード行のパース結果配列
     */
    private function _unyamlize($line, $indentCnt)
    {
        $ret = array();
        $temp = array();
        $key = '';

        $line = trim($line);

        if (preg_match('/^\-.+:$/', $line)) {

            // YAML値がハッシュの配列

            // ハッシュキーの取得
            $key = $this->_unquote(trim(substr($line, 1, -1)));
            $temp[ $key ] = array();
            $this->_delayedPath = array(
                strpos($line, $key) + $indentCnt => $key
            );

            $ret = array($temp);
        } else if (preg_match('/^\-[^\-]*$/', $line)) {

            // YAML値が配列要素
            if (strlen($line) > 1) {
                $value = trim(substr($line, 1));
                $ret[] = $this->_toType($value);
            }

        } else if (preg_match('/^\[.*\]$/', $line)) {

            // YAML値がフロースタイルの配列
            $ret = $this->_toType($line);

        } else if (preg_match('/.*:$/', $line)) {

            // YAML値がハッシュ (キーのみ)

            $key = $this->_unquote(trim(substr($line, 0, -1)));
            $ret[ $key ] = '';
        } else {

            if (strpos($line, ':')) {

                // YAML値がハッシュ
                if (
                    ($line[ 0 ] == '"' || $line[ 0 ] == "'") &&
                    preg_match('/^(["\'](.*)["\'](\s)*:)/', $line, $matches)
                ) {
                    $value = trim(str_replace($matches[ 1 ], '', $line));
                    $key = $matches[ 2 ];
                } else {
                    $explode = explode(':', $line);
                    $key = trim($explode[ 0 ]);
                    array_shift($explode);
                    $value = trim(implode(':', $explode));
                }

                $value = $this->_toType($value);

                if ($key === '0') {
                    $key = '__!YAMLZero';
                }

                $ret[ $key ] = $value;
            } else {
                $ret = array($line);
            }

        }

        return $ret;
    }

    // }}}
    // {{{ _getParentPath

    /**
     * 親要素のパス情報配列取得メソッド
     *
     * @param int $indentCnt インデント幅
     * @return array 親要素のパス情報配列
     */
    private function _getParentPath($indentCnt)
    {
        $ret = array();

        if ($indentCnt !== 0) {
            $ret = $this->_path;
            end($ret);
            $lastPathKey = key($ret);

            while ($indentCnt <= $lastPathKey) {
                array_pop($ret);
                end($ret);
                $lastPathKey = key($ret);
            }

        }

        return $ret;
    }

    // }}}
    // {{{ _isComment

    /**
     * コメント行チェックメソッド
     *
     * @param string $line YAMLコード行
     * @return bool チェック結果 true: コメント行, false: コメント行以外
     */
    private function _isComment($line)
    {
        $ret = false;

        if (!empty($line) && is_string($line)) {
            $line = trim($line);

            if ($line[ 0 ] == '#') {
                $ret = true;
            } else if (trim($line, " \r\n\t") == '---') {
                $ret = true;
            }

        }

        return $ret;
    }

    // }}}
    // {{{ _getIndentLength

    /**
     * インデント幅計算メソッド
     *
     * @param string $value 文字列
     * @return int インデント幅
     */
    private function _getIndentLength($value)
    {
        return strlen($value) - strlen(ltrim($value));
    }

    // }}}
    // {{{ _isSeveralInline

    /**
     * 改行ありフロー記述チェックメソッド
     *
     * @param string $line YAMLコード行文字列
     * @return boolean true: 改行あり, false: 改行なし
     */
    private function _isSeveralInline($line)
    {
        $ret = false;

        $line = trim($line);

        if (preg_match('/^\[[^\]]*$/', $line)) {
            $ret = true;
        } else if (preg_match('/^[^:]+?:\s*\[[^\]]*$/', $line)) {
            $ret = true;
        }

        return $ret;
    }

    // }}}
    // {{{ _addLiteralBlock

    /**
     * リテラルブロック文字列の追加
     *
     * @param array $line YAML要素の配列
     * @param string $literalBlock リテラルブロック文字列
     * @return array リテラルブロック文字列を追加したYAML要素の文字列
     */
    private function _addLiteralBlock($line, $literalBlock)
    {
        $ret = array();
        $placeHolderLength = strlen($this->_placeHolder);

        foreach ($line as $key => $value) {
            if (is_array($value)) {
                $ret[ $key ] = $this->_addLiteralBlock($value, $literalBlock);
            } else if (
                substr($value, -1 * $placeHolderLength) == $this->_placeHolder
            ) {
                $ret[ $key ] = rtrim($literalBlock, " \r\n");
            } else {
                $ret[ $key ] = $value;
            }
        }

        return $ret;
    }

    // }}}
    // {{{ _toType

    /**
     * YAML値の型変換メソッド
     *
     * @param mixed $value YAML値
     * @return mixed YAML値をPHPの型に変換
     */
    private function _toType($value)
    {
        $ret = null;
        $firstChar = '';
        $lastChar = '';
        $isQuoted = false;

        if ( $value !== '' ) {
            $firstChar = $value[ 0 ];
            $lastChar = substr($value, -1, 1);

            // クォーテーション判定
            if (
                !empty($value) &&
                ($firstChar == '\'' || $firstChar == '"') &&
                ($lastChar == '\'' || $lastChar == '"')
            ) {
                $isQuoted = true;
            }

            if ($isQuoted) {
                $ret = strtr(
                    substr($value, 1, -1),
                    array(
                        '\\"' => '"',
                        '\'\'' => '\'',
                        '\\\'' => '\''
                    )
                );
            } else {
                $explode = array();
                $innerValue = '';

                if (strpos($value, ' #') !== false) {
                    $value = preg_replace('/\s+#(.+)$/', '', $value);
                }

                if ($firstChar == '[' && $lastChar == ']') {

                    // フロー記述された配列のパース
                    $innerValue = trim(substr($value, 1, -1));

                    if ($innerValue !== '') {
                        $explode = $this->_parseInline($innerValue);

                        foreach ($explode as $item) {
                            $ret[] = $this->_toType(trim($item));
                        }

                    } else {
                        $ret = array();
                    }

                } else if ($firstChar == '{' && $lastChar == '}') {

                    $ret = array();
                    $sub = array();
                    $innerValue = trim(substr($value, 1, -1));

                    if ($innerValue !== '') {

                        $explode = $this->_parseInline($innerValue);

                        foreach ($explode as $item) {
                            $sub = $this->_toType($item);

                            if (empty($sub)) {
                                continue;
                            } else if (is_array($sub)) {

                                $ret[ key($sub) ] = $sub[ key($sub) ];
                                continue;
                            }

                            $ret[] = $sub;
                        }

                    }

                } else if (
                    strpos($value, ': ') !== false && $firstChar != '{'
                ) {
                    $explode = explode(': ', $value);
                    $key = trim($explode[ 0 ]);
                    array_shift($explode);
                    $value = trim(implode(': ', $explode));
                    $value = $this->_toType($value);

                    $ret = array($key => $value);
                } else if (
                    strtolower($value) == 'null' ||
                    $value == '' ||
                    $value == '~'
                ) {

                    // 値がNULLに相当
                    $ret = null;
                } else if (is_numeric($value)) {

                    // 値が数値
                    if (
                        intval($firstChar) > 0 &&
                        preg_match('/^[1-9]+[0-9]*$/', $value)
                    ) {

                        // 整数

                        $ret = (int)$value;

                        if ($value != PHP_INT_MAX && $ret == PHP_INT_MAX) {
                            $ret = $value;
                        }

                    } else {

                        // 整数以外
                        if ($value === '0') {
                            $ret = 0;
                        } else if (trim($value, 0) == (float)$value) {
                            $ret = (float)$value;
                        } else {
                            $ret = $value;
                        }

                    }

                } else if (
                    in_array(
                        strtolower($value),
                        array('true', 'on', '+', 'yes', 'y')
                    )
                ) {

                    // 値がBoolean Trueに相当
                    $ret = true;
                } else if (
                    in_array(
                        strtolower($value),
                        array('false', 'off', '-', 'no', 'n')
                    )
                ) {

                    // 値がBoolean Falseに相当
                    $ret = false;
                } else {
                    $ret = $value;
                }

            }

        }

        return $ret;
    }

    // }}}
    // {{{ _parseInline

    /**
     * フロー記述パースメソッド
     *
     * @param string $linline フロー記述されたYAMLコード
     * @return  array パース結果
     */
    private function _parseInline($linline)
    {
        $sequens = array();
        $maps = array();
        $savedStrings = array();
        $reg = '/(?:(")|(?:\'))((?(1)[^"]+|[^\']+))(?(1)"|\')/';
        $cnt = 0;
        $finished = false;

        // YAML文字列のエスケープ
        if (preg_match_all($reg, $linline, $match)) {
            $savedStrings = $match[ 0 ];
            $linline  = preg_replace($reg, 'YAMLString', $linline);
        }

        do {
            $reg = '/\[([^{}\[\]]+)\]/U';

            // 配列チェック
            while (preg_match($reg, $linline, $match)) {
                $sequens[] = $match[ 0 ];
                $linline = preg_replace(
                    $reg, 'YAMLSeq' . (count($sequens) - 1) . 's', $linline, 1
                );
            }

            $reg = '/\[([^{}\[\]]+)\]/U';

            // ハッシュチェック
            while (preg_match($reg, $linline, $match)) {
                $maps[] = $match[ 0 ];
                $linline = preg_replace(
                    $reg, 'YAMLMap' . (count($maps) - 1) . 's', $linline, 1
                );
            }

            if ($cnt++ >= 10) {
                break;
            }

        } while (
            strpos($linline, '[') !== false ||
            strpos($linline, '{') !== false
        );

        $explode = explode(', ', $linline);
        $stringCnt = 0;
        $cnt = 0;

        while (1) {

            // YAML配列の再配置
            if (!empty($sequens)) {

                foreach ($explode as $key => $value) {

                    if (strpos($value, 'YAMLSeq') !== false) {

                        foreach ($sequens as $seqKey => $seqValue) {
                            $explode[ $key ] = str_replace(
                                'YAMLSeq' . $seqKey . 's',
                                $seqValue,
                                $value
                            );

                            $value = $explode[ $key ];
                        }

                    }

                }

            }

            // YAMLハッシュの再配置
            if (!empty($maps)) {

                foreach ($explode as $key => $value) {

                    if (strpos($value, 'YAMLMap') !== false) {

                        foreach ($maps as $mapKey => $mapValue) {
                            $explode[ $key ] = str_replace(
                                'YAMLMap' . $mapKey . 's',
                                $mapValue,
                                $value
                            );

                            $value = $explode[ $key ];
                        }

                    }

                }

            }

            // YAML文字列再配置
            if (!empty($savedStrings)) {

                foreach ($explode as $key => $value) {

                    while (strpos($value, 'YAMLString') !== false) {
                        $explode[ $key ] = preg_replace(
                            '/YAMLString/',
                            $savedStrings[ $stringCnt ],
                            $value,
                            1
                        );

                        unset($savedStrings[ $stringCnt ]);
                        ++$stringCnt;
                        $value = $explode[ $key ];
                    }

                }

            }

            $finished = true;

            foreach ($explode as $key => $value) {

                if (strpos($value, 'YAMLSeq') !== false) {
                    $finished = false;
                    break;
                }

                if (strpos($value, 'YAMLMap') !== false) {
                    $finished = false;
                    break;
                }

                if (strpos($value, 'YAMLString') !== false) {
                    $finished = false;
                    break;
                }

            }

            if ($finished) {
                break;
            }

            if ($cnt++ >= 10) {
                break;
            }

        }

        return $explode;
    }

    // }}}
    // {{{ _addArray

    /**
     * 配列の追加
     *
     * @param array $line 追加する配列
     * @param int $indent インデント
     * @param array $ret 要素追加用配列
     * @param array 配列を追加する配列
     * @return array 要素配列追加後の配列
     */
    private function _addArray($line, $indent, $ret)
    {
        $history = array();
        $key = '';
        $value = '';

        if (count($line) > 1) {

            // 配列の要素数が複数
            $commonPath = $this->_path;

            foreach ($line as $index => $item) {
                $ret = $this->_addArray(
                    array($index => $item), $indent, $ret
                );
                $this->_path = $commonPath;
            }

        } else {

            // 配列の要素数が1
            $key = key($line);
            $value = isset($line[ $key ]) ? $line[ $key ] : null;
            if ($key === '__!YAMLZero') {
                $key = '0';
            }

            if (
                $indent === 0 &&
                empty($this->_groupAnchor) &&
                empty($this->_groupAlias)
            ) {

                // インデント0 アンカーとエイリアスが存在している
                if ($key || $key === '' || $key === '0') {
                    $ret[ $key ] = $value;
                } else {
                    $ret[] = $value;
                    end($ret);
                    $key = key($ret);
                }

                $this->_path[ $indent ] = $key;
            } else {
                $_arr = $ret;
                $history[] = $_arr;

                foreach ($this->_path as $item) {
                    $_arr = $_arr[ $item ];
                    $history[] = $_arr;
                }

                if (!empty($this->_groupAlias)) {

                    // 要素がエイリアス
                    $alias = $this->_groupAlias;

                    if (!isset($this->_savedGroups[ $alias ])) {
                        throw new xFrameworkPX_YAML_Exception();
                    }

                    $groupPath = $this->_savedGroups[ $alias ];

                    $aliasItem = $ret;

                    foreach ($groupPath as $item) {
                        $aliasItem = $aliasItem[$item];
                    }

                    $value = $aliasItem;

                    $this->_groupAlias = '';
                }

                // 通常の要素
                if (is_string($key) && $key == '<<') {

                    if (!is_array($_arr)) {
                        $_arr = array ();
                    }

                    $_arr = array_merge($_arr, $value);
                } else if ($key || $key === '' || $key === '0') {
                    $_arr[$key] = $value;
                } else {

                    if (!is_array($_arr)) {
                        $_arr = array($value);
                        $key = 0;
                    } else {
                        $_arr[] = $value;
                        end($_arr);
                        $key = key($_arr);
                    }
                }

                $pathR = array_reverse($this->_path);
                $historyR = array_reverse($history);
                $historyR[0] = $_arr;

                $cnt = count($historyR) - 1;
                for ($i = 0; $i < $cnt; $i++) {
                    $historyR[ $i+1 ][ $pathR[ $i ] ] = $historyR[ $i ];
                }

                $ret = $historyR[ $cnt ];

                $this->_path[ $indent ] = $key;

                if (!empty($this->_groupAnchor)) {

                    // 要素がアンカー
                    $anchor = $this->_groupAnchor;

                    $this->_savedGroups[ $anchor ] = $this->_path;

                    if (is_array($value)) {
                        $key = key($value);

                        if (!is_int($key)) {
                            $this->_savedGroups[ $anchor ][ $indent + 2 ]
                                = $key;
                        }

                    }

                    $this->_groupAnchor = '';
                }

            }

        }

        return $ret;
    }
    // }}}
    // {{{ _unquote

    /**
     * クォーテーション除去メソッド
     *
     * 文字列を囲っている引用符を除去する
     *
     * @param string $value 文字列
     * @return string クォーテーションが除去された文字列
     * @access private
     */
    private function _unquote($value)
    {
        $ret = $value;

        if (!empty($value) && is_string($value)) {

            if ($value[ 0 ] == '\'') {
                $ret = trim($value, '\'');
            } else if ($value[0] == '"') {
                $ret = trim($value, '"');
            }

        }

        return $ret;
    }

    // }}}
    // {{{ _yamlizeValue

    /**
     * YAML 値変換メソッド
     *
     * @param mixed $value 値
     * @param int indentCnt インデント幅
     * @return mixed YAML 値
     * @access private
     */
    private function _yamlizeValue($value, $indentCnt, $stringQuote = true)
    {
        $ret = '';
        $quoteMode = (is_bool($stringQuote)) ? $stringQuote : true;

        if ( is_null($value)) {
            $ret = 'null';
        } else if (is_string($value)) {

            if (strPos($value, "\n") !== false) {

                // 改行あり
                $exploded = explode("\n", $value);
                $ret = '|';

                // インデント生成
                $indentCnt += $this->_indent;
                $indent = str_repeat(' ', $indentCnt);

                foreach ($exploded as $line) {
                    $ret .= "\n" . $indent . trim($line);
                }

            } else {

                // 改行なし
                $ret = $value;

                if (
                    !preg_match('/^(?:(")|(?:\')).*(?(1)"|\')$/', $ret) &&
                    $quoteMode
                ) {
                    $ret = sprintf("'%s'", $value);
                }

            }

        } else if (is_bool($value)) {
            $ret = ($value) ? 'true' : 'false';
        } else if (is_numeric($value)) {
            $ret = (string)$value;

            if (is_float($value) && preg_match('/^[0-9]+$/', $ret)) {
                $ret .= '.0';
            }

        } else {
            $ret = $value;
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
