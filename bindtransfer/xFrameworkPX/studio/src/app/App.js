/*!
 * xFrameworkPX Studio
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ PXSTUDIO.app.App

PXSTUDIO.app.App = Ext.extend(Ext.util.Observable, {

    // {{{ commonInit

    /**
     * 共通初期化メソッド
     */
    commonInit : function() {

        // プロパティ設定
        Ext.apply(this, {

            // アプリケーション名
            appName: 'xFrameworkPX Studio Express',

            // バージョン情報
            version: '1.0.0a',

            // 表示フラグ
            showed: false,

            // アプリケーション操作フラグ
            enable: false

        });

        Ext.form.Field.prototype.msgTarget = '';

        // s.gif設定
        Ext.BLANK_IMAGE_URL = relpath + 'extjs/resources/images/default/s.gif';

        // クイックチップス初期化
        Ext.QuickTips.init();

        // Ext.Directプロバイダ追加
        Ext.Direct.addProvider(PXSTUDIO.app.REMOTING_API);

        // Window z-index設定
        var zIndex = Ext.maxZindex();
        if(Ext.WindowMgr.zseed < zIndex) {
            Ext.WindowMgr.zseed = zIndex;
        }
    },

    // }}}
    // {{{ initApp

    /**
     * 初期化メソッド
     */
    initApp : function() {

        // キーマップ設定
        this.keymap = new Ext.KeyMap(document, {
            key: Ext.EventObject.F8,
            fn: function() {

                if(PXSTUDIO_APP.enable && PXDEBUG_APP.showed !== true) {

                    // アプリケーション操作停止
                    PXSTUDIO_APP.enable = false;

                    // イベント発火
                    this.fireEvent('showcontainer', this.showed);
                }
            },
            scope: this
        });

        // イベントリスナー追加
        this.on('showcontainer', function(showed){

            if(!showed) {

                // コンテンツアーカイブ
                this.archiveId = Ext.Phantom.archive(
                    Ext.id(),
                    ['PXStudio-all.css'],
                    [{
                        id: 'ExtJS_All_CSS',
                        href: relpath + 'extjs/resources/css/ext-all.css'
                    },{
                        id: 'ExtJS_All_CSS_THEME',
                        href: relpath + 'extjs/resources/css/xtheme-gray.css'
                    }]
                );

                // ローディングマスク生成
                Ext.DomHelper.insertFirst(Ext.getBody(), [{
                    id: 'pxstudio-loading-mask'
                }]);

                if(!this.bootMsg) {
                    this.bootMsg = new Ext.Layer({
                        dh: {
                        id: 'pxdebug-loading',
                        cn: [{
                            cls: 'loading-indicator',
                            cn: [{
                                tag: 'object',
                                id: 'pxdebuicon',
                                classid: 'clsid:d27cdb6e-ae6d-11cf-96b8-444553540000',
                                codebase: 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0',
                                width: 150,
                                height: 50,
                                style: 'margin-right:8px;',
                                align: 'absmiddle',
                                cn: [{
                                    tag: 'param',
                                    name: 'allowScriptAccess',
                                    value: 'sameDomain'
                                },{
                                    tag: 'param',
                                    name: 'allowFullScreen',
                                    value: 'false'
                                },{
                                    tag: 'param',
                                    name: 'movie',
                                    value: relpath + 'xFrameworkPX/studio/resources/images/pxstudioicon.swf'
                                },{
                                    tag: 'param',
                                    name: 'quality',
                                    value: 'high'
                                },{
                                    tag: 'param',
                                    name: 'bgcolor',
                                    value: '#ffffff'
                                },{
                                    tag: 'embed',
                                    src: relpath + 'xFrameworkPX/studio/resources/images/pxstudioicon.swf',
                                    quality: 'high',
                                    bgcolor: '#ffffff',
                                    width: 150,
                                    height: 50,
                                    name: 'pxdebuicon',
                                    align: 'middle',
                                    allowScriptAccess: 'sameDomain',
                                    allowFullScreen: 'false',
                                    type: 'application/x-shockwave-flash',
                                    pluginspage: 'http://www.macromedia.com/go/getflashplayer'
                                }]
                            }]
                        }]
                    }
                    });
                }

                var size = Ext.getBody().getViewSize();
                this.bootMsg.setTop((size.height - this.bootMsg.getHeight())/2);
                this.bootMsg.setLeft((size.width - this.bootMsg.getWidth())/2);
                this.bootMsg.show();

                (function(){

                    // 表示フラグ更新
                    PXSTUDIO_APP.showed = true;

                    // ビューポート生成
                    this.viewport = new PXSTUDIO.Viewport();
                    this.viewport.on('hidecontainer', this.hideContainer, this);

                    Ext.fly('pxstudio-loading-mask').remove();
                    this.bootMsg.remove();
                    this.bootMsg = null;

                    // アプリケーション操作開始
                    PXSTUDIO_APP.enable = true;

/*
                    Ext.fly('pxdebug-loading-mask').remove();
                    this.bootMsg.remove();
                    this.bootMsg = null;


                    // キーマップ有効化
                    this.keymap(true);
*/

                }).defer(1000,this);


            } else {
                this.hideContainer();
            }

        }, this);

        // アプリケーション捜査開始
        PXSTUDIO_APP.enable = true;
    },

    // }}}
    // {{{ hideContainer
    
    hideContainer: function() {
    
        // アーカイブ復元
        Ext.Phantom.expand(this.archiveId);

        // ビューポート削除
        this.viewport.destroy();

        // アプリケーション操作開始
        PXSTUDIO_APP.enable = true;

        // 表示フラグ更新
        PXSTUDIO_APP.showed = false;    
    },
    
    // }}}
    // {{{ run

    /**
     * アプリケーション実行メソッド
     */
    run : function() {
        this.commonInit();
        this.initApp();
    }

    // }}}

});

// }}}
// {{{ Ext.onReady

Ext.onReady(function(){

    if(!window.relpath) {
        window.relpath = '';
    }

    window.PXSTUDIO_APP = new PXSTUDIO.app.App();
    PXSTUDIO_APP.run();
});

// }}}
