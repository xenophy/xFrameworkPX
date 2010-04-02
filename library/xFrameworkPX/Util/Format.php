<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Util_Format Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Util
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Format.php 1398 2010-01-19 10:08:38Z tamari $
 */

// {{{ xFrameworkPX_Util_Format

/**
 * xFrameworkPX_Util_Format Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Util
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Util_Format
 */
class xFrameworkPX_Util_Format
{

    // {{{ props

    /**
     * 括弧の未完回数
     *
     * @var int
     */
    private $_parenthesis = 0;

    /**
     * BETWEEN句発生フラグ
     *
     * @var bool
     */
    private $_betweenFlg = false;

    /**
     * LIMIT句発生フラグ
     *
     * @var bool
     */
    private $_limitFlg = false;

    /**
     * UNION句の発生フラグ
     *
     * @var bool
     */
    private $_unionFlg = false;

    /**
     * ON句出現回数
     *
     * @var int
     */
    private $_onCnt = 0;

    /**
     * タブ文字
     *
     * @var string
     */
    private $_tab = '';

    // }}}
    // {{{ fromatSQL

    /**
     * SQL整形スタティックメソッド
     *
     * @param string $targetSQL
     * @return string
     */
    public static function formatSQL($targetSql, $html=false, $color=false)
    {
        $format = new xFrameworkPX_Util_Format();
        if($html) {

            if($color) {
                require_once "geshi/geshi.php";
                $geshi = new GeSHi($format->_formatSQL($targetSql), 'sql');
                $geshi->set_header_type(GESHI_HEADER_DIV);
                $geshi->set_symbols_style('color: red;');

                return str_replace(
                    '    ',
                    '&nbsp;&nbsp;&nbsp;&nbsp;',
                    $geshi->parse_code()
                );
            }

            return nl2br(
                str_replace(
                    '    ',
                    '&nbsp;&nbsp;&nbsp;&nbsp;',
                    $format->_formatSQL($targetSql)
                )
            );
        }

        return $format->_formatSQL($targetSql);
    }

    // }}}
    // {{{ _formatSQL

    /**
     * SQL整形メソッド
     *
     * @param string $targetSQL
     * @return string
     */
    private function _formatSQL($targetSql)
    {

        $retSql = '';
        $startPos = 0;
        $endPos = 0;
        $sqlTemp = array();
        $unionFlg = false;
        $match = array();
        $temp = null;

        if (!is_null($targetSql) && $targetSql !== '') {
            $targetSql = str_replace(array("\r\n", "\r"), "\n", $targetSql);

            $targetSql = str_replace(
                array("\t", "\n"),
                ' ',
                $targetSql
            );

            $targetSql = str_replace(';', ';><', $targetSql);
            $sqlTemp = explode('><', $targetSql);

            foreach ($sqlTemp as $sql) {

                for ($i = 0; $i < strlen($sql); ++$i) {

                    if (preg_match('/^[a-z]/i', substr($sql, $i)) === 1) {
                        $sql = substr($sql, $i);
                        break;
                    }

                }

                if (preg_match('/^[a-z]/i', $sql) === 0) {
                    continue;
                }

                switch (1) {
                    case (preg_match('/^select/i', $sql)):

                        while (true) {
                            $retSql .= $this->_formatSelect(
                                $sql, $startPos, $endPos
                            );

                            if (!$this->_unionFlg) {
                                $startPos = 0;
                                break;
                            }

                            $startPos = $endPos + 1;
                            $this->_tab = '';
                            $this->_parenthesis = 0;
                            $this->_onCnt = 0;
                        }

                        break;

                    case (preg_match('/^insert/i', $sql)):
                        $retSql .= $this->_formatInsert(
                            $sql, $startPos, $endPos
                        );
                        $startPos = 0;

                        break;

                    case (preg_match('/^update/i', $sql)):
                        $retSql .= $this->_formatUpdate(
                            $sql, $startPos, $endPos
                        );
                        $startPos = 0;

                        break;

                    case (preg_match('/^delete/i', $sql)):
                        $retSql .= $this->_formatDelete(
                            $sql, $startPos, $endPos
                        );
                        $startPos = 0;

                        break;

                    case (preg_match('/^replace/i', $sql)):
                        $retSql .= $this->_formatReplace(
                            $sql, $startPos, $endPos
                        );
                        $startPos = 0;

                        break;

                    default:
                        $retSql .= '';
                        break;
                }

                if (preg_match("/\n$/", $sql) === 0) {
                    $retSql .= "\n";
                }

            }
        }

        while (true) {
            $temp = $retSql;
            $retSql = str_replace('  ', ' ', $retSql);
            $retSql = str_replace('( ', '(', $retSql);
            $retSql = str_replace(' )', ')', $retSql);
            $retSql = str_replace(")\t", ') ', $retSql);
            $retSql = str_replace(" \n", "\n", $retSql);
            $retSql = str_replace("\t ", "\t", $retSql);

            if ($temp === $retSql) {
                break;
            }

        }

        $retSql = str_replace("\t", '    ', $retSql);
        $retSql = str_replace('@', ' ', $retSql);

        return $retSql;
    }

