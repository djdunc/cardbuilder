/*
 * From Ajaxupload for Jeditable
 *
 * Copyright (c) 2008-2009 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Depends on Ajax fileupload jQuery plugin by PHPLetter guys:
 *   http://www.phpletter.com/Our-Projects/AjaxFileUpload/
 *
 * Project home:
 *   http://www.appelsiini.net/projects/jeditable
 *
 * Revision: 1
 *
 */

$.editable.addInputType('ajaxupload', {
    /* create input element */
    element: function(settings) {
        settings.onblur = 'ignore';
        var input = $('<input type="file" id="upload" name="upload" />');
        $(this).append(input);
        return (input);
    },
    content: function(string, settings, original) {
        /* do nothing */
    },
    plugin: function(settings, original) {
        var form = this;
        form.attr("enctype", "multipart/form-data");
        $("button.submit", form).bind('click',
        function() {
            //$(".message").show();
            // Modification to include original id and submitdata in target's querystring
            var queryString;
            if ($.isFunction(settings.submitdata)) {
                queryString = jQuery.param(settings.submitdata.apply(self, [self.revert, settings]));
            } else {
                queryString = jQuery.param(settings.submitdata);
            }
            if (settings.target.indexOf('?') < 0) {
                queryString = '?' + settings.id + '=' + $(original).attr('id') + '&' + queryString;
            } else {
                queryString = '&' + settings.id + '=' + $(original).attr('id') + '&' + queryString;
            }
            settings.target += queryString;
            // End modification
           var callback = settings.callback || function() { };
            $.ajaxFileUpload({
                url: settings.target,
                secureuri: false,
                fileElementId: 'upload',
                dataType: 'html',
                success: function(data, status) {
                    if (data.substring(0, 4) == 'http') {
                        callback("true");
                        $(original).html('<span>Wrong image? Click here to change it.</span>');
                        var img_path = '#ebebeb url(' + data + ') no-repeat center center';
                        $(original).css("background", img_path);
                    } else {
                        callback("false");
                        $(original).html('<span>' + data + '</span>');
                    }
                    original.editing = false;
                },
                error: function(data, status, e) {
                    alert(e);
                }
            });
            /* Show the saving indicator. */
            $(original).html(settings.indicator);
            return (false);
        });
    }
});
