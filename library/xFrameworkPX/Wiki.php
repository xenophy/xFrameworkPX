<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Wiki Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Wiki.php 1344 2010-01-14 14:29:51Z kotsutsumi $
 */

// {{{ xFrameworkPX_Wiki

/**
 * xFrameworkPX_Wiki Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Wiki
 */
class xFrameworkPX_Wiki
{
    // {{{ props

    /**
     * hタグ開始レベル
     *
     * @var int
     */
    private $_hStartLv = 3;

    /**
     * 注釈カウンター
     *
     * @var int
     */
    private $_noteCount = 0;

    /**
     * 注釈一覧
     *
     * @var array
     */
    private $_notes = array();

    // }}}
    // {{{ _addBlock

    /**
     * ブロック要素追加メソッド
     *
     * @param array $to
     * @param array $from
     * @param string $preType
     * @return array
     */
    private function _addBlock($to, $from, $preType = null)
    {

        // ローカル変数初期化
        $ret = $to;

        // 要素追加処理
        if ($preType === $from['type']) {

            // 子要素として追加
            $arrLast = array_pop($ret);
            $arrLast['content'][] = $from['content'][0];
            $ret[] = $arrLast;
        } else {
            $ret[] = $from;
        }

        return $ret;
    }


    // }}}
    // {{{ _getBlock

    /**
     * ブロック取得メソッド
     *
     * @param array $src
     * @param int $index
     * @param string $type
     * @param string $inType
     * @return string
     */
    private function _getBlock($src, &$index, $type, $inType = null)
    {

        $ret = '';
        $cnt = count($src);
        $i = 0;
        $line = null;
        $nestFlag = 0;

        // ブロック要素取得
        for ($i = $index + 1; $i < $cnt; ++$i) {
            $srcLine = $src[$i];

            if ($type == 'src') {

                // 整形済みテキスト( src )の場合

                // ネスト判定
                $nestFlag += preg_match('/^\{{3}/', $srcLine) ? 1 : 0;

                if (preg_match('/\}{3}$/', $srcLine)) {

                    if ($nestFlag > 0) {
                        --$nestFlag;
                    } else {
                        break;
                    }

                }

                $ret .= ($i === $index + 1) ? $srcLine : "\n" . $srcLine;
            } else {
                $line = $this->_parseLine($srcLine);

                if (is_null($inType)) {
                    $inType = ($line['type'] !== false)
                                ? $line['type']
                                : null;
                }

                if ($line['type'] !== $inType) {
                    --$i;
                    break;
                }

                if (strpos($srcLine, '//') !== 0) {
                    $ret .= "\n" . $srcLine;
                }
            }

        }

        $index = $i;

        return $ret;
    }

    // }}}
    // {{{ _getLevel

    /**
     * ネストレベル取得メソッド
     *
     * @param string $line
     * @return int
     */
    private function _getLevel($line)
    {

        $ret = 1;

        // ネストレベルの計算
        for ($i = 1; $i < 3; ++$i) {

            if ($line[0] !== $line[$i]) {
                break;
            }

            $ret = $i + 1;
        }

        return $ret;
    }

    // }}}
    // {{{ _parse

