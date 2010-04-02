/*!
 * xFrameworkPX Debug Tools
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ Namespace

Ext.ns(
    'PXDEBUG',
    'PXDEBUG.app',
    'PXDEBUG.grid',
    'PXDEBUG.form',
    'PXDEBUG.tree'
);

// }}}
/*!
 * xFrameworkPX Debug Tools
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ Console Stubs

if( !window.console ) {
    window.console = {
        log : Ext.emptyFn,
        dir : Ext.emptyFn,
        debug : Ext.emptyFn,
        info : Ext.emptyFn,
        warn : Ext.emptyFn,
        error : Ext.emptyFn,
        time : Ext.emptyFn,
        timeEnd : Ext.emptyFn,
        pforile : Ext.emptyFn,
        pfofileEnd : Ext.emptyFn,
        trace : Ext.emptyFn,
        group : Ext.emptyFn,
        groupEnd : Ext.emptyFn,
        dirxml : Ext.emptyFn
    };
}

// }}}
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
/*!
 * xFrameworkPX Debug Tools
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ Ext Extender

Ext.apply(Ext,{

    // {{{ maxZindex

    /**
     * DOMツリー内のzindex最大値を取得します。
     *
     * @return zindex最大値
     */
    maxZindex : function() {

        var ret = 0;
        var els = Ext.select('*');

        els.each(function(el){

            var zIndex = el.getStyle('z-index');
            if(Ext.isNumber(parseInt(zIndex)) && ret < zIndex) {
                ret = zIndex;
            }

        }, this);

        return ret;
    },

    // }}}
    // {{{ getScrollPos

    getScrollPos: function() {

        var y = (document.documentElement.scrollTop > 0)
            ? document.documentElement.scrollTop
            : document.body.scrollTop;
        var x = (document.documentElement.scrollLeft > 0)
            ? document.documentElement.scrollLeft
            : document.body.scrollLeft;

        return {
            x: x,
            y: y
        };

    }

    // }}}

});

// }}}
// {{{ String

String.prototype.endsWith = function(suffix) {
  var sub = this.length - suffix.length;
  return (sub >= 0) && (this.lastIndexOf(suffix) === sub);
};

