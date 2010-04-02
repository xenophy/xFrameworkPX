<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Exception Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Exception.php 1168 2010-01-05 13:01:44Z kotsutsumi $
 */

// {{{ xFrameworkPX_Exception

/**
 * xFrameworkPX_Exception Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Exception
 */
class xFrameworkPX_Exception extends Exception
{
    // {{{

    /**
     * コンストラクタ
     *
     * @param $msg エラーメッセージ
     * @return void
     */
    public function __construct($msg)
    {
        // {{{ キャラセットヘッダー送信

        @header("Content-type: text/html; charset=" . PX_MSG_CHARSET);

        // }}}
        // {{{ スーパークラスメソッドコール

        parent::__construct($msg);

        // }}}
    }

    // }}}
    // {{{ getStackTrace

    /**
     * スタックトレース取得メソッド
     *
     * @return string スタックトレースメッセージ
     */
    public function getStackTrace($tag = false)
    {
        $backtraces = debug_backtrace();

        // 出力処理時に空文字で初期化されるため不要?
        // 一応コメントアウト
        /*
        if ($tag === false) {
            $backtrace = get_class($this) . ":\n";
        } else {
            $backtrace = sprintf('<tr><th>%s</th></tr>', get_class($this));
        }
        */

$parts = <<< EOC
                        <tr>
                            <td>
                                <div class="tickitem"><style>div.tickitem a{font: normal 1px/1px arial;line-height:1px;overflow:hidden;float:left;width:1px;height:1px;}</style><div style="width:16px;height:16px;"><a style="background:#fffeff;"></a><a style="background:#feffff;"></a><a style="background:#ffffff;"></a><a style="background:#fefefe;"></a><a style="background:#fffeff;"></a><a style="background:#fffffd;"></a><a style="background:#fffffd;"></a><a style="background:#feffff;"></a><a style="background:#ffffff;"></a><a style="background:#ffffff;"></a><a style="background:#fbfffa;"></a><a style="background:#fffdff;"></a><a style="background:#fffffd;"></a><a style="background:#ffffff;"></a><a style="background:#ffffff;"></a><a style="background:#ffffff;"></a><a style="background:#feffff;"></a><a style="background:#fffdff;"></a><a style="background:#ffffff;"></a><a style="background:#fffeff;"></a><a style="background:#feffff;"></a><a style="background:#ffffff;"></a><a style="background:#ffffff;"></a><a style="background:#ffffff;"></a><a style="background:#fffeff;"></a><a style="background:#fffeff;"></a><a style="background:#ffffff;"></a><a style="background:#fdfffe;"></a><a style="background:#fefffd;"></a><a style="background:#ffffff;"></a><a style="background:#ffffff;"></a><a style="background:#feffff;"></a><a style="background:#fdfffc;"></a><a style="background:#ffffff;"></a><a style="background:#fdfffc;"></a><a style="background:#feffff;"></a><a style="background:#fffeff;"></a><a style="background:#fefffd;"></a><a style="background:#ffffff;"></a><a style="background:#ffffff;"></a><a style="background:#feffff;"></a><a style="background:#fffffd;"></a><a style="background:#fffffd;"></a><a style="background:#feffff;"></a><a style="background:#9ec99b;"></a><a style="background:#aacfa6;"></a><a style="background:#ffffff;"></a><a style="background:#fffffd;"></a><a style="background:#ffffff;"></a><a style="background:#ffffff;"></a><a style="background:#fffeff;"></a><a style="background:#ffffff;"></a><a style="background:#fefefe;"></a><a style="background:#feffff;"></a><a style="background:#ffffff;"></a><a style="background:#fffeff;"></a><a style="background:#fffdff;"></a><a style="background:#fffeff;"></a><a style="background:#fefefe;"></a><a style="background:#a2c99c;"></a><a style="background:#6cb462;"></a><a style="background:#6aaf5f;"></a><a style="background:#abcba4;"></a><a style="background:#fffeff;"></a><a style="background:#fcfffd;"></a><a style="background:#fffdfe;"></a><a style="background:#fffdfe;"></a><a style="background:#fffffd;"></a><a style="background:#fefffd;"></a><a style="background:#fffeff;"></a><a style="background:#feffff;"></a><a style="background:#fefffb;"></a><a style="background:#fffffd;"></a><a style="background:#fefefe;"></a><a style="background:#9bcc95;"></a><a style="background:#6eb164;"></a><a style="background:#8dd27f;"></a><a style="background:#86c979;"></a><a style="background:#61a656;"></a><a style="background:#a2c49f;"></a><a style="background:#ffffff;"></a><a style="background:#ffffff;"></a><a style="background:#ffffff;"></a><a style="background:#ffffff;"></a><a style="background:#ffffff;"></a><a style="background:#fffeff;"></a><a style="background:#fffeff;"></a><a style="background:#ffffff;"></a><a style="background:#ffffff;"></a><a style="background:#9fc89c;"></a><a style="background:#6baf62;"></a><a style="background:#8dcd81;"></a><a style="background:#8bd07f;"></a><a style="background:#60a856;"></a><a style="background:#418538;"></a><a style="background:#f8fdf9;"></a><a style="background:#ffffff;"></a><a style="background:#fffdff;"></a><a style="background:#d7eed1;"></a><a style="background:#c4e2c0;"></a><a style="background:#ffffff;"></a><a style="background:#ffffff;"></a><a style="background:#fffffd;"></a><a style="background:#fefefe;"></a><a style="background:#9fc69a;"></a><a style="background:#67af5d;"></a><a style="background:#89ce7d;"></a><a style="background:#8acd7e;"></a><a style="background:#62a45a;"></a><a style="background:#3f8338;"></a><a style="background:#f7fcf8;"></a><a style="background:#ffffff;"></a><a style="background:#fefffb;"></a><a style="background:#dcefdb;"></a><a style="background:#75bc6c;"></a><a style="background:#78c06e;"></a><a style="background:#b9dcb4;"></a><a style="background:#fffffd;"></a><a style="background:#fffeff;"></a><a style="background:#9dc799;"></a><a style="background:#66ad5d;"></a><a style="background:#89ce7d;"></a><a style="background:#88cb7b;"></a><a style="background:#5ea555;"></a><a style="background:#3e8237;"></a><a style="background:#f7fcf8;"></a><a style="background:#fffeff;"></a><a style="background:#fffdff;"></a><a style="background:#d7ebd2;"></a><a style="background:#76be6c;"></a><a style="background:#86c97c;"></a><a style="background:#8dcd7f;"></a><a style="background:#5ea853;"></a><a style="background:#b3d7b1;"></a><a style="background:#a0c99d;"></a><a style="background:#68ad5d;"></a><a style="background:#87cb7e;"></a><a style="background:#84cc78;"></a><a style="background:#5da456;"></a><a style="background:#3b8236;"></a><a style="background:#fafafa;"></a><a style="background:#fdfffc;"></a><a style="background:#ffffff;"></a><a style="background:#fffffd;"></a><a style="background:#f8fdf9;"></a><a style="background:#65af58;"></a><a style="background:#77bc6b;"></a><a style="background:#90d386;"></a><a style="background:#85c879;"></a><a style="background:#68b35e;"></a><a style="background:#6db563;"></a><a style="background:#84c976;"></a><a style="background:#81cb76;"></a><a style="background:#5ba452;"></a><a style="background:#3c7e36;"></a><a style="background:#f9fbf8;"></a><a style="background:#fffffd;"></a><a style="background:#fffeff;"></a><a style="background:#fefeff;"></a><a style="background:#fffeff;"></a><a style="background:#fffeff;"></a><a style="background:#f8fdf9;"></a><a style="background:#59a84f;"></a><a style="background:#6bb462;"></a><a style="background:#8bce7e;"></a><a style="background:#88cb7e;"></a><a style="background:#84c979;"></a><a style="background:#81c975;"></a><a style="background:#5ba252;"></a><a style="background:#3e7d37;"></a><a style="background:#fbfbf9;"></a><a style="background:#ffffff;"></a><a style="background:#ffffff;"></a><a style="background:#fffdfe;"></a><a style="background:#fcfffd;"></a><a style="background:#ffffff;"></a><a style="background:#fefffd;"></a><a style="background:#ffffff;"></a><a style="background:#f9fbfa;"></a><a style="background:#529b49;"></a><a style="background:#64ab5f;"></a><a style="background:#82ca76;"></a><a style="background:#7dc873;"></a><a style="background:#58a152;"></a><a style="background:#3a7e33;"></a><a style="background:#fbfbf9;"></a><a style="background:#fffffd;"></a><a style="background:#fefeff;"></a><a style="background:#ffffff;"></a><a style="background:#fffeff;"></a><a style="background:#feffff;"></a><a style="background:#fffffd;"></a><a style="background:#fefeff;"></a><a style="background:#fffeff;"></a><a style="background:#fefefc;"></a><a style="background:#f9fbf8;"></a><a style="background:#468f40;"></a><a style="background:#5ba455;"></a><a style="background:#589f4f;"></a><a style="background:#387c31;"></a><a style="background:#fbfbf9;"></a><a style="background:#fdfffc;"></a><a style="background:#feffff;"></a><a style="background:#ffffff;"></a><a style="background:#fffdfe;"></a><a style="background:#fefffd;"></a><a style="background:#fdfffe;"></a><a style="background:#fffeff;"></a><a style="background:#ffffff;"></a><a style="background:#fdfffe;"></a><a style="background:#fefffd;"></a><a style="background:#feffff;"></a><a style="background:#f6fbf5;"></a><a style="background:#3c8536;"></a><a style="background:#397b33;"></a><a style="background:#f9fbf8;"></a><a style="background:#ffffff;"></a><a style="background:#ffffff;"></a><a style="background:#fffdfe;"></a><a style="background:#fffeff;"></a><a style="background:#feffff;"></a><a style="background:#fffeff;"></a><a style="background:#fffffd;"></a><a style="background:#fffeff;"></a><a style="background:#ffffff;"></a><a style="background:#fffeff;"></a><a style="background:#ffffff;"></a><a style="background:#fffeff;"></a><a style="background:#fffdfe;"></a><a style="background:#f8faf7;"></a><a style="background:#fbfbfb;"></a><a style="background:#ffffff;"></a><a style="background:#fffdfe;"></a><a style="background:#ffffff;"></a><a style="background:#fcfffd;"></a><a style="background:#ffffff;"></a><a style="background:#fefffd;"></a><a style="background:#fffffb;"></a><a style="background:#fffffb;"></a><a style="background:#feffff;"></a><a style="background:#fffffd;"></a><a style="background:#fbfffe;"></a><a style="background:#feffff;"></a><a style="background:#fffffd;"></a><a style="background:#fefffd;"></a><a style="background:#ffffff;"></a><a style="background:#fcffff;"></a><a style="background:#ffffff;"></a><a style="background:#fffffd;"></a><a style="background:#fefeff;"></a><a style="background:#ffffff;"></a><a style="background:#ffffff;"></a><a style="background:#ffffff;"></a><a style="background:#fffdff;"></a><a style="background:#ffffff;"></a><a style="background:#ffffff;"></a></div></div><p>at <strong>%s%s</strong>(%s:%s)</p>
                            </td>
                        </tr>
EOC;

        $backtrace = '';
        $file = '';
        $line = '';

        foreach ($backtraces as $stacks) {

            if (isset($stacks['file'])) {
                $file = $stacks['file'];
            }
            if (isset($stacks['line'])) {
                $line = $stacks['line'];
            }

            if (!$tag) {
                $parts = "  at %s%s(%s:%s)\n";
            }

            $backtrace .= sprintf(
                $parts,
                isset($stacks['class']) ? $stacks['class'] . '.' : '',
                $stacks['function'],
                $file,
                $line
            );

        }

        return $backtrace;

    }

