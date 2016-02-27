/**
 * Created by behruz on 2/27/16.
 */

$(function () {

    var message_block = $('#message_block'),
        submit_button = $('#submit_button');

    /**
     *
     */
    submit_button.on('click', function (e) {
        e.preventDefault();
        if (!validate_form()) {
            return false;
        }
        disable_form_fields(true);
        validate_url_accessibility(create_short_url);
    });

    /**
     * Create short URL
     */
    var create_short_url = function () {
        submit_button.html('Create short URL processing...');
        var request = $.ajax({
            url: base_url + 'welcome/ajax_create_short_url',
            type: 'POST',
            dataType: 'json',
            data: {
                url: $('#url_long').val(),
                desired_url: $('#url_short').val()
            }
        });
        request.done(function (data) {
            if (data) {
                if (data.success) {
                    $('#url_short').val(data.short_url);
                    showMessage('Short URL was successfully created!', 'success', 'Success!');
                } else {
                    showMessage(data.message);
                }
            }
        });
        request.fail(function (xhr, text_status) {
            showMessage('Request failed: ' + text_status);
        });
        request.always(function () {
            submit_button.html('Submit');
            disable_form_fields(false);
        });
    };

    /**
     * Validate URL accessibility
     */
    var validate_url_accessibility = function () {
        submit_button.html('Validating URL accessibility...');
        var request = $.ajax({
            url: base_url + 'welcome/ajax_validate_long_url',
            type: 'GET',
            dataType: 'json',
            data: {
                url: $('#url_long').val()
            }
        });
        request.done(function (data) {
            if (data) {
                if (data.success) {
                    create_short_url();
                } else {
                    showMessage(data.message);
                    disable_form_fields(false);
                }
            }
        });
        request.fail(function (xhr, text_status) {
            showMessage('Request failed: ' + text_status);
            disable_form_fields(false);
        });
        request.always(function () {
            submit_button.html('Submit');
        });
    };

    /**
     * Disable/Enable form fields
     * @param flag
     */
    var disable_form_fields = function (flag) {
        flag = !!flag;
        $('#url_long').attr('disabled', flag);
        submit_button.attr('disabled', flag);
    };

    /**
     * Validate form fields before submit to the server
     */
    var validate_form = function () {
        if (!$('#url_long').val()) {
            $('#url_long').parents().addClass('has-error').removeClass('has-success');
            showMessage('Please fill long URL field');
            return false;
        }
        if ($('#url_short').val()) {
            var url_short = $('#url_short').val();
            var myRegEx = /^([a-zA-Z0-9 _-]+)$/;
            if (!(myRegEx.test(url_short))) {
                showMessage('Only alphanumeric characters are available for short URL field');
                return false;
            }
        }
        return true;
    };

    /**
     * Show message to user
     * @param message
     * @param type
     * @param title
     * @param block
     */
    var showMessage = function (message, type, title, block) {
        title = title || '';
        block = block || message_block;
        type = type || 'danger';
        var button = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        block.empty();
        block.append('<div class="alert alert-' + type + '">' + button + ((title != '') ? '<strong>' + title + '</strong> ' : '') +
            message + '</div>');
    }

});