<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Controller_Component_PhpSession Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Controller_Component
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: PhpSession.php 1173 2010-01-05 14:22:46Z tamari $
 */

// {{{ xFrameworkPX_Controller_Component_PhpSession

/**
 * xFrameworkPX_Controller_Component_PhpSession Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Controller_Component
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Controller_Component_PhpSession
 */
class xFrameworkPX_Controller_Component_PhpSession
extends xFrameworkPX_Controller_Component_Session
{
    // {{{ props

    /*
     * セッションファイル格納ディレクトリ
     */
    private $_savePath = null;

    // }}}
    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @param xFrameworkPX_Util_MixedCollection $conf 設定オブジェクト
     */
    public function __construct($conf)
    {

        // セッション保存パス設定
        $this->_savePath = session_save_path();

        // セッション保存パスのデフォルト設定
        if (empty($this->_savePath)) {
            $this->_savePath = sys_get_temp_dir();
        }

        // ハンドラ設定
        session_set_save_handler(
            array($this, 'sessionHandlerOpen'),
            array($this, 'sessionHandlerClose'),
            array($this, 'sessionHandlerRead'),
            array($this, 'sessionHandlerWrite'),
            array($this, 'sessionHandlerDestroy'),
            array($this, 'sessionHandlerClean')
        );

        // スーパークラスメソッドコール
        parent::__construct($conf);

    }

    // }}}
    // {{{ sessionHandlerOpen

    /**
     * セッションオープンメソッド
     *
     * セッションを開始します。
     * セッションデータ保存パス、セッションの名前を指定し、
     * ファイルセッションを開始します。
     * PHPのsession_save_path()が内部的にコールされています。 
     * ユーザーはこのメソッドを使用することで
     * ファイルセッションを自動的に開始することができます。
     * ファイルセッションを使用する場合、ユーザーは
     * 必ず最初にこのメソッドを呼び出す必要があります。
     * 設定はphp.iniに依存します。
     * xFrameworkPXでは、デフォルトとしてPHPセッションを使用します。
     * オートセッションの設定や、セッションIDの設定はpublic_html下の
     * index.phpで変更します。
     *
     * <code>
     * <?php
     *    // デフォルト設定は、以下の通りです。
     *    // 変更する場合は、これと同じ書式でindex.php内の
     *    // 該当する箇所に記述します。
     *
     *    // {{{ セッション設定
     *
     *    'SESSION' => array(
     *
     *        // {{{ ID設定
     *
     *        'ID' => 'PHPSESSID',
     *
     *        // }}}
     *        // {{{ 自動スタート設定
     *
     *        'AUTO_START' => true,
     *
     *        // }}}
     *        // {{{ タイプ設定
     *
     *        'TYPE' => 'Php',
     *
     *        // }}}
     *        // {{{ タイムアウト設定
     *
     *        'TIMEOUT' => null
     *
     *        // }}}
     *
     *    )
     *
     *    // }}}
     * ?>
     * </code>
     *
     *
     * @param $savePath セッションデータ保存パス
     * @param $sessionName セッションの名前
     * @return boolean true 固定
     */
    function sessionHandlerOpen($savePath, $sessionName)
    {

        // ファイル保存先が設定されない場合は
        if (empty($savePath)) {
            $savePath = sys_get_temp_dir();
        }

        // ファイル保存先変更
        $this->_savePath = $savePath;

        return true;
    }

    // }}}
    // {{{ sessionHandlerClose

    /**
     * セッションクローズメソッド
     *
     * ファイルセッションを終了します。
     * このメソッドのみではセッションファイルは削除されません。
     * sessionHandlerOpenメソッドをコールした後に使用してください。
     *
     * @return boolean true 固定
     */
    function sessionHandlerClose()
    {
        return(true);
    }

    // }}}
    // {{{ sessionHandlerClean

    /**
     * セッションクリアメソッド
     *
     * ファイルセッションの有効期限を引数に指定し、
     * その時間経過するとセッションが破棄されます。
     * このメソッドのみではセッションファイルは削除されません。
     * sessionHandlerOpenメソッドをコールした後に使用してください。
     *
     * @param $lifetime セッション生存時間
     * @return boolean true:成功 false:失敗
     */
    function sessionHandlerClean($lifetime)
    {

        // タイムアウト時間を過ぎたファイル削除
        foreach (glob($this->_savePath . '/sess_*') as $fileName) {

            if (@filemtime($fileName) + $lifetime < time()) {
                @unlink($fileName);
            }
        }

        return true;
    }

    // }}}
    // {{{ sessionHandlerRead

    /**
     * セッションリードメソッド
     *
     * セッション値を読み込みます。
     * あらかじめ書き込まれているセッションIDを引数に指定し、
     * セッションファイルからセッション値を読み込みます。
     * sessionHandlerOpenメソッドをコールした後に使用してください。
     *
     * @param $id セッションID
     * @return string シリアライズ化された文字列
     */
    public function sessionHandlerRead($id)
    {

        // ファイルパス作成
        $fileName = $this->_savePath  . '/sess_' . $id;

        // タイムアウト時間を過ぎたファイル削除
        if (
            @filemtime($fileName) + $this->_timeout < time() &&
            $this->_timeout > 0
        ) {
            @unlink($fileName);
        }

        // セッション保存内容取得
        $session = (string)@file_get_contents($fileName);

        return $session;
    }

    // }}}
    // {{{ sessionHandlerWrite

    /**
     * セッションライトメソッド
     *
     * セッションを書き込みます。
     * セッションに保持する値として第1引数にセッションID、
     * 第2引数にセッション値を指定し、セッションファイルに書き込みます。
     * 第1引数がファイル名の一部となり、ファイルが自動生成され、
     * あらかじめ指定されているフォルダへ保存されます。
     * sessionHandlerOpenメソッドの後に使用してください。
     *
     * @param $id セッションID
     * @param $data 書き込む内容
     * @return  成功時はストリームデータ、失敗時はfalse
     */
    public function sessionHandlerWrite($id, $data)
    {

        // ファイルパス作成
        $fileName = $this->_savePath  . '/sess_' . $id;

        // セッションファイルに書き込み
        if ($fp = @fopen($fileName, "w")) {

            $ret = fwrite($fp, $data);
            fclose($fp);

            return $ret;

        // @codeCoverageIgnoreStart

        } else {

            // ファイルオープン失敗（書き込み権限がない場合のみ）
            return( false );

        }

        // @codeCoverageIgnoreEnd
    }

    // }}}
    // {{{ sessionHandlerDestroy

    /**
     * セッション消滅メソッド
     *
     * セッションを消滅させます。
     * セッションの書き込み先であるセッションファイルを削除します。
     * このメソッドのみではセッションファイルは削除されません。
     * sessionHandlerOpenメソッドをコールした後に使用してください。
     *
     * @param $id セッションID
     * @return boolean true 固定
     */
    public function sessionHandlerDestroy($id)
    {
        // セッションファイル削除
        return(@unlink($this->_savePath  . 'sess_' . $id));
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
