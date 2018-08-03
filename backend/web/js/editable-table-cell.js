
    $('.status').on('change', function () {
        var newStatus = this.value;
        var fieldId = $(this).parent().parent().data('key');
        $.ajax({
            url : '/admin/admin/bid/update-bid-status',
            type : "POST",
            async: false,
            data: {'status':newStatus, 'id':fieldId},

            success : function (data) {
            },

            error : function (data) {
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