    // }}}
    // {{{ _formatSelect

    /**
     * セレクト文フォーマットメソッド
     *
     * @param string $sql
     * @param int $startPos
     * @param int $endPos
     * @return string
     */
    private function _formatSelect($sql, &$startPos, &$endPos)
    {

        $addSql = '';
        $retSql = '';
        $sqlCnt = strlen($sql);
        $temp = null;

        $this->_unionFlg = false;

        if (
            preg_match(
                '/^select distinct /i',
                substr($sql, ($startPos >= 0) ? $startPos : 0)
            ) === 1
        ) {
            $retSql = substr($sql, ($startPos >= 0) ? $startPos : 0, 15);
            $retSql .= "\n\t" . $this->_tab;
            $startPos += 16;
        } else if (
            preg_match(
                '/^select /i',
                substr($sql, ($startPos >= 0) ? $startPos : 0)
            ) === 1
        ) {
            $retSql = substr($sql, ($startPos >= 0) ? $startPos : 0, 6);
            $retSql .= "\n\t" . $this->_tab;
            $startPos += 7;
        } else {
            $retSql = "\t";
        }

        for ($i = $startPos; $i < $sqlCnt; ++$i) {
            $temp = $sql[$i];
            $endPos = $i;

            if ($temp === ')') {
                $this->_parenthesis -= 1;

                if ($this->_parenthesis < 0) {
                    $retSql .= $addSql;
                    $addSql = "\n" . $this->_tab;
                    $addSql = str_replace("\t", '', $addSql);
                    $i = $sqlCnt;
                } else {
                    $addSql .= $temp;
                    $retSql .= $addSql;
                    $addSql = '';

                    continue;
                }
            }

            $addSql .= $temp;

            if ($temp === ',' && $this->_parenthesis === 0) {

                if ($this->_limitFlg) {
                    $this->_limitFlg = false;

                    $retSql .= $addSql;
                    $addSql = '';
                } else {
                    $retSql .= $addSql;
                    $addSql = "\n\t" . $this->_tab;
                }

            } else if ($temp === '(') {
                $this->_parenthesis += 1;
            } else {

                switch (1) {

                    case (preg_match('/ and /i', $addSql)):
                        
                        if ($this->_betweenFlg || $this->_parenthesis > 0) {
                            $retSql .= $addSql;

                            $this->_betweenFlg = false;
                        } else {
                            $retSql .= "\t";
                            $retSql .= substr(
                                $addSql, 0, strlen($addSql)
                            );

                            $retSql .= "\n\t" . $this->_tab;
                        }

                        $addSql = '';

                        break;

                    case (preg_match('/ or /i', $addSql)):

                        if ($this->_parenthesis > 0) {
                            $retSql .= $addSql;
                        } else {
                            $retSql .= "\t";
                            $retSql .= substr(
                                $addSql, 0, strlen($addSql) - 1
                            );
                            $retSql .= "\n\t" . $this->_tab;
                        }

                        $addSql = '';

                        break;

                    case (preg_match('/ from /i', $addSql)):
                        $retSql .= substr($addSql, 0, strlen($addSql) - 5);
                        $retSql .= "\n" . $this->_tab . substr($addSql, -5);
                        $addSql = "\n\t" . $this->_tab;

                        break;

                    case (preg_match('/ where /i', $addSql)):
                        $retSql .= substr($addSql, 0, strlen($addSql) - 6);
                        $retSql .= "\n" . $this->_tab . substr($addSql, -6);
                        $retSql .= "\n";
                        $addSql = "\t" . $this->_tab;

                        break;

                    case (preg_match('/ inner join | right join /i', $addSql)):
                        $retSql .= substr($addSql, 0, strlen($addSql) - 11);
                        $retSql .= "\n" . $this->_tab . substr($addSql, -11);
                        $addSql = "\n\t" . $this->_tab;

                        break;

                    case (preg_match('/ left join /i', $addSql)):
                        $retSql .= substr($addSql, 0, strlen($addSql) - 10);
                        $retSql .= "\n" . $this->_tab . substr($addSql, -10);
                        $addSql = "\n\t" . $this->_tab;

                        break;

                    case (preg_match('/ right outer join /i', $addSql)):
                        $retSql .= substr($addSql, 0, strlen($addSql) - 17);
                        $retSql .= "\n" . $this->_tab . substr($addSql, -17);
                        $addSql = "\n\t" . $this->_tab;

                        break;

                    case (preg_match('/ left outer join /i', $addSql)):
                        $retSql .= substr($addSql, 0, strlen($addSql) - 16);
                        $retSql .= "\n" . $this->_tab . substr($addSql, -16);
                        $addSql = "\n\t" . $this->_tab;

                        break;

                    case (preg_match('/ on /i', $addSql)):
                        $retSql .= substr($addSql, 0, strlen($addSql) - 3);
                        $retSql .= "\n" . $this->_tab . substr($addSql, -3);
                        $addSql = "\n\t" . $this->_tab;
                        $this->_onCnt += 1;

                        break;

                    case (preg_match('/ group by | order by /i', $addSql)):
                        $retSql .= substr($addSql, 0, strlen($addSql) - 9);
                        $retSql .= "\n" . $this->_tab . substr($addSql, -9);
                        $addSql = "\n\t" . $this->_tab;

                        break;

                    case (preg_match('/ having /i', $addSql)):
                        $retSql .= substr($addSql, 0, strlen($addSql) - 7);
                        $retSql .= "\n" . $this->_tab . substr($addSql, -7);
                        $addSql = "\n\t" . $this->_tab;

                        break;

                    case (preg_match('/ union all /i', $addSql)):
                        $retSql .= substr($addSql, 0, strlen($addSql) - 10);
                        $retSql .= "\n" . $this->_tab . substr($addSql, -10);
                        $addSql = "\n\t" . $this->_tab;
                        $i = $sqlCnt;
                        $this->_unionFlg = true;

                        break;

                    case (preg_match('/ between /i', $addSql)):
                        $this->_betweenFlg = true;

                        break;

                    case (preg_match('/ limit /i', $addSql)):
                        $this->_limitFlg = true;

                        break;

                    case (preg_match('/select /i', $addSql)):
                        $startPos = $i - 6;
                        $this->_parenthesis = 0;
                        $this->_onCnt = 0;
                        $this->_tab .= "\t\t";
                        $retSql .= substr($addSql, 0, strlen($addSql) - 7);
                        $retSql .= "\n" . $this->_tab;
                        $addSql = '';

                        while (true) {
                            $retSql .= $this->_formatSelect(
                                $sql, $startPos, $endPos
                            );

                            if ($this->_unionFlg === false) {
                                break;
                            }

                            $startPos = $endPos + 1;
                            $this->_parenthesis = 0;
                            $this->_onCnt = 0;
                        }

                        $i = $endPos;
                        $this->_parenthesis = 0;
                        $this->_tab = str_replace("\t\t", '', $this->_tab);

                        break;
                }

            }

        }

        $retSql .= $addSql;

        return $retSql;
    }