// }}}
/*!
 * xFrameworkPX Debug Tools
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ Ext.Phantom

Ext.Phantom = function(){

    var link = {};
    var appendedCss = [];

    return {

        // {{{ archive

        archive : function(id, ignoreCss, appendCss) {

            var id = id || Ext.id();
            var dh = Ext.DomHelper;

            if (!Ext.isArray) {
                ignoreCss = [];
            }

            // 既にアーカイブタグが存在する場合は削除
            if (Ext.fly(id) !== null) {
                Ext.fly(id).remove();
            }

            // LINK情報初期化
            link[id] = [];

            // アーカイブタグ生成
            var base = dh.append(Ext.getBody(), {
                id: id,
                style: [
                    'position: absolute',
                    'top: -10000px',
                    'left: -10000px',
                    'display: none'
                ].join('; ')
            });

            // Bodyタグ内のエレメントを待避
            if(!Ext.isIE6) {
                Ext.each(document.body.childNodes, function(el){

                    if(el){
                        var el = Ext.get(el);
                        if(el && el.dom.id !== id) {
                            base.appendChild(el.dom);
                        }
                    }

                }, this);
            } else {

                var b = Ext.getBody().dom;

                Ext.select('*', true, document).each(function(el){
                    if(el.dom.parentNode.id === b.id && base.id != el.id ) {
                        base.appendChild(el.dom);
                    }
                }, this);

            }

            // CSSファイル無効化
            Ext.select('link', true).each(function(item){

                var dom = item.dom;
                if (dom.href.toLowerCase().endsWith('.css')) {

                    Ext.each(ignoreCss, function(ignore){

                        if(!dom.href.endsWith(ignore)) {
                            link[id].push({
                                id: dom.id,
                                href: dom.href
                            });
                            dom.parentNode.removeChild(dom);
                        }

                    }, this);
                }
            }, this);

            // CSS追加
            Ext.each(appendCss, function(o){
                Ext.util.CSS.swapStyleSheet(o.id, o.href);
                appendedCss.push(o);
            }, this);

            return id;
        },

        // }}}
        // {{{ expand

        expand : function(id) {

            var archive = Ext.get(id);

            // アーカイブタグ内のエレメントを待避
            if(!Ext.isIE6) {

                var b = Ext.getBody().dom;

                Ext.select('*', true, archive.dom).each(function(el){

                    if(el.dom.parentNode.id === archive.dom.id) {
                        document.body.appendChild(el.dom);
                    }
                }, this);

            } else {

                var b = Ext.getBody().dom;

                Ext.select('*', true, archive.dom).each(function(el){
                    if(el.dom.parentNode.id === archive.dom.id) {
                        document.body.appendChild(el.dom);
                    }
                }, this);
            }

            Ext.each(link[id],function(o){

                Ext.util.CSS.swapStyleSheet(o.id, o.href);

            }, this);

            // 追加CSS削除
            Ext.each(appendedCss, function(o){
                Ext.util.CSS.removeStyleSheet(o.id);
            }, this);
        }

        // }}}

    };

}();

// }}}
/*!
 * xFrameworkPX Debug Tools
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ PXDEBUG.BootIcon

PXDEBUG.BootIcon = Ext.extend(Ext.Component, {

    // {{{ initComponent

    initComponent: function() {

        // 設定適用
        Ext.apply(this,{

            // 表示フラグ設定
            showed : false,

            // Duration設定
            duration : {

                // 表示時
                show: .3,

                // 非表示時
                hide: .3
            },

            // アイコンレイヤー
            layer: new Ext.Layer({
                dh: {
                    cls: 'pxdebug-booticon',
                    cn: [{
                        id: 'pxdebug_booticon_link',
                        tag: 'a',
                        cls: 'pxdebug-booticon-icon'
                    }]
                }
            })
        });

        // ウィンドウスクロールイベントリスナー追加
        Ext.fly(window).on('scroll', function(e){
            var pos = Ext.getScrollPos();
            this.layer.setTop(pos.y);
        }, this);

        // キーマップ設定
        this.keymap = new Ext.KeyMap(document, {
            key: Ext.EventObject.F9,
            fn: function() {

                if(!PXDEBUG_APP.enable) {
                    return;
                }

                if (this.showed) {
                    this.hide();
                } else {
                    this.fireEvent('hidecontainer', this);
                }

            },
            scope: this
        });

        // スーパークラスメソッドコール
        PXDEBUG.BootIcon.superclass.initComponent.call(this);
    },

    // }}}
    // {{{ show

    show : function() {

        // アプリケーション操作停止
        PXDEBUG_APP.enable = false;

        // スクロール位置復元
        if(this.preY) {
            window.scrollTo(0,this.preY);
        }

        this.layer.show();
        this.layer.setTop(Ext.getScrollPos().y);
        this.layer.setLeft(-50);
        this.layer.shift({
            x: 0,
            duration: this.duration.show,
            easing: 'easeOutStrong',
            opacity: 1,
            callback: function(el) {

                // イベントリスナー追加
                this.layer.on('click', this.hide, this);

                // 表示フラグ設定
                this.showed = true;

                // アプリケーション操作開始
                PXDEBUG_APP.enable = true;

                // イベント発火
                this.fireEvent('show', this, el);

            },
            scope: this
        });
    },

    // }}}
    // {{{ hide

    hide : function() {

        if (PXDEBUG_APP.enable === false || PXSTUDIO_APP.showed === true){
            return;
        }

        // スクロール位置記憶
        this.preY = Ext.getScrollPos().y;

        // アプリケーション操作停止
        PXDEBUG_APP.enable = false;

        // イベントリスナー削除
        this.layer.un('click', this.hide, this);

        // 非表示
        this.layer.shift({
            x: -50,
            duration: this.duration.hide,
            easing: 'easeOutStrong',
            opacity: 0,
            callback: function(el) {

                // レイヤー非表示
                this.layer.hide();

                // 表示フラグ設定
                this.showed = false;

                // イベント発火
                this.fireEvent('hide', this, this.layer);
            },
            scope: this
        });

    }

    // }}}

});

// }}}
/*!
 * xFrameworkPX Debug Tools
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ PXDEBUG.ParameterPanel

PXDEBUG.CacheClearWindow = Ext.extend(Ext.Window, {

    // {{{ initComponent

    initComponent: function() {

        this.id = 'pxdebug_cache_clear_window';

        Ext.apply(this,{
            title: 'キャッシュの削除',
            iconCls: 'pxdebug-icon-cacheclear',
            modal: true,
            resizable: false,
            autoHeight: true,
            defaultButton: this.id + '_btnDelete',
            width: 300,
            items: [{
                id: this.id + '_tree',
                xtype: 'treepanel',
                border: false,
                padding: 10,
                useArrows:true,
                rootVisible: false,
                root: {
                    children: [{
                        id: 'ccw_schema',
                        text: "スキーマキャッシュ",
                        iconCls: 'pxdebug-icon-none',
                        checked: true,
                        leaf: true
                    },{
                        id: 'ccw_config',
                        text: "設定キャッシュ",
                        iconCls: 'x-hidden',
                        checked: true,
                        leaf: true
                    },{
                        id: 'ccw_template',
                        text: "テンプレートキャッシュ",
                        iconCls: 'x-hidden',
                        checked: true,
                        leaf: true
                    }]
                }
            
            }],
            buttons: [{
                id: this.id + '_btnDelete',
                text: '今すぐ削除',
                handler: function() {

                    var root = Ext.getCmp(this.id + '_tree').root;
                    this.disable();

                    PXDEBUG_APP.keymap(false);

                    xFrameworkPX_DebugTools.clearCaches(
                        root.findChild('id', 'ccw_schema').attributes.checked,
                        root.findChild('id', 'ccw_config').attributes.checked,
                        root.findChild('id', 'ccw_template').attributes.checked,
                        function(){
                            PXDEBUG_APP.keymap(true);
                            this.close();
                        },
                        this
                    );

                },
                scope: this
            },{
                text: 'キャンセル',
                handler: function() {
                    this.close();
                },
                scope: this
            }]
        });

        // スーパークラスメソッドコール
        PXDEBUG.CacheClearWindow.superclass.initComponent.call(this);
    }

    // }}}

});

// }}}
/*!
 * xFrameworkPX Debug Tools
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ PXDEBUG.ParameterPanel

PXDEBUG.HelpWindow = Ext.extend(Ext.Window, {

    // {{{ initComponent

    initComponent: function() {

        Ext.apply(this,{
            title: 'ヘルプ',
            defaultButton: this.id + '_btnOk',
            iconCls: 'pxdebug-icon-help',
            modal: true,
            resizable: false,
            autoHeight: true,
            width: 400,
            items: [{
                border: false,
                height: 100,
                style: 'border-bottom: solid 1px #ccc; padding-top: 25px; padding-left: 25px; background-color: white;',
                html: [
                    '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="260" height="50" id="pxdebuicon" align="middle">' +
                    '<param name="allowScriptAccess" value="sameDomain" />' +
                    '<param name="allowFullScreen" value="false" />' +
                    '<param name="movie" value="' + window.relpath +'xFrameworkPX/debug/resources/images/pxdebugversion.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" /><embed src="' + window.relpath +'xFrameworkPX/debug/resources/images/pxdebugversion.swf" quality="high" bgcolor="#ffffff" width="260" height="50" name="pxdebuicon" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />' +
                    '</object>'
                ]
            },{
                xtype: 'grid',
                border: false,
                store: new Ext.data.ArrayStore({
                    autoDestroy: true,
                    storeId: 'HelpMenuStore',
                    fields: [
                        'shortcut',
                        'description'
                    ],
                    data: [
                        ['T','トレース表示'],
                        ['P','パラメータ表示'],
                        ['S','セッション表示'],
                        ['K','クッキー表示'],
                        ['U','ユーザーデータ表示'],
                        ['Q','クエリー表示'],
                        ['F','プロファイル表示'],
                        ['Ctrl+Alt+C','キャッシュの削除ウィンドウ表示']
                    ]
                }),
                colModel: new Ext.grid.ColumnModel({
                    defaults: {
                        sortable: false
                    },
                    columns: [
                        {header: 'ショートカット', width: 150, dataIndex: 'shortcut'},
                        {header: '説明', width: 350, dataIndex: 'description'}
                    ]
                }),
                viewConfig: {
                    forceFit: true
                },
                listeners: {
                    'afterrender': {
                        fn: function() {
                            (function() {
                                this.syncShadow();
                            }).defer(100, this);
                        },
                        scope: this
                    }
                },
                autoHeight: true,
                sm: new Ext.grid.RowSelectionModel({singleSelect:true})
            }],
            buttons: [{
                id: this.id + '_btnOk',
                text: 'OK',
                handler: function() {
                    this.close();
                },
                scope: this
            }]
        });

        // スーパークラスメソッドコール
        PXDEBUG.HelpWindow.superclass.initComponent.call(this);

//        this.syncSize();
    }

    // }}}

});

// }}}
/*!
 * xFrameworkPX Debug Tools
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ PXDEBUG.ParameterPanel

PXDEBUG.ParameterPanel = Ext.extend(Ext.Panel, {

    // {{{ initComponent

    initComponent: function() {

        // 初期設定
        Ext.applyIf(this, {id: Ext.id()});


        // 設定適用
        Ext.apply(this, {
            title: 'パラメーター',
            iconCls: 'pxdebug-icon-parameter',
            layout: 'border',
            items: [{
                region: 'center',
                margins: '4 4 0 4',
                xtype: 'grid',
                id: this.id + '_grid',
                store: new Ext.data.ArrayStore({
                    autoDestroy: true,
                    storeId: 'ParameterStore',
                    fields: [
                        'no',
                        'valiable',
                        'summary',
                        'detail',
                        'type'
                    ],
                    data: PXDEBUG_DATA.parameter
                }),
                colModel: new Ext.grid.ColumnModel({
                    defaults: {
                        width: 120,
                        sortable: true
                    },
                    columns: [
                        {header: 'No', width: 50, dataIndex: 'no'},
                        {header: '種別', width: 70, dataIndex: 'type'},
                        {header: '変数', dataIndex: 'valiable'},
                        {header: '内容', width: 700, dataIndex: 'summary'}
                    ]
                }),
                viewConfig: {
                    //forceFit: true
                },
                sm: new Ext.grid.RowSelectionModel({singleSelect:true})
            },{
                region: 'south',
                margins: '0 4 4 4',
                split: true,
                id: this.id + '_detail',
                bodyStyle: 'padding:10px;',
                iconCls: 'pxdebug-icon-monitor-magnifier',
                title: 'パラメーター詳細',
                autoScroll: true,
                collapsible: true,
                height: 300
            }]
        });

        // スーパークラスメソッドコール
        PXDEBUG.ParameterPanel.superclass.initComponent.call(this);

    },

    // }}}
    // {{{ initEvents

    initEvents: function() {

        // スーパークラスメソッドコール
        PXDEBUG.ParameterPanel.superclass.initEvents.call(this);

        var sm = this.getComponent(this.id + '_grid').getSelectionModel();
        sm.on('rowselect', this.onRowSelect, this);
    },

    // }}}
    // {{{ onRowSelect

    onRowSelect: function(sm, rowIdx, r) {

        Ext.getCmp(this.id + '_detail').update(r.data.detail);

    }

    // }}}

});

// }}}
// {{{ xtype register

Ext.reg('pxdebug-parameter', PXDEBUG.ParameterPanel);

// }}}
/*!
 * xFrameworkPX Debug Tools
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ PXDEBUG.ProfilerPanel

PXDEBUG.ProfilerPanel = Ext.extend(Ext.Panel, {

    // {{{ initComponent

    initComponent: function() {

        // 初期設定
        Ext.applyIf(this, {id: Ext.id()});

        // 設定適用
        Ext.apply(this, {
            title: 'プロファイル',
            iconCls: 'pxdebug-icon-profiler',
            layout: 'border',
            items: [{
                region: 'center',
                margins: '4 4 4 4',
                xtype: 'grid',
                id: this.id + '_grid',
                store: new Ext.data.ArrayStore({
                    autoDestroy: true,
                    storeId: 'UserDataStore',
                    fields: [
                        'no',
                        'instance',
                        'cls',
                        'method',
                        'type',
                        'time'
                    ],
                    data: PXDEBUG_DATA.profiler
                }),
                colModel: new Ext.grid.ColumnModel({
                    defaults: {
                        width: 120,
                        sortable: false
                    },
                    columns: [
                        {
                            header: 'No',
                            width: 50,
                            dataIndex: 'no',
                            renderer: function(
                                value,
                                metaData,
                                record,
                                rowIndex,
                                colIndex,
                                store
                            ) {
                                if(store.data.items.length == record.data.no) {
                                    return '';
                                }
                                return record.data.no;
                            }
                        },
                        {
                            header: 'インスタンス',
                            width: 250,
                            dataIndex: 'instance'
                        },{
                            header: 'クラス',
                            dataIndex: 'cls',
                            width: 250,
                            renderer: function(
                                value,
                                metaData,
                                record,
                                rowIndex,
                                colIndex,
                                store
                            ) {
                                if(record.data.cls === '[TOTAL]') {
                                    return '';
                                }
                                return record.data.cls;
                            }
                        },
                        {header: 'メソッド', dataIndex: 'method'},
                        {header: '種別', dataIndex: 'type'},
                        {
                            header: 'パフォーマンス',
                            width: 700,
                            dataIndex: 'time',
                            renderer: function(
                                value,
                                metaData,
                                record,
                                rowIndex,
                                colIndex,
                                store
                            ) {
                                if(record.data.cls === '[TOTAL]') {
                                    var html = '<div class="pxdebug-proctime">%1$s<span>ms</span><div>';
                                    html = html.replace('%1$s', record.data.time);
                                    return html;
                                } else {
                                    if(record.data.type === 'User') {
                                        var html = '<div class="pxdebug-usertime">%1$s<span>ms</span><div>';
                                        html = html.replace('%1$s', record.data.time);
                                        return html;
                                    }
                                    return record.data.time;
                                }
                            }
                        }
                    ]
                }),
                viewConfig: {
                    //forceFit: true
                },
                sm: new Ext.grid.RowSelectionModel({singleSelect:true})
            }]
        });

        // スーパークラスメソッドコール
        PXDEBUG.ProfilerPanel.superclass.initComponent.call(this);
    },

    // }}}
    // {{{ initEvents

    initEvents: function() {

        // スーパークラスメソッドコール
        PXDEBUG.ProfilerPanel.superclass.initEvents.call(this);
    }

    // }}}

});

// {{{ xtype register

Ext.reg('pxdebug-profiler', PXDEBUG.ProfilerPanel);

// }}}
/*!
 * xFrameworkPX Debug Tools
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ PXDEBUG.QueryPanel

PXDEBUG.QueryPanel = Ext.extend(Ext.Panel, {

    // {{{ initComponent

    initComponent: function() {

        // 初期設定
        Ext.applyIf(this, {id: Ext.id()});

        // 設定適用
        Ext.apply(this, {
            title: 'クエリー',
            iconCls: 'pxdebug-icon-query',
            layout: 'border',
            items: [{
                region: 'center',
                margins: '4 4 0 4',
                xtype: 'grid',
                id: this.id + '_grid',
                store: new Ext.data.ArrayStore({
                    autoDestroy: true,
                    storeId: 'QueryStore',
                    fields: [
                        'no',
                        'table',
                        'module',
                        'summary',
                        'rows',
                        {name: 'time', type: 'float'},
                        'query'
                    ],
                    data: PXDEBUG_DATA.query
                }),
                colModel: new Ext.grid.ColumnModel({
                    defaults: {
                        width: 120,
                        sortable: true
                    },
                    columns: [
                        {header: 'No', width: 50, dataIndex: 'no'},
                        {header: 'テーブル', dataIndex: 'table'},
                        {header: 'モジュール', width: 300, dataIndex: 'module'},
                        {header: 'クエリー', width: 500,dataIndex: 'summary'},
                        {header: '取得行数', width: 80, dataIndex: 'rows'},
                        {
                            header: '実行時間',
                            dataIndex: 'time',
                            renderer: function(
                                value,
                                metaData,
                                record,
                                rowIndex,
                                colIndex,
                                store
                            ) {
                                var col = 'green';
                                var html = '<span style="color:%$1s;">%$2sms</span>';
                                if(record.data.time > 3) {
                                    col = 'red';
                                } else if(record.data.time > 1) {
                                    col = 'orange';
                                }
                                html = html.replace('%$1s', col);
                                html = html.replace('%$2s', record.data.time);
                                return html;
                            }
                        }
                    ]
                }),
                viewConfig: {
                    //forceFit: true
                },
                sm: new Ext.grid.RowSelectionModel({singleSelect:true})
            },{
                region: 'south',
                margins: '0 4 4 4',
                split: true,
                id: this.id + '_detail',
                //contentEl: 'PXDEBUG_CONTENT_SQL',
                bodyStyle: 'padding:10px;',
                iconCls: 'pxdebug-icon-database-magnifier',
                title: 'クエリー詳細',
                /*
                tools: [{
                    id: 'save',
                    handler: function() {

                        Ext.copyText('aaa');

                    },
                    scope: this
                }],
                */
                autoScroll: true,
                collapsible: true,
                height: 300
            }]
        });

        // スーパークラスメソッドコール
        PXDEBUG.QueryPanel.superclass.initComponent.call(this);

    },

    // }}}
    // {{{ initEvents

    initEvents: function() {

        PXDEBUG.QueryPanel.superclass.initEvents.call(this);


        var sm = this.getComponent(this.id + '_grid').getSelectionModel();
        sm.on('rowselect', this.onRowSelect, this);
    },

    // }}}
    // {{{ onRowSelect

    onRowSelect: function(sm, rowIdx, r) {

        Ext.getCmp(this.id + '_detail').update(r.data.query);

    }

    // }}}

});

// }}}
// {{{ xtype register

Ext.reg('pxdebug-query', PXDEBUG.QueryPanel);

// }}}
/*!
 * xFrameworkPX Debug Tools
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ PXDEBUG.TracePanel

PXDEBUG.TracePanel = Ext.extend(Ext.Panel, {

    // {{{ initComponent

    initComponent: function() {

        // 初期設定
        Ext.applyIf(this, {id: Ext.id()});

        // 設定適用
        Ext.apply(this, {
            title: 'トレース',
            iconCls: 'pxdebug-icon-trace',
            layout: 'border',
            items: [{
                region: 'center',
                margins: '4 4 0 4',
                xtype: 'grid',
                id: this.id + '_grid',
                store: new Ext.data.ArrayStore({
                    autoDestroy: true,
                    storeId: 'TraceStore',
                    fields: [
                        'no',
                        'cls',
                        'method',
                        'line',
                        'tag',
                        'summary',
                        'detail'
                    ],
                    data: PXDEBUG_DATA.trace
                }),
                colModel: new Ext.grid.ColumnModel({
                    defaults: {
                        width: 120,
                        sortable: true
                    },
                    columns: [
                        {header: 'No', width: 50, dataIndex: 'no'},
                        {header: '内容', width: 700, dataIndex: 'summary'},
                        {header: 'タグ', dataIndex: 'tag'},
                        {header: 'クラス', dataIndex: 'cls'},
                        {header: 'メソッド', dataIndex: 'method'},
                        {header: 'ライン', dataIndex: 'line'}
                    ]
                }),
                viewConfig: {
                    //forceFit: true
                },
                sm: new Ext.grid.RowSelectionModel({singleSelect:true})
            },{
                region: 'south',
                margins: '0 4 4 4',
                split: true,
                id: this.id + '_detail',
                bodyStyle: 'padding:10px;',
                iconCls: 'pxdebug-icon-flag-green-magnifier',
                title: 'トレース詳細',
                autoScroll: true,
                collapsible: true,
                height: 300
            }]
        });

        // スーパークラスメソッドコール
        PXDEBUG.TracePanel.superclass.initComponent.call(this);

    },

    // }}}
    // {{{ initEvents

    initEvents: function() {

        PXDEBUG.TracePanel.superclass.initEvents.call(this);

        var sm = this.getComponent(this.id + '_grid').getSelectionModel();
        sm.on('rowselect', this.onRowSelect, this);
    },

    // }}}
    // {{{ onRowSelect

    onRowSelect: function(sm, rowIdx, r) {

        Ext.getCmp(this.id + '_detail').update(r.data.detail);

    }

    // }}}

});

// {{{ xtype register

Ext.reg('pxdebug-trace', PXDEBUG.TracePanel);

// }}}
/*!
 * xFrameworkPX Debug Tools
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ PXDEBUG.UserDataPanel

PXDEBUG.UserDataPanel = Ext.extend(Ext.Panel, {

    // {{{ initComponent

    initComponent: function() {

        // 初期設定
        Ext.applyIf(this, {id: Ext.id()});

        // 設定適用
        Ext.apply(this, {
            title: 'ユーザーデータ',
            iconCls: 'pxdebug-icon-userdata',
            layout: 'border',
            items: [{
                region: 'center',
                margins: '4 4 0 4',
                xtype: 'grid',
                id: this.id + '_grid',
                store: new Ext.data.ArrayStore({
                    autoDestroy: true,
                    storeId: 'UserDataStore',
                    fields: [
                        'no',
                        'valiable',
                        'summary',
                        'detail'
                    ],
                    data: PXDEBUG_DATA.userdata
                }),
                colModel: new Ext.grid.ColumnModel({
                    defaults: {
                        width: 120,
                        sortable: true
                    },
                    columns: [
                        {header: 'No', width: 50, dataIndex: 'no'},
                        {header: 'アサイン変数', dataIndex: 'valiable'},
                        {header: '内容', width: 700, dataIndex: 'summary'}
                    ]
                }),
                viewConfig: {
                    //forceFit: true
                },
                sm: new Ext.grid.RowSelectionModel({singleSelect:true})
            },{
                region: 'south',
                margins: '0 4 4 4',
                split: true,
                id: this.id + '_detail',
                bodyStyle: 'padding:10px;',
                iconCls: 'pxdebug-icon-note-magnifier',
                title: 'ユーザーデータ詳細',
                autoScroll: true,
                collapsible: true,
                height: 300
            }]
        });

        // スーパークラスメソッドコール
        PXDEBUG.UserDataPanel.superclass.initComponent.call(this);

    },

    // }}}
    // {{{ initEvents

    initEvents: function() {

        PXDEBUG.UserDataPanel.superclass.initEvents.call(this);

        var sm = this.getComponent(this.id + '_grid').getSelectionModel();
        sm.on('rowselect', this.onRowSelect, this);
    },

    // }}}
    // {{{ onRowSelect

    onRowSelect: function(sm, rowIdx, r) {

        Ext.getCmp(this.id + '_detail').update(r.data.detail);

    }

    // }}}

});

// {{{ xtype register

Ext.reg('pxdebug-userdata', PXDEBUG.UserDataPanel);

// }}}
/*!
 * xFrameworkPX Debug Tools
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ PXDEBUG.SessionPanel

PXDEBUG.SessionPanel = Ext.extend(Ext.Panel, {

    // {{{ initComponent

    initComponent: function() {

        // 初期設定
        Ext.applyIf(this, {id: Ext.id()});

        // 設定適用
        Ext.apply(this, {
            title: 'セッション',
            iconCls: 'pxdebug-icon-session',
            layout: 'border',
            items: [{
                tbar: [{
                    id: this.id + '_btnDelete',
                    text: '削除',
                    disabled: true,
                    iconCls: 'pxdebug-icon-session-delete',
                    handler: function() {
                        var grid = Ext.getCmp(this.id + '_grid');
                        var sm = grid.getSelectionModel();

                        Ext.MessageBox.confirm(
                            '確認',
                            sm.getSelected().data.key + 'を削除しますか？',
                            function(btn) {
                                if (btn == 'yes') {

                                    Ext.MessageBox.show({
                                        title: 'セッション削除',
                                        msg : '削除中...',
                                        width: 200,
                                        buttons: false,
                                        closable:false,
                                        wait:true,
                                        modal:true,
                                        minWidth: this.minProgressWidth,
                                        waitConfig: {}
                                    });
                                    xFrameworkPX_DebugTools.removeSession(
                                        sm.getSelected().data.key,
                                        function() {
                                            grid.store.remove(sm.getSelected());
                                            Ext.getCmp(this.id + '_detail').update('');

                                            if (grid.store.getCount() == 0) {
                                                Ext.getCmp(this.id + '_btnDelete').disable();
                                            }

                                            Ext.MessageBox.hide();
                                        },
                                        this
                                    );
                                }
                            },
                            this
                        );
                    },
                    scope: this
                }],
                region: 'center',
                margins: '4 4 0 4',
                xtype: 'grid',
                id: this.id + '_grid',
                store: new Ext.data.ArrayStore({
                    autoDestroy: true,
                    storeId: 'SessionStore',
                    fields: [
                        'no',
                        'key',
                        'detail',
                        'summary'
                    ],
                    data: PXDEBUG_DATA.session
                }),
                colModel: new Ext.grid.ColumnModel({
                    defaults: {
                        width: 120,
                        sortable: true
                    },
                    columns: [
                        {header: 'No', width: 50, dataIndex: 'no'},
                        {header: '変数', dataIndex: 'key'},
                        {header: '内容', width: 700, dataIndex: 'summary'}
                    ]
                }),
                viewConfig: {
                    //forceFit: true
                },
                sm: new Ext.grid.RowSelectionModel({
                    singleSelect:true,
                    listeners: {
                        'rowselect' : {
                            fn: function() {
                                Ext.getCmp(this.id + '_btnDelete').enable();
                            },
                            scope: this
                        },
                        'rowdeselect' : {
                            fn: function() {
                                Ext.getCmp(this.id + '_btnDelete').disable();
                            },
                            scope: this
                        }
                    }
                })
            },{
                region: 'south',
                margins: '0 4 4 4',
                split: true,
                id: this.id + '_detail',
                bodyStyle: 'padding:10px;',
                iconCls: 'pxdebug-icon-flag-bricks-magnifier',
                title: 'セッション詳細',
                autoScroll: true,
                collapsible: true,
                height: 300
            }]
        });

        // スーパークラスメソッドコール
        PXDEBUG.SessionPanel.superclass.initComponent.call(this);

    },

    // }}}
    // {{{ initEvents

    initEvents: function() {

        // スーパークラスメソッドコール
        PXDEBUG.SessionPanel.superclass.initEvents.call(this);

        var sm = this.getComponent(this.id + '_grid').getSelectionModel();
        sm.on('rowselect', this.onRowSelect, this);
    },

    // }}}
    // {{{ onRowSelect

    onRowSelect: function(sm, rowIdx, r) {

        Ext.getCmp(this.id + '_detail').update(r.data.detail);

    }

    // }}}

});

// }}}
// {{{ xtype register

Ext.reg('pxdebug-session', PXDEBUG.SessionPanel);

// }}}
/*!
 * xFrameworkPX Debug Tools
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ PXDEBUG.CookiePanel

PXDEBUG.CookiePanel = Ext.extend(Ext.Panel, {

    // {{{ initComponent

    initComponent: function() {

        // 初期設定
        Ext.applyIf(this, {id: Ext.id()});

        // 設定適用
        Ext.apply(this, {
            title: 'クッキー',
            iconCls: 'pxdebug-icon-cookie',
            layout: 'border',
            items: [{
                tbar: [{
                    id: this.id + '_btnDelete',
                    text: '削除',
                    disabled: true,
                    iconCls: 'pxdebug-icon-cookie-delete',
                    handler: function() {
                        var grid = Ext.getCmp(this.id + '_grid');
                        var sm = grid.getSelectionModel();

                        Ext.MessageBox.confirm(
                            '確認',
                            sm.getSelected().data.key + 'を削除しますか？',
                            function(btn) {
                                if (btn == 'yes') {
                                    var expDay = new Date();
                                    expDay.setTime(expDay.getTime()+(-1*1000*60*60*24));
                                    expDay = expDay.toGMTString();
                                    document.cookie = sm.getSelected().data.key + "="+escape(0)+";expires="+expDay;

                                    grid.store.remove(sm.getSelected());
                                    Ext.getCmp(this.id + '_detail').update('');

                                    if (grid.store.getCount() == 0) {
                                        Ext.getCmp(this.id + '_btnDelete').disable();
                                    }
                                }
                            },
                            this
                        );
                    },
                    scope: this
                }],
                region: 'center',
                margins: '4 4 0 4',
                xtype: 'grid',
                id: this.id + '_grid',
                store: new Ext.data.ArrayStore({
                    autoDestroy: true,
                    storeId: 'CookieStore',
                    fields: [
                        'no',
                        'key',
                        'detail',
                        'summary'
                    ],
                    data: PXDEBUG_DATA.cookie
                }),
                colModel: new Ext.grid.ColumnModel({
                    defaults: {
                        width: 120,
                        sortable: true
                    },
                    columns: [
                        {header: 'No', width: 50, dataIndex: 'no'},
                        {header: '変数', dataIndex: 'key'},
                        {header: '内容', width: 700, dataIndex: 'summary'}
                    ]
                }),
                viewConfig: {
                    //forceFit: true
                },
                sm: new Ext.grid.RowSelectionModel({
                    singleSelect:true,
                    listeners: {
                        'rowselect' : {
                            fn: function() {
                                Ext.getCmp(this.id + '_btnDelete').enable();
                            },
                            scope: this
                        },
                        'rowdeselect' : {
                            fn: function() {
                                Ext.getCmp(this.id + '_btnDelete').disable();
                            },
                            scope: this
                        }
                    }
                })
            },{
                region: 'south',
                margins: '0 4 4 4',
                split: true,
                id: this.id + '_detail',
                bodyStyle: 'padding:10px;',
                iconCls: 'pxdebug-icon-flag-asterisk-yellow-magnifier',
                title: 'クッキー詳細',
                autoScroll: true,
                collapsible: true,
                height: 300
            }]
        });

        // スーパークラスメソッドコール
        PXDEBUG.CookiePanel.superclass.initComponent.call(this);

    },

    // }}}
    // {{{ initEvents

    initEvents: function() {

        // スーパークラスメソッドコール
        PXDEBUG.CookiePanel.superclass.initEvents.call(this);

        var sm = this.getComponent(this.id + '_grid').getSelectionModel();
        sm.on('rowselect', this.onRowSelect, this);
    },

    // }}}
    // {{{ onRowSelect

    onRowSelect: function(sm, rowIdx, r) {

        Ext.getCmp(this.id + '_detail').update(r.data.detail);

    }

    // }}}

});

// }}}
// {{{ xtype register

Ext.reg('pxdebug-cookie', PXDEBUG.CookiePanel);

// }}}
/*!
 * xFrameworkPX Debug Tools
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ PXDEBUG.Viewport

PXDEBUG.Viewport = Ext.extend(Ext.Viewport, {

    // {{{ initComponent

    initComponent: function() {

        Ext.apply(this, {
            id: Ext.id()
        });

        Ext.apply(this, {
            layout: 'fit',
            items: [{
                title: 'xFrameworkPX Debug Tools',
                iconCls: 'pxdebug-icon-logo',
                layout: 'border',
                tools: [{
                    id: 'gear',
                    handler: function() {
                        var w = new PXDEBUG.CacheClearWindow();
                        w.show();
                    },
                    scope: this
                },{
                    id: 'help',
                    handler: function() {
                        var w = new PXDEBUG.HelpWindow();
                        w.show();
                    },
                    scope: this
                },{
                    id: 'close',
                    handler: function() {
                        this.fireEvent('hidecontainer');
                    },
                    scope: this
                }],
                border: false,
                items: [{
                    xtype: 'tabpanel',
                    id: this.id + '_tab',
                    deferredRender: false,
                    activeItem: 0,
                    border: false,
                    listeners: {
                        'tabchange': {
                            fn: function(tab, np, cp) {
                                var grid = Ext.getCmp(np.id + '_grid');
                                var sm = grid.getSelectionModel();

                                if (grid.store.getCount() > 0 ) {
                                    (function(){
                                        grid.getView().focusEl.focus();
                                        sm.selectFirstRow();
                                    }).defer(100, this);
                                }

                                Ext.iterate(tab.items.items, function(item, n){
                                    if(item == np) {
                                        Ext.getCmp(this.id + '_tab').activeTabPos = n;
                                    }
                                }, this);
                            },
                            scope: this
                        },
                        'afterrender': {
                            fn: function() {
                                Ext.getCmp(this.id + '_tab').activeTabPos = 0;
                            },
                            scope: this
                        }
                    },
                    region: 'center',
                    items: [{
                        xtype: 'pxdebug-trace',
                        id: this.id + '_trace'
                    },{
                        xtype: 'pxdebug-parameter',
                        id: this.id + '_parameter'
                    },{
                        xtype: 'pxdebug-session',
                        id: this.id + '_session'
                    },{
                        xtype: 'pxdebug-cookie',
                        id: this.id + '_cookie'
                    },{
                        xtype: 'pxdebug-userdata',
                        id: this.id + '_userdata'
                    },{
                        xtype: 'pxdebug-query',
                        id: this.id + '_query'
                    },{
                        xtype: 'pxdebug-profiler',
                        id: this.id + '_profiler'
                    }]
                }]
            }]
        });

        // スーパークラスメソッドコール
        PXDEBUG.Viewport.superclass.initComponent.call(this);
    },

    // }}}
    // {{{ getTab

    getTab : function() {
        return Ext.getCmp(this.id + '_tab');
    },

    // }}}
    // {{{ setActiveTab

    setActiveTab : function(name) {

        switch(name) {
            case 'query':
                this.getTab().setActiveTab(5);
            break;
            case 'profile':
                this.getTab().setActiveTab(6);
            break;
            case 'session':
                this.getTab().setActiveTab(2);
            break;
            case 'cookie':
                this.getTab().setActiveTab(3);
            break;
            case 'userdata':
                this.getTab().setActiveTab(4);
            break;
            case 'parameter':
                this.getTab().setActiveTab(1);
            break;
            case 'trace':
                this.getTab().setActiveTab(0);
            break;
            case 'cache':
                var w = new PXDEBUG.CacheClearWindow();
                w.show();
            break;
            case 'left':
                var tab = this.getTab();
                var pos = --tab.activeTabPos;
                if (pos < 0) {
                    pos = tab.items.items.length - 1;
                    tab.activeTabPos = pos;
                }
                tab.setActiveTab(pos);
            break;
            case 'right':
                var tab = this.getTab();
                var pos = ++tab.activeTabPos;
                if (pos >= tab.items.items.length) {
                    pos = 0;
                    tab.activeTabPos = 0;
                }
                tab.setActiveTab(pos);
            break;
        }
    }

    // }}}

});

// }}}
