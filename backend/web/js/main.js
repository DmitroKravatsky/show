$(document).ready(function () {
    ('#reset').on('click', function () {
        $(this).closest('form').trigger('reset');
    });
    $(function () {
        window.setTimeout(function () {
            $('.alert').alert('close');
        }, 3000);
    });
});
