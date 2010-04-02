<?php

// SVN $Id: Tools.class.php 451 2009-12-08 12:22:37Z  $

/**
 * xFrameworkPX_Tools Class File
 *
 * PHP versions 5
 *
 * xFrameworkPX : MVC Web application framework (http://px.xframework.net)
 * Copyright 2006 - 2009 Xenophy CO., LTD.(http://www.xenophy.com)
 *
 * Licensed under The MIT
 *
 * @filesource
 * @copyright     (c) 2006 - 2009 Xenophy CO., LTD.(http://www.xenophy.com)
 * @link          http://www.xframeworkpx.com xFrameworkPX
 * @package       xFrameworkPX
 * @since         xFrameworkPX 3.5.0
 * @version       $Revision: 451 $
 * @license       http://www.opensource.org/licenses/mit-license.php
 */

// {{{ xFrameworkPX_Tools

/**
 * xFrameworkPX_Tools Class
 *
 * @copyright     (c) 2006 - 2009 Xenophy CO., LTD.(http://www.xenophy.com)
 * @link          http://www.xframeworkpx.com xFrameworkPX
 * @package       xFrameworkPX
 * @version       xFrameworkPX 3.5.0
 * @license       http://www.opensource.org/licenses/mit-license.php
 */
class xFrameworkPX_Tools {




    // {{{ array_join

    /**
     * 配列要素結合
     *
     * @param array $arrTarget エンコード配列
     * @param array $strSeparator 区切り文字。初期値:なし
     * @return string 配列を結合した文字列
     * @access public
     */
    public static function ajoin( $arrTarget, $strSeparator = '' ) {

        $strRet = '';

        foreach( $arrTarget as $nKey => $strLine ) {

            if( $nKey > 0 ) {
                $strRet .= $strSeparator;
            }

            if( is_array( $strLine ) ) {

                $strRet .= self::ajoin( $strLine, $strSeparator );

            } else {

                $strRet .= $strLine;

            }

        }

        return $strRet;

    }

    // }}}
    // {{{ normalizePath

    /**
     * ディレクトリパス正規化メソッド
     *
     * @param string $strPath パス
     * @return string 正規化パス
     * @access public
     */
    public static function normalizePath( $strPath ) {

        return preg_replace(
            '/(\/+)/i',
            '/',
            str_replace( '\\', '/', $strPath )
        );

    }

    // }}}
    // {{{ file_put_contents

    /**
     * ファイル出力メソッド
     *
     * @param string $strPath パス
     * @param mixed $xData コンテンツ
     * @param int $nFlags (オプション)フラグ
     * @param resource $context (オプション)コンテキストリソース
     * @return int バイト数、失敗した場合はfalseを返します。
     * @access public
     */
    public static function file_put_contents() {

        // {{{ 引数取得

        $arrArgs = func_get_args();

        $strPath    = $arrArgs[ 0 ];
        $xData      = $arrArgs[ 1 ];
        $nFlag      = isset( $arrArgs[ 2 ] ) ? $arrArgs[ 2 ] : null;
        $resContext = isset( $arrArgs[ 3 ] ) ? $arrArgs[ 3 ] : null;

        // }}}
        // {{{ 存在しないディレクトリパス生成

        $strPath = self::normalizePath( $strPath );
        $arrParts = explode( '/', $strPath );
        $strFile = array_pop( $arrParts );
        $strPath = '';

        foreach( $arrParts as $nKey => $strPart ) {

            if( $nKey > 0 ) { $strPath .= '/'; }

            $strPath .= $strPart;

            if( !is_dir( $strPath ) ) { mkdir( $strPath ); }

        }

        // }}}
        // {{{ 出力

        $nRet = 0;

        if( !is_null( $nFlag ) && !is_null( $resContext ) ) {

            // @codeCoverageIgnoreStart
            $nRet = file_put_contents(
                "$strPath/$strFile",
                $xData,
                $nFlag,
                $resContext
            );
            // @codeCoverageIgnoreEnd

        } else if( !is_null( $nFlag ) && is_null( $resContext ) ) {

            $nRet = file_put_contents( "$strPath/$strFile", $xData, $nFlag );

        } else {

            $nRet = file_put_contents( "$strPath/$strFile", $xData );

        }

        // }}}

        return $nRet;

    }

