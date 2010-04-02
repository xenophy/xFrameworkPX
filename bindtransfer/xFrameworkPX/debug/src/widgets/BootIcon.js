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
