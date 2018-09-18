$(document).ready(function () {
    $('#password-reset').modal("show");
});

$(function () {
    $(document).on('click', '.ajaxReInviteMessage', function () {
        var reInviteUrl = $(this).attr('reInviteUrl');
        $.ajax({
            url: reInviteUrl,
            type : "post",
            dataType : 'json',
            data : {'language' : language},
            success: function (result) {
                $('#re-invite-success').html(
                    '<div class="alert alert-success alert-dismissible fade in" role="alert">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                            '<span aria-hidden="true">×</span>' +
                        '</button>'
                        + '<i class="icon fa fa-check"></i>'
                        + result.message +
                    '</div>'
                );
            },
            error : function (result) {
                $('#re-invite-error').html(
                    '<div class="alert alert-error alert-dismissible fade in" role="alert">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                            '<span aria-hidden="true">×</span>' +
                        '</button>'
                        + '<i class="icon fa fa-ban"></i>'
                        + result.responseJSON.message +
                    '</div>'
                );
            }
        })
    });
});
