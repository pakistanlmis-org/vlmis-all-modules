$(function() {


    var add_edit = $("#show-add-edit").val();
    if (add_edit == 1) {
        $("#add-edit").show();
    }
    else
    {
        $("#add-edit").hide();

    }
    
    // validate signup form on keyup and submit
    $("#search_form").validate({
        rules: {
            campaign_id: {
                required: true
            },
            province_id: {
                required: true
            },
            district_id: {
                required: true
            },
            item_id: {
                required: true
            }
        }
    });




    $(".update-target").click(function() {
        $.ajax({
            type: "POST",
            url: appName + "/campaign/manage-campaigns/ajax-campaigns-name",
            data: {campaign_id: $('#campaign_id').val(), province_id: $('#province_id').val(), district_id: $('#district_id').val()},
            dataType: 'html',
            success: function(data) {
                $('#camp_name').html(data);
            }
        });
        $.ajax({
            type: "POST",
            url: appName + "/campaign/manage-campaigns/ajax-campaigns-target",
            data: {campaign_id: $('#campaign_id').val(), province_id: $('#province_id').val(), district_id: $('#district_id').val(), item_id: $('#item_id').val()},
            dataType: 'html',
            success: function(data) {
                $('#modal-body-contents').html(data);
                $('#update-button').show();

                $(document).ready(function() {
                    //this calculates values automatically 
                    calculateSum();

                    $(".txt").live("keydown keyup", function() {
                        calculateSum();
                    });
                });

                function calculateSum() {
                    var sum = 0;
                    //iterate through each textboxes and add the values
                    $(".txt").each(function() {
                        var d_target1 = this.value.replace(',', '');
                        var d_target2 = d_target1.replace(',', '');
                        var d_target = d_target2.replace(',', '');

                        if (!isNaN(d_target) && d_target.length != 0) {
                            sum += parseFloat(d_target);

                        }
                        else if (d_target.length != 0) {

                        }
                    });
                    $("input#total").val(sum);

                }

            }
        });
    });


    $(".close-campaigns-target").click(function() {
        $.ajax({
            type: "POST",
            url: appName + "/campaign/manage-campaigns/close-campaigns-target",
            data: {campaign_id: $('#campaign_id').val(), province_id: $('#province_id').val(), district_id: $('#district_id').val()},
            dataType: 'html',
            success: function(data) {
                //alert('close');
                $(".close-campaigns-target").css('visibility', 'hidden');
                $(".update-target").css('visibility', 'hidden');
                $(".import").css('visibility', 'hidden');
                //  $('.open-campaigns-target').show();
                $(".open-campaigns-target").css('visibility', 'visible');
            }
        });

    });

    $(".open-campaigns-target").click(function() {
        $.ajax({
            type: "POST",
            url: appName + "/campaign/manage-campaigns/open-campaigns-target",
            data: {campaign_id: $('#campaign_id').val(), province_id: $('#province_id').val(), district_id: $('#district_id').val()},
            dataType: 'html',
            success: function(data) {
                //alert('open');
                $(".open-campaigns-target").css('visibility', 'hidden');
                $(".update-target").css('visibility', 'visible');
                $(".import").css('visibility', 'visible');
                $(".close-campaigns-target").css('visibility', 'visible');


            }
        });

    });

    $('#campaign_id').change(function(e) {
        $('#province_id').val('');
        $('#district_id').val('');
        $('#wh_id').val('');
        showProvinces('');
        showItems('');
    });

    function showProvinces(province_id) {
        var campaign_id = $('#campaign_id').val();
        if (campaign_id != '') {
            $.ajax({
                url: appName + "/campaign/manage-campaigns/ajax-for-campaign",
                type: 'POST',
                data: {condition: '007', province_id: province_id, campaign_id: campaign_id, provinces: 1},
                success: function(data) {
                    $('#province_id').html(data);
                    if (district_id) {
                        showDistricts(district_id);
                    }
                }})
        } else {
            $('#province_id').empty();
        }
    }

    if ($('#campaign_id').val() != "") {
        var campaign_id = $('#campaign_id').val();
        if (campaign_id != '') {
            $.ajax({
                url: appName + "/campaign/manage-campaigns/ajax-for-campaign",
                type: 'POST',
                data: {condition: '007', province_id: '', campaign_id: campaign_id, provinces: 1},
                success: function(data) {
                    $('#province_id').html(data);
                    $('#province_id').val($('#province_id_hidden').val());
                    if (district_id) {
                        showDistricts(district_id);
                    }
                }})
        }
    }

    // Show districts
    $('#province_id').change(function(e) {
        showDistricts('');
    });

})

