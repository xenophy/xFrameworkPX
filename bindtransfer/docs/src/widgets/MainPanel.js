/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Docs.MainPanel Class File
 *
 * JavaScript
 *
 * @category   xFrameworkPX 3.5 Samples
 * @package    Docs
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: MainPanel.js 1365 2010-01-18 10:31:42Z kotsutsumi $
 */

// {{{ Docs.MainPanel

/**
 * Docs.MainPanel Class
 *
 * @category   xFrameworkPX 3.5 Samples
 * @package    Docs
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 1.0
 */
Docs.MainPanel = Ext.extend(Ext.Panel, {

    // {{{ initComponent

    /**
     * コンポーネント初期化メソッド
     *
     * @return void
     */
    initComponent : function()
    {
        // 初期設定
        Ext.applyIf(this, {id: Ext.id()});

        var startPage = 'welcome.html';
        var direct = false;
        var page = window.location.href.split('?')[1];
        if(page){
            var ps = Ext.urlDecode(page);
            var node = ps['node'];
            direct = true;
            startPage = 'wiki.html?id=' + node;
        }

        // 設定適用
        Ext.apply(this, {

            // レイアウト設定
            layout: 'card',

            // アクティブアイテム設定
            activeItem: 0,

            // ボディースタイル設定
            bodyStyle: 'background-color:transparent;',

            // アイテム設定
            items: [{

                // ID設定
                id: this.id + '_content',

                // ボーダー設定
                border: false,

                // CSSクラス設定
                cls: 'content',

                // 自動読み込み設定
                autoLoad: startPage,
                
                // 自動スクロール設定
                autoScroll: true
            }],

            // ボトムツールバー設定
            bbar: [{
                id: 'directBtn',
                iconCls: 'x-icon-direct',
                text: 'ダイレクトリンク',
                handler: function() {
                    window.open('index.html?node=' + this.contentNode);
                },
                scope: this,
                disabled: !direct
            },'->', {
                iconCls: 'x-icon-prev',
//                disabled: true,
                id: 'prevBtn',
                handler: this.navigate.createDelegate(this, [-1])
            },{
                iconCls: 'x-icon-next',
//                disabled: true,
                id: 'nextBtn',
                handler: this.navigate.createDelegate(this, [1])
            }]
        });

        // スーパークラスメソッドコール
        Docs.MainPanel.superclass.initComponent.call(this);
    },

    // }}}
    // {{{ initEvents

    /**
     * イベント初期化メソッド
     */
    initEvents : function() {
        Docs.MainPanel.superclass.initEvents.call(this);
        this.body.on('click', this.onClick, this);
    },

    // }}}
    // {{{ onClick

    /**
     * クリックイベントハンドラ
     */
    onClick : function(e, t) {

        if(e.getTarget('a.external')) {
            e.stopEvent();
            window.open(e.getTarget('a.external').getAttribute('href'));
        } else if(e.getTarget('a.internal')) {
            e.stopEvent();
            var href = e.getTarget('a.internal').getAttribute('href');
            href = href.substr(1);
            var moduleSm = Ext.getCmp('Docs-tree').getSelectionModel();
            var node = Ext.getCmp('Docs-tree').getNodeById(href);
            moduleSm.select(node);
            this.switchPanel(node);
        }

    },

    // }}}
    // {{{ switchPanel

    /**
     * switchPanel
     *
     * @param node ノードオブジェクト
     * @param e イベントオブジェクト
     * @return void
     */
    switchPanel : function(node, e) {

        if (node.isLeaf()) {
            if (e) {
                // イベント停止
                e.stopEvent();
            }
            this.contentUrl = 'wiki.html?id=' + node.id;
            this.contentNode = node.id;
            Ext.getCmp(this.id + '_content').load({
                url: this.contentUrl
            });
            Ext.getCmp('directBtn').enable();

        } else if(node.id === 'root') {
            this.contentUrl = 'welcome.html';
            Ext.getCmp(this.id + '_content').load({
                url: this.contentUrl
            });
            Ext.getCmp('directBtn').disable();
        }
    },

    // }}}
    // {{{ navigate

    /**
     * ナビゲーションメソッド
     *
     */
    navigate : function(delta) {
        var moduleSm = Ext.getCmp('Docs-tree').getSelectionModel();
        if (delta > 0) {
            var node = moduleSm.selectNext();
            if (!node) {
                Ext.getCmp('Docs-tree').root.select();
                node = moduleSm.selectNext();
            }
            if (!node.isExpanded()) {
                node.expand();
            }
            this.switchPanel(node);
        }else{
            var node = moduleSm.selectPrevious();
            if (!node) {
                Ext.getCmp('Docs-tree').root.select();
                node = moduleSm.selectNext();
            }
            if (!node.isExpanded()) {
                node.expand();
            }
            this.switchPanel(node);
        }
    }

    // }}}

});

// }}}
// {{{ register xtype

Ext.reg('Docs.main', Docs.MainPanel);

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
