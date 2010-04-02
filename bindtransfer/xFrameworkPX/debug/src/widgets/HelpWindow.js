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
