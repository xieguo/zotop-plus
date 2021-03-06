/*! Global js */

// Ajax 全局
$(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        error: function(jqXHR) {
            // 422 为表单验证提示，在每次提交时候单独处理
            if (jqXHR.status != 422 ) {

                // 如果从确认弹出，自动关闭确认对话框
                if ($.dialog('confirm')) {
                    $.dialog('confirm').close().remove();
                }

                // 如果消息弹出，自动关闭消息
                if ($.dialog('message')) {
                    $.dialog('message').close().remove();
                }  

                // 弹出警告对话框
                $.alert(jqXHR.responseJSON.message || jqXHR.responseText).title(jqXHR.statusText);
            }        
        }
    });
});


$(function(){
    
    // tooltip
    $(document).tooltip({placement:function(tip, element){
        return $(element).data('placement') ? $(element).data('placement') : 'bottom';
    },selector:'[data-toggle="tooltip"],a[title]',html:true,trigger:'hover'});   
 
    // niceScroll
    $('.nice-scrollable').niceScroll();
    // 窗口改变大小时，重置滚动条，如果页面内元素高度发生改变，使用触发 $(window).trigger('resize')
    $(window).resize(function(){
        $(".nice-scrollable").getNiceScroll().resize();
        $('.tooltip').remove();
    });

    // maxlength
    $('input[maxlength],textarea[maxlength]').maxlength({alwaysShow:true,appendToParent:true,threshold:10,separator:'/',placement:'bottom-right-inside'});  

    $('textarea[maxlength]').on('autosize.resized', function() {
        $(this).trigger('maxlength.reposition');
    });

    // 复制到剪贴板
    var clipboard = new ClipboardJS('.btn-copy');
        clipboard.on('success', function(e) {
            e.clearSelection();
            $.success($(e.trigger).data('success'));
        });      
});

// Validation 扩展
$(function(){

    // 增加正则验证
    $.validator.addMethod("pattern", function(value, element, param) {
        if (this.optional(element)) {
            return true;
        }
        if (typeof param === "string") {
            param = new RegExp("^(?:" + param + ")$");
        }
        return param.test(value);
    }, $.validator.messages.pattern);

    // 使用bootstrap tooltip 作为错误提示
    $.extend(jQuery.validator.defaults, {
        ignoreTitle: true,
        showErrors: function(errorMap, errorList) {
            
            $.each(this.successList, function(index, value) {
                return $(value).removeClass('error').tooltip("dispose");
            });

            return $.each(errorList, function(index, value) {

                // 处理元素不可见的情况 2018.09.20
                if ($(value.element).is(':visible') == false) {
                    $.error(value.message);
                    return false;
                }

                $(value.element).removeClass('error').tooltip("dispose");
                
                var tooltip = $(value.element).addClass('error').tooltip({
                    trigger: "manual",
                    container: $(value.element).parents('div:first'),
                    html: true,
                    title: function(element){
                        var title='';               
                        if (typeof(value.message) == 'object') {
                            $.each(value.message,function(i,message){
                                title += '<div class="tooltip-item">'+message+'</div>';
                            });
                        } else {
                            title = value.message;
                        }
                        return title;
                    },
                    placement: function(tip, element){
                        return $(element).data('placement') ? $(element).data('placement') : 'bottom';
                    }            
                }).on('show.bs.tooltip', function () {
                    $($(this).data('bs.tooltip').getTipElement()).addClass('tooltip-error');
                });

                //tooltip.data("bs.tooltip").options.title = value.message;

                return $(value.element).tooltip("show");
            });
        }
    });


    $.validator.prototype.__resetForm = $.validator.prototype.resetForm;

    $.extend($.validator.prototype, {
        
        resetForm : function(){

            $.each(this.errorList, function (index, value) {
                $(value.element).tooltip('dispose');
            });

            this.__resetForm();
            return this;
        }
    });    
});

// 绑定默认搜索
$(function(){
    $('form.form-search').validate();
});