    /**
     * Wikiパースメソッド
     *
     * @param mixed $source Wikiソースコード
     * @return array パース結果
     */
    private function _parse($source, $lv = 0)
    {
        $ret = array();
        $src = null;
        $lv = (empty($lv)) ? 0 : (int)$lv;
        $cnt = 0;
        $temp = null;
        $line = null;
        $bakType = '';
        $inType = '';

        // ソースの配列変換
        if (!is_array($source)) {
            $source = str_replace(array("\r\n", "\r"), "\n", $source);
            $src = explode("\n", $source);
        } else {
            $src = $source;
        }

        // パース処理
        $cnt = count($src);

        for ($i = 0; $i < $cnt; ++$i) {
            $bakType = (!empty($temp['type']))
                        ? $temp['type']
                        : '';
            $temp = $this->_parseLine($src[$i]);

            switch ($temp['type']) {

                // 見出し, 水平線
                case ('h'):

                case ('hr'):

                    // 要素追加
                    $ret = $this->_addBlock($ret, $temp);

                    $temp['type'] = '';

                    break;

                // 整形済みテキスト
                case ('pre'):

                    // 要素追加
                    $ret = $this->_addBlock($ret, $temp, $bakType);

                    break;

                case ('src'):

                    if (!preg_match('/^\{{3}.*\}{3}$/', $src[$i])) {

                        if (
                            is_null($temp['content'][0]['line']) ||
                            $temp['content'][0]['line'] === ''
                        ) {
                            $temp['content'][0]['line'] =
                                $this->_getBlock($src, $i, 'src');
                        } else {
                            $temp['content'][0]['line'] .=
                                "\n" . $this->_getBlock($src, $i, 'src');
                        }

                    }

                    // 要素追加
                    $ret = $this->_addBlock($ret, $temp, $bakType);

                    break;

                // 引用文
                case ('quote'):

                    // 要素追加
                    $ret = $this->_addBlock($ret, $temp, $bakType);

                    break;

                // リスト
                case ('ul'):

                case ('ol'):

                case ('dl'):
                    $line = $temp['content'][0]['line'];

                    if ($line[0] == '~') {
                        $line = substr($line, 1);

                        $line = ($line === false) ? '' : $line;

                        if ($line !== '') {
                            $parseLine = $this->_parseLine($line);
                            $inType = ($parseLine['type'] !== false)
                                        ? $parseLine['type']
                                        : null;
                        } else {
                            $inType = null;
                        }

                        $line .= $this->_getBlock(
                            $src, $i, $temp['type'], $inType
                        );

                        $temp['content'][0]['line'] =
                            $this->_parse($line, $lv + 1);
                    }

                    // 要素追加
                    $ret = $this->_addBlock($ret, $temp, $bakType);

                    break;

                // テーブル
                case ('table'):

                    // 要素追加
                    $ret = $this->_addBlock($ret, $temp, $bakType);

                    break;

                // 配置
                case ('div'):
                    $line = $temp['content'][0]['line'];

                    if ($line !== '') {
                        $parseLine = $this->_parseLine($line);
                        $inType = ($parseLine['type'] !== false)
                                    ? $parseLine['type']
                                    : null;
                    } else {
                        $inType = null;
                    }

                    $line .= $this->_getBlock(
                        $src, $i, $temp['type'], $inType
                    );

                    $temp['content'][0]['line'] = $line;

                    // 要素追加
                    $ret = $this->_addBlock($ret, $temp);

                    break;

                // 段落
                case ('p'):

                    // 要素追加
                    $ret = $this->_addBlock($ret, $temp, $bakType);

                    break;

                // コメント
                case ('com'):
                    $temp['type'] = $bakType;

                    break;

            }

        }

        return $ret;
    }

    // }}}
    // {{{ _parseLine

