(function ($) {

    "use strict";

    $.fn.iframeTabs = function (options) {
        this.tab = '<li class="tab-menus"><a href="javascript:void(0);" data-toggle="tab"> <span class="title hidden-xs">tabName</span> <span class="close" href="#">×</span> </a> </li>';
        this.data = {
            pageStack: {
                "/report": "数据报告"
            }
        };

        if(typeof options.iframes == 'object') {
            this.iframes = options.iframes;
        }else {
            this.iframes = $('#' + options.iframes);
        }


        this.init = function () {
            const iframe = this.iframes.find("iframe");
            $(document).ready(function () {
                $(window.parent.document).resize(function () {
                    const height = $(window.parent.document).height() - 91;
                    iframe.height(height);
                });
                $(window.parent.document).resize();
            });
        };

        this.linkHookFromTabs = function () {
            const that = this;
            $(document).find("a[iframe]").on('click', function () {
                const href = $(this).attr("href");
                that.openTabPage($(this).text(), href);
                return false;
            })
        };

        this.openTabPage = function (title, href) {
            title = title.trim();

            if (this.data.pageStack.hasOwnProperty(href)) {
                return;
            }
            const that = this;
            // 入
            this.data.pageStack[href] = title;
            // UI
            const tab = $(this.tab);
            var hashName = this.hashStr(href);
            tab.find("a").attr("href", "#" + hashName);
            tab.data("url", href);
            tab.find(".title").text(title);
            // 鼠标右键
            tab.on('contextmenu', function () {

            });
            // 关闭
            tab.find(".close").on("click", function () {
                const url = $(this).parent().parent().data("url");
                that.deletePageStack(url, hashName);
                $(this).parent().parent().prev().find('a').trigger('click');
                $(this).parent().parent().remove();
            });
            this.append(tab);
            // ifarme 载入页面
            this.iframes.append($('<div class="tab-pane" id="' + hashName + '"> <iframe width="100%" height="100%" frameborder="0" scrolling="y" src="' + href + '"></iframe> </div>'));
            this.init();
            tab.find("a").trigger("click");
        };

        this.deletePageStack = function (url, hashName) {
            for (const item in this.data.pageStack) {
                if (url === item) {
                    delete this.data.pageStack[item];
                    break;
                }
            }
            this.iframes.find("#" + hashName).remove();
        };

        this.hashStr = function (input, caseSensitive) {
            if(!caseSensitive){
                var str = input.toLowerCase();
            }
            var hash  =   1315423911,i,ch;
            for (i = str.length - 1; i >= 0; i--) {
                ch = str.charCodeAt(i);
                hash ^= ((hash << 5) + ch + (hash >> 2));
            }

            return  (hash & 0x7FFFFFFF);
        };

        this.init();
        this.linkHookFromTabs();

    }
})(jQuery);
