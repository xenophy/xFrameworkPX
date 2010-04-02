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