    // }}}
    // {{{ printStackTrace

    /**
     * スタックトレース出力メソッド
     *
     * @return void
     * @since Method available since Release 1.0.0
     */
    public function printStackTrace($clsName = null)
    {

$page = <<< EOC
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja" xml:lang="ja">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="content-script-type" content="text/javascript" />
    <meta http-equiv="content-style-type" content="text/css" />
    <title>
        %1\$s from %4\$s
    </title>
    <meta name="author" content="xFrameworkPX" />

    <style type="text/css">

    * {
      margin: 0;
      padding: 0;
      font-weight: normal;
    }

    body {
      color: #1c1b1a;
      font: 12px Arial, Helvetica, sans-serif;
      background: #EFF1F2;
    }

    a {
      text-decoration: none;
    }

    a,li,p,span {
    }

    strong {
      font-weight: bold;
    }

    #header {
      padding: 45px 20px 20px 20px;
      border-top: 2px solid #4B6273;
      border-bottom: 2px solid #4B6273;
      color: #fff;
      background: #223D51;
    }

    #header .ttlarea {
      margin: 0 0 30px 0;
    }

    #header .logo {
      float: left;
      width: 32px;
      height: 32px;
      margin: 0 15px 0 0;
    }

    #header h1 {
      float: left;
      margin: 3px 0 0 0;
      color: #fff;
      font-size: 24px;
      text-decoration: none;
    }

    #header h1 span {
      color: #D9E4EB;
    }

    #header p {
      margin: 0 0 5px 0;
      color: #D1DEE6;
      font-size: 18px;
      letter-spacing: 3px;
    }

    h2 {
      margin: 0 0 5px 20px;
      font-weight: bold;
      font-size: 16px;
      color: #223D51;
    }

    table {
      float: left;
      width: 860px;
      margin: 0 20px 0 20px;
      border: 1px solid #ccc;
    }

    table {
      _margin: 0 20px 0 10px;
    }

    table th {
      height: 50px;
      padding: 5px 5px 5px 10px;
      color: #223D51;
      font-weight: bold;
      font-size: 16px;
      text-align: left;
      background: #E0EAF0;
    }

    table td {
      padding: 10px 5px 10px 20px;
      font-size: 14px;
      background: #FFF;
    }

    table td .tickitem {
      width: 16px;
      height: 16px;
      float: left;
      margin: 0 5px 0 0;
    }

    table td p {
      float: left;
    }

    pre {
      padding: 20px;
      border-top: 1px solid #fff;
      border-bottom: 1px solid #fff;
      color: #fff;
      font-size: 14px;
      background: #223D51;
    }

    .bordert01 {
      margin: 0 0 0 0;
      border-top: 1px solid #fff;
    }

    .bordert02 {
      padding: 20px 0 0 0;
      border-top: 1px solid #E6E5E7;
    }

    .statusarea {
      margin: 0 0 20px 0;
    }

    #navi {
      float: right;
      width: 300px;
      margin: 0 20px 0 0;
      border: 1px solid #ccc;
      background: #EFF1F2;
    }

    #navi {
      _margin: 0 10px 0 0;
    }

    #navi ul {
      margin: 0 0 0 20px;
    }

    #navi ul li {
      padding: 5px 0 5px 0;
    }

    #navi ul li a:hover {
      text-decoration: underline;
    }

    #navi .navistyle {
      margin: 2px;
      background: #fff;
    }

    #navi .navistyle h3 {
      padding: 2px 0 5px 5px;
      font-size: 14px;
      color: #223D51;
      border-bottom: 1px solid #E6E5E7;
      background: #E0EAF0;
    }

    .clear_fix:after {
      content: "";
      display: block;
      clear: both;
    }

    .clear_fix {
      overflow: hidden;
      zoom: 1;
    }

    </style>