    /**
     * 行パースメソッド
     *
     * @param string $line
     * @return array
     */
    private function _parseLine($line)
    {
        $ret = array();
        $level = 0;
        $match = array();

        // ブロックのパース処理
        switch (true) {

            // 見出し
            case (preg_match('/^\*{1,3}([^\*]+.*)$/', $line, $match)):
                $level = $this->_getLevel($line);
                $ret['type'] = 'h';
                $ret['content'][] = array(
                    'level' => $level,
                    'line' => $match[1]
                );

                break;

            // 整形済みテキスト
            case (preg_match('/^(?: |\t)(.*)$/', $line, $match)):
                $ret['type'] = 'pre';
                $ret['content'][] = array('line' => $match[1]);

                break;

            case (preg_match(
                '/^\{{3}(?:([^\{\}\|]*)\|)?(.*)\}{3}$/', $line, $match
            )):

            case (preg_match(
                '/^\{{3}(?:([^\{\}\|]*)\|)?(.*)$/', $line, $match
            )):
                $ret['type'] = 'src';
                $ret['content'][] = array(
                    'name' => ($match[1] !== '')
                               ? strtolower(trim($match[1]))
                               : null,

                    'line' => ($match[2] !== '') ? $match[2] : null
                );

                break;

            // 引用文
            case (preg_match('/^>{1,3}([^>]+.*)$/', $line, $match)):
                $level = $this->_getLevel($line);
                $ret['type'] = 'quote';
                $ret['content'][] = array(
                    'level' => $level,
                    'line' => $match[1]
                );

                break;

            // 水平線
            case (preg_match('/^-{4,}/', $line)):
                $ret['type'] = 'hr';

                break;

            // リスト（順序なし）
            case (preg_match('/^-{1,3}(.*)$/', $line, $match)):
                $level = $this->_getLevel($line);
                $ret['type'] = 'ul';
                $ret['content'][] = array(
                    'level' => $level,
                    'line' => $match[1]
                );

                break;

            // リスト（順序あり）
            case (preg_match('/^\+{1,3}(.*)$/', $line, $match)):
                $level = $this->_getLevel($line);
                $ret['type'] = 'ol';
                $ret['content'][] = array(
                    'level' => $level,
                    'line' => $match[1]
                );

                break;

            // 定義リスト
            case (preg_match('/^:{1,3}([^\|]*)\|(.+)$/', $line, $match)):
                $level = $this->_getLevel($line);
                $ret['type'] = 'dl';
                $ret['content'][] = array(
                    'level' => $level,
                    'line' => $match[2],
                    'option' => $match[1]
                );

                break;

            // テーブル
            case (preg_match(
                '/^\|(.+)\|(c|r|l)?(?:\((.*)\))?$/i', $line, $match
            )):
                $ret['type'] = 'table';
                $ret['align'] = (isset($match[2]))
                                ? strtolower($match[2])
                                : '';
                $ret['summary'] = (isset($match[3])) ? $match[3] : '';
                $ret['content'][] = array('line' => $match[1]);

                break;

            // 配置
            case (preg_match('/^(left):(.*)$/i', $line, $match)):

            case (preg_match('/^(center):(.*)$/i', $line, $match)):

            case (preg_match('/^(right):(.*)$/i', $line, $match)):
                $ret['type'] = 'div';
                $ret['content'][] = array(
                    'line' => $match[2],
                    'option' => strtolower($match[1])
                );

                break;

            // コメント
            case (preg_match('/^\/{2}(.*)/', $line, $match)):
                $ret['type'] = 'com';

                break;

            // 段落
            case (preg_match('/^~(.*)$/', $line, $match)):

            case (preg_match('/^(.+)$/', $line, $match)):
                $ret['type'] = 'p';
                $ret['content'][] = array('line' => $match[1]);

                break;

            default:
                $ret['type'] = false;
        }

        return $ret;
    }

    // }}}
    // {{{ wiki

    /**
     * Wiki解析メソッド
     *
     * @param string $text
     * @return string
     */
    public function wiki($text)
    {
        $ret = '';
        $temp = array();

        // 注釈ノート初期化
        $this->_notes = array();
        $this->_noteCount = 0;

        // wikiコード解析
        $temp = $this->_parse($text);
        $ret = $this->_render($temp);
        $ret .= $this->_getNotes();

        return $ret;
    }

    // }}}
    // {{{ _render

    /**
     * レンダリングメソッド
     *
    * @param array $parse
    * @return mixed
    */
    private function _render($parse)
    {
        $ret = '';

        // レンダリング処理
        foreach ($parse as $line) {

            $ret .= $this->_rendLine($line);

        }

        return $ret;
    }

    // }}}
    // {{{ _rendLine

