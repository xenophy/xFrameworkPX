/*!
 * xFrameworkPX Studio
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ PXSTUDIO.NavigationPanel

PXSTUDIO.NavigationPanel = Ext.extend(Ext.Panel, {

    // {{{ initComponent

    initComponent: function() {

        // 初期設定
        Ext.applyIf(this, {id: Ext.id()});

        Ext.apply(this, {
            layout: 'fit',
            items: [{
                xtype: 'tabpanel',
                activeItem: 0,
                tabPosition: 'bottom',
                border: false,
                items: [{
                    id: this.id + '_VSCREEN',
                    xtype: 'pxstudio-navigation-vscreen',
                    listeners: {
                        'activate': {
                            fn: function(p) {

                                // タブアクティブ化時処理実行
                                this._activeTab(p, {
                                    vscreen: true,
                                    folder: true,
                                    con: false,
                                    html: false,
                                    pxml: false
                                });
                            },
                            scope: this
                        }
                    }
                },{
                    id: this.id + '_FILE',
                    xtype: 'pxstudio-navigation-file',
                    listeners: {
                        'activate': {
                            fn: function(p) {

                                // タブアクティブ化時処理実行
                                this._activeTab(p, {
                                    vscreen: false,
                                    folder: true,
                                    con: true,
                                    html: true,
                                    pxml: true
                                });

                            },
                            scope: this
                        }
                    }
                }]
            }]
        });

        // イベントリスナー追加
        this.on('clickToolbar', this.onClickToolbar, this);

        // スーパークラスメソッドコール
        PXSTUDIO.NavigationPanel.superclass.initComponent.call(this);
    },

    // }}}
    // {{{ initEvents

    initEvents: function() {

        PXSTUDIO.NavigationPanel.superclass.initEvents.call(this);
    },

    // }}}
    // {{{ onClickToolbar

    onClickToolbar: function(type) {

        if (type === 'vscreen') {
            Ext.getCmp(this.id + '_VSCREEN').createVScreen();
        }



    },

    // }}}
    // {{{ _activeTab

    _activeTab: function(p, o) {

        // セレクションモデル取得
        var sm = p.getSelectionModel();

        // ノードが選択されていない場合rootノードを選択
        if (sm.getSelectedNode() === null) {
            sm.select(p.root);
        }

        // イベント発火
        this.fireEvent('switchToolbar', o);
    }

    // }}}

});

// {{{ xtype register

Ext.reg('pxstudio-navigation', PXSTUDIO.NavigationPanel);

// }}}
