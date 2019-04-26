$.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results==null){
       return null;
    }
    else{
       return results[1] || 0;
    }
}
$('.dashlets').each(function(i, obj) {
    var url = $(this).attr('href');
    var id = $(this).attr('id');

    $.ajax({
        type: "POST",
        url: appName + '/' + url,
        data: {token: $.urlParam('token'), level: $('#office').val(), province: $('#combo1').val(), district: $('#combo2').val(), prov_campaign: $('#prov').val(), camp: $('#camp').val(), date: $('#date').val(), item: $('#items').val(), period: $('#period').val()},
        dataType: 'html',
        success: function(data) {
            $("#" + id).html(data);
        }
    });
});

$(document).ready(function() {
    $("#ri_btn").click(function(e) {
        e.preventDefault();
        var item = $("#items").val();
        var date = $("#date").val();
        var office = $("#office").val();
        var combo1 = $("#combo1").val();
        var combo2 = $("#combo2").val();
        var period = $("#period").val();
        var id = $("#tabid").val();
        
        window.location = appName + '/dashboard/vlmis/?token=' + $.urlParam('token') + '&id=' + id + '&ri_btn=ri&items=' + item + '&period=' + period + '&date=' + date + '&office=' + office + '&combo1=' + combo1 + '&combo2=' + combo2;
    });
    
    $("#im_btn").click(function(e) {
        e.preventDefault();
        var item = $("#items").val();
        var date = $("#date").val();
        var office = $("#office").val();
        var combo1 = $("#combo1").val();
        var combo2 = $("#combo2").val();
        var period = $("#period").val();
        var id = $("#tabid").val();
        
        window.location = appName + '/dashboard/vlmis/?token=' + $.urlParam('token') + '&id=' + id + '&im_btn=im&items=' + item + '&period=' + period + '&date=' + date + '&office=' + office + '&combo1=' + combo1 + '&combo2=' + combo2;
    });

    $("#camp_btn").click(function(e) {
        e.preventDefault();
        var prov = $("#prov").val();
        var camp = $("#camp").val();
        var office = $("#office").val();
        var combo1 = $("#combo1").val();
        var combo2 = $("#combo2").val();
        var id = $("#tabid").val();
        
        window.location = appName + '/dashboard/vlmis/?token=' + $.urlParam('token') + '&id=' + id + '&camp_btn=camp&camp=' + camp + '&prov=' + prov + '&office=' + office + '&combo1=' + combo1 + '&combo2=' + combo2;
    });

    $("#go").click(function(e) {
        e.preventDefault();
        //var prov = $("#prov").val();
        //var camp = $("#camp").val();
        var item = $("#items").val();
        var date = $("#date").val();
        var office = $("#office").val();
        var combo1 = $("#combo1").val();
        var combo2 = $("#combo2").val();
        var period = $("#period").val();
        var id = $("#tabid").val();
        
        window.location = appName + '/dashboard/vlmis/?token=' + $.urlParam('token') + '&id=' + id + '&ri_btn=ri&items=' + item + '&period=' + period + '&date=' + date + '&office=' + office + '&combo1=' + combo1 + '&combo2=' + combo2;
    });
});
