
    $('body').on('change', '.status', function () {
        var newStatus = this.value;
        var fieldId = $(this).parent().parent().data('key');
        var STATUS_REJECTED = 'rejected';
        var STATUS_PAID_BY_US_DONE = 'paid_by_us_done';
        var ACTION_ID = 'view';

        $.ajax({
            url : '/admin/admin/bid/update-bid-status',
            type : "POST",
            data: {'status' : newStatus, 'id' : fieldId, 'language' : language},
            success : function (result) {
                var isAdmin = result.isAdmin;
                var processedStatus = result.processedStatus;
                var processedBy = result.processedBy;
                var bidStatus = result.bidStatus;
                var inProgressByManager = result.inProgressByManager;
                var tableRow = $('tr[data-key="' + fieldId + '"]');

                if (document.location.pathname.indexOf(ACTION_ID) !== -1) {
                    $('#status').html(bidStatus);
                    $('#in-progress-by-column').html(inProgressByManager);
                    $('#processed-by').html(processedBy);
                    if (!isAdmin && (bidStatus === STATUS_PAID_BY_US_DONE || bidStatus === STATUS_REJECTED)) {
                        $('.status').prop('disabled', true);
                    }
                } else {
                    tableRow.removeClass('success');
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
                        '</button>' + result.message +
                    '</div>'
                );
            },
            error : function (result) {
                $('#bid-status-error').html(
                    '<div class="alert alert-error alert-dismissible fade in" role="alert">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                            '<span aria-hidden="true">×</span>' +
                        '</button>' + result.responseJSON.message +
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

