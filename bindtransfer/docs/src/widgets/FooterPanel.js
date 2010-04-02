/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Docs.FooterPanel Class File
 *
 * JavaScript
 *
 * @category   xFrameworkPX 3.5 Samples
 * @package    Docs
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: FooterPanel.js 981 2009-12-26 10:41:03Z kotsutsumi $
 */

// {{{ Docs.FooterPanel

/**
 * Docs.FooterPanel Class
 *
 * @category   xFrameworkPX 3.5 Samples
 * @package    Docs
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 1.0
 */
Docs.FooterPanel = Ext.extend(Ext.Panel, {

    // {{{ initComponent

    /**
     * コンポーネント初期化メソッド
     */
    initComponent : function()
    {
        // 初期設定
        Ext.applyIf(this, {id: Ext.id()});

        // 設定適用
        Ext.apply(this, {

            // HTML設定
            html: {

                // タグ設定
                tag: 'address',

                // HTML設定
                html: 'Copyright &copy; 2006-2010 Xenophy.CO.,LTD All rights Reserved.'
            }
        });

        // スーパークラスメソッドコール
        Docs.FooterPanel.superclass.initComponent.call( this );
    }

    // }}}

});

// }}}
// {{{ register xtype

Ext.reg('Docs.footer', Docs.FooterPanel);

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