    /**
     * 行レンダリングメソッド
     *
     * @param array $line
     * @return mixed
     */
    private function _rendLine($line)
    {
        $ret = '';
        $content = null;
        $lv = 0;
        $name = '';
        $lineContent = '';
        $temp = array();

        // レンダリング処理
        switch ($line['type']) {

            // 見出し
            case ('h'):
                $content = $line['content'][0];
                $lv = $content['level'] + $this->_hStartLv - 1;
                $lineContent = $this->_toHtml($content['line']);

                $ret .= sprintf("<h%s>%s</h%s>\n", $lv, $lineContent, $lv);

                break;

            // 整形済みテキスト
            case ('pre'):

                foreach ($line['content'] as $content) {
                    $temp[] = $content['line'];
                }

                $ret = sprintf(
                    "<pre>%s</pre>\n",
                    $this->_toHtml(implode("\n", $temp), false)
                );

                break;

            case ('src'):
                $content = $line['content'][0];

                if (!is_null($content['name'])) {
                    $name = '_' . $content['name'];
                }

                $ret = sprintf(
                    "<pre class=\"code%s\">\n%s\n</pre>\n",
                    $name,
                    $this->_toHtml($content['line'], false)
                );
                break;

            // 引用文
            case ('quote'):

                $i = 0;
                foreach ($line['content'] as $content) {
                    $ret .= $this->_createQuote(
                        $content['line'],
                        $i,
                        $content['level'],
                        'blockquote'
                    );
                }

                $ret .= $this->_createQuote('', $i, 0, 'blockquote');

                break;

            // 水平線
            case ('hr'):
                $ret = "<hr />\n";

                break;

            // リスト（順序なし）
            case ('ul'):
                $i = 0;

                foreach ($line['content'] as $content) {

                    if (is_array($content['line'])) {

                        while (is_array($content['line'])) {

                            $content['line'] = $this->_render(
                                $content['line']
                            );

                        }

                    } else {
                        $content['line'] = $this->_toHtml(
                            $content['line']
                        );
                    }

                    $ret .= $this->_createList(
                        $content['line'],
                        $i,
                        $content['level'],
                        'ul',
                        'li'
                    );
                }

                $ret .= $this->_createList('', $i, 0, 'ul', 'li');

                break;

            // リスト（順序あり）
            case ('ol'):

                $i = 0;
                foreach ($line['content'] as $content) {

                    if (is_array($content['line'])) {

                        while (is_array($content['line'])) {

                            $content['line'] = $this->_render(
                                $content['line']
                            );

                        }

                    } else {
                        $content['line'] = $this->_toHtml(
                            $content['line']
                        );
                    }

                    $ret .= $this->_createList(
                        $content['line'],
                        $i,
                        $content['level'],
                        'ol',
                        'li'
                    );
                }

                $ret .= $this->_createList('', $i, 0, 'ol', 'li');

                break;

            // 定義リスト
            case ('dl'):
                $i = 0;

                foreach ($line['content'] as $content) {

                    if (is_array($content['line'])) {

                        while (is_array($content['line'])) {
                            $content['line'] = $this->_render(
                                $content['line']
                            );
                        }

                    } else {
                        $content['line'] = $this->_toHtml(
                            $content['line']
                        );
                    }

                    $content['option'] = $this->_toHtml(
                        $content['option']
                    );

                    $ret .= $this->_createList(
                        $content['line'],
                        $i,
                        $content['level'],
                        'dl',
                        'dd',
                        $content['option']
                    );
                }

                $ret .= $this->_createList('', $i, 0, 'dl', 'dd');

                break;

            // テーブル
            case ('table'):

                // テーブルパース
                $line = $this->_parseTable($line);

                // テーブル生成
                $align = (empty($line['align']))
                        ? ''
                        : sprintf(' align="%s"', $line['align']);
                $summary = (empty($line['summary']))
                        ? ''
                        : sprintf(' summary="%s"', $line['summary']);

                $ret = sprintf("<table%s%s>\n", $align, $summary);

                // caption
                $firstCell = $line['content'][0]['line'][0];

                if ($firstCell['type'] == 'caption') {
                    $align = sprintf(' align="%s"', $firstCell['align']);

                    $ret .= sprintf(
                        "<caption%s>%s</caption>\n",
                        (empty($firstCell['align']))
                        ? ''
                        : $align,
                        $this->_toHtml($firstCell['value'])
                    );

                    array_shift($line['content'][0]['line']);

                    if (empty($line['content'][0]['line'])) {
                        array_shift($line['content']);
                    }
                }

                foreach ($line['content'] as $content) {
                    $ret .= "<tr>\n";

                    foreach ($content['line'] as $cell) {

                        if (!$cell['colspan'] && !$cell['rowspan']) {
                            $align = ($cell['align'] == '')
                                    ? ''
                                    : sprintf(
                                        ' align="%s"', $cell['align']
                                    );
                            $valign = ($cell['valign'] == '')
                                        ? ''
                                        : sprintf(
                                            ' valign="%s"',
                                            $cell['valign']
                                        );
                            $bgColor = ($cell['bgcolor'] == '')
                                        ? ''
                                        : sprintf(
                                            ' bgcolor="%s"',
                                            $cell['bgcolor']
                                        );
                            $colSpan = ($cell['colcount'] <= 1)
                                        ? ''
                                        : sprintf(
                                            ' colspan="%s"',
                                            $cell['colcount']
                                        );
                            $rowSpan = ($cell['rowcount'] <= 1)
                                        ? ''
                                        : sprintf(
                                            ' rowspan="%s"',
                                            $cell['rowcount']
                                        );

                            $ret .= sprintf(
                                "<%s%s%s%s%s%s>%s</%s>\n",
                                $cell['type'],
                                $align,
                                $valign,
                                $bgColor,
                                $colSpan,
                                $rowSpan,
                                $this->_toHtml($cell['value']),
                                $cell['type']
                            );
                        }
                    }

                    $ret .= "</tr>\n";
                }

                $ret .= "</table>\n";

                break;

            // 配置
            case ('div'):
                $content = $line['content'][0];
                $lineContent = $this->_toHtml($content['line']);

                $ret = sprintf(
                    "<div align=\"%s\">%s</div>\n",
                    $content['option'],
                    $lineContent
                );

                break;

            // 段落
            case ('p'):

                foreach ($line['content'] as $key => $line) {
                    $lineContent .= ($key > 0)
                                ? "\n" . $line['line']
                                : $line['line'];
                }

                $lineContent = $this->_toHtml($lineContent);

                $ret = sprintf("<p>%s</p>\n", $lineContent);

                break;

        }

        return $ret;
    }

