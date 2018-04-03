(function ($) {
    $.fn.screenshotPaste=function(options){
        var me = this;

        if(typeof options ==='string'){
            var method = $.fn.screenshotPaste.methods[options];

            if (method) {
                return method();
            } else {
                return;
            }
        }

        var defaults = {
            imgContainer: '#imgPreview',  //预览图片的容器
            imgHeight:200    //预览图片的默认高度
        };

        options = $.extend(defaults,options);

        var imgReader = function( item ){
            var file = item.getAsFile();
            var reader = new FileReader();

            reader.readAsDataURL( file );
            reader.onload = function( e ){
                var img = new Image();

                img.src = e.target.result;

                $(img).css({ height: options.imgHeight });
                insertHtmlAtCaret(img);
                //$(document).find(options.imgContainer).show().append(img);
            };
        };
        //事件注册
        $(me).on('paste',function(e){
            var clipboardData = e.originalEvent.clipboardData;
            var items, item, types;

            if( clipboardData ){
                items = clipboardData.items;

                if( !items ){
                    return;
                }

                item = items[0];
                types = clipboardData.types || [];

                for(var i=0 ; i < types.length; i++ ){
                    if( types[i] === 'Files' ){
                        item = items[i];
                        break;
                    }
                }

                if( item && item.kind === 'file' && item.type.match(/^image\//i) ){
                    imgReader( item );
                }
            }
        });

        $.fn.screenshotPaste.methods = {
            getImgData: function () {
                var src = $(document).find(options.imgContainer).find('img').attr('src');

                if(src===undefined){
                    src='';
                }

                return src;
            }
        };
    };
})(jQuery);

//聊天内容框 插入文本或者其他元素后，移动置光标到最新处
function insertHtmlAtCaret(childElement) {
    var sel, range;
    if (window.getSelection) {
        // IE9 and non-IE
        sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            range = sel.getRangeAt(0);
            range.deleteContents();

            var el = document.createElement("div");
            el.appendChild(childElement);
            var frag = document.createDocumentFragment(), node, lastNode;
            while ((node = el.firstChild)) {
                lastNode = frag.appendChild(node);
            }

            range.insertNode(frag);
            if (lastNode) {
                range = range.cloneRange();
                range.setStartAfter(lastNode);
                range.collapse(true);
                sel.removeAllRanges();
                sel.addRange(range);
            }
        }
    }
    else if (document.selection && document.selection.type != "Control") {
        // IE < 9
        //document.selection.createRange().pasteHTML(html);
    }
}