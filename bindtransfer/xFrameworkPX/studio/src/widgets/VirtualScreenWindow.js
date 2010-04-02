/*!
 * xFrameworkPX Studio
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ PXSTUDIO.VirtualScreenWindow

PXSTUDIO.VirtualScreenWindow = Ext.extend(Ext.Window, {

    // {{{ initComponent

    initComponent: function() {

        // 初期設定
        Ext.applyIf(this, {id: Ext.id()});

        Ext.apply(this, {
            title: '新規仮想スクリーン作成',
            iconCls: 'pxstudio-navi-vscreen-add',
            resizable: false,
            modal: true,
            defaultButton: this.id + '_btnOk',
            layout: 'form',
            padding: 20,
            items:[{
                id: this.id + 'txtName',
                xtype: 'textfield',
                width: 200,
                enableKeyEvents: true,
                vtype: 'alphanum',
                fieldLabel: '仮想スクリーン名',
                listeners: {
                    'keyup': {
                        fn: function(field, e) {


                            if (e.getKey() === e.ENTER && !this.nameKeyup) {
                                this.nameKeyup = true;
                                this.onOk();
                            }

                        },
                        scope: this
                    }
                }
            },{
                id: this.id + 'chkController',
                xtype: 'checkbox',
                checked: true,
                boxLabel : 'コントローラーを生成する',
                listeners: {
                    'check': {
                        fn: function(checkbox, checked) {

                            // テンプレートチェックボックス取得
                            var cmp = Ext.getCmp(this.id + 'chkTemplate');

                            if(!checked) {
                                cmp.setValue(false);
                                cmp.disable();
                            } else {
                                cmp.enable();
                            }

                        },
                        scope: this
                    }
                }
            },{
                id: this.id + 'chkTemplate',
                xtype: 'checkbox',
                boxLabel : 'テンプレートを作成しない'
            }],
            buttons: [{
                id: this.id + '_btnOk',
                handler: this.onOk,
                scope: this,
                text: 'OK'
            },{
                id: this.id + '_btnCancel',
                handler: function() {
                    this.close();
                },
                scope: this,
                text: 'キャンセル'
            }]
        });

        // イベントリスナー追加
        this.on('show', function(p){

            // フォーカス設定
            (function(){
                Ext.getCmp(this.id + 'txtName').focus();
            }).defer(100,this);

        }, this);

        // スーパークラスメソッドコール
        PXSTUDIO.VirtualScreenWindow.superclass.initComponent.call(this);
    },

    // }}}
    // {{{ initEvents

    initEvents: function() {

        PXSTUDIO.VirtualScreenWindow.superclass.initEvents.call(this);
    },

    // }}}
    // {{{ onOk

    onOk: function() {

        var name = Ext.getCmp(this.id + 'txtName').getValue();

        if (name === '') {

            alert('仮想スクリーン名を入力してください。');

            // フォーカス設定
            (function(){
                Ext.getCmp(this.id + 'txtName').focus();
                this.nameKeyup = false;
            }).defer(100,this);

        } else if(!Ext.form.VTypes.alphanum(name)) {

            alert('仮想スクリーン名をは半角英数で指定してください。');

            // フォーカス設定
            (function(){
                Ext.getCmp(this.id + 'txtName').focus();
                this.nameKeyup = false;
            }).defer(100,this);

        } else {

            this.exec = true;
            this.createData = {
                name: name,
                controller: Ext.getCmp(this.id + 'chkController').getValue(),
                template: !Ext.getCmp(this.id + 'chkTemplate').getValue()
            }

            this.close();
        }
    }

    // }}}

});

// {{{ xtype register

Ext.reg('pxstudio-vscreen-window', PXSTUDIO.VirtualScreenWindow);

// }}}
