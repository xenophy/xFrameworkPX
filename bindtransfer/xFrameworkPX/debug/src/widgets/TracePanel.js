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
