$('.dashlets').each(function(i, obj) {
    var url = $(this).attr('href');
    var id = $(this).attr('id');

    $.ajax({
        type: "POST",
        url: appName + '/' + url,
        data: {level: $('#office').val(), province: $('#combo1').val(), district: $('#combo2').val(), prov_campaign: $('#prov').val(), camp: $('#camp').val(), date: $('#dateri').val(), item: $('#itemsri').val(), period: $('#periodri').val()},
        dataType: 'html',
        success: function(data) {
            $("#" + id).html(data);
        }
    });
});

$(document).ready(function() {
    $("#ri_btn").click(function(e) {
        e.preventDefault();
        var item = $("#itemsri").val();
        var date = $("#dateri").val();
        var office = $("#office").val();
        var combo1 = $("#combo1").val();
        var combo2 = $("#combo2").val();
        var period = $("#periodri").val();
        window.location = appName + '/dashboard/index/?ri_btn=ri&items=' + item + '&period=' + period + '&date=' + date + '&office=' + office + '&combo1=' + combo1 + '&combo2=' + combo2;
    });
    
    $("#im_btn").click(function(e) {
        e.preventDefault();
        var item = $("#itemsim").val();
        var date = $("#dateim").val();
        var office = $("#office").val();
        var combo1 = $("#combo1").val();
        var combo2 = $("#combo2").val();
        var period = $("#periodim").val();
        window.location = appName + '/dashboard/index/?im_btn=im&items=' + item + '&period=' + period + '&date=' + date + '&office=' + office + '&combo1=' + combo1 + '&combo2=' + combo2;
    });

    $("#camp_btn").click(function(e) {
        e.preventDefault();
        var prov = $("#prov").val();
        var camp = $("#camp").val();
        var office = $("#office").val();
        var combo1 = $("#combo1").val();
        var combo2 = $("#combo2").val();
        window.location = appName + '/dashboard/index/?camp_btn=camp&camp=' + camp + '&prov=' + prov + '&office=' + office + '&combo1=' + combo1 + '&combo2=' + combo2;
    });

    $("#go").click(function(e) {        
        e.preventDefault();
        //var prov = $("#prov").val();
        //var camp = $("#camp").val();
        var item = $("#itemsri").val();
        var date = $("#dateri").val();
        var office = $("#office").val();
        var combo1 = $("#combo1").val();
        var combo2 = $("#combo2").val();
        var period = $("#periodri").val();
        window.location = appName + '/dashboard/index/?ri_btn=ri&items=' + item + '&period=' + period + '&date=' + date + '&office=' + office + '&combo1=' + combo1 + '&combo2=' + combo2;
    });
});
