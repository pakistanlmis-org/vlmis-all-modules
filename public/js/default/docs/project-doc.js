
$(function () {
    $(".btn-download").click(function () {
        var url = $(this).attr('href');
        var doc_id = $(this).attr('doc-id');
        $.ajax({
            type: "POST",
            url: appName + "/docs/ajax-doc-user-log", 
            data: {
                url: url
            },
            dataType: 'html',
            success: function (data) {
//                $("#output").html(data);
//                alert("Success");
            }
        });
    });

});