function showDistricts(dist_id) {
    var province_id = $('#province_id').val();
    var campaign_id_combo = $('#campaign_id').val();
    if (campaign_id_combo != "") {
        campaign_id = campaign_id_combo;
    }
    if (province_id != '') {
        $.ajax({
            url: appName + "/campaign/manage-campaigns/ajax-for-campaign",
            type: 'POST',
            data: {condition: '002', province_id: province_id, dist_id: dist_id, campaign_id: campaign_id},
            success: function(data) {
                $('#district_id').html(data);
            }
        })
    }
    else {
        $('#district_id').empty();
    }
}
if ($('#province_id').val() != "") {
    var province_id = $('#province_id_hidden').val();
    var campaign_id_combo = $('#campaign_id').val();
    if (campaign_id_combo != "") {
        campaign_id = campaign_id_combo;
    }
    if (province_id != '') {
        $.ajax({
            url: appName + "/campaign/manage-campaigns/ajax-for-campaign",
            type: 'POST',
            data: {condition: '002', province_id: province_id, dist_id: '', campaign_id: campaign_id},
            success: function(data) {
                $('#district_id').html(data);
                $('#district_id').val($('#district_id_hidden').val());
            }
        })
    }

}

function showItems(item_id) {
    var campaign_id = $('#campaign_id').val();
    if (campaign_id != '') {
        $.ajax({
            url: appName + "/campaign/manage-campaigns/ajax-for-campaign",
            type: 'POST',
            data: {condition: '005', campaign_id: campaign_id, item_id: item_id},
            success: function(data) {
                $('#item_id').html(data);
                //showSyrWastages($('#item_id').val());
            }
        })
    } else {
        $('#item_id').html('<option value="">Select</option>');
    }
}

if ($('#campaign_id').val() != "") {
    //  alert($('#campaign_id').val());
    var campaign_id = $('#campaign_id').val();
    if (campaign_id != '') {
        $.ajax({
            url: appName + "/campaign/manage-campaigns/ajax-for-campaign",
            type: 'POST',
            data: {condition: '005', campaign_id: campaign_id},
            success: function(data) {
                //alert(data);
                $('#item_id').html(data);
                $('#item_id').val($('#item_id_hidden').val());
                //showSyrWastages($('#item_id').val());
            }
        })
    } else {
        $('#item_id').html('<option value="">Select</option>');
    }
}

// Older "accept" file extension method. Old docs: http://docs.jquery.com/Plugins/Validation/Methods/accept
jQuery.validator.addMethod("extension", function(value, element, param) {
    param = typeof param === "string" ? param.replace(/,/g, "|") : "png|jpe?g|gif";
    return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
}, jQuery.validator.format("Please select file with a valid extension."));

$(function() {
    // Form validate
    $("#import_form").validate({
        rules: {
            import_option: 'required',
            cvs_import: {
                required: true,
                extension: "csv"
            },
            campaign_import_id: 'required'
        }
    });

    $('input[name="import_option"]').click(function(e) {
        var selOpt = $(this).val();
        if (selOpt == 'csv')
        {
            $('#file_import').css('display', 'block');
            $('#campaign_import').css('display', 'none');
        }
        else if (selOpt == 'campaign')
        {
            $('#file_import').css('display', 'none');

            $.ajax({
                type: "POST",
                url: appName + "/campaign/manage-campaigns/ajax-previous-campaigns-name",
                data: {campaign_id: $('#campaign_id').val(), province_id: $('#province_id').val(), district_id: $('#district_id').val(), item_id: $('#item_id').val()},
                dataType: 'html',
                success: function(data) {
                    $('#campaign_import_id').html(data);
                }
            });

            $('#campaign_import').css('display', 'block');
        }
    });
});

