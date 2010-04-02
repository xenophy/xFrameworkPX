/*!
 * xFrameworkPX Studio
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ PXSTUDIO.tree.VirtualScreenTreePanel

PXSTUDIO.tree.VirtualScreenTreePanel = Ext.extend(Ext.tree.TreePanel, {

    // {{{ initComponent

    initComponent: function() {

        // 初期設定
        Ext.applyIf(this, {id: Ext.id()});

        Ext.apply(this, {
            id: 'PXSTUDIO_TREE_VSCREEN',
            iconCls: 'pxstudio-navi-vscreen',
            title: '仮想スクリーン',
            autoScroll: true,
            useArrows: true,
            root: {
                id: 'root',
                expanded: true,
                text: 'Webルート'
            },
            loader: new Ext.tree.TreeLoader({
                directFn: xFrameworkPX_Studio.getVirtualScreen
            })
        });

        // イベントリスナー追加
        this.on('contextmenu', this.onContextMenu, this);

        // スーパークラスメソッドコール
        PXSTUDIO.tree.VirtualScreenTreePanel.superclass.initComponent.call(this);
    },

    // }}}
    // {{{ initEvents

    initEvents: function() {

        // スーパークラスメソッドコール
        PXSTUDIO.tree.VirtualScreenTreePanel.superclass.initEvents.call(this);
    },

    // }}}
    // {{{ onContextMenu

    onContextMenu: function(node, e) {

        if(!this.menu){
            this.menu = new Ext.menu.Menu([{
                id: this.id + 'ctxEdit',
                iconCls: 'pxstudio-navi-vscreen-edit',
                text: '編集'
            },'-',{
                id: this.id + 'ctxOpen',
                text: '開く',
                handler: function() {

                    xFrameworkPX_Studio.getOpenUrl(node.id, function(url){
                        location.href = url;
                    }, this);

                },
                scope: this
            },{
                id: this.id + 'ctxNewOpen',
                text: '新しいウィンドウで開く',
                handler: function() {

                    xFrameworkPX_Studio.getOpenUrl(node.id, function(url){
                        window.open(url, "");
                    }, this);

                },
                scope: this
            },'-',{
                id: this.id + 'ctxAddVScreen',
                iconCls: 'pxstudio-navi-vscreen-add',
                text: '仮想スクリーン追加'
            },{
                id: this.id + 'ctxAddFolder',
                iconCls: 'pxstudio-navi-file',
                text: 'フォルダー追加'
            },'-',{
                id: this.id + 'ctxCut',
                iconCls: 'pxstudio-navi-vscreen-cut',
                text: '切り取り'
            },{
                id: this.id + 'ctxCopy',
                iconCls: 'pxstudio-navi-vscreen-copy',
                text: 'コピー'
            },{
                id: this.id + 'ctxPaste',
                disabled: true,
                iconCls: 'pxstudio-navi-vscreen-paste',
                text: '貼り付け'
            },'-',{
                id: this.id + 'ctxRemove',
                iconCls: 'pxstudio-navi-vscreen-remove',
                text: '削除'
            },{
                id: this.id + 'ctxRename',
                iconCls: 'pxstudio-navi-vscreen-rename',
                text: '名前の変更'
            }]);
        }

        // イベント停止
        e.stopEvent();

        // コンテキストメニュー表示
        this.menu.showAt(e.getPoint());
    },
    
    // }}}
    // {{{ createVScreen

    createVScreen: function() {

        // セレクションモデル取得
        var sm = this.getSelectionModel();

        // 選択ノード取得
        var node = sm.getSelectedNode();

        if (node === null) {
            return;
        }

        // 選択ノードの保存
        var selectedNode = node;

        // 選択ノードがリーフの場合は、親ノードに切り替え
        if (node.isLeaf()) {
            node = node.parentNode;
        }

        // ノード無効化
        node.cascade(function(node){
            node.disable();
        }, this);

        // ウィンドウ生成
        var win = new PXSTUDIO.VirtualScreenWindow();
        win.on('close', function() {

            // ノードテキスト変更
            var nodeText = node.text;

            if (win.exec) {

                // ローディングアイコン設定
                Ext.get(node.ui.elNode).addClass('x-tree-node-loading');

                // ノードテキスト変更
                node.setText('仮想スクリーン追加中...');

                // パス設定
                win.createData.path = (node.id === 'root') ? '' : node.id;

                // 追加処理開始
                xFrameworkPX_Studio.createVScreen(win.createData, function(){

                    // ノードテキスト設定
                    node.setText(nodeText);

                    // リロード
                    node.reload();

                    // ノード有効化
                    node.cascade(function(node){
                        node.enable();
                    }, this);
                }, this);
            } else {

                // ローディングアイコン解除
                Ext.get(node.ui.elNode).removeClass('x-tree-node-loading');

                // ノード有効化
                node.cascade(function(node){
                    node.enable();
                }, this);

                // ノード選択
                selectedNode.select();
            }


        }, this);

        // ウィンドウ表示
        win.show();

    }

    // }}}

});

// {{{ xtype register

Ext.reg('pxstudio-navigation-vscreen', PXSTUDIO.tree.VirtualScreenTreePanel);

// }}}
