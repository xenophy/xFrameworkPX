<?php

// {{{ Message Charset Define

if( !defined( 'PX_MSG_CHARSET' ) ) define( 'PX_MSG_CHARSET', 'UTF-8' );

// }}}
// {{{ xFrameworkPX\Dispatcher

if( !defined( 'PX_ERR10000' ) ) define( 'PX_ERR10000', '.pxファイルが重複して存在しているため、認識できませんでした。' );
if( !defined( 'PX_ERR10001' ) ) define( 'PX_ERR10001', '.pxファイルにコントローラーの指定がありません。' );
if( !defined( 'PX_ERR10002' ) ) define( 'PX_ERR10002', '.pxファイルにモジュールの指定がありません。' );
if( !defined( 'PX_ERR10003' ) ) define( 'PX_ERR10003', 'Webルート( %s )ディレクトリが存在しません。' );
if( !defined( 'PX_ERR10004' ) ) define( 'PX_ERR10004', '.pxが存在します、削除してアクセスしてください。' );
if( !defined( 'PX_ERR90001' ) ) define( 'PX_ERR90001', 'Cloneの使用は、%sによって許可されていません。' );

// }}}
// {{{ xFrameworkPX\DB\Adapter\MySQL

if( !defined( 'PX_ERR11101' ) ) define( 'PX_ERR11101', '接続エラー (%s) %s' );
if( !defined( 'PX_ERR11102' ) ) define( 'PX_ERR11102', 'フィールド情報を取得できませんでした。' );
if( !defined( 'PX_ERR11103' ) ) define( 'PX_ERR11103', '%s は利用可能なタイプではありません。' );

// }}}
// {{{ xFrameworkPX\Storage

if( !defined( 'PX_ERR12000' ) ) define( 'PX_ERR12000', 'エラー: %s' );

// }}}
// {{{ xFrameworkPX\View

if( !defined( 'PX_ERR13000' ) ) define( 'PX_ERR13000', 'レイアウトファイル( %s )が見つかりません。' );

// }}}
// {{{ xFrameworkPX\View\Helper\Html

if( !defined( 'PX_ERR14000' ) ) define( 'PX_ERR14000', '属性は配列で指定してください。' );

if( !defined( 'PX_ERR14001' ) ) define( 'PX_ERR14001', '%sは引数を最低１つは渡してください。' );
if( !defined( 'PX_ERR14002' ) ) define( 'PX_ERR14002', '%sの第%s引数は文字列で指定してください。' );
if( !defined( 'PX_ERR14003' ) ) define( 'PX_ERR14003', '%sの第%s引数はtrue、またはfalseで指定してください。' );
if( !defined( 'PX_ERR14004' ) ) define( 'PX_ERR14004', '%sの第%s引数は配列で指定してください。' );
if( !defined( 'PX_ERR14005' ) ) define( 'PX_ERR14005', '%sの第%s引数は配列、または真偽型で指定してください。' );
if( !defined( 'PX_ERR14006' ) ) define( 'PX_ERR14006', '%sの第%s引数は配列、または文字列で指定してください。' );
//if( !defined( 'PX_ERR14007' ) ) define( 'PX_ERR14007', '%sの第%s引数は空。' );
//if( !defined( 'PX_ERR14008' ) ) define( 'PX_ERR14008', '%sの第%s引数は配列を指定するか、または空の状態にしてください。' );
if( !defined( 'PX_ERR14010' ) ) define( 'PX_ERR14010', '%sの第%s引数が間違っています。詳しくはドキュメントを参照してください。' );

// }}}
// {{{ xFrameworkPX\Util\Observable

if( !defined( 'PX_ERR20001' ) ) define( 'PX_ERR20001', 'イベント名 %s は登録されていません。' );
if( !defined( 'PX_ERR20002' ) ) define( 'PX_ERR20002', 'コールバックの設定が不正です。' );

// }}}
// {{{ xFrameworkPX\Model

if( !defined( 'PX_ERR30001' ) ) define( 'PX_ERR30001', '指定した%sは存在しません。' );
if( !defined( 'PX_ERR30002' ) ) define( 'PX_ERR30002', 'コネクション\'%s\'は存在しません。' );
if( !defined( 'PX_ERR30003' ) ) define( 'PX_ERR30003', '\'%s\'は未定義のアダプタです。' );
if( !defined( 'PX_ERR30004' ) ) define( 'PX_ERR30004', 'PDOオブジェクトは存在しません。' );

// }}}
// {{{ xFrameworkPX\Config

if( !defined( 'PX_ERR35000' ) ) define( 'PX_ERR35000', '%sが存在しません。' );

// }}}

?>