    // }}}
    // {{{ _parseTable

    /**
     * テーブルパーサーメソッド
     *
     * @param array $arrLine
     * @return array
     */
    private function _parseTable($line)
    {
        $ret = $line;
        $temp = null;
        $cells = null;
        $cell = null;
        $match = array();
        $isNotValue = true;

        // align
        switch ($ret['align']) {

            case ('l'):
                $ret['align'] = 'left';
                break;

            case ('c'):
                $ret['align'] = 'center';
                break;

            case ('r'):
                $ret['align'] = 'right';
                break;

            default:
                $ret['align'] = null;

        }

        // セル
        foreach ($ret['content'] as $rowKey => $rowContent) {

            $cells = array();
            $temp = explode('|', $rowContent['line']);

            foreach ($temp as $colKey => $colContent) {

                // セル要素初期化
                $cell = array();
                $cell['type'] = 'td';
                $cell['align'] = '';
                $cell['valign'] = '';
                $cell['value'] = '';
                $cell['bgcolor'] = '';
                $cell['colspan'] = false;
                $cell['rowspan'] = false;
                $cell['colcount'] = 1;
                $cell['rowcount'] = 1;

                // セルタイプ設定
                if (
                    $rowKey === 0 &&
                    $colKey === 0 &&
                    preg_match('/^\*(.*)/', $colContent, $match)
                ) {

                    // caption
                    $cell['type'] = 'caption';
                    $colContent = $match[1];
                } else if (preg_match('/^~(.*)/', $colContent, $match)) {

                    // th
                    $cell['type'] = 'th';
                    $colContent = $match[1];
                } else if (preg_match('/^>[[:blank:]]*$/', $colContent)) {

                    // colspan
                    $cell['colspan'] = true;
                    $colContent = '';
                } else if (preg_match('/^\^[[:blank:]]*$/', $colContent)) {

                    // rowspan
                    if ($rowKey > 0) {
                        $cell['rowspan'] = true;
                        $colContent = '';
                    }

                }

                if ($colKey > 0 && $cells[$colKey - 1]['colspan']) {
                    $cell['colcount'] =
                        $cells[$colKey - 1]['colcount'] + 1;
                }

                if ($cell['rowspan'] && $rowKey > 0) {
                    $y = $rowKey - 1;
                    $x = $colKey;
                    $rowCount = 1;

                    while (
                        $y >= 0 &&
                        isset($ret['content'][$y]['line'][$x])
                    ) {
                        $preCell = $ret['content'][$y]['line'][$x];

                        ++$rowCount;

                        if (!$preCell['rowspan']) {
                            $preCell['rowcount'] = $rowCount;
                            $ret['content'][$y]['line'][$x] =
                                $preCell;

                            break;
                        }

                        --$y;
                    }

                }

                // 表示設定
                $isNotValue = true;

                while ($isNotValue) {

                    switch (true) {

                        // align = "left"
                        case (
                            preg_match('/^left:(.*)$/i', $colContent, $match)
                        ):
                            $cell['align'] = 'left';
                            $colContent = $match[1];

                            break;

                        // align = "center"
                        case (
                            preg_match(
                                '/^center:(.*)$/i', $colContent, $match
                            )
                        ):
                            $cell['align'] = 'center';
                            $colContent = $match[1];

                            break;

                        // align = "right"
                        case (
                            preg_match('/^right:(.*)$/i', $colContent, $match)
                        ):
                            $cell['align'] = 'right';
                            $colContent = $match[1];

                            break;

                        // valign = "top"
                        case (
                            preg_match('/^top:(.*)$/i', $colContent, $match)
                        ):

                            if ( $cell['type'] == 'caption' ) {
                                $cell['align'] = 'top';
                            } else {
                                $cell['valign'] = 'top';
                            }

                            $colContent = $match[1];

                            break;

                        // valign = "middle"
                        case (
                            $cell['type'] != 'caption' &&
                            preg_match(
                                '/^middle:(.*)$/i', $colContent, $match
                            )
                        ):
                            $cell['valign'] = 'middle';
                            $colContent = $match[1];

                            break;

                        // valign = "bottom"
                        case (
                            preg_match(
                                '/^bottom:(.*)$/i', $colContent, $match
                            )
                        ):

                            if ($cell['type'] == 'caption') {
                                $cell['align'] = 'bottom';
                            } else {
                                $cell['valign'] = 'bottom';
                            }

                            $colContent = $match[1];

                            break;

                        // bgcolor
                        case (
                            $cell['type'] != 'caption' &&
                            preg_match(
                                '/^bgcolor\(([^\)]+)\):(.*)$/i',
                                $colContent,
                                $match
                            )
                        ):
                            $cell['bgcolor'] = $match[1];
                            $colContent = $match[2];

                            break;

                        // 表示設定なし
                        default:
                            $isNotValue = false;
                    }

                }

                $cell['value'] = $colContent;
                $cells[$colKey] = $cell;
            }

            $ret['content'][$rowKey]['line'] = $cells;
        }

        return $ret;
    }

