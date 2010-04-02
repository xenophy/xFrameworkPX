/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Docs.app.App Class File
 *
 * JavaScript
 *
 * @category   xFrameworkPX 3.5 Samples
 * @package    Docs.app
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: App.js 1154 2010-01-04 03:46:16Z kotsutsumi $
 */

// {{{ Docs.app.App

/**
 * Docs.app.App Class
 *
 * @category   xFrameworkPX 3.5 Samples
 * @package    Docs.app
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 1.0
 */
Docs.app.App = Ext.extend(Ext.util.Observable, {

    // {{{ commonInit

    /**
     * 共通初期化メソッド
     *
     * @return void
     */
    commonInit : function() {

        // クイックチップス初期化
        Ext.QuickTips.init();

        // プロバイダ追加
        Ext.Direct.addProvider(Ext.app.REMOTING_API);
    },

    // }}}
    // {{{ initApp

    /**
     * アプリケーション初期化メソッド
     *
     * @return void
     */
    initApp : function() {

        // ビューポート生成
        var vp = new Ext.Viewport({

            // レイアウト設定
            layout: 'border',

            // アイテム設定
            items: [{

                // ID設定
                id: 'Docs-header',

                // xtype設定
                xtype: 'Docs.header',

                // ボーダー設定
                border: false,

                // リージョン設定
                region: 'north'
            },{

                // ID設定
                id: 'Docs-tree',

                // xtype設定
                xtype: 'Docs.tree',

                // サイズ設定
                width: 260,
                minSize: 175,
                maxSize: 500,

                // スプリット設定
                split: true,

                // 開閉モード設定
                collapseMode: 'mini',

                // マージン設定
                margins: '0 0 5 5',

                // リージョン設定
                region: 'west'
            },{

                // ID設定
                id: 'Docs-main',

                // xtype設定
                xtype: 'Docs.main',

                // マージン設定
                margins: '0 5 5 0',

                // リージョン設定
                region: 'center'
            }/*,{

                // ID設定
                id: 'Docs-footer',

                // xtype設定
                xtype: 'Docs.footer',

                // ボーダー設定
                border: false,

                // リージョン設定
                region: 'south'
            }*/],

            // レンダリング先設定
            renderTo: Ext.getBody()
        });

        // イベントリスナー追加
        Ext.getCmp( 'Docs-tree' ).on(
            'click',
            Ext.getCmp( 'Docs-main' ).switchPanel,
            Ext.getCmp( 'Docs-main' )
        );
    },

    // }}}
    // {{{ run

    /**
     * アプリケーション実行メソッド
     *
     * @return void
     */
    run : function() {

        // 共通初期化処理実行
        this.commonInit();

        // アプリケーション初期化
        this.initApp();
    }

    // }}}

});

// }}}
// {{{ onReady

Ext.onReady( function() {

    // アプリケーション実行
    window.Application = new Docs.app.App();
    Application.run();

});

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