    // }}}
    // {{{ makeDirectory

    /**
     * ディレクトリ生成メソッド
     *
     * @param string $strDir 生成ディレクトリ
     * @param integer $nMode パーミッション値(省略時:0755)
     * @return bool true:成功,false:失敗
     * @access public
     */
    public static function makeDirectory( $strDir, $nMode = 0755 ) {

        if( is_dir($strDir) || @mkdir( $strDir, $nMode ) ) {
            return true;
        }

        self::makeDirectory( dirname( $strDir ), $nMode );

        return @mkdir( $strDir, $nMode );

    }

    // }}}
    // {{{ removeDirectory

    /**
     * ディレクトリ削除メソッド
     *
     * @param string $strDir 削除ディレクトリ
     * @return void
     * @access private
     */
    public static function removeDirectory( $strDir ) {

        if( $objHandle = opendir( "$strDir" ) ) {

            while( false !== ( $strItem = readdir( $objHandle ) ) ) {

                if( $strItem != "." && $strItem != ".." ) {

                    if( is_dir( "$strDir/$strItem" ) ) {

                        self::removeDirectory( "$strDir/$strItem" );

                    } else {

                        unlink( "$strDir/$strItem" );

                    }
                }

            }

            closedir( $objHandle );
            rmdir( $strDir );
        }
    }

    // }}}
    // {{{ copyFile

    /**
     * ファイルコピーメソッド
     *
     * @param string $strSrc コピー元ファイルパス
     * @param string $strDest コピー先ファイルパス
     * @return int バイト数、失敗した場合はfalseを返します。
     * @access public
     */
    public static function copyFile( $strDest, $strSrc ) {

        return self::file_put_contents(
            $strDest,
            file_get_contents( $strSrc )
        );

    }

    // }}}
    // {{{ getPathInfoFileName

    /**
     * ファイル名取得メソッド
     *
     * @param string $strFileName ファイル名の含まれているパス
     * @return string 拡張子なしのファイル名、失敗した場合は空文字を返します。
     * @access public
     */
    public static function getPathInfoFileName( $strFileName ) {

        $strRet = '';

        if( defined( 'PATHINFO_FILENAME' ) ) {

            $strRet = pathinfo( $strFileName, PATHINFO_FILENAME );

        // @codeCoverageIgnoreStart
        } else if( strstr( $strFileName, '.' ) ) {

            $strRet = substr(
                pathinfo( $strFileName, PATHINFO_BASENAME ),
                0,
                strrpos(
                    pathinfo( $strFileName, PATHINFO_BASENAME ),
                    '.'
                )
            );

        }
        // @codeCoverageIgnoreEnd

        return $strRet;

    }

    // }}}
    // {{{ compressSource

