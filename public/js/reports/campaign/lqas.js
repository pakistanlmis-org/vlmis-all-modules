$(function () {
    $("#office").change(function () {
        $("#campaign").empty();
        var level = $("#office").val();
        if (level == 1) {
            $.ajax({
                type: "POST",
                url: appName + "/reports/campaign/ajax-get-campaigns",
                data: {district: $('#combo2').val(), level: 1},
                dataType: 'html',
                success: function (data) {
                    $("#campaign").html(data);
                }
            });
        }
    });

    $("#combo1").change(function () {
        $("#campaign").empty();
        var prov = $("#combo1").val();
        $.ajax({
            type: "POST",
            url: appName + "/reports/campaign/ajax-get-campaigns",
            data: {province: prov, level: 2},
            dataType: 'html',
            success: function (data) {
                $("#campaign").html(data);
            }
        });
    });
    
    $("#go").click(function (e) {
        e.preventDefault();
        var validator = $("#lqas_form").validate();
        if ($("#combo1").val() == '') {
            validator.showErrors({
                "combo1": "Please select Province name"
            });
            return false;
        } else {
            $("#lqas_form").submit();
        }
    });

});