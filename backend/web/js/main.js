$(document).ready(function () {
    ('#reset').on('click', function () {
        $(this).closest('form').trigger('reset');
    });
});