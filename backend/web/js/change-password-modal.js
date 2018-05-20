$(document).ready(function () {
    $('#password-reset').modal("show");
});

$(function () {
    $(document).on('click', '.ajaxDelete', function () {
        var deleteUrl = $(this).attr('deleteUrl');
        $.ajax({
            url: deleteUrl,
            type : "post",
            dataType : 'json',
            error: function () {
                alert('Error');
            },
            success : function () {
                alert('Message was successfully send');
            }
        })
    });
});
