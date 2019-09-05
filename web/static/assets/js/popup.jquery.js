(function ($) {

    "use strict";

    window.__popupCallback__ = null;

    $.fn.popupPage = function (options) {

        this.init = function () {
            const that = this;
            $(document).find("*[popup]").on('click', function () {
                const title = $(this).data("title") || $(this).text();
                const width = $(this).data("width") || "40%";
                const height = $(this).data("height") || "260px";
                const url = $(this).attr("href");
                that.openModalPage(title, url, width, height);
                return false;
            })
        };

        /**
         * 弹出模态对话框
         */
        this.openModalPage = function (title, url, width, height) {
            layer.open({
                type: 2,
                title: title || '&nbsp;',
                shadeClose: true,
                maxmin: true,
                area: ['693px', '600px'],
                offset: 'top',
                content: url,
                end: function () {
                    if(typeof window.__popupCallback__ === "function") {
                        window.__popupCallback__();
                    }
                }
            });
        };

        this.init();
    }

})(jQuery);

$(document).popupPage();