    // }}}
    // {{{ _formatInsert

    /**
     * インサート文フォーマットメソッド
     *
     * @param string $sql
     * @param int $startPos
     * @param int $endPos
     * @return string
     */
    private function _formatInsert($sql, &$startPos, &$endPos)
    {

        $retSql = '';
        $addSql = '';
        $sqlCnt = strlen($sql);
        $temp = null;

        for ($i = $startPos; $i < $sqlCnt; ++$i) {
            $temp = $sql[$i];

            if ($temp === ')') {
                $this->_parenthesis -= 1;
                $addSql .= "\n" . $this->_tab;
            }

            $addSql .= $temp;

            if ($temp === ',') {
                $addSql .= "\n\t" . $this->_tab;
            } else if ($temp === '(') {
                $this->_parenthesis += 1;
                $addSql .= "\n\t" . $this->_tab;
            } else if (preg_match('/ values/i', $addSql)) {
                $retSql .= substr($addSql, 0, strlen($addSql) - 6);
                $retSql .= "\n" . substr($addSql, -6);
                $addSql = '';
            } else if (preg_match('/select /i', $addSql)) {
                $startPos = $i - 6;
                $this->_onCnt = 0;
                $retSql .= substr($addSql, 0, strlen($addSql) - 7);

                if ($this->_parenthesis === 0) {
                    $retSql .= "\n" . $this->_tab;
                }

                $this->_parenthesis = 0;

                $retSql .= $this->_formatSelect($sql, $startPos, $endPos);

                $i = $endPos;
                $addSql = '';
                $this->_parenthesis = 0;
            }
        }

        $retSql .= $addSql;
        $addSql = '';

        return $retSql;
    }

