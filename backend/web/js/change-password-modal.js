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
                alert('Error occurred while processing request');
            },
            success : function () {
                alert('Message was successfully send');
            }
        })
    });
});
