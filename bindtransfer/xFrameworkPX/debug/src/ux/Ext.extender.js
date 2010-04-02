/*!
 * xFrameworkPX Debug Tools
 * Copyright (c) 2006-2009 Xenophy.CO.,LTD All rights Reserved.
 * info@xenophy.com
 * http://www.xenophy.com/
 */

// {{{ Ext Extender

Ext.apply(Ext,{

    // {{{ maxZindex

    /**
     * DOMツリー内のzindex最大値を取得します。
     *
     * @return zindex最大値
     */
    maxZindex : function() {

        var ret = 0;
        var els = Ext.select('*');

        els.each(function(el){

            var zIndex = el.getStyle('z-index');
            if(Ext.isNumber(parseInt(zIndex)) && ret < zIndex) {
                ret = zIndex;
            }

        }, this);

        return ret;
    },

    // }}}
    // {{{ getScrollPos

    getScrollPos: function() {

        var y = (document.documentElement.scrollTop > 0)
            ? document.documentElement.scrollTop
            : document.body.scrollTop;
        var x = (document.documentElement.scrollLeft > 0)
            ? document.documentElement.scrollLeft
            : document.body.scrollLeft;

        return {
            x: x,
            y: y
        };

    }

    // }}}

});

// }}}
// {{{ String

String.prototype.endsWith = function(suffix) {
  var sub = this.length - suffix.length;
  return (sub >= 0) && (this.lastIndexOf(suffix) === sub);
};

// }}}
