$('#ccm_status_list_id').change(function () {
    $('#reason').html("");
    //$('#utilization').html("");

    if ($(this).val() == "3") {
        $('#loader_reason').show();
        $.ajax({
            type: "POST",
            url: appName + "/cold-chain/ajax-get-reasons",
            data: {working_status: $(this).val()},
            dataType: 'html',
            success: function (data) {
                $('#reason').html(data);
                $('#reason').show();
                $('#reason_div').show();
                $('#reason_div').removeClass("hidden");
                $('#loader_reason').hide();
            }
        });

        /*$.ajax({
         type: "POST",
         url: appName + "/cold-chain/ajax-get-utilizations",
         data: {working_status: $(this).val()},
         dataType: 'html',
         success: function(data) {
         $('#utilization').show();
         $('#utilization_div').show("slow");
         $('#utilization').html(data);
         $('#utilization_div').removeClass("hidden");
         $('#loader_reason').hide();
         }
         });*/
    } else {
        $('#reason_div').hide("");
        //$('#utilization_div').hide("");
        $('#reason_div').addClass("hidden");
        //$('#utilization_div_div').addClass("hidden");
    }
});

$("#all_level_combo").hide();
$("#placed_at-0").click(function () {
    $("#all_level_combo").hide();
});
$("#placed_at-1").click(function () {
    $("#all_level_combo").show();
    $('#office').css('backgroundColor', 'Green');
    $.cookie('blink_div_background_color', "office");
    setTimeout(changeColor, 500);
});

$('#ccm_status_list_id').css('float', 'left');
