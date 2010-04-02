/*!
 * xFrameworkPX Debug Tools
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ PXDEBUG.app.App

PXDEBUG.app.App = Ext.extend(Ext.util.Observable, {

    // {{{ commonInit

    /**
     * 共通初期化メソッド
     */
    commonInit : function() {

        // s.gif設定
        Ext.BLANK_IMAGE_URL = relpath + 'extjs/resources/images/default/s.gif';

        // クイックチップス初期化
        Ext.QuickTips.init();

        // Ext.Directプロバイダ追加
        Ext.Direct.addProvider(PXDEBUG.app.REMOTING_API);

        // Window z-index設定
        var zIndex = Ext.maxZindex();
        if(Ext.WindowMgr.zseed < zIndex) {
            Ext.WindowMgr.zseed = zIndex;
        }

        // プロパティ設定
        Ext.apply(this, {
            showed: false,
            enable: true,
            keymaps: {},
            config:{
                css: {
                    ExtJS_All_CSS: relpath + 'extjs/resources/css/ext-all.css',
                    ExtJS_All_CSS_THEME: relpath + 'extjs/resources/css/xtheme-gray.css'
                }
            }
        });

        this.keymaps['query'] = new Ext.KeyMap(document, {
            key: Ext.EventObject.Q,
            fn: function() {
                if(PXDEBUG_APP.enable && this.viewport) {
                    this.viewport.setActiveTab('query');
                }
            },
            scope: this
        });

        this.keymaps['parameter'] = new Ext.KeyMap(document, {
            key: Ext.EventObject.P,
            fn: function() {
                if(PXDEBUG_APP.enable && this.viewport) {
                    this.viewport.setActiveTab('parameter');
                }
            },
            scope: this
        });

        this.keymaps['session'] = new Ext.KeyMap(document, {
            key: Ext.EventObject.S,
            fn: function() {
                if(PXDEBUG_APP.enable && this.viewport) {
                    this.viewport.setActiveTab('session');
                }
            },
            scope: this
        });

        this.keymaps['trace'] = new Ext.KeyMap(document, {
            key: Ext.EventObject.T,
            fn: function() {
                if(PXDEBUG_APP.enable && this.viewport) {
                    this.viewport.setActiveTab('trace');
                }
            },
            scope: this
        });

        this.keymaps['cookie'] = new Ext.KeyMap(document, {
            key: Ext.EventObject.K,
            fn: function() {
                if(PXDEBUG_APP.enable && this.viewport) {
                    this.viewport.setActiveTab('cookie');
                }
            },
            scope: this
        });

        this.keymaps['userdata'] = new Ext.KeyMap(document, {
            key: Ext.EventObject.U,
            fn: function() {
                if(PXDEBUG_APP.enable && this.viewport) {
                    this.viewport.setActiveTab('userdata');
                }
            },
            scope: this
        });

        this.keymaps['profile'] = new Ext.KeyMap(document, {
            key: Ext.EventObject.F,
            fn: function() {
                if(PXDEBUG_APP.enable && this.viewport) {
                    this.viewport.setActiveTab('profile');
                }
            },
            scope: this
        });

        this.keymaps['cache'] = new Ext.KeyMap(document, {
            key: Ext.EventObject.C,
            ctrl: true,
            alt: true,
            fn: function() {
                if(PXDEBUG_APP.enable && this.viewport) {
                    this.viewport.setActiveTab('cache');
                }
            },
            scope: this
        });

        this.keymaps['left'] = new Ext.KeyMap(document, {
            key: Ext.EventObject.LEFT,
            fn: function() {
                if(PXDEBUG_APP.enable && this.viewport) {
                    this.viewport.setActiveTab('left');
                }
            },
            scope: this
        });

        this.keymaps['right'] = new Ext.KeyMap(document, {
            key: Ext.EventObject.RIGHT,
            fn: function() {
                if(PXDEBUG_APP.enable && this.viewport) {
                    this.viewport.setActiveTab('right');
                }
            },
            scope: this
        });
    },

    // }}}
    // {{{ initApp

    /**
     * 初期化メソッド
     */
    initApp : function() {

        // 起動アイコンコンポーネント
        this.bootIcon = new PXDEBUG.BootIcon();

        // コンテナ非表示イベント追加
        this.bootIcon.on('hidecontainer', this.hideContainer, this);

        // ブートアイコン非表示イベント
        this.bootIcon.on('hide', function(){

            // コンテンツアーカイブ
            this.archiveId = Ext.Phantom.archive(
                Ext.id(),
                ['PXDebug-all.css'],
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
                id: 'pxdebug-loading-mask'
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
                                value: relpath + 'xFrameworkPX/debug/resources/images/pxdebuicon.swf'
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
                                src: relpath + 'xFrameworkPX/debug/resources/images/pxdebuicon.swf',
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

                PXDEBUG_APP.showed = true;

                // ビューポート生成
                this.viewport = new PXDEBUG.Viewport();
                this.viewport.on('hidecontainer', this.hideContainer, this);

                Ext.fly('pxdebug-loading-mask').remove();
                this.bootMsg.remove();
                this.bootMsg = null;

                // アプリケーション操作開始
                PXDEBUG_APP.enable = true;

                // キーマップ有効化
                this.keymap(true);

            }).defer(1000,this);

        }, this);

        // 起動アイコン表示
        this.bootIcon.show();
    },

    // }}}
    // {{{ hideContainer

    hideContainer : function() {

        // 表示中のウィンドウを非表示
        Ext.WindowMgr.each(function(win) {
            win.close();
        }, this);

        // キーマップ無効化
        this.keymap(false);

        // ビューポート削除
        this.viewport.destroy();

        // アーカイブ復元
        Ext.Phantom.expand(this.archiveId);

        // アプリケーション操作開始
        PXDEBUG_APP.enable = true;

        // 起動アイコン表示
        this.bootIcon.show();

        PXDEBUG_APP.showed = false;
    },

    // }}}
    // {{{ checkEnv

    /**
     * 動作環境確認メソッド
     */
    checkEnv: function() {

        if (Ext.get(document.documentElement).hasClass('pxdebug-disable')) {
            return false;
        }

        return true;

    },

    // }}}
    // {{{ keymap

    keymap : function(enable) {

        Ext.iterate(this.keymaps, function(name, keymap) {
            if(enable) {
                keymap.enable();
            } else {
                keymap.disable();
            }
        }, this);

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

    window.PXDEBUG_APP = new PXDEBUG.app.App();
    PXDEBUG_APP.run();
});

// }}}