    // }}}
    // {{{ _formatUpdate

    /**
     * アップデート文フォーマットメソッド
     *
     * @param string $sql
     * @param int $startPos
     * @param int $endPos
     * @return string
     */
    private function _formatUpdate($sql, &$startPos, &$endPos)
    {

        $retSql = '';
        $addSql = '';
        $sqlCnt = strlen($sql);
        $temp = null;

        $endPos = $sqlCnt;

        for ($i = $startPos; $i < $sqlCnt; ++$i) {
            $temp = $sql[$i];

            if ($temp === ')') {
                $this->_parenthesis -= 1;

                if ($this->_parenthesis < 0) {
                    $retSql .= $addSql;
                    $addSql = "\n" . $this->_tab;
                    $addSql = str_replace("\t", '', $addSql);
                    $endPos = $i;
                    $i = $sqlCnt;
                }

            }

            $addSql .= $temp;

            if ($temp === ',' && $this->_parenthesis === 0) {
                $retSql .= $addSql;
                $addSql = "\n\t" . $this->_tab;
            } else if ($temp === '(') {
                $this->_parenthesis += 1;
            } else {

                switch (1) {

                    case (preg_match('/ set /i', $addSql)):
                        $retSql .= substr($addSql, 0, strlen($addSql) - 4);
                        $retSql .= substr($addSql, -4);
                        $addSql = "\n\t";

                        break;

                    case (preg_match('/ and /i', $addSql)):

                        if ($this->_betweenFlg) {
                            $retSql .= substr(
                                $addSql, 0, strlen($addSql) - 4
                            );

                            $this->_betweenFlg = false;
                        } else {
                            $retSql .= "\t";
                            $retSql .= substr(
                                $addSql, 0, strlen($addSql) - 4
                            );

                            $retSql .= "\n\t" . $this->_tab;
                        }

                        $retSql .= substr($addSql, -4);
                        $addSql = '';

                        break;

                    case (preg_match('/ or /i', $addSql)):
                        $retSql .= "\t";
                        $retSql .= substr($addSql, 0, strlen($addSql) - 3);
                        $retSql .= "\n\t" . $this->_tab . substr($addSql, -3);
                        $retSql .= '@';
                        $addSql = '';

                        break;

                    case (preg_match('/ where /i', $addSql)):
                        $retSql .= substr($addSql, 0, strlen($addSql) - 6);
                        $retSql .= "\n" . $this->_tab . substr($addSql, -6);
                        $retSql .= "\n";
                        $addSql = "\t" . $this->_tab;

                        break;

                    case (preg_match('/ between /i', $addSql)):
                        $this->_betweenFlg = true;

                        break;

                    case (preg_match('/select /i', $addSql)):
                        $startPos = $i - 6;
                        $this->_parenthesis = 0;
                        $this->_onCnt = 0;
                        $this->_tab .= "\t\t";
                        $retSql .= substr(
                            $addSql, 0, strlen($addSql) - 7
                        );
                        $retSql .= "\n" . $this->_tab;
                        $addSql = '';

                        $retSql .= $this->_formatSelect(
                            $sql, $startPos, $endPos
                        );

                        $startPos = $endPos + 1;
                        $this->_parenthesis = 0;
                        $this->_onCnt = 0;
                        $addSql = '';

                        $i = $endPos;
                        $this->_tab = str_replace(
                            "\t\t", '', $this->_tab
                        );

                        break;
                }

            }

        }

        $retSql .= $addSql;

        return $retSql;
    }

    // }}}
    // {{{ _formatDelete

