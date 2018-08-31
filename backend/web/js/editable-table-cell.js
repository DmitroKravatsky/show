
    $('.status').on('change', function () {
        var newStatus = this.value;
        var fieldId = $(this).parent().parent().data('key');
        $.ajax({
            url : '/admin/admin/bid/update-bid-status',
            type : "POST",
            async: false,
            data: {'status':newStatus, 'id':fieldId},
            success : function (result) {
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
                        '</button>' + result.statusText +
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