    // }}}
    // {{{ _toHtml

    /**
     * HTML変換メソッド
     *
     * @param string $line
     * @param bool $bInline
     * @return string
     */
    private function _toHtml($line, $inliner = true)
    {
        $ret = $line;

        // HTMLエスケープ処理
        $ret = str_replace('<', '&lt;', $ret);
        $ret = str_replace('>', '&gt;', $ret);
        $ret = str_replace('"', '&quot;', $ret);

        // インライン要素HTML変換
        if ($inliner) {
            $ret = $this->_execFootNote($ret);
            $ret = $this->_execInline($ret);
        }

        return $ret;
    }

    // }}}
    // {{{ _createList

    /**
     * リスト生成メソッド
     *
     * @param string $line
     * @param int $lv
     * @param int $nextLv
     * @param string $tag1
     * @param string $tag2
     * @param string $option
     * @return string
     */
    private function _createList(
        $line, &$lv, $nextLv, $tag1, $tag2, $option = null
    )
    {
        $ret = '';

        // リスト生成処理
        if ($lv == $nextLv) {
            $ret = "\n";
            $i = $nextLv;
        } else if ($lv > $nextLv) {

            for ($i = $lv; $i > $nextLv; --$i) {
                $ret .= sprintf("</%s>\n", $tag1);
            }

        } else if ($lv < $nextLv) {
            $ret = "\n";

            for ($i = $lv; $i < $nextLv; ++$i) {
                $ret .= sprintf("<%s>\n", $tag1);
            }

        }
        $lv = $i;

        if ($tag1 == 'dl' && $line !== '') {
            $ret .= sprintf("<dt>%s</dt>\n", $option);
        }

        if ($line !== '') {
            $ret .= sprintf("<%s>%s</%s>\n", $tag2, $line, $tag2);
        }

        return $ret;
    }

