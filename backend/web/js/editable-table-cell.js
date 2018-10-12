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
                var bidStatus = result.bidStatus;
                var inProgressByManager = result.inProgressByManager;
                var tableRow = $('tr[data-key="' + fieldId + '"]');
                var bidOldStatusValue = result.bidOldStatusValue;
                var bidOldStatusText = result.bidOldStatusText;

                if (document.location.pathname.indexOf(ACTION_ID) !== -1) {
                    $('#status').html(bidStatus);
                    $('#in-progress-by-column').html(inProgressByManager);
                    $('#processed-by').html(processedBy);
                    if (!isAdmin && (bidStatus === STATUS_PAID_BY_US_DONE || bidStatus === STATUS_REJECTED)) {
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

                    tableRow.each(function () {
                        $(this).find('.status-column').html(bidStatus);
                        $(this).find('.processed-column').html(processedStatus);
                        $(this).find('.processed-by-column').html(processedBy);
                        $(this).find('.in-progress-by-column').html(inProgressByManager);

                        if (!isAdmin && (bidStatus === STATUS_PAID_BY_US_DONE || bidStatus === STATUS_REJECTED)) {
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
        var STATUS_DELETED = 'DELETED';
        var STATUS_BANNED = 'BANNED';

        $.ajax({
            url: '/admin/admin/user/update-status',
            type: "POST",
            data: {'status': newStatus, 'id': fieldId, 'language': language},
            success: function (result) {
                var isAdmin = result.isAdmin;
                var userStatus = result.userStatus;

                $('#user-status-success').html(
                    '<div class="alert alert-success alert-dismissible fade in" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">×</span>' +
                    '</button>'
                    + '<i class="icon fa fa-check"></i>'
                    + result.message +
                    '</div>'
                );

                $('.status-column').html(userStatus);
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


