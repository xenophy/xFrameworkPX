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
