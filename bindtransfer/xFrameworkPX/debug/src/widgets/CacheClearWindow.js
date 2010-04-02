/*!
 * xFrameworkPX Debug Tools
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ PXDEBUG.ParameterPanel

PXDEBUG.CacheClearWindow = Ext.extend(Ext.Window, {

    // {{{ initComponent

    initComponent: function() {

        this.id = 'pxdebug_cache_clear_window';

        Ext.apply(this,{
            title: 'キャッシュの削除',
            iconCls: 'pxdebug-icon-cacheclear',
            modal: true,
            resizable: false,
            autoHeight: true,
            defaultButton: this.id + '_btnDelete',
            width: 300,
            items: [{
                id: this.id + '_tree',
                xtype: 'treepanel',
                border: false,
                padding: 10,
                useArrows:true,
                rootVisible: false,
                root: {
                    children: [{
                        id: 'ccw_schema',
                        text: "スキーマキャッシュ",
                        iconCls: 'pxdebug-icon-none',
                        checked: true,
                        leaf: true
                    },{
                        id: 'ccw_config',
                        text: "設定キャッシュ",
                        iconCls: 'x-hidden',
                        checked: true,
                        leaf: true
                    },{
                        id: 'ccw_template',
                        text: "テンプレートキャッシュ",
                        iconCls: 'x-hidden',
                        checked: true,
                        leaf: true
                    }]
                }
            
            }],
            buttons: [{
                id: this.id + '_btnDelete',
                text: '今すぐ削除',
                handler: function() {

                    var root = Ext.getCmp(this.id + '_tree').root;
                    this.disable();

                    PXDEBUG_APP.keymap(false);

                    xFrameworkPX_DebugTools.clearCaches(
                        root.findChild('id', 'ccw_schema').attributes.checked,
                        root.findChild('id', 'ccw_config').attributes.checked,
                        root.findChild('id', 'ccw_template').attributes.checked,
                        function(){
                            PXDEBUG_APP.keymap(true);
                            this.close();
                        },
                        this
                    );

                },
                scope: this
            },{
                text: 'キャンセル',
                handler: function() {
                    this.close();
                },
                scope: this
            }]
        });

        // スーパークラスメソッドコール
        PXDEBUG.CacheClearWindow.superclass.initComponent.call(this);
    }

    // }}}

});

// }}}
