$(document).ready(function () {
    const editableLinkInColumnClass = 'kv-editable-value kv-editable-link';
    const editableFormClass = 'kv-editable-popover.skip-export.popover.popover-default.popover-x.has-footer.kv-popover-active.in.right';

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

    $('#update-manager-password-button').on('click', function (event) {
        event.preventDefault();
        $('#update-manager-password-form').modal("show");
    });

    $(document).click(function(event) {
        editableForm = $('.' + editableFormClass);
        currentlyClickedOjb = $(event.target);
        if(
            editableForm.is(":visible")
            && !currentlyClickedOjb.closest('#' + editableForm.attr('id')).length
            && currentlyClickedOjb.attr('class') != editableLinkInColumnClass
        ) {
            editableForm.find('.close:button').click();
        }
    });
});
