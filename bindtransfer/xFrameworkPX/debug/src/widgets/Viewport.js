/*!
 * xFrameworkPX Debug Tools
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ PXDEBUG.Viewport

PXDEBUG.Viewport = Ext.extend(Ext.Viewport, {

    // {{{ initComponent

    initComponent: function() {

        Ext.apply(this, {
            id: Ext.id()
        });

        Ext.apply(this, {
            layout: 'fit',
            items: [{
                title: 'xFrameworkPX Debug Tools',
                iconCls: 'pxdebug-icon-logo',
                layout: 'border',
                tools: [{
                    id: 'gear',
                    handler: function() {
                        var w = new PXDEBUG.CacheClearWindow();
                        w.show();
                    },
                    scope: this
                },{
                    id: 'help',
                    handler: function() {
                        var w = new PXDEBUG.HelpWindow();
                        w.show();
                    },
                    scope: this
                },{
                    id: 'close',
                    handler: function() {
                        this.fireEvent('hidecontainer');
                    },
                    scope: this
                }],
                border: false,
                items: [{
                    xtype: 'tabpanel',
                    id: this.id + '_tab',
                    deferredRender: false,
                    activeItem: 0,
                    border: false,
                    listeners: {
                        'tabchange': {
                            fn: function(tab, np, cp) {
                                var grid = Ext.getCmp(np.id + '_grid');
                                var sm = grid.getSelectionModel();

                                if (grid.store.getCount() > 0 ) {
                                    (function(){
                                        grid.getView().focusEl.focus();
                                        sm.selectFirstRow();
                                    }).defer(100, this);
                                }

                                Ext.iterate(tab.items.items, function(item, n){
                                    if(item == np) {
                                        Ext.getCmp(this.id + '_tab').activeTabPos = n;
                                    }
                                }, this);
                            },
                            scope: this
                        },
                        'afterrender': {
                            fn: function() {
                                Ext.getCmp(this.id + '_tab').activeTabPos = 0;
                            },
                            scope: this
                        }
                    },
                    region: 'center',
                    items: [{
                        xtype: 'pxdebug-trace',
                        id: this.id + '_trace'
                    },{
                        xtype: 'pxdebug-parameter',
                        id: this.id + '_parameter'
                    },{
                        xtype: 'pxdebug-session',
                        id: this.id + '_session'
                    },{
                        xtype: 'pxdebug-cookie',
                        id: this.id + '_cookie'
                    },{
                        xtype: 'pxdebug-userdata',
                        id: this.id + '_userdata'
                    },{
                        xtype: 'pxdebug-query',
                        id: this.id + '_query'
                    },{
                        xtype: 'pxdebug-profiler',
                        id: this.id + '_profiler'
                    }]
                }]
            }]
        });

        // スーパークラスメソッドコール
        PXDEBUG.Viewport.superclass.initComponent.call(this);
    },

    // }}}
    // {{{ getTab

    getTab : function() {
        return Ext.getCmp(this.id + '_tab');
    },

    // }}}
    // {{{ setActiveTab

    setActiveTab : function(name) {

        switch(name) {
            case 'query':
                this.getTab().setActiveTab(5);
            break;
            case 'profile':
                this.getTab().setActiveTab(6);
            break;
            case 'session':
                this.getTab().setActiveTab(2);
            break;
            case 'cookie':
                this.getTab().setActiveTab(3);
            break;
            case 'userdata':
                this.getTab().setActiveTab(4);
            break;
            case 'parameter':
                this.getTab().setActiveTab(1);
            break;
            case 'trace':
                this.getTab().setActiveTab(0);
            break;
            case 'cache':
                var w = new PXDEBUG.CacheClearWindow();
                w.show();
            break;
            case 'left':
                var tab = this.getTab();
                var pos = --tab.activeTabPos;
                if (pos < 0) {
                    pos = tab.items.items.length - 1;
                    tab.activeTabPos = pos;
                }
                tab.setActiveTab(pos);
            break;
            case 'right':
                var tab = this.getTab();
                var pos = ++tab.activeTabPos;
                if (pos >= tab.items.items.length) {
                    pos = 0;
                    tab.activeTabPos = 0;
                }
                tab.setActiveTab(pos);
            break;
        }
    }

    // }}}

});

// }}}
