$(document).ready(function () {
    $('#reset').on('click', function () {
        $(this).closest('form').trigger('reset');
    });

    $(function () {
        window.setTimeout(function () {
            $('.alert').alert('close');
        }, 3000);
    });

    $('#password-reset').on('hidden.bs.modal', function () {
        var url = window.location.href;
        var code = url.substring(url.lastIndexOf('=') + 1);

        $.ajax({
            url: 'invite/destroy',
            type: 'get',
            data: {'inviteCode': code},
        });
    });
});
