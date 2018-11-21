$(document).ready(function () {
    $('body').on('click', ".delete-button", function (e) {
        e.preventDefault();

        var url = $(this).attr("href");

        krajeeDialog.confirm($(this).data("message"), function (result) {
            if (result) {
                $.ajax({
                    url: url
                }).done(function () {
                    $.pjax.reload({container: "#pjax-container"});
                });
            } else {
                return;
            }
        });
    });
});