    /**
     * デリート文フォーマットメソッド
     *
     * @param string $sql
     * @param int $startPos
     * @param int $endPos
     * @return string
     */
    private function _formatDelete($sql, &$startPos, &$endPos)
    {

        $retSql = '';
        $addSql = '';
        $sqlCnt = strlen($sql);
        $temp = null;

        $endPos = $sqlCnt;

        for ($i = $startPos; $i < $sqlCnt; ++$i) {
            $temp = $sql[$i];

            if ($temp === ')') {
                $this->_parenthesis -= 1;

                if ($this->_parenthesis < 0) {
                    $retSql .= $addSql;
                    $addSql = "\n" . $this->_tab;
                    $addSql = str_replace("\t", '', $addSql);
                    $endPos = $i;
                    $i = $sqlCnt;
                }

            }

            $addSql .= $temp;

            if ($temp === ',' && $this->_parenthesis === 0) {
                $retSql .= $addSql;
                $addSql = "\n\t" . $this->_tab;
            } else if ($temp == '(') {
                $this->_parenthesis += 1;
            } else {

                switch (1) {

                    case (preg_match('/ and /i', $addSql)):

                        if ($this->_betweenFlg) {
                            $retSql .= substr(
                                $addSql, 0, strlen($addSql) - 4
                            );

                            $this->_betweenFlg = false;
                        } else {
                            $retSql .= "\t";
                            $retSql .= substr(
                                $addSql, 0, strlen($addSql) - 4
                            );

                            $retSql .= "\n\t" . $this->_tab;
                        }

                        $retSql .= substr($addSql, -4);
                        $addSql = '';

                        break;

                    case (preg_match('/ or /i', $addSql)):
                        $retSql .= "\t";
                        $retSql .= substr($addSql, 0, strlen($addSql) - 3);
                        $retSql .= "\n\t" . $this->_tab . substr($addSql, -3);
                        $retSql .= '@';
                        $addSql = '';

                        break;

                    case (preg_match('/ where /i', $addSql)):
                        $retSql .= substr($addSql, 0, strlen($addSql) - 6);
                        $retSql .= "\n" . $this->_tab . substr($addSql, -6);
                        $retSql .= "\n";
                        $addSql = "\t" . $this->_tab;

                        break;

                    case (preg_match('/ between /i', $addSql)):
                        $this->_betweenFlg = true;

                        break;

                    case (preg_match('/select /i', $addSql)):
                        $startPos = $i - 6;
                        $this->_parenthesis = 0;
                        $this->_onCnt = 0;
                        $this->_tab .= "\t\t";
                        $retSql .= substr(
                            $addSql, 0, strlen($addSql) - 7
                        );
                        $retSql .= "\n" . $this->_tab;
                        $addSql = '';

                        $retSql .= $this->_formatSelect(
                            $sql, $startPos, $endPos
                        );

                        $startPos = $endPos + 1;
                        $this->_parenthesis = 0;
                        $this->_onCnt = 0;
                        $addSql = '';

                        $i = $endPos;
                        $this->_tab = str_replace(
                            "\t\t", '', $this->_tab
                        );

                        break;
                }

            }

        }

        $retSql .= $addSql;
        $addSql = '';

        return $retSql;
    }

    // }}}
    // {{{ _formatReplace

    /**
     * リプレース文フォーマットメソッド
     *
     * @param string $sql
     * @param int $startPos
     * @param int $endPos
     * @return string
     */
    private function _formatReplace($sql, &$startPos, &$endPos)
    {

        $retSql = '';
        $addSql = '';
        $sqlCnt = strlen($sql);
        $temp = null;

        for ($i = $startPos; $i < $sqlCnt; ++$i) {
            $temp = $sql[$i];

            if ($temp === ')') {
                $this->_parenthesis -= 1;
                $addSql .= "\n" . $this->_tab;
            }

            $addSql .= $temp;

            if ($temp === ',') {
                $addSql .= "\n\t" . $this->_tab;
            } else if ($temp === '(') {
                $this->_parenthesis += 1;
                $addSql .= "\n\t" . $this->_tab;
            } else if (preg_match('/ values/i', $addSql)) {
                $retSql .= substr($addSql, 0, strlen($addSql) - 6);
                $retSql .= "\n" . substr($addSql, -6);
                $addSql = '';
            } else if (preg_match('/select /i', $addSql)) {
                $startPos = $i - 6;
                $this->_onCnt = 0;
                $retSql .= substr($addSql, 0, strlen($addSql) - 7);

                if ($this->_parenthesis === 0) {
                    $retSql .= "\n" . $this->_tab;
                }

                $this->_parenthesis = 0;

                $retSql .= $this->_formatSelect($sql, $startPos, $endPos);

                $i = $endPos;
                $addSql = '';
                $this->_parenthesis = 0;
            }
        }

        $retSql .= $addSql;
        $addSql = '';

        return $retSql;
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