    // }}}
    // {{{ _createQuote

    /**
     * クォート生成メソッド
     *
     * @param string $line
     * @param int $lv
     * @param int $nextLv
     * @param string $tag
     * @return string
     */
    private function _createQuote($line, &$lv, $nextLv, $tag)
    {
        $ret = '';

        // クォート生成処理
        if ($lv == $nextLv) {
            $ret = "<br />\n";
            $i = $nextLv;
        } else if ($lv > $nextLv) {
            $ret = "\n";

            for ($i = $lv; $i > $nextLv; --$i) {
                $ret .= sprintf("</%s>\n", $tag);
            }

        } else if ($lv < $nextLv) {
            $ret = "\n";

            for ($i = $lv; $i < $nextLv; ++$i) {
                $ret .= sprintf("<%s>\n", $tag);
            }

        }

        $lv = $i;
        $ret .= (empty($line)) ? '' : $line;

        return $ret;
    }

    // }}}
    // {{{ _execInline

    /**
     * インライン要素変換メソッド
     *
     * @param string $line
     * @return string
     */
    private function _execInline($line)
    {
        $ret = $line;

        // 改行
        $ret = str_replace('&br;', "\n", $ret);

        // 強調と斜体
        $ret = preg_replace(
            "/'{5}('[^']+?')'{5}/", '<strong><em>$1</em></strong>', $ret
        );
        $ret = preg_replace(
            "/'{5}([^']+?)'{5}/", '<strong><em>$1</em></strong>', $ret
        );
        $ret = preg_replace("/'{3}([^']+?)'{3}/", '<em>$1</em>', $ret);
        $ret = preg_replace(
            "/'{2}([^']+?)'{2}/", '<strong>$1</strong>', $ret
        );

        // 打ち消し線
        $ret = preg_replace('/%{3}(.+?)%{3}/', '<del>$1</del>', $ret);

        // 下線
        $ret = preg_replace(
            '/%{2}(.+?)%{2}/',
//            '<span class="underline">$1</span>',
            '<u>$1</u>',
            $ret
        );

        // アンカー
        $ret = $this->_convertTag('aname', '<a id="$1">$2</a>', $ret);

        // フォントサイズ
        $ret = $this->_convertTag(
            'size', '<span style="font-size:$1;">$2</span>', $ret
        );

        // 文字色
        $ret = $this->_convertTag(
            'color', '<span style="color:$1;">$2</span>', $ret
        );

        // 画像
        $ret = $this->_convertTag('img', '<img src="$1" alt="$2" />', $ret);

        // リンク
        $ret = $this->_convertLink($ret);

        return nl2br($ret);
    }

