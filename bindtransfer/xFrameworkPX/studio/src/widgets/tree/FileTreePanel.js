/*!
 * xFrameworkPX Studio
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ PXSTUDIO.tree.FileTreePanel

PXSTUDIO.tree.FileTreePanel = Ext.extend(Ext.tree.TreePanel, {

    // {{{ initComponent

    initComponent: function() {

        // 初期設定
        Ext.applyIf(this, {id: Ext.id()});

        Ext.apply(this, {
            id: 'PXSTUDIO_TREE_FILE',
            iconCls: 'pxstudio-navi-file',
            title: 'ファイル',
            autoScroll: true,
            useArrows: true,
            root: {
                id: 'root',
                expanded: true,
                text: '開発ルート'
            },
            loader: new Ext.tree.TreeLoader({
                directFn: xFrameworkPX_Studio.getVirtualScreen
            })
        });

        // スーパークラスメソッドコール
        PXSTUDIO.tree.FileTreePanel.superclass.initComponent.call(this);
    },

    // }}}
    // {{{ initEvents

    initEvents: function() {

        // スーパークラスメソッドコール
        PXSTUDIO.tree.FileTreePanel.superclass.initEvents.call(this);
    }

    // }}}

});

// {{{ xtype register

Ext.reg('pxstudio-navigation-file', PXSTUDIO.tree.FileTreePanel);

// }}}
