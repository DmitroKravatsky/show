$(function () {
    $("td").dblclick(function () {
        $.ajax({
            url : '/admin/admin/bid/status',
            type : "POST",
            async: false,
            success : function (data) {
                alert(data);
                var select = $('<select/>');

                for (var val in data){
                    alert(val);
                    $('<option/>', {value : val, text: data[val]}).appendTo(select);
                    select.appendTo('body');
                }
            },
            error : function (data) {
                console.log('failure');
            }
        });
        console.log('failure');
        alert('fail');

        var OriginalContent = $(this).text();

        $(this).addClass("cellEditing");
        $(this).html('' + OriginalContent+ "");
        alert(OriginalContent);
        $(this).children().first().focus();

        $(this).children().first().change(function (e) {

        });

    })
});
