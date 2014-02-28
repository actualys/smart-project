(function ($) {
    $.fn.ajaxForm = function (options) {
        options = $.extend({}, $.fn.ajaxForm.defaults, options);

        // A simple helper function
        function callHandler(name, form, args) {
            if(typeof options[name] === "function") {
                options[name].apply(form, [].slice.call(args, 0));
            }
        }

        return this.each(function () {
            var settings,
                form = $(this);

            settings = $.extend({}, {
                action:   form.prop("action"),
                method:   form.prop("method"),
                accepts:  form.attr("accept"),
                dataType: form.data("dataType")
            }, options);

            settings.beforeSend = function () {
                form.addClass("ajaxForm-loading");
                callHandler("beforeSend", form, arguments);
            };

            settings.complete = function () {
                form.removeClass("ajaxForm-loading");
                callHandler("complete", form, arguments);
            };

            settings.success = function () {
                callHandler("success", form, arguments);
            };

            settings.error = function () {
                callHandler("error", form, arguments);
            };

            $(this).on("submit", function (event) {
                event.preventDefault();
                settings.data = form.serializeArray();
                $.ajax(settings.action, settings);
            });
        });
    };

    $.fn.ajaxForm.defaults = {};

})(jQuery);