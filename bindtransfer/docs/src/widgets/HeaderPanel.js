/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Docs.HeaderPanel Class File
 *
 * JavaScript
 *
 * @category   xFrameworkPX 3.5 Samples
 * @package    Docs
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: HeaderPanel.js 1200 2010-01-06 18:42:26Z  $
 */

// {{{ Docs.HeaderPanel

/**
 * Docs.HeaderPanel Class
 *
 * @category   xFrameworkPX 3.5 Samples
 * @package    Docs
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 1.0
 */
Docs.HeaderPanel = Ext.extend(Ext.Panel, {

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

            // HTML設定
            html: {

                // ID設定
                id: 'docs-header-el',

                // 子要素設定
                cn: [{

                    // タグ設定
                    tag: 'a',

                    // HREF設定
                    href: Ext.app.RELATIVE_PATH + 'docs/',

                    // HTML設定
                    html: 'xFrameworkPX 3.5 Documentation',
                }]
            }
        });

        // スーパークラスメソッドコール
        Docs.HeaderPanel.superclass.initComponent.call(this);
    }

    // }}}

});

// }}}
// {{{ register xtype

Ext.reg('Docs.header', Docs.HeaderPanel);

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
