
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
            data: {SkOfcLvl: whType, provSelId: province , report:'rep'},
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
            data: {SkOfcLvl: whType, provId: provId, distSelId: district, report:'rep'},
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
            data: {SkOfcLvl: whType, distId: distId, tehSelId: tehsil, report:'rep'},
            dataType: 'html',
            success: function(data)
            {
                $('#tehsil').html(data);
            }
        });
    }
}

 