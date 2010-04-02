/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Docs.tree.TreePanel Class File
 *
 * JavaScript
 *
 * @category   xFrameworkPX 3.5 Samples
 * @package    Docs
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: TreePanel.js 986 2009-12-26 13:27:45Z kotsutsumi $
 */

// {{{ Docs.tree.TreePanel

/**
 * Docs.tree.TreePanel Class
 *
 * @category   xFrameworkPX 3.5 Samples
 * @package    Docs
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 1.0
 */
Docs.tree.TreePanel = Ext.extend(Ext.tree.TreePanel, {

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

        // 設定適用
        Ext.apply(this, {

            // 自動スクロール設定
            autoScroll: true,

            // ライン表示設定
            lines: false,

            // アニメーション設定
            animate: false,

            // ルートノード設定
            root: {

                // ID設定
                id: 'root',

                // 展開設定
                expanded: true,

                // 編集設定
                editable: false,

                // テキスト設定
                text: 'xFrameworkPX Documentation'
            },

            // ローダー設定
            loader: new Ext.tree.TreeLoader({
                directFn: Docs_tree.getNodes
            }),

            // ボトムツールバー設定
            bbar: [{
                tooltip: 'すべて展開する',
                iconCls: 'x-icon-expand-all',
                handler: function() {
                    this.expandAll();
                },
                scope: this
            },'-',{
                tooltip: 'すべて折り畳む',
                iconCls: 'x-icon-collapse-all',
                handler: function() {
                    this.collapseAll();
                    this.root.expand();
                },
                scope: this
            }]

        });

        // スーパークラスメソッドコール
        Docs.tree.TreePanel.superclass.initComponent.call(this);
    }

    // }}}

});

// }}}
// {{{ register xtype

Ext.reg('Docs.tree', Docs.tree.TreePanel);

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
