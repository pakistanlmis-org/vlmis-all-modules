$(function() {
    $("#spinner13").hide();
    $("#tehsil-combo").change(function() {
        $("#spinner13").show();
        $("#after-filter13").hide();
        $.ajax({
            type: "POST",
            url: appName + "/reports/dashlet/ajax-get-consumption",
            data: {date: $("#date").val(),wastages:$("input[name='wastages']:checked").val(), item: $("#items").val(),province: $('#combo1').val(), district: $('#combo2').val(), level: $('#office').val(), teh_id: $(this).val()},
            dataType: 'html',
            success: function(data) {
                $("#spinner13").hide();
                $("#after-filter13").show();
                $("#after-filter13").html(data);
            }
        });
    });
    
    
    $(document).on('change', 'input:radio[name^="wastages"]', function (event) {
  

        $("#spinner13").show();
        $("#after-filter13").hide();
        $.ajax({
            type: "POST",
            url: appName + "/reports/dashlet/ajax-get-consumption",
            data: {date: $("#date").val(),wastages:$(this).val(), item: $("#items").val(),province: $('#combo1').val(), district: $('#combo2').val(), level: $('#office').val(), teh_id: $('#tehsil-combo').val()},
            dataType: 'html',
            success: function(data) {
                $("#spinner13").hide();
                $("#after-filter13").show();
                $("#after-filter13").html(data);
            }
        });
    });
});