    /**
     * ソースコード圧縮メソッド
     *
     * @param string $strSource 圧縮対象ソースコード
     * @return string 圧縮済ソースコード
     * @access public
     */
    public static function compressSource( $strSource ) {

        // {{ ローカル変数初期化

        $strDestCode = '';
        $bHereDoc = false;
        $bPrevWhiteSpace = false;

        // }}}
        // {{{ PHP トークン分割

        $arrTokens = token_get_all( $strSource );

        // }}}


        $arrVar = array();

        foreach( $arrTokens as $token ) {


            if ( is_string( $token ) ) {
                $strDestCode .= $token;
            } else {
                // トークン配列
                list( $id, $text ) = $token;

                switch ($id) {
                    case T_COMMENT:
                    case T_DOC_COMMENT:
                    break;

                    case T_START_HEREDOC:
                        $bHereDoc = true;
                        $strDestCode .= $text;
                        $bPrevWhiteSpace = false;
                    break;

                    case T_END_HEREDOC:
                        $bHereDoc = false;
                        $strDestCode .= $text;
                        $strDestCode .= "\n";
                        $bPrevWhiteSpace = false;
                    break;

                    case T_WHITESPACE:

                        if( !$bPrevWhiteSpace ) {
                            $strDestCode .= ' ';
                            $bPrevWhiteSpace = true;
                        }
                    break;

                    case T_ENCAPSED_AND_WHITESPACE:
/*
                        if( $text == '' ) {
                            if( !$bPrevWhiteSpace ) {
                                $strDestCode .= ' ';
                                $bPrevWhiteSpace = true;
                            }
                        } else {
*/
                            $strDestCode .= $text;
                            $bPrevWhiteSpace = false;
/*
                        }
*/
                    break;

                    case T_VARIABLE:
                        if(
                            $text != '$this' &&
                            strlen( $text ) > 3 &&
                            $text != '$_REQUEST' &&
                            $text != '$_ENV' &&
                            $text != '$_SERVER' &&
                            $text != '$_SESSION' &&
                            $text != '$_GET' &&
                            $text != '$_POST'
                        ) {
                            $arrVar[] = $text;
                        }

                    default:
                        if( $bHereDoc ) {
                            $strDestCode .= $text;
                        } else {
                            $text = str_replace( "\n", "", $text );
                            $strDestCode .= preg_replace(
                                "/^ +$/", "", $text
                            );
                        }
                        $bPrevWhiteSpace = false;
                        break;
                }
                $strPrev = $text;
            }
        }
/*
        $strDestCode = str_replace(
            array(
                ' => ',
                ' =',
                '= ',
                '; ',
                ' (',
                '( ',
                ' )',
                ') ',
                ' [',
                '[ ',
                ' ]',
                '] ',
                ' {',
                '{ ',
                ' }',
                '} ',
                ' .',
                '. ',
                ', '
            ),
            array(
                '=>',
                '=',
                '=',
                ';',
                '(',
                '(',
                ')',
                ')',
                '[',
                '[',
                ']',
                ']',
                '{',
                '{',
                '}',
                '}',
                '.',
                '.',
                ','
            ),
            $strDestCode
        );
*/
        return $strDestCode;

    }

    // }}}
    // {{{ stripslashes

    /**
     * クォートされた文字列のクォート部分を取り除く
     *
     * @param mixed 対象オブジェクト
     * @return 処理後のオブジェクト
     * @access public
     */
    public static function stripslashes( $xValues ) {

        if( is_array( $xValues ) ) {

            foreach( $xValues as $strKey => $xValue ) {

                $xValues[ $strKey ] = self::stripslashes( $xValue );
            }

        } else {

            $xValues = stripslashes( $xValues );

        }

        return $xValues;

    }

    // }}}
    // {{{ env

    /**
     * サーバー変数取得メソッド
     *
     * @param mixed サーバー変数のキー
     * @return サーバー変数値
     * @access public
     */
    public static function env( $strName ) {

        // {{{ 初期化

        $xRet = null;

        // }}}
        // {{{ サーバー変数の値を取得して返却

        if( isset( $_SERVER[ $strName ] ) ) {

            $xRet = $_SERVER[ $strName ];

        }
        return $xRet;

        // }}}

    }

    // }}}
    // {{{ refererAction

    /**
     * リファラーによるファイル名取得メソッド
     *
     * @return ファイル名
     * @access public
     */
    public static function refererAction() {

        // {{{ HTTP_REFERERがない場合はnullを返す

        if( is_null( self::env( 'HTTP_REFERER' ) ) ) {
            return null;
        }

        // }}}
        // {{{ HTTP_REFERERからファイルパス取得

        $strReferer = self::env( 'HTTP_REFERER' );

        if( pathinfo( $strReferer, PATHINFO_EXTENSION ) === '' ) {
            $strReferer .= 'index.html';
        }

        return self::getPathInfoFileName( $strReferer );

        // }}}

    }

    // }}}
    // {{{ sys_get_temp_dir

