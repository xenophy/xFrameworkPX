/*!
 * xFrameworkPX Debug Tools
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ Ext.Phantom

Ext.Phantom = function(){

    var link = {};
    var appendedCss = [];

    return {

        // {{{ archive

        archive : function(id, ignoreCss, appendCss) {

            var id = id || Ext.id();
            var dh = Ext.DomHelper;

            if (!Ext.isArray) {
                ignoreCss = [];
            }

            // 既にアーカイブタグが存在する場合は削除
            if (Ext.fly(id) !== null) {
                Ext.fly(id).remove();
            }

            // LINK情報初期化
            link[id] = [];

            // アーカイブタグ生成
            var base = dh.append(Ext.getBody(), {
                id: id,
                style: [
                    'position: absolute',
                    'top: -10000px',
                    'left: -10000px',
                    'display: none'
                ].join('; ')
            });

            // Bodyタグ内のエレメントを待避
            if(!Ext.isIE6) {
                Ext.each(document.body.childNodes, function(el){

                    if(el){
                        var el = Ext.get(el);
                        if(el && el.dom.id !== id) {
                            base.appendChild(el.dom);
                        }
                    }

                }, this);
            } else {

                var b = Ext.getBody().dom;

                Ext.select('*', true, document).each(function(el){
                    if(el.dom.parentNode.id === b.id && base.id != el.id ) {
                        base.appendChild(el.dom);
                    }
                }, this);

            }

            // CSSファイル無効化
            Ext.select('link', true).each(function(item){

                var dom = item.dom;
                if (dom.href.toLowerCase().endsWith('.css')) {

                    Ext.each(ignoreCss, function(ignore){

                        if(!dom.href.endsWith(ignore)) {
                            link[id].push({
                                id: dom.id,
                                href: dom.href
                            });
                            dom.parentNode.removeChild(dom);
                        }

                    }, this);
                }
            }, this);

            // CSS追加
            Ext.each(appendCss, function(o){
                Ext.util.CSS.swapStyleSheet(o.id, o.href);
                appendedCss.push(o);
            }, this);

            return id;
        },

        // }}}
        // {{{ expand

        expand : function(id) {

            var archive = Ext.get(id);

            // アーカイブタグ内のエレメントを待避
            if(!Ext.isIE6) {

                var b = Ext.getBody().dom;

                Ext.select('*', true, archive.dom).each(function(el){

                    if(el.dom.parentNode.id === archive.dom.id) {
                        document.body.appendChild(el.dom);
                    }
                }, this);

            } else {

                var b = Ext.getBody().dom;

                Ext.select('*', true, archive.dom).each(function(el){
                    if(el.dom.parentNode.id === archive.dom.id) {
                        document.body.appendChild(el.dom);
                    }
                }, this);
            }

            Ext.each(link[id],function(o){

                Ext.util.CSS.swapStyleSheet(o.id, o.href);

            }, this);

            // 追加CSS削除
            Ext.each(appendedCss, function(o){
                Ext.util.CSS.removeStyleSheet(o.id);
            }, this);
        }

        // }}}

    };

}();

// }}}
