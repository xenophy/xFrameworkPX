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
