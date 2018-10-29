$(document).ready(function () {
    $('body').on('change', '.status', function () {
        var newStatus = this.value;
        var fieldId = $(this).parent().parent().data('key');
        var STATUS_REJECTED = 'rejected';
        var STATUS_PAID_BY_US_DONE = 'paid_by_us_done';
        var ACTION_ID = 'view';
        var selectId = $(this).attr('id');

        $.ajax({
            url: '/admin/admin/bid/update-bid-status',
            type: "POST",
            data: {'status': newStatus, 'id': fieldId, 'language': language},
            success: function (result) {
                var isAdmin = result.isAdmin;
                var processedStatus = result.processedStatus;
                var processedBy = result.processedBy;
                var bidStatusValue = result.bidStatusValue;
                var bidStatusText = result.bidStatusText;
                var inProgressByManager = result.inProgressByManager;
                var tableRow = $('tr[data-key="' + fieldId + '"]');
                var bidOldStatusValue = result.bidOldStatusValue;
                var bidOldStatusText = result.bidOldStatusText;
                var statusNew = 'new';

                if (document.location.pathname.indexOf(ACTION_ID) !== -1) {
                    $('#status').html(bidStatusText);
                    $('#in-progress-by-column').html(inProgressByManager);
                    $('#processed-by').html(processedBy);
                    if (!isAdmin && (bidStatusValue === STATUS_PAID_BY_US_DONE || bidStatusValue === STATUS_REJECTED)) {
                        $('.status').prop('disabled', true);
                    }
                    location.reload()
                } else {
                    tableRow.removeClass('success');
                    $('#' + selectId + ' option:selected').remove();
                    $('#' + selectId).append(
                        $('<option></option>')
                            .attr('value', bidOldStatusValue)
                            .text(bidOldStatusText)
                    );

                    if (!isAdmin) {
                        $('#' + selectId + ' option[value="' + statusNew + '"]').remove();
                    }

                    tableRow.each(function () {
                        $(this).find('.status-column').html(bidStatusText);
                        $(this).find('.processed-column').html(processedStatus);
                        $(this).find('.processed-by-column').html(processedBy);
                        $(this).find('.in-progress-by-column').html(inProgressByManager);

                        if (!isAdmin && (bidStatusValue === STATUS_PAID_BY_US_DONE || bidStatusValue === STATUS_REJECTED)) {
                            $(this).find('.status').prop('disabled', true);
                        }
                    });
                }

                $('#bid-status-success').html(
                    '<div class="alert alert-success alert-dismissible fade in" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">×</span>' +
                    '</button>'
                    + '<i class="icon fa fa-check"></i>'
                    + result.message +
                    '</div>'
                );
            },
            error: function (result) {
                $('#bid-status-error').html(
                    '<div class="alert alert-error alert-dismissible fade in" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">×</span>' +
                    '</button>'
                    + '<i class="icon fa fa-bun"></i>'
                    + result.responseJSON.message +
                    '</div>'
                );
            }
        });

    });

    $('body').on('change', '.user-status', function () {
        var newStatus = this.value;
        var fieldId = $(this).parent().parent().data('key');
        var tableRow = $('tr[data-key="' + fieldId + '"]');
        var selectId = $(this).attr('id');
        var STATUS_DELETED = 'DELETED';
        var STATUS_BANNED = 'BANNED';
        var ACTION_ID = 'view';

        $.ajax({
            url: '/admin/admin/user/update-status',
            type: "POST",
            data: {'status': newStatus, 'id': fieldId, 'language': language},
            success: function (result) {
                var isAdmin = result.isAdmin;
                var userStatus = result.userStatus;
                var userOldStatusText = result.userOldStatusText;
                var userOldStatusValue = result.userOldStatusValue;

                $('#user-status-success').html(
                    '<div class="alert alert-success alert-dismissible fade in" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">×</span>' +
                    '</button>'
                    + '<i class="icon fa fa-check"></i>'
                    + result.message +
                    '</div>'
                );

                if (document.location.pathname.indexOf(ACTION_ID) !== -1) {
                    $('.status-column').html(userStatus);
                } else {
                    tableRow.find('.status-column').html(userStatus);
                }

                $('#' + selectId + ' option:selected').remove();
                $('#' + selectId).append(
                    $('<option></option>')
                        .attr('value', userOldStatusValue)
                        .text(userOldStatusText)
                );
                if (!isAdmin && (userStatus === STATUS_DELETED || userStatus === STATUS_BANNED)) {
                    $('.user-status').prop('disabled', true);
                }
            },
            error: function (result) {
                $('#user-status-error').html(
                    '<div class="alert alert-error alert-dismissible fade in" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">×</span>' +
                    '</button>'
                    + '<i class="icon fa fa-bun"></i>'
                    + result.responseJSON.message +
                    '</div>'
                );
            }
        });
    });

    $(document).on({
        ajaxStart: function () {
            $('#loader').show();
        },

        ajaxStop: function () {
            $('#loader').hide();
        }
    });
});