    // }}}
    // {{{ _getNotes

    /**
     * 注釈取得
     *
     * @return string
     */
    private function _getNotes()
    {
        $ret = '';
        if (count($this->_notes) > 0) {
            ksort($this->_notes);
            $ret = sprintf(
                "<div class=\"note\">\n%s\n</div>",
                implode("\n", $this->_notes)
            );
        }

        return $ret;
    }

    // }}}
    // {{{ _execFootNote

    /**
     * 注釈変換処理
     *
     * @param string $line
     * @return string
     */
    private function _execFootNote($line)
    {
        $ret = $line;
        $pattern = '/\(\(((?:(?R)|(?!\)\)).)*)\)\)/i';
        $match = array();
        $cnt = 0;
        $replace = '';

        // 注釈変換処理
        while (preg_match($pattern, $ret, $match)) {
            $cnt = $this->_noteCount = $this->_noteCount + 1;
            $replace = sprintf(
                '<sup class="footnote">'.
                '<a href="#foottext%s" id="footnote%S">*%s</a></sup>',
                $cnt, $cnt, $cnt
            );

            $temp = $this->_execInline($this->_execFootNote($match[1]));

            $this->_notes[$cnt] = sprintf(
                '<p class="foottext"><a href="#footnote%s" id="foottext%s">' .
                '*%s</a>%s</p>',
                $cnt, $cnt, $cnt, $temp
            );

            $ret = preg_replace($pattern, $replace, $ret, 1);

        }

        return $ret;
    }

    // }}}
    // {{{ _convertLink

    /**
     * リンク変換メソッド
     *
     * @param string $line
     * @return string
     */
    private function _convertLink($line)
    {
        $ret = $line;
        $pattern = '/\[\[(?:((?:(?!\]\]).)+)(?:&gt;))?'
                    . '((?:(?:https?|ftp|news):\/\/|'
                    . 'mailto:|\?|#|\.|\/)'
                    . '[\w\/\@\$()!?&%#:;.,~\'=*+-]*)\]\]/i';
        $match = array();
        $replace = '';

        // 変換処理
        while (preg_match($pattern, $ret, $match)) {

            if ($match[1] == '') {
                $match[1] = $match[2];
            }

            $temp = explode('&gt;', $match[2]);
            $attr = '';
            if (count($temp>0)) {
                $attr = ' ';
                $match[2] = $temp[0];
                if (isset($temp[1])) {
                    $attr .= sprintf('class="%s"', $temp[1]);
                }
            }

            $replace = sprintf(
                '<a href="%2$s"%3$s>%1$s</a>',
                $match[1],
                $match[2],
                $attr
            );

            $ret = preg_replace($pattern, $replace, $ret, 1);
        }

        return $ret;
    }

    // }}}
    // {{{ _convertTag

    /**
     * タグ変換メソッド
     *
     * @param string $name
     * @param string $replace
     * @param string $line
     * @return string
     */
    private function _convertTag($name, $replace, $line)
    {
        // ローカル変数初期化
        $ret = $line;
        $pattern = sprintf(
            '/&(?:%s)(?:\(((?:(?!\)[;{]).)*)\))\{((?:(?R)|(?!};).)*)\};/i',
            $name
        );

        // 変換処理
        while (preg_match($pattern, $ret)) {
            $ret = preg_replace($pattern, $replace, $ret);
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
