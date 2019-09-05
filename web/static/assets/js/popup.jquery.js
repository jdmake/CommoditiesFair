(function ($) {

    "use strict";

    window.__popupCallback__ = null;

    $.fn.popupPage = function (options) {

        this.init = function () {
            const that = this;
            $(document).find("*[popup]").on('click', function () {
                const title = $(this).data("title");
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
            $('#popup').remove();
            var modal = $('<div style="display: block" class="modal fade  in" id="modal-2" aria-hidden="false"><div class="modal-dialog" style="width: 80%;height: 80%;"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title"></h4></div><div class="modal-body"><iframe style="overflow-x: hidden" width="100%" height="100%" frameborder="0" scrolling="y" src=""></iframe></div></div></div></div> ');
            modal.find(".modal-body iframe").attr("src", url);
            modal.find(".modal-title").text(title);

            modal.find('.modal-dialog').width('40%');
            modal.find(".modal-body iframe").height(document.body.clientHeight - 250);

            this.find('body').append(modal);
            this.find('body').append($('<div class="modal-backdrop fade in"></div>'));

            $('.modal-backdrop').on('click', function() { modal.remove(); $(this).remove() })
            modal.find(".close").on('click', function () {
                modal.remove();
                $('.modal-backdrop').remove()
                if(typeof window.__popupCallback__ === "function") {
                    window.__popupCallback__();
                }
            });

        };

        this.init();
    }

})(jQuery);

$(document).popupPage();