//dialog
$(function(){
    
    // ajax post 点击链接使用post链接，并返回提示信息
    $(document).on('click', 'a.js-post',function(event){
        event.preventDefault();

        var icon = $(this).find('.fa');
        var href = $(this).data('url') || $(this).attr('href');
        var data = $(this).data('post') || {};

        if ( icon.length > 0 ){
            icon.addClass('fa-loading');
        }else{
            $.loading();
        }       
        
        $.post(href, data, function(msg) {
            $.msg(msg);

            if (icon.length > 0) {
                icon.removeClass('fa-loading');
            }          
        },'json');

        event.stopPropagation();
    });

    $(document).on('click', 'a.js-confirm', function(event){
        event.preventDefault();

        var href    = $(this).data('url') || $(this).attr('href');
        var text    = $(this).data('title') || $(this).text() || $(this).attr('title');
        var confirm = $(this).data('confirm') || $.trans('您确定要 [ {0} ] 嘛?', text);
        var method  = $(this).data('method') || 'POST';

        var $dialog = $.confirm(confirm,function(){
            $dialog.loading(true);
            $.ajax({url:href,type:method,dataType:'json',success:function(msg){
                $dialog.close().remove();
                $.msg(msg);
            }});
            return false;
        }).title(text);

        event.stopPropagation();
    });    

    $(document).on('click', 'a.js-delete', function(event){
        event.preventDefault();

        var href    = $(this).data('url') || $(this).attr('href');
        var text    = $(this).attr('title') || $(this).data('original-title') || $(this).text();
        var confirm = $(this).data('confirm') || $.trans('您确定要 {0} 嘛?', text);
        var method  = $(this).data('method') || 'DELETE';

        var $dialog = $.confirm(confirm,function(){
            $dialog.loading(true);
            $.ajax({url:href,type:method,dataType:'json',success:function(msg){
                $dialog.close().remove();
                $.msg(msg);
            }});
            return false;
        }).title(text);

        event.stopPropagation();
    });

    $(document).on('click', 'a.js-open',function(event){
        event.preventDefault();

        var url     = $(this).data('url') || $(this).attr('href');
        var title   = $(this).attr('title') || $(this).data('original-title') || $(this).text();
        var width   = $(this).data('width') || 'auto';
        var height  = $(this).data('height') || 'auto';     
        var $dialog = $.dialog({
            title        : title,
            url          : url,
            width        : width,
            height       : height,
            ok           : $.noop,
            cancel       : $.noop,
            oniframeload : function() {
                this.loading(false);
            }
        },true).loading(true);

        event.stopPropagation();
    });

    $(document).on('click', 'a.js-prompt', function(event){
        event.preventDefault();

        var href   = $(this).data('url') || $(this).attr('href');
        var name   = $(this).data('name') || 'newvalue';
        var value  = $(this).data('value') || '';
        var prompt = $(this).data('prompt') || '';
        var type   = $(this).data('type') || 'text';
        var title  = $(this).attr('title') || $(this).data('original-title') || $(this).text();
        var posts  = {};   
        var $dialog = $.prompt(prompt, function(newvalue, input) {
            posts[name] = newvalue;
            $dialog.loading(true);
            $.post(href, posts, function(msg) {
                if( msg.state ){
                    $dialog.close().remove();
                }else{
                    $dialog.loading(false);
                }
                $.msg(msg);
            },'json').fail(function(jqXHR){
                input.select();
                input.focus();
                $dialog.loading(false);
                $.error(jqXHR.responseJSON.errors[name][0]);
            });
            return false;
        }, value, type).title(title);

        event.stopPropagation();
    });

    $(document).on('click', 'a.js-image', function(event){
        event.preventDefault();

        var url   = $(this).data('url');
        var title = $(this).data('title');
        var info  = $(this).data('info');

        var $dialog = $.image(url, title).statusbar(info);

        event.stopPropagation();
    });

    $(document).on('contextmenu', '.js-contextmenu', function(event){
        
        // contextmenu 必须在当前元素之内
        var contextmenu = $(this).data('contextmenu') || '.contextmenu';
            contextmenu = $(this).find(contextmenu);

        if (contextmenu.length && contextmenu.html()) {

            // 使用dialog函数，在当前页面打开对话框，使用 $.dialog 为顶级页面打开
            var d = dialog({
                skin       : 'ui-contextmenu',
                quickClose : true,
                content    : contextmenu.html()
            }).show(event);

            event.preventDefault();
            event.stopPropagation();

            return d.destroyed;
        }       
    });    
})


//表格行排序 sortable
$(function(){

    $("table.table-sortable").each(function(index,table){
        $(table).sortable({
            items: "tbody > tr:not(.ui-sortable-disabled)",
            handle: "td.drag",
            axis: "y",   
            placeholder:"ui-sortable-placeholder",
            helper: function(e, ui) {
                ui.children().each(function(){
                    $(this).width($(this).width());
                });
                return ui;
            },
            start: function(e, ui){
                ui.placeholder.height(ui.helper[0].scrollHeight);
            },           
            update:function() {
                // 如果是表单内拖动，自动提交表单
                var form = $(this).parents('form');
                if (form.length) {
                    $.post(form.attr('action'), form.serialize(), function(msg){
                        $.msg(msg);
                    },'json');                    
                }
            }
        });        
    }).disableSelection();

});
