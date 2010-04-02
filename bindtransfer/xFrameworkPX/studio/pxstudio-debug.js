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
 * xFrameworkPX Studio
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ Namespace

Ext.ns(
    'PXSTUDIO',
    'PXSTUDIO.app',
    'PXSTUDIO.grid',
    'PXSTUDIO.form',
    'PXSTUDIO.tree'
);

// }}}
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
/*!
 * xFrameworkPX Studio
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
 * xFrameworkPX Studio
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

// {{{ PXSTUDIO.Viewport

PXSTUDIO.Viewport = Ext.extend(Ext.Viewport, {

    // {{{ initComponent

    initComponent: function() {

        Ext.apply(this, {
            id: Ext.id()
        });

        Ext.apply(this, {
            layout: 'fit',
            items: [{
                title: PXSTUDIO_APP.appName,
                iconCls: 'pxstudio-icon-logo',
                layout: 'border',
                tools: [{
                    id: 'gear',
                    handler: function() {
                        //var w = new PXSTUDIO.CacheClearWindow();
                        //w.show();
                    },
                    scope: this
                },{
                    id: 'help',
                    handler: function() {
                        //var w = new PXSTUDIO.HelpWindow();
                        //w.show();
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
region: 'center',
                    title: 'めいん'

                }]
            }]
        });

        // スーパークラスメソッドコール
        PXSTUDIO.Viewport.superclass.initComponent.call(this);
    }

    // }}}

});

// }}}
