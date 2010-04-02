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
