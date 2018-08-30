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
            error: function () {
                $('#re-invite-error').show();
            },
            success : function () {
                $('#re-invite-success').show();
            }
        })
    });
});