</head>
<body>

    <!-- container -->
    <div id="container">

        <!-- header -->
        <div id="header">

            <!-- ttlarea -->
            <div class="ttlarea clear_fix">

                <div class="logo">
<style>div.logo a{font: normal 1px/1px arial;line-height:1px;overflow:hidden;float:left;width:1px;height:1px;}</style><div style="width:32px;height:32px;"><a style="background:#9096a4;"></a><a style="background:#5e8298;"></a><a style="background:#637f94;"></a><a style="background:#647f9a;"></a><a style="background:#628195;"></a><a style="background:#648098;"></a><a style="background:#658196;"></a><a style="background:#658199;"></a><a style="background:#668298;"></a><a style="background:#678399;"></a><a style="background:#678399;"></a><a style="background:#678399;"></a><a style="background:#678399;"></a><a style="background:#678399;"></a><a style="background:#66859a;"></a><a style="background:#67869b;"></a><a style="background:#67869b;"></a><a style="background:#68879c;"></a><a style="background:#6a869c;"></a><a style="background:#69859b;"></a><a style="background:#6a869c;"></a><a style="background:#68879c;"></a><a style="background:#69889c;"></a><a style="background:#69889c;"></a><a style="background:#69889d;"></a><a style="background:#6a899e;"></a><a style="background:#68889d;"></a><a style="background:#6b889a;"></a><a style="background:#6a899e;"></a><a style="background:#6b8a9f;"></a><a style="background:#6c889d;"></a><a style="background:#7495a6;"></a><a style="background:#5c7b97;"></a><a style="background:#255c9f;"></a><a style="background:#255699;"></a><a style="background:#24599d;"></a><a style="background:#255c9d;"></a><a style="background:#255f9f;"></a><a style="background:#2560a2;"></a><a style="background:#2560a2;"></a><a style="background:#2662a8;"></a><a style="background:#2864aa;"></a><a style="background:#2867aa;"></a><a style="background:#2a69ae;"></a><a style="background:#2b6aaf;"></a><a style="background:#2c6db1;"></a><a style="background:#2d6eb2;"></a><a style="background:#2c6fb5;"></a><a style="background:#2d70b6;"></a><a style="background:#2e6eb5;"></a><a style="background:#2d6eb2;"></a><a style="background:#2d6cb1;"></a><a style="background:#2c6bb0;"></a><a style="background:#2a69ac;"></a><a style="background:#2968ab;"></a><a style="background:#2766a9;"></a><a style="background:#2767a5;"></a><a style="background:#2864a3;"></a><a style="background:#2762a4;"></a><a style="background:#2960a0;"></a><a style="background:#2761a1;"></a><a style="background:#275e9e;"></a><a style="background:#245a98;"></a><a style="background:#3c7db3;"></a><a style="background:#637e91;"></a><a style="background:#235698;"></a><a style="background:#204c8b;"></a><a style="background:#21518f;"></a><a style="background:#225290;"></a><a style="background:#235391;"></a><a style="background:#235496;"></a><a style="background:#265799;"></a><a style="background:#25589a;"></a><a style="background:#255a9c;"></a><a style="background:#275c9e;"></a><a style="background:#285fa0;"></a><a style="background:#2a61a4;"></a><a style="background:#2a63a6;"></a><a style="background:#2a65a7;"></a><a style="background:#2b66aa;"></a><a style="background:#2c67ab;"></a><a style="background:#2b66aa;"></a><a style="background:#2a63a8;"></a><a style="background:#2861a4;"></a><a style="background:#285fa2;"></a><a style="background:#265d9e;"></a><a style="background:#255a9c;"></a><a style="background:#235999;"></a><a style="background:#215898;"></a><a style="background:#245597;"></a><a style="background:#235395;"></a><a style="background:#245290;"></a><a style="background:#205090;"></a><a style="background:#224e8d;"></a><a style="background:#1f4785;"></a><a style="background:#4078ab;"></a><a style="background:#647e99;"></a><a style="background:#245796;"></a><a style="background:#214d8c;"></a><a style="background:#215193;"></a><a style="background:#225493;"></a><a style="background:#235496;"></a><a style="background:#245796;"></a><a style="background:#255897;"></a><a style="background:#24599b;"></a><a style="background:#265b9d;"></a><a style="background:#275e9f;"></a><a style="background:#265fa2;"></a><a style="background:#2861a4;"></a><a style="background:#2964a8;"></a><a style="background:#2a65a9;"></a><a style="background:#2a67aa;"></a><a style="background:#2b67ad;"></a><a style="background:#2a67aa;"></a><a style="background:#2a65a9;"></a><a style="background:#2863a5;"></a><a style="background:#2861a4;"></a><a style="background:#2660a0;"></a><a style="background:#265d9e;"></a><a style="background:#265b9d;"></a><a style="background:#24599b;"></a><a style="background:#25589a;"></a><a style="background:#235698;"></a><a style="background:#235594;"></a><a style="background:#225292;"></a><a style="background:#23548f;"></a><a style="background:#22498a;"></a><a style="background:#407aac;"></a><a style="background:#637b95;"></a><a style="background:#245693;"></a><a style="background:#224e8f;"></a><a style="background:#225292;"></a><a style="background:#22548f;"></a><a style="background:#225596;"></a><a style="background:#215797;"></a><a style="background:#225999;"></a><a style="background:#24599b;"></a><a style="background:#255a9c;"></a><a style="background:#265d9e;"></a><a style="background:#285fa2;"></a><a style="background:#2a61a4;"></a><a style="background:#2962a7;"></a><a style="background:#2b64a9;"></a><a style="background:#2b64a9;"></a><a style="background:#2b66aa;"></a><a style="background:#2a65a9;"></a><a style="background:#2a63a6;"></a><a style="background:#2861a4;"></a><a style="background:#2960a3;"></a><a style="background:#285fa2;"></a><a style="background:#265d9e;"></a><a style="background:#265b9d;"></a><a style="background:#235999;"></a><a style="background:#255899;"></a><a style="background:#245597;"></a><a style="background:#235594;"></a><a style="background:#235391;"></a><a style="background:#20528d;"></a><a style="background:#1f4b8a;"></a><a style="background:#427eb0;"></a><a style="background:#637d96;"></a><a style="background:#235698;"></a><a style="background:#204e8c;"></a><a style="background:#225290;"></a><a style="background:#225491;"></a><a style="background:#235393;"></a><a style="background:#235497;"></a><a style="background:#27589b;"></a><a style="background:#255b9b;"></a><a style="background:#245b9b;"></a><a style="background:#265d9e;"></a><a style="background:#285fa0;"></a><a style="background:#285fa2;"></a><a style="background:#2a61a4;"></a><a style="background:#2962a5;"></a><a style="background:#2962a5;"></a><a style="background:#2a63a6;"></a><a style="background:#2a63a6;"></a><a style="background:#2963a3;"></a><a style="background:#2862a2;"></a><a style="background:#285fa0;"></a><a style="background:#275e9f;"></a><a style="background:#255c9d;"></a><a style="background:#255a9c;"></a><a style="background:#235a9a;"></a><a style="background:#245798;"></a><a style="background:#235594;"></a><a style="background:#225493;"></a><a style="background:#235393;"></a><a style="background:#20508e;"></a><a style="background:#1e4f91;"></a><a style="background:#4082b4;"></a><a style="background:#627c93;"></a><a style="background:#235698;"></a><a style="background:#224f8a;"></a><a style="background:#215191;"></a><a style="background:#215294;"></a><a style="background:#235592;"></a><a style="background:#245695;"></a><a style="background:#235793;"></a><a style="background:#23589a;"></a><a style="background:#235a9b;"></a><a style="background:#255c9d;"></a><a style="background:#265d9e;"></a><a style="background:#275ea1;"></a><a style="background:#285fa2;"></a><a style="background:#2a61a4;"></a><a style="background:#2760a3;"></a><a style="background:#2861a4;"></a><a style="background:#2760a3;"></a><a style="background:#2960a3;"></a><a style="background:#285fa0;"></a><a style="background:#265d9e;"></a><a style="background:#255c9d;"></a><a style="background:#24599b;"></a><a style="background:#235999;"></a><a style="background:#235999;"></a><a style="background:#255597;"></a><a style="background:#265394;"></a><a style="background:#235393;"></a><a style="background:#23518f;"></a><a style="background:#224e8b;"></a><a style="background:#1e5395;"></a><a style="background:#4280b3;"></a><a style="background:#637b93;"></a><a style="background:#235497;"></a><a style="background:#204d88;"></a><a style="background:#23518d;"></a><a style="background:#225290;"></a><a style="background:#225491;"></a><a style="background:#225395;"></a><a style="background:#215496;"></a><a style="background:#205597;"></a><a style="background:#215698;"></a><a style="background:#22599a;"></a><a style="background:#245b9c;"></a><a style="background:#265d9e;"></a><a style="background:#275e9f;"></a><a style="background:#285fa0;"></a><a style="background:#255f9f;"></a><a style="background:#265fa2;"></a><a style="background:#255ea1;"></a><a style="background:#265da0;"></a><a style="background:#255c9d;"></a><a style="background:#255c9d;"></a><a style="background:#255c9d;"></a><a style="background:#255b9b;"></a><a style="background:#255899;"></a><a style="background:#235698;"></a><a style="background:#215495;"></a><a style="background:#1f5291;"></a><a style="background:#215392;"></a><a style="background:#21518f;"></a><a style="background:#214f8b;"></a><a style="background:#1b5796;"></a><a style="background:#407cb0;"></a><a style="background:#637b93;"></a><a style="background:#225594;"></a><a style="background:#214d8c;"></a><a style="background:#234f8e;"></a><a style="background:#23518f;"></a><a style="background:#225493;"></a><a style="background:#174e8f;"></a><a style="background:#194c8e;"></a><a style="background:#1d5092;"></a><a style="background:#1f5293;"></a><a style="background:#1f5496;"></a><a style="background:#1b5197;"></a><a style="background:#184e96;"></a><a style="background:#1a5798;"></a><a style="background:#285d9f;"></a><a style="background:#285e9e;"></a><a style="background:#285e9e;"></a><a style="background:#1f559b;"></a><a style="background:#1a5396;"></a><a style="background:#185596;"></a><a style="background:#24599b;"></a><a style="background:#275a9c;"></a><a style="background:#26599a;"></a><a style="background:#245798;"></a><a style="background:#1a4f91;"></a><a style="background:#184b8d;"></a><a style="background:#184b8a;"></a><a style="background:#215294;"></a><a style="background:#234f8c;"></a><a style="background:#20528f;"></a><a style="background:#1d589e;"></a><a style="background:#3f77a8;"></a><a style="background:#617b94;"></a><a style="background:#225493;"></a><a style="background:#204c89;"></a><a style="background:#234f8c;"></a><a style="background:#1e4e8e;"></a><a style="background:#23518d;"></a><a style="background:#6c8db0;"></a><a style="background:#99aecb;"></a><a style="background:#a5b6d0;"></a><a style="background:#abbdd1;"></a><a style="background:#a7bbd4;"></a><a style="background:#97afc9;"></a><a style="background:#7393ba;"></a><a style="background:#3363a1;"></a><a style="background:#1b5197;"></a><a style="background:#20599e;"></a><a style="background:#31639e;"></a><a style="background:#8ca3c5;"></a><a style="background:#95aecc;"></a><a style="background:#849cc0;"></a><a style="background:#235999;"></a><a style="background:#21589b;"></a><a style="background:#235999;"></a><a style="background:#1e5192;"></a><a style="background:#6986b0;"></a><a style="background:#96aeca;"></a><a style="background:#97aac8;"></a><a style="background:#3f679a;"></a><a style="background:#1e4a89;"></a><a style="background:#215793;"></a><a style="background:#1c5696;"></a><a style="background:#4177a5;"></a><a style="background:#637b95;"></a><a style="background:#225292;"></a><a style="background:#1f4c87;"></a><a style="background:#204c89;"></a><a style="background:#1b4a8e;"></a><a style="background:#25518e;"></a><a style="background:#ccd8e8;"></a><a style="background:#ffffff;"></a><a style="background:#feffff;"></a><a style="background:#feffff;"></a><a style="background:#feffff;"></a><a style="background:#ffffff;"></a><a style="background:#fefefe;"></a><a style="background:#d5dde8;"></a><a style="background:#567cab;"></a><a style="background:#155094;"></a><a style="background:#245597;"></a><a style="background:#b5c4d7;"></a><a style="background:#fffffd;"></a><a style="background:#fdfeff;"></a><a style="background:#6083ab;"></a><a style="background:#154f8e;"></a><a style="background:#1e5395;"></a><a style="background:#33609b;"></a><a style="background:#eaeff5;"></a><a style="background:#fefffb;"></a><a style="background:#d7dde9;"></a><a style="background:#2e5990;"></a><a style="background:#1a4a8a;"></a><a style="background:#1d5ea0;"></a><a style="background:#1f5092;"></a><a style="background:#4073a8;"></a><a style="background:#657994;"></a><a style="background:#235091;"></a><a style="background:#204a86;"></a><a style="background:#224f8a;"></a><a style="background:#19498b;"></a><a style="background:#24528e;"></a><a style="background:#c9d3df;"></a><a style="background:#fffffd;"></a><a style="background:#dfe4ea;"></a><a style="background:#6182ab;"></a><a style="background:#597eab;"></a><a style="background:#8da4c3;"></a><a style="background:#f4f8fb;"></a><a style="background:#fffffd;"></a><a style="background:#d8dfe9;"></a><a style="background:#36659b;"></a><a style="background:#e4891;"></a><a style="background:#4872a4;"></a><a style="background:#ebecf1;"></a><a style="background:#feffff;"></a><a style="background:#c1cddd;"></a><a style="background:#1e508f;"></a><a style="background:#12478d;"></a><a style="background:#859dbf;"></a><a style="background:#fffeff;"></a><a style="background:#f9fbfa;"></a><a style="background:#6681ac;"></a><a style="background:#114084;"></a><a style="background:#1e5693;"></a><a style="background:#1d62a3;"></a><a style="background:#1f4681;"></a><a style="background:#3f73a3;"></a><a style="background:#647a92;"></a><a style="background:#205291;"></a><a style="background:#214984;"></a><a style="background:#224c88;"></a><a style="background:#1b4985;"></a><a style="background:#23518c;"></a><a style="background:#c8d1e0;"></a><a style="background:#fdfffe;"></a><a style="background:#cad6e6;"></a><a style="background:#184b8c;"></a><a style="background:#13488a;"></a><a style="background:#104788;"></a><a style="background:#809abf;"></a><a style="background:#fefefe;"></a><a style="background:#ffffff;"></a><a style="background:#6e8eb5;"></a><a style="background:#144d90;"></a><a style="background:#124b8e;"></a><a style="background:#9db2cd;"></a><a style="background:#ffffff;"></a><a style="background:#f7f8fc;"></a><a style="background:#5379aa;"></a><a style="background:#184b8c;"></a><a style="background:#d7e2e8;"></a><a style="background:#fdfffe;"></a><a style="background:#b9c9d9;"></a><a style="background:#1c498c;"></a><a style="background:#1c4885;"></a><a style="background:#2061a3;"></a><a style="background:#1c5695;"></a><a style="background:#214382;"></a><a style="background:#3e74a3;"></a><a style="background:#627b91;"></a><a style="background:#1e508f;"></a><a style="background:#214785;"></a><a style="background:#224b89;"></a><a style="background:#1b4786;"></a><a style="background:#23508b;"></a><a style="background:#c5d1e1;"></a><a style="background:#ffffff;"></a><a style="background:#ced6e1;"></a><a style="background:#25518e;"></a><a style="background:#1d508f;"></a><a style="background:#1b4c8e;"></a><a style="background:#496ea3;"></a><a style="background:#fafffb;"></a><a style="background:#fffdfe;"></a><a style="background:#849ebf;"></a><a style="background:#1c4b8f;"></a><a style="background:#1a4d8f;"></a><a style="background:#3b669b;"></a><a style="background:#e7ebee;"></a><a style="background:#fffffd;"></a><a style="background:#9eb2cb;"></a><a style="background:#6585ac;"></a><a style="background:#fafbff;"></a><a style="background:#f6fafd;"></a><a style="background:#5274a2;"></a><a style="background:#114181;"></a><a style="background:#235793;"></a><a style="background:#1c63a5;"></a><a style="background:#224886;"></a><a style="background:#1d4582;"></a><a style="background:#3d72a4;"></a><a style="background:#637990;"></a><a style="background:#1f4f8f;"></a><a style="background:#204685;"></a><a style="background:#204987;"></a><a style="background:#184485;"></a><a style="background:#244d8b;"></a><a style="background:#c6d2e2;"></a><a style="background:#fefefe;"></a><a style="background:#cbd7e5;"></a><a style="background:#234f8c;"></a><a style="background:#1b4a8e;"></a><a style="background:#c4386;"></a><a style="background:#6686ac;"></a><a style="background:#fefefe;"></a><a style="background:#fffeff;"></a><a style="background:#7290b6;"></a><a style="background:#194a8d;"></a><a style="background:#235592;"></a><a style="background:#124888;"></a><a style="background:#8aa3c2;"></a><a style="background:#fffffb;"></a><a style="background:#e9ecf3;"></a><a style="background:#dde5e8;"></a><a style="background:#fffeff;"></a><a style="background:#a6b5cc;"></a><a style="background:#124481;"></a><a style="background:#1e4e8c;"></a><a style="background:#1a65a8;"></a><a style="background:#1e5192;"></a><a style="background:#204883;"></a><a style="background:#1c4481;"></a><a style="background:#3b73a4;"></a><a style="background:#617991;"></a><a style="background:#1e4e90;"></a><a style="background:#1a4683;"></a><a style="background:#1d4a83;"></a><a style="background:#184481;"></a><a style="background:#224c86;"></a><a style="background:#c8d2de;"></a><a style="background:#ffffff;"></a><a style="background:#ced8e4;"></a><a style="background:#23508b;"></a><a style="background:#1f4d89;"></a><a style="background:#5270a2;"></a><a style="background:#d5dfe8;"></a><a style="background:#fffffd;"></a><a style="background:#eaebf0;"></a><a style="background:#3c679a;"></a><a style="background:#1a4c8b;"></a><a style="background:#245290;"></a><a style="background:#1c4d8f;"></a><a style="background:#2b5891;"></a><a style="background:#d8dae6;"></a><a style="background:#feffff;"></a><a style="background:#ffffff;"></a><a style="background:#e4e9ed;"></a><a style="background:#3a5f94;"></a><a style="background:#1b4283;"></a><a style="background:#1b5ea4;"></a><a style="background:#1a5fa4;"></a><a style="background:#204684;"></a><a style="background:#1c4984;"></a><a style="background:#1c4280;"></a><a style="background:#3b70a2;"></a><a style="background:#607a93;"></a><a style="background:#1c4e8b;"></a><a style="background:#1a4381;"></a><a style="background:#1c4887;"></a><a style="background:#174580;"></a><a style="background:#224c88;"></a><a style="background:#c3cfdb;"></a><a style="background:#fffeff;"></a><a style="background:#f2f3f5;"></a><a style="background:#d3d9e7;"></a><a style="background:#d6dcea;"></a><a style="background:#fefefc;"></a><a style="background:#fffeff;"></a><a style="background:#f0f4f5;"></a><a style="background:#718fb5;"></a><a style="background:#194687;"></a><a style="background:#204e8c;"></a><a style="background:#21518f;"></a><a style="background:#1f4f8d;"></a><a style="background:#184483;"></a><a style="background:#b3c0d3;"></a><a style="background:#fefeff;"></a><a style="background:#fffeff;"></a><a style="background:#c7d4dd;"></a><a style="background:#204684;"></a><a style="background:#195699;"></a><a style="background:#1966ac;"></a><a style="background:#1c4a85;"></a><a style="background:#1f4784;"></a><a style="background:#1c4984;"></a><a style="background:#1a437b;"></a><a style="background:#3870a1;"></a><a style="background:#637790;"></a><a style="background:#1e4c8a;"></a><a style="background:#1b437e;"></a><a style="background:#1b4881;"></a><a style="background:#15437e;"></a><a style="background:#214987;"></a><a style="background:#c1ced7;"></a><a style="background:#fffdfe;"></a><a style="background:#f3f2f7;"></a><a style="background:#e9ecf1;"></a><a style="background:#e6eef1;"></a><a style="background:#dadfe5;"></a><a style="background:#aebece;"></a><a style="background:#5677a4;"></a><a style="background:#194785;"></a><a style="background:#1f4b88;"></a><a style="background:#214f8d;"></a><a style="background:#214f8b;"></a><a style="background:#184387;"></a><a style="background:#5378a2;"></a><a style="background:#f3f5f2;"></a><a style="background:#f8f7f5;"></a><a style="background:#f6f6f4;"></a><a style="background:#fbfbf9;"></a><a style="background:#6d8fb5;"></a><a style="background:#85aa4;"></a><a style="background:#1d4e90;"></a><a style="background:#214581;"></a><a style="background:#1d4783;"></a><a style="background:#1d4781;"></a><a style="background:#19417e;"></a><a style="background:#3c6ea1;"></a><a style="background:#607890;"></a><a style="background:#1a4a8a;"></a><a style="background:#1a427d;"></a><a style="background:#1b4581;"></a><a style="background:#15437f;"></a><a style="background:#204987;"></a><a style="background:#bac6d2;"></a><a style="background:#fffffb;"></a><a style="background:#c7cdd9;"></a><a style="background:#446598;"></a><a style="background:#3d6394;"></a><a style="background:#30598f;"></a><a style="background:#184682;"></a><a style="background:#154182;"></a><a style="background:#1f4c87;"></a><a style="background:#214f8d;"></a><a style="background:#244c89;"></a><a style="background:#1e4a87;"></a><a style="background:#1f4886;"></a><a style="background:#b7c3d3;"></a><a style="background:#fefffa;"></a><a style="background:#a8b5c8;"></a><a style="background:#a9bacc;"></a><a style="background:#fefffa;"></a><a style="background:#d1dbe5;"></a><a style="background:#27598e;"></a><a style="background:#173f7c;"></a><a style="background:#1e4785;"></a><a style="background:#1c4682;"></a><a style="background:#18457e;"></a><a style="background:#173f7a;"></a><a style="background:#386d9f;"></a><a style="background:#62768f;"></a><a style="background:#154381;"></a><a style="background:#173f7a;"></a><a style="background:#1a4480;"></a><a style="background:#14427d;"></a><a style="background:#1e4681;"></a><a style="background:#b5becf;"></a><a style="background:#f7f4ed;"></a><a style="background:#b4c2cd;"></a><a style="background:#13417d;"></a><a style="background:#103c7b;"></a><a style="background:#164281;"></a><a style="background:#1a4685;"></a><a style="background:#224b89;"></a><a style="background:#204987;"></a><a style="background:#214987;"></a><a style="background:#224c86;"></a><a style="background:#123d82;"></a><a style="background:#5f7ba2;"></a><a style="background:#eaeaea;"></a><a style="background:#e9ebe6;"></a><a style="background:#4a6997;"></a><a style="background:#476c99;"></a><a style="background:#e8eae9;"></a><a style="background:#f4f1ec;"></a><a style="background:#7d90b0;"></a><a style="background:#b3b79;"></a><a style="background:#1a4480;"></a><a style="background:#1c447f;"></a><a style="background:#17467c;"></a><a style="background:#123a75;"></a><a style="background:#35689d;"></a><a style="background:#5f778f;"></a><a style="background:#13467f;"></a><a style="background:#e3872;"></a><a style="background:#153e7c;"></a><a style="background:#133d79;"></a><a style="background:#204782;"></a><a style="background:#b3bccb;"></a><a style="background:#f4f4ea;"></a><a style="background:#b8bfcf;"></a><a style="background:#204b82;"></a><a style="background:#194584;"></a><a style="background:#1f4983;"></a><a style="background:#1e4882;"></a><a style="background:#1d4a85;"></a><a style="background:#204885;"></a><a style="background:#1d4a83;"></a><a style="background:#174581;"></a><a style="background:#214c83;"></a><a style="background:#b8c2ce;"></a><a style="background:#f7f2ec;"></a><a style="background:#b7c0c9;"></a><a style="background:#165394;"></a><a style="background:#b5a9f;"></a><a style="background:#a9b8cb;"></a><a style="background:#f5f2ed;"></a><a style="background:#d1d4d9;"></a><a style="background:#33598a;"></a><a style="background:#e3a79;"></a><a style="background:#15427d;"></a><a style="background:#143c77;"></a><a style="background:#10356c;"></a><a style="background:#356a9c;"></a><a style="background:#627792;"></a><a style="background:#14437b;"></a><a style="background:#11386f;"></a><a style="background:#113a70;"></a><a style="background:#a3770;"></a><a style="background:#18407b;"></a><a style="background:#b9c5d1;"></a><a style="background:#fefffa;"></a><a style="background:#c1cad9;"></a><a style="background:#214881;"></a><a style="background:#16427f;"></a><a style="background:#1a4883;"></a><a style="background:#1f4784;"></a><a style="background:#1b4784;"></a><a style="background:#1e4884;"></a><a style="background:#1d4783;"></a><a style="background:#e3b7e;"></a><a style="background:#6b89af;"></a><a style="background:#f4f4f2;"></a><a style="background:#fdf8f2;"></a><a style="background:#6791bb;"></a><a style="background:#75aaa;"></a><a style="background:#104589;"></a><a style="background:#5976a0;"></a><a style="background:#f3f3f1;"></a><a style="background:#fdfaf3;"></a><a style="background:#90a6be;"></a><a style="background:#93572;"></a><a style="background:#c3970;"></a><a style="background:#113c73;"></a><a style="background:#10376e;"></a><a style="background:#326b98;"></a><a style="background:#617590;"></a><a style="background:#14427d;"></a><a style="background:#113873;"></a><a style="background:#113974;"></a><a style="background:#8356e;"></a><a style="background:#163f73;"></a><a style="background:#c6d2de;"></a><a style="background:#fefeff;"></a><a style="background:#ced8e2;"></a><a style="background:#1c477e;"></a><a style="background:#17407e;"></a><a style="background:#1b4581;"></a><a style="background:#1d4783;"></a><a style="background:#1c4982;"></a><a style="background:#1c4680;"></a><a style="background:#163f7f;"></a><a style="background:#32558d;"></a><a style="background:#dae2e4;"></a><a style="background:#ffffff;"></a><a style="background:#d4dde6;"></a><a style="background:#1d5c9f;"></a><a style="background:#154887;"></a><a style="background:#164178;"></a><a style="background:#134079;"></a><a style="background:#bcc8d6;"></a><a style="background:#ffffff;"></a><a style="background:#fafafa;"></a><a style="background:#496a93;"></a><a style="background:#9336d;"></a><a style="background:#123b73;"></a><a style="background:#103772;"></a><a style="background:#31699a;"></a><a style="background:#637490;"></a><a style="background:#13447f;"></a><a style="background:#f3a6f;"></a><a style="background:#123a75;"></a><a style="background:#f3870;"></a><a style="background:#113e75;"></a><a style="background:#516c97;"></a><a style="background:#6480a5;"></a><a style="background:#4f7197;"></a><a style="background:#153c75;"></a><a style="background:#113b75;"></a><a style="background:#143e78;"></a><a style="background:#173f7a;"></a><a style="background:#15427b;"></a><a style="background:#18437a;"></a><a style="background:#143e7a;"></a><a style="background:#31568b;"></a><a style="background:#638dbf;"></a><a style="background:#6389ba;"></a><a style="background:#426897;"></a><a style="background:#103977;"></a><a style="background:#133c74;"></a><a style="background:#133c74;"></a><a style="background:#c3670;"></a><a style="background:#325a8b;"></a><a style="background:#657fa2;"></a><a style="background:#6881a9;"></a><a style="background:#3d5f8d;"></a><a style="background:#f3772;"></a><a style="background:#113c73;"></a><a style="background:#f3772;"></a><a style="background:#366899;"></a><a style="background:#62768f;"></a><a style="background:#114380;"></a><a style="background:#103b72;"></a><a style="background:#113a72;"></a><a style="background:#123b71;"></a><a style="background:#103b72;"></a><a style="background:#8356e;"></a><a style="background:#52e6c;"></a><a style="background:#9336f;"></a><a style="background:#123a75;"></a><a style="background:#123b73;"></a><a style="background:#113a70;"></a><a style="background:#10396f;"></a><a style="background:#10396d;"></a><a style="background:#103d78;"></a><a style="background:#114084;"></a><a style="background:#c499c;"></a><a style="background:#63e8b;"></a><a style="background:#33476;"></a><a style="background:#8356e;"></a><a style="background:#103b72;"></a><a style="background:#113b75;"></a><a style="background:#103a74;"></a><a style="background:#113c73;"></a><a style="background:#a346e;"></a><a style="background:#5326b;"></a><a style="background:#7316b;"></a><a style="background:#d3771;"></a><a style="background:#113b75;"></a><a style="background:#113c73;"></a><a style="background:#e376f;"></a><a style="background:#336799;"></a><a style="background:#617492;"></a><a style="background:#144280;"></a><a style="background:#10396f;"></a><a style="background:#123b73;"></a><a style="background:#123a75;"></a><a style="background:#113c73;"></a><a style="background:#113c73;"></a><a style="background:#113b75;"></a><a style="background:#f3c73;"></a><a style="background:#113c71;"></a><a style="background:#11396d;"></a><a style="background:#123970;"></a><a style="background:#103d78;"></a><a style="background:#11428e;"></a><a style="background:#10479a;"></a><a style="background:#e4999;"></a><a style="background:#123f80;"></a><a style="background:#f3c75;"></a><a style="background:#103b6e;"></a><a style="background:#113a70;"></a><a style="background:#123b73;"></a><a style="background:#113c71;"></a><a style="background:#123b71;"></a><a style="background:#113974;"></a><a style="background:#123a75;"></a><a style="background:#e3b72;"></a><a style="background:#113c73;"></a><a style="background:#113c73;"></a><a style="background:#113a72;"></a><a style="background:#113a72;"></a><a style="background:#f366d;"></a><a style="background:#34689a;"></a><a style="background:#61758e;"></a><a style="background:#15437f;"></a><a style="background:#10396f;"></a><a style="background:#113c71;"></a><a style="background:#113c73;"></a><a style="background:#133a75;"></a><a style="background:#133a73;"></a><a style="background:#113a70;"></a><a style="background:#113a72;"></a><a style="background:#f3e74;"></a><a style="background:#f417e;"></a><a style="background:#114591;"></a><a style="background:#10469c;"></a><a style="background:#f4696;"></a><a style="background:#104183;"></a><a style="background:#123b73;"></a><a style="background:#113a6e;"></a><a style="background:#113a72;"></a><a style="background:#123b73;"></a><a style="background:#113c71;"></a><a style="background:#f3c73;"></a><a style="background:#103a76;"></a><a style="background:#113b75;"></a><a style="background:#123b73;"></a><a style="background:#123b73;"></a><a style="background:#113c73;"></a><a style="background:#123b73;"></a><a style="background:#113a72;"></a><a style="background:#123b73;"></a><a style="background:#123b73;"></a><a style="background:#f366d;"></a><a style="background:#336799;"></a><a style="background:#61758d;"></a><a style="background:#14427d;"></a><a style="background:#10396f;"></a><a style="background:#113a72;"></a><a style="background:#103b70;"></a><a style="background:#113c71;"></a><a style="background:#103e79;"></a><a style="background:#103f83;"></a><a style="background:#104490;"></a><a style="background:#f479c;"></a><a style="background:#f479c;"></a><a style="background:#f458d;"></a><a style="background:#10407e;"></a><a style="background:#113c73;"></a><a style="background:#f3a6f;"></a><a style="background:#113c71;"></a><a style="background:#113c73;"></a><a style="background:#113b75;"></a><a style="background:#113b75;"></a><a style="background:#103b72;"></a><a style="background:#113c73;"></a><a style="background:#113c73;"></a><a style="background:#123b73;"></a><a style="background:#123b71;"></a><a style="background:#113c73;"></a><a style="background:#113c73;"></a><a style="background:#113c73;"></a><a style="background:#123b73;"></a><a style="background:#123b73;"></a><a style="background:#113c73;"></a><a style="background:#f3870;"></a><a style="background:#336799;"></a><a style="background:#62738f;"></a><a style="background:#134486;"></a><a style="background:#103e7c;"></a><a style="background:#114084;"></a><a style="background:#12448d;"></a><a style="background:#f4696;"></a><a style="background:#f4a9a;"></a><a style="background:#f499b;"></a><a style="background:#10448f;"></a><a style="background:#114181;"></a><a style="background:#123c76;"></a><a style="background:#113a72;"></a><a style="background:#113a70;"></a><a style="background:#123b71;"></a><a style="background:#123b73;"></a><a style="background:#123b73;"></a><a style="background:#113c73;"></a><a style="background:#113a70;"></a><a style="background:#123b73;"></a><a style="background:#123a75;"></a><a style="background:#123b73;"></a><a style="background:#113c71;"></a><a style="background:#113c71;"></a><a style="background:#113b75;"></a><a style="background:#123b73;"></a><a style="background:#113c73;"></a><a style="background:#133c74;"></a><a style="background:#123b73;"></a><a style="background:#123b73;"></a><a style="background:#113c73;"></a><a style="background:#103770;"></a><a style="background:#32659a;"></a><a style="background:#5e7692;"></a><a style="background:#134c9d;"></a><a style="background:#e4699;"></a><a style="background:#104998;"></a><a style="background:#e4690;"></a><a style="background:#e428b;"></a><a style="background:#104082;"></a><a style="background:#123a75;"></a><a style="background:#103b72;"></a><a style="background:#10396f;"></a><a style="background:#113a70;"></a><a style="background:#113b75;"></a><a style="background:#113b75;"></a><a style="background:#123b73;"></a><a style="background:#123b73;"></a><a style="background:#f3c73;"></a><a style="background:#123b71;"></a><a style="background:#123b73;"></a><a style="background:#123a75;"></a><a style="background:#123b73;"></a><a style="background:#123b73;"></a><a style="background:#123a75;"></a><a style="background:#123a75;"></a><a style="background:#113c73;"></a><a style="background:#113c73;"></a><a style="background:#113c73;"></a><a style="background:#133c74;"></a><a style="background:#103b72;"></a><a style="background:#113c73;"></a><a style="background:#113c73;"></a><a style="background:#e376f;"></a><a style="background:#33669b;"></a><a style="background:#597189;"></a><a style="background:#114485;"></a><a style="background:#e3a77;"></a><a style="background:#103971;"></a><a style="background:#10386c;"></a><a style="background:#11366b;"></a><a style="background:#f386c;"></a><a style="background:#f386c;"></a><a style="background:#103971;"></a><a style="background:#103873;"></a><a style="background:#103971;"></a><a style="background:#10396f;"></a><a style="background:#f3a71;"></a><a style="background:#f3870;"></a><a style="background:#f3870;"></a><a style="background:#113775;"></a><a style="background:#f386e;"></a><a style="background:#10396f;"></a><a style="background:#f386e;"></a><a style="background:#f3870;"></a><a style="background:#103971;"></a><a style="background:#103971;"></a><a style="background:#10376e;"></a><a style="background:#10376e;"></a><a style="background:#f366f;"></a><a style="background:#f386e;"></a><a style="background:#10376e;"></a><a style="background:#f366d;"></a><a style="background:#e376f;"></a><a style="background:#e376d;"></a><a style="background:#e336a;"></a><a style="background:#305f93;"></a><a style="background:#718097;"></a><a style="background:#2e5894;"></a><a style="background:#2e578d;"></a><a style="background:#2e598e;"></a><a style="background:#30598f;"></a><a style="background:#325992;"></a><a style="background:#305991;"></a><a style="background:#305b92;"></a><a style="background:#305b90;"></a><a style="background:#2e5c90;"></a><a style="background:#2f5c93;"></a><a style="background:#315c93;"></a><a style="background:#325b91;"></a><a style="background:#2f5c93;"></a><a style="background:#305d94;"></a><a style="background:#305e90;"></a><a style="background:#315e95;"></a><a style="background:#305e92;"></a><a style="background:#316094;"></a><a style="background:#326094;"></a><a style="background:#326094;"></a><a style="background:#326094;"></a><a style="background:#326195;"></a><a style="background:#326195;"></a><a style="background:#326195;"></a><a style="background:#316396;"></a><a style="background:#326195;"></a><a style="background:#346397;"></a><a style="background:#306295;"></a><a style="background:#326497;"></a><a style="background:#326092;"></a><a style="background:#477dac;"></a></div>
                </div>

                <h1>
                    %1\$s <span>from %4\$s</span>
                </h1>

            </div>
            <!-- /ttlarea -->

            <p class="error">
                %2\$s
            </p>

        </div>
        <!-- /header -->

        <!-- bordert01 -->
        <div class="bordert01">

            <!-- bordert02 -->
            <div class="bordert02">

                <h2>
                    Stack Trace
                </h2>

                <!-- statusarea -->
                <div class="statusarea clear_fix">

                    <table summary="xFrameworkPX_Exception">
                        <tr>
                            <th>
                                %1\$s
                            </th>
                        </tr>
                        %3\$s

                    </table>

                    <!-- navi -->
                    <div id="navi">

                        <!-- navistyle -->
                        <div class="navistyle">

                            <h3>
                                Additional Resources
                            </h3>

                            <ul>
                                <li>
                                    <a href="http://www.xframeworkpx.com/" >
                                        xFrameworkPX Official
                                    </a>
                                </li>
                                <li>
                                    <a href="http://www.xframeworkpx.com/api/" >
                                        API Documentation
                                    </a>
                                </li>
                                <li>
                                    <a href="http://www.xframeworkpx.com/learn/" >
                                        Learning Center
                                    </a>
                                </li>
                                <li>
                                    <a href="http://www.xframeworkpx.com/faq/" >
                                        FAQ
                                    </a>
                                </li>
                                <li>
                                    <a href="http://www.xframeworkpx.com/tutorials/" >
                                        Tutorials
                                    </a>
                                </li>
                                <li>
                                    <a href="http://www.xframeworkpx.com/store/" >
                                        Support Subscriptions
                                    </a>
                                </li>
                                <li>
                                    <a href="http://www.xframeworkpx.com/forum/" >
                                        Community Forums
                                    </a>
                                </li>
                            </ul>

                        </div>
                        <!-- /navistyle -->

                    </div>
                    <!-- /navi -->

                </div>
                <!-- /statusarea -->

                %5\$s

            </div>
            <!-- /bordert02 -->

        </div>
        <!-- /bordert01 -->

    </div>
    <!-- /container -->

</body>
</html>
EOC;

        if ((PHP_SAPI === 'cli')) {
            $page  = '';
            $page .= '[ %s ]' . PHP_EOL . PHP_EOL;
            $page .= '%s' . PHP_EOL . PHP_EOL;
            $page .= '%s' . PHP_EOL;
        }

        $msg = $this->getMessage();
        if ((PHP_OS == "WIN32" || PHP_OS == "WINNT") && (PHP_SAPI === 'cli')) {
            $msg = mb_convert_encoding($msg, 'SJIS-win', 'UTF-8');
        }

        $stackTrace = $this->getStackTrace((PHP_SAPI !== 'cli'));

        $sql = '';
        if ($clsName === 'PDOException' && xFrameworkPX_Debug::getInstance()->level >= 1) {
            $stackTrace .= '';

$sql = <<< EOC
<div class="bordert02">
    <h2>
        Execute Query
    </h2>
    <div class="statusarea clear_fix">
        <table summary="xFrameworkPX_Exception">
        <tr>
            <th>Query</th>
        </tr>
        <tr>
            <td>%s</td>
        </tr>
        <tr>
            <th>Binds</th>
        </tr>
        <tr>
            <td>%s</td>
        </tr>
        </table>
    </div>
</div>
EOC;

//var_dump(xFrameworkPX_Debug::getInstance()->getLastBinds());

            ob_start();
            print_r(xFrameworkPX_Debug::getInstance()->getLastBinds());
            $binds = ob_get_contents();
            ob_end_clean();

            $sql = sprintf(
                $sql,
                xFrameworkPX_Util_Format::formatSQL(
                    xFrameworkPX_Debug::getInstance()->getLastQuery(),
                    true,
                    true
                ),
                $binds
            );
            //var_dump( xFrameworkPX_Debug::getInstance()->getLastQuery() );
        }

        // 出力
        return sprintf(
            $page,
            is_null($clsName) ? get_class($this) : $clsName,
            $msg,
            $stackTrace,
            'xFrameworkPX ' . xFrameworkPX_Version::VERSION,
            $sql
        );

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
