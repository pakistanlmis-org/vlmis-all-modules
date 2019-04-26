//$(function() {
//$('#wh_type').change(function() {
//    //alert("test");
//    if ($(this).val() == 4 || $(this).val() == 5)
//    {
//        if ($(this).val() == 5)
//        {
//            $('#wh_prov_sel').val(1);
//        }
//        $('#wh_prov_col').show();
//        $.ajax({
//            type: "POST",
//            url: appName + "/reports/inventory-management/province-to-warehouse",
//            data: {SkOfcLvl: $('#wh_type').val(), combo: '1'},
//            dataType: 'html',
//            success: function(data)
//            {
//                $('#warehouse_id').html(data);
//            }
//        });
//    }
//    else
//    {
//        $('#wh_prov_col').hide();
//        $('#loader').show();
//        $.ajax({
//            type: "POST",
//            url: appName + "/reports/inventory-management/province-to-warehouse",
//            data: {SkOfcLvl: $(this).val(), combo: ''},
//            dataType: 'html',
//            success: function(data)
//            {
//                $('#loader').hide();
//                $('#warehouse_id').html(data);
//            }
//        });
//    }
//});
//
//$('#wh_prov_sel').change(function() {
//    $.ajax({
//        type: "POST",
//        url: appName + "/reports/inventory-management/province-to-warehouse",
//        data: {SkOfcLvl: $('#wh_type').val(), combo: $(this).val()},
//        dataType: 'html',
//        success: function(data)
//        {
//            $('#warehouse_id').html(data);
//        }
//    });
//});
//
//$('#prov_sel').change(function() {
//    $('#loader').show();
//    $.ajax({
//        type: "POST",
//        url: "prov2dist.php",
//        data: {prov_sel: $(this).val(), combo: '2'},
//        dataType: 'html',
//        success: function(data)
//        {
//            $('#loader').hide();
//            $('#dist_id').html(data);
//            $('#teh_id').empty();
//            $('#uc_id').empty();
//        }
//    });
//});
//
//$('#dist_id').change(function() {
//    var dist_id = $(this).val();
//    $('#loader').show();
//    $.ajax({
//        type: "POST",
//        url: "prov2dist.php",
//        data: {dist_sel: dist_id, combo: '4'},
//        dataType: 'html',
//        success: function(data)
//        {
//            /*$('#hidden_dist').val(dist_id);
//             $('#hidden_teh').val($('#teh_id').val());*/
//            $('#loader').hide();
//            $('#teh_id').html(data);
//        }
//    });
//    $.ajax({
//        type: "POST",
//        url: "prov2dist.php",
//        data: {dist_sel: dist_id, combo: '5'},
//        dataType: 'html',
//        success: function(data)
//        {
//            $('#loader').hide();
//            $('#uc_id').html(data);
//        }
//    });
//});
//
//$('#teh_id').change(function() {
//    var teh_id = $(this).val();
//    $('#loader').show();
//    $.ajax({
//        type: "POST",
//        url: "prov2dist.php",
//        data: {teh_sel: teh_id, combo: '5'},
//        dataType: 'html',
//        success: function(data)
//        {
//            /*$('#hidden_dist').val(dist_id);
//             $('#hidden_teh').val($('#teh_id').val());*/
//            $('#loader').hide();
//            $('#uc_id').html(data);
//        }
//    });
//});
//
//});

$(function() {

    if (whType) {
        officeType(whType);
        showProv(whType);
        showDistricts(province);
        showTehsils(district);
    }

    $('#wh_type').change(function() {

        var whType = $(this).val();
        officeType(whType);
    });
    $('#province').change(function() {
        var provId = $(this).val();
        if (provId != 'all')
        {
            showDistricts(provId);
        }
        else
        {
            $('#district_combo').fadeOut();
            $('#district').empty();
            $('#tehsil_combo').fadeOut();
            $('#tehsil').empty();
        }
    });
    $('#district').change(function() {
        var distId = $(this).val();
        if (distId != 'all')
        {
            showTehsils(distId);
        }
    });
});
function officeType(whType)
{
    if (whType == 1)
    {
        $('#province_combo').fadeOut();
        $('#province').empty();
        $('#district_combo').fadeOut();
        $('#district').empty();
        $('#tehsil_combo').fadeOut();
        $('#tehsil').empty();
    }
    else
    {
        if (whType == 2)
        {
            $('#district_combo').fadeOut();
            $('#district').empty();
            $('#tehsil_combo').fadeOut();
            $('#tehsil').empty();
        }
        else if (whType == 4)
        {
            $('#district_combo').fadeOut();
            $('#district').empty();
            $('#tehsil_combo').fadeOut();
            $('#tehsil').empty();
        }
        else if (whType == 5)
        {
            $('#province_combo').fadeIn();
            $('#district_combo').fadeOut();
            $('#district').empty();
        }
        showProv(whType);
    }
}
function showProv(whType)
{
    
    if (whType != 1)
    {
        $('#province_combo').fadeIn();
        $('#province').html('<option value="">Loading...</option>');
        $.ajax({
            type: "POST",
            url: appName + "/reports/inventory-management/ajax-combos",
            data: {SkOfcLvl: whType, provSelId: province , report:'rep1'},
            dataType: 'html',
            success: function(data)
            {
                $('#province').html(data);
                var selProv = $('#province').val();
                if (selProv != 'all')
                {
                    showDistricts(selProv);
                }
            }
        });
    }
}
function showDistricts(provId)
{
    var whType = $('#wh_type').val();
    if (whType == 4 || whType == 5)
    {
        $('#district_combo').fadeIn();
        $('#district').html('<option value="">Loading...</option>');
        $.ajax({
            type: "POST",
            url: appName + "/reports/inventory-management/ajax-combos",
            data: {SkOfcLvl: whType, provId: provId, distSelId: district, report:'rep1'},
            dataType: 'html',
            success: function(data)
            {
                $('#district').html(data);
            }
        });
    }
}
function showTehsils(distId)
{
    var whType = $('#wh_type').val();
    if (whType == 5)
    {
        $('#tehsil_combo').fadeIn();
        $('#tehsil').html('<option value="">Loading...</option>');
        $.ajax({
            type: "POST",
            url: appName + "/reports/inventory-management/ajax-combos",
            data: {SkOfcLvl: whType, distId: distId, tehSelId: tehsil, report:'rep1'},
            dataType: 'html',
            success: function(data)
            {
                $('#tehsil').html(data);
            }
        });
    }
}

 