    /**
     * 一時ファイル保存ディレクトリパス取得メソッド
     *
     * @return ディレクトリパス
     * @access public
     */
    public static function sys_get_temp_dir() {

        // {{{ 関数存在判定

        if ( !function_exists( 'sys_get_temp_dir' ) ) {

            // @codeCoverageIgnoreStart

            // {{{ sys_get_temp_dirが存在しない場合の処理

            if ( !empty( $_ENV['TMP'] ) ) {

                return realpath( $_ENV['TMP'] );
            } else if ( !empty( $_ENV['TMPDIR'] ) ) {

                return realpath( $_ENV['TMPDIR'] );
            } else if ( !empty($_ENV['TEMP']) ) {

                return realpath( $_ENV['TEMP'] );
            } else {

                $temp_file = tempnam( md5( uniqid( rand(), TRUE ) ), '' );
                if ( $temp_file ) {
                    $temp_dir = realpath( dirname( $temp_file ) );
                    unlink( $temp_file );
                    return $temp_dir;
                } else {
                    return FALSE;
                }
            }

            // @codeCoverageIgnoreEnd

            // }}}

        } else {

            // {{{ sys_get_temp_dirが存在する場合の処理

            return sys_get_temp_dir();

            // }}}
        }

    // }}}

    }

    // }}}
    // {{{ redirect

    /**
     * リダイレクトメソッド
     *
     * @param mixed URL
     * @param int ステータス
     * @param bool 
     * @return ディレクトリパス
     * @access public
     */
    public function redirect( $strUrl, $nStatus = null, $bExit = true, $bTest = false ) {

        // {{{ ステータスコード配列

        if( !empty( $nStatus ) ) {

            $arrCodes = array(
                100 => 'Continue',
                101 => 'Switching Protocols',
                200 => 'OK',
                201 => 'Created',
                202 => 'Accepted',
                203 => 'Non-Authoritative Information',
                204 => 'No Content',
                205 => 'Reset Content',
                206 => 'Partial Content',
                300 => 'Multiple Choices',
                301 => 'Moved Permanently',
                302 => 'Found',
                303 => 'See Other',
                304 => 'Not Modified',
                305 => 'Use Proxy',
                307 => 'Temporary Redirect',
                400 => 'Bad Request',
                401 => 'Unauthorized',
                402 => 'Payment Required',
                403 => 'Forbidden',
                404 => 'Not Found',
                405 => 'Method Not Allowed',
                406 => 'Not Acceptable',
                407 => 'Proxy Authentication Required',
                408 => 'Request Time-out',
                409 => 'Conflict',
                410 => 'Gone',
                411 => 'Length Required',
                412 => 'Precondition Failed',
                413 => 'Request Entity Too Large',
                414 => 'Request-URI Too Large',
                415 => 'Unsupported Media Type',
                416 => 'Requested range not satisfiable',
                417 => 'Expectation Failed',
                500 => 'Internal Server Error',
                501 => 'Not Implemented',
                502 => 'Bad Gateway',
                503 => 'Service Unavailable',
                504 => 'Gateway Time-out'
            );

            // }}}
            // {{{ ステータスが文字列だった場合の処理

            if( is_string( $nStatus ) ) {
                $arrCodes = array_flip( $arrCodes );
            }

            // }}}
            // {{{ ステータス配列に一致するステータスが存在した場合の処理

            if( isset( $arrCodes[ $nStatus ] ) ) {

                $strCode = $arrCodes[ $nStatus ];
                $strMsg = $arrCodes[ $nStatus ];

                if( is_numeric( $nStatus ) ) {
                    $strCode = $nStatus;
                }
                if( is_string( $nStatus ) ) {
                    $strMsg = $nStatus;
                }

                // {{{ ステータス文字列生成

                $nStatus = "HTTP/1.1 {$strCode} {$strMsg}";

                // }}}

            } else {
                $nStatus = null;
            }

        // }}}

        }

        // }}}
        // {{{ ステータスステータスコードによるリダイレクト


        if( !empty( $nStatus ) ) {
            if( $bTest ) {
                return $nStatus;
            }

// @codeCoverageIgnoreStart

            header( $nStatus );
        }

// @codeCoverageIgnoreEnd

        // }}}
        // {{{ URLによるリダイレクト

        if( !is_null( $strUrl ) ) {
            if( $bTest ) {
                return sprintf( 'Location: %s',$strUrl );
            }

// @codeCoverageIgnoreStart

            header( sprintf( 'Location: %s',$strUrl ) );
        }

// @codeCoverageIgnoreEnd

        // }}}
        // {{{ ステータスが空ではなく、コード300～400の場合はリダイレクト
/*
        // 絶対に通過しないコード
        if( !empty( $nStatus ) && ( $nStatus >= 300 && $nStatus < 400 ) ) {
            if( $bTest ) {
                return $nStatus;
            }
            // @codeCoverageIgnoreStart
            header( $nStatus );
            // @codeCoverageIgnoreEnd
        }
*/
        // }}}
        // {{{ exit

// @codeCoverageIgnoreStart
        if( $bExit && !$bTest ) {
            exit(0);
        }
// @codeCoverageIgnoreEnd

        // }}}

    }

    // }}}
    // {{{ convert_encoding

    /**
     * 多階層mb_convert_encodingメソッド
     *
     * @param $objValue 対象変換文字列、または配列
     * @param $strToEncodeType 変換文字コード
     * @param $strFromEncodeType 現在の文字コード
     * @return mixed 変換後の値
     */
    public static function convert_encoding(
        $objValue,
        $strToEncodeType = null,
        $strFromEncodeType = 'auto'
    ) {

        if( is_array( $objValue ) ) {

            $arrTemp = array();
            foreach( $objValue as $strKey => $objTarget ) {

                $arrTemp[] = self::convert_encoding(
                    $objTarget,
                    $strToEncodeType,
                    $strFromEncodeType
                );

            }

        } elseif ( !empty( $objValue ) && is_string( $objValue ) ) {

            if( is_null( $strToEncodeType ) ) {
                throw new xFrameworkPX_Exception( 'arg2が設定されていません。' );
            }

            $objValue = mb_convert_encoding(
                $objValue,
                $strToEncodeType,
                $strFromEncodeType
            );
        }

        return $objValue;
    }

    // }}}
    // {{{ getFileList

    public static function getFileList( $strDir, $arrFilter = null ) {

        $iterator = new RecursiveDirectoryIterator( $strDir );

        $arrRet = array();

        foreach (
            new RecursiveIteratorIterator(
                $iterator,
                RecursiveIteratorIterator::CHILD_FIRST
            ) as $objFile
        ) {

            if( !$objFile->isDir() ) {

                if( is_null( $arrFilter ) ) {

                    $arrRet[] = $objFile->getPathname();

                } else {

                    $bValid = true;

                    if( isset( $arrFilter[ 'ext' ] ) ) {

                        if(
                            pathinfo(
                                $objFile->getPathname(),
                                PATHINFO_EXTENSION
                            ) !== $arrFilter[ 'ext' ]
                        ) {
                            $bValid = false;
                        }

                    }

                    if( isset( $arrFilter[ 'filename' ] ) ) {

                        if(
                            self::getPathInfoFileName( $objFile->getPathname() )
                            !== $arrFilter[ 'filename' ]
                        ) {
                            $bValid = false;
                        }

                    }

                    if( $bValid === true ) {

                        $arrRet[] = $objFile->getPathname();

                    }
                }
            }
        }

        return $arrRet;

    }

    // }}}
    // {{{ getRelativeUrl

    public static function getRelativeUrl( $strBase, $strTarget ) {

        $strRet = '';

        $arrBase   = explode( '/', $strBase );
        $arrTarget = explode( '/', $strTarget );

        do {
            $strTo = array_shift( $arrBase );
            $strFrom = array_shift( $arrTarget );

        } while ( $strTo  == $strFrom );

        return str_repeat(
            '../',
            count( $arrBase )
        );

    }

    // }}}

}

// }}}

?>