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
                    region: 'north',
                    border: false,
                    tbar: [{
                        text: 'ファイル',
                        menu: [{
                            text: '終了',
                            iconCls: 'pxstudio-icon-logo',
                            handler: function() {
                                (function(){
                                    this.fireEvent('hidecontainer');
                                }).defer(100, this);
                            },
                            scope: this
                        }]
                    },{
                        text: '編集'
                    },{
                        text: '検索'
                    },{
                        text: '表示'
                    },{
                        text: 'ツール'
                    },{
                        text: 'ウィンドウ'
                    },{
                        text: 'ヘルプ'
                    }],
                    bbar: [{
                        id: this.id + '_toolbar_vscreen',
                        iconCls: 'pxstudio-navi-vscreen-add',
                        tooltip: '新規仮想スクリーン作成',
                        handler: function() {
                            var cmp = Ext.getCmp(this.id + '_Navi');
                            cmp.fireEvent('clickToolbar', 'vscreen');
                        },
                        scope: this,
                        disabled: true
                    },{
                        id: this.id + '_toolbar_folder',
                        iconCls: 'pxstudio-navi-file',
                        tooltip: '新規フォルダー作成',
                        handler: function() {
                            var cmp = Ext.getCmp(this.id + '_Navi');
                            cmp.fireEvent('clickToolbar', 'folder');
                        },
                        scope: this,
                        disabled: true
                    },{
                        id: this.id + '_toolbar_con',
                        iconCls: 'pxstudio-navi-file-con',
                        tooltip: '新規コントローラー作成',
                        handler: function() {
                            var cmp = Ext.getCmp(this.id + '_Navi');
                            cmp.fireEvent('clickToolbar', 'controller');
                        },
                        scope: this,
                        disabled: true
                    },{
                        id: this.id + '_toolbar_html',
                        iconCls: 'pxstudio-navi-file-html',
                        tooltip: '新規テンプレート作成',
                        handler: function() {
                            var cmp = Ext.getCmp(this.id + '_Navi');
                            cmp.fireEvent('clickToolbar', 'template');
                        },
                        scope: this,
                        disabled: true
                    }]
                },{
                    id: this.id + '_Navi',
                    margins: '5 0 0 5',
                    region: 'west',
                    width: 250,
                    split: true,
                    xtype: 'pxstudio-navigation',
                    listeners: {
                        'switchToolbar': {
                            fn: this.onSwitchToolbar,
                            scope: this
                        }
                    }
                },{
                    region: 'south',
                    margins: '0 5 5 5',
                    split: true,
                    collapsible: true,
                    height: 100,
                    title: ''
                },{
                    margins: '5 5 0 0',
                    region: 'center',
                    activeItem: 0,
                    border: false,
                    layout: 'card',
                    items: [{
                        id: 'PXSTUDIO_EMPTY_PANEL'
                    }]
                }]
            }]
        });

        // スーパークラスメソッドコール
        PXSTUDIO.Viewport.superclass.initComponent.call(this);
    },

    // }}}
    // {{{ onSwitchToolbar

    /**
     * ツールバー状態切り替えイベントハンドラ
     * 
     * @param o ボタン状態オブジェクト
     */
    onSwitchToolbar: function(o)
    {
        var btn = [
            'vscreen',
            'folder',
            'con',
            'html'
        ];

        Ext.each(btn, function(item) {

            var cmp = Ext.getCmp(this.id + '_toolbar_' + item);

            if (o[item]) {
                cmp.enable();
            } else {
                cmp.disable();
            }

        }, this);

    }

    // }}}

});

// }}}
