
    $('body').on('change', '.status', function () {
        var newStatus = this.value;
        var fieldId = $(this).parent().parent().data('key');
        var processedDataColSeq = 5;
        var processedByDataColSeq = 7;
        var STATUS_REJECTED = 'rejected';
        var STATUS_PAID_BY_US_DONE = 'paid_by_us_done';
        var ACTION_ID = 'view';

        $.ajax({
            url : '/admin/admin/bid/update-bid-status',
            type : "POST",
            async: false,
            data: {'status' : newStatus, 'id' : fieldId, 'language' : language},
            success : function (result) {
                var isAdmin = result.isAdmin;
                var processedStatus = result.processedStatus;
                var processedBy = result.processedBy;
                var bidStatus = result.bidStatus;
                var tableRow = $('tr[data-key="' + fieldId + '"]');

                if (document.location.pathname.indexOf(ACTION_ID) !== -1) {
                    if (!isAdmin && (bidStatus === STATUS_PAID_BY_US_DONE || bidStatus === STATUS_REJECTED)) {
                        $('.status').prop('disabled', true);
                    }
                } else {
                    tableRow.removeClass('success');
                    tableRow.children().each(function () {
                        if ((typeof processedStatus !== undefined) && (typeof processedBy !== undefined)) {
                            if ($(this).data('col-seq') === processedDataColSeq) {
                                $(this).html(processedStatus);
                            }
                            if ($(this).data('col-seq') === processedByDataColSeq) {
                                $(this).html(processedBy);
                            }
                        }
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

