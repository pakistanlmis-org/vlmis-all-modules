$("#all_level_combo").hide();
$("#placed_at-0").click(function() {
    $("#all_level_combo").hide();
});
$("#placed_at-1").click(function() {
    $("#all_level_combo").show();
    $('#office').css('backgroundColor', 'Green');
    $.cookie('blink_div_background_color', "office");
    setTimeout(changeColor, 500);
});
$('#asset_add_popup-save').click(function() {
    $('#asset_add_popup').submit();
    //form_validate();
//    var form_data = $("#asset_add_popup").serialize();
//    $.ajax({
//        type: "POST",
//        url: appName + "/cold-chain/add-new-make-model",
//        data: {form_data: form_data},
//        dataType: 'html',
//        success: function(data) {
//            //$('#alert-message').show('slow');
//        }
//    });
});
$("#vaccine_carrier_form").validate({
    rules: {
        'catalogue_id': {
            required: true
        },
        'quantity': {
            required: true,
            number: true,
            min: 0
        },
        'office': {
            required: true

        },
        'combo1': {
            required: true
        },
        //  'asset_dimension_length': {
        //     required: true
        // },
        'warehouse': {
            required: true
        }
    },
    messages: {
        'catalogue_id': {
            required: "Please enter catalogue ID"
        },
        'quantity': {
            required: "Please enter quantity"
        }
        //   'asset_dimension_length': {
        //       required: false
        //   }
    }
});


$(function() {
    $('#ccm_make_id').change(function() {
        $('#ccm_model_id').html('');
        $('#loader').show();
        if ($(this).val() != "") {
            $.ajax({
                type: "POST",
                url: appName + "/cold-chain/ajax-get-models",
                data: {make: $(this).val()},
                dataType: 'html',
                success: function(data) {
                    $('#loader').hide();
                    $('#ccm_model_id').html(data);
                    $('#ccm_model_id').css('backgroundColor', 'Green');
                    $.cookie('blink_div_background_color', "ccm_model_id");
                    setTimeout(changeColor, 1000);
                }
            });
        }
    });

    /* DataTables */
    if ($('.vaccineCarrier').size() > 0)
    {
        var datatable = $('.vaccineCarrier').dataTable({
            "sPaginationType": "bootstrap",
            //"sDom": 'W<"clear">lfrtip',
            "sDom": 'T<"clear">lfrtip',
            // "sDom": '<"clear">lfrtipT',
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page"
            },
            "oColumnFilterWidgets": {
                "aiExclude": [0, 5, 6, 7, 8, 9]
            },
            "oTableTools": {
                "aButtons": [
                    {
                        "sExtends": "copy",
                        "sButtonText": "Copy",
                        "mColumns": [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }

                ],
                "sSwfPath": appName + "/common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf"
            }
        });
    }

//Start of code for Catalog id logic
//Hiding those elements which loads after Catalogue ID selection
    $('#div_make').hide();
    $('#div_model').hide();
    $('#internal_dimensions').hide();

    $('#catalogue_id').change(function() {
        if ($(this).val() != "") {
            $('#loader_catalogue_id').show();
            $.ajax({
                url: appName + "/cold-chain/ajax-get-data-by-catalogue-id",
                data: {catalogue_id: $(this).val()},
                dataType: 'json',
                success: function(data) {
                    var ccm_make_id = data['ccm_make'];
                    var ccm_model_id = data['ccm_model'];

                    $('#ccm_model_id').html('');
                    if (ccm_make_id != "") {
                        $('#loader_make').show();
                        $.ajax({
                            type: "POST",
                            url: appName + "/cold-chain/ajax-get-models",
                            data: {make: ccm_make_id},
                            dataType: 'html',
                            success: function(data) {
                                $('#ccm_model_id').html(data);
                                $('#ccm_model_id').val(ccm_model_id);
                                $('#ccm_model_id_hidden').val(ccm_model_id);
                                $('#ccm_model_id').attr("disabled", true);
                            }
                        });
                    }
                    $('#asset_dimension_height').val(data['assetDimensionHeight']);
                    $('#asset_dimension_height').attr("readonly", true);

                    $('#asset_dimension_width').val(data['assetDimensionWidth']);
                    $('#asset_dimension_width').attr("readonly", true);

                    $('#asset_dimension_length').val(data['assetDimensionLength']);
                    $('#asset_dimension_length').attr("readonly", true);

                    $('#ccm_make_id').val(ccm_make_id);

                    $('#ccm_make_id_hidden').val(ccm_make_id);
                    $('#ccm_make_id').attr("disabled", true);

                    $.ajax({
                        type: "POST",
                        url: appName + "/cold-chain/ajax-get-make",
                        data: {make: ccm_make_id},
                        dataType: 'html',
                        success: function(data) {
                            $('#ccm_make_id').html(data);
                            $('#ccm_make_id').attr("disabled", true);
                        }
                    });

                    //Show the required Divs
                    $('#div_make').show('slow');
                    $('#div_model').show('slow');
                    $('#internal_dimensions').show('slow');
                    //Hide Loaders
                    $('#loader_catalogue_id').hide();
                    $('#loader_make').hide();
                }
            });
        } else {
            $('#div_make').hide('slow');
            $('#div_model').hide('slow');
            $('#internal_dimensions').hide('slow');
        }
    });


});

//function form_validate(){
$("#asset_add_popup").validate({
    rules: {
        catalogue_id_popup: {
            required: true
        },
        ccm_make_popup: {
            required: true
        },
        ccm_model_popup: {
            required: true
        },
        'asset_dimension_length_popup': {
            required: true,
            number: true,
            min: 0
        },
        'asset_dimension_width_popup': {
            required: true,
            number: true,
            min: 0
        },
        'asset_dimension_height_popup': {
            required: true,
            number: true,
            min: 0
        },
        'net_capacity_4': {
           required: true,
            number: true,
            min: 0
        },
        cold_life: {
            
            number: true,
            min: 0
        }
    },
    messages: {
        'catalogue_id_popup': {
            required: "Please enter catalogue ID"
        },
        ccm_make_popup: {
            required: "Please enter make"
        },
        ccm_model_popup: {
            required: "Please enter model"
        },
        'asset_dimension_length_popup': {
            required: "Please enter length"
        },
        'asset_dimension_width_popup': {
            required: "Please enter width"
        },
        'asset_dimension_height_popup': {
            required: "Please enter height"
        }
    },
    submitHandler: function() {
        var form_data = $("#asset_add_popup").serialize();
        $.ajax({
            type: "POST",
            url: appName + "/cold-chain/add-new-make-model?" + form_data,
            data: {},
            dataType: 'html',
            success: function(data) {
                $("#asset_add_popup")[0].reset();
                $(".close").trigger("click");
                $("#catalogue_id").html(data);
            }
        });
    }
});

$.validator.addMethod("alphanumspace", function(value, element) {
    return this.optional(element) || value == value.match(/^[a-zA-Z0-9\-\s]+$/);
});