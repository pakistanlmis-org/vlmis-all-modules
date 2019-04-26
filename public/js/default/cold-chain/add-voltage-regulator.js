$(function () {
    $('#ccm_make_id').change(function () {
        $('#ccm_model_id').html('');
        if ($(this).val() != "") {
            $.ajax({
                type: "POST",
                url: appName + "/cold-chain/ajax-get-models",
                data: {make: $(this).val()},
                dataType: 'html',
                success: function (data) {
                    $('#ccm_model_id').html(data);
                    $('#ccm_model_id').css('backgroundColor', 'Green');
                    $.cookie('blink_div_background_color', "ccm_model_id");
                    setTimeout(changeColor, 1000);
                }
            });
        }
    });
});

$("#asset_add").validate({
    rules: {
        quantity: {
            required: true,
            number: true,
            min: 0
        },
        office: {
            required: true
        },
        combo1: {
            required: true
        },
        warehouse: {
            required: true
        },
        catalogue_id: {
            required: true
        }
    },
    messages: {
        'quantity': {
            required: "Please enter quantity."
        },
        ccm_make_id: {
            required: "Please select make."
        },
        ccm_model_id: {
            required: "Please select model."
        }
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
})

//Start of code for Catalog id logic
//Hiding those elements which loads after Catalogue ID selection
$('#div_make').hide();
$('#div_model').hide();

$('#catalogue_id').change(function () {
    if ($(this).val() != "") {
        $('#loader_catalogue_id').show();
        $.ajax({
            url: appName + "/cold-chain/ajax-get-data-by-catalogue-id",
            data: {catalogue_id: $(this).val()},
            dataType: 'json',
            success: function (data) {
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
                        success: function (data) {
                            $('#ccm_model_id').html(data);
                            $('#ccm_model_id').val(ccm_model_id);
                            $('#ccm_model_id').attr("disabled", true);
                        }
                    });
                }
                $('#ccm_make_id').val(ccm_make_id);
                $('#ccm_make_id').attr("disabled", true);
                $.ajax({
                    type: "POST",
                    url: appName + "/cold-chain/ajax-get-make",
                    data: {make: ccm_make_id},
                    dataType: 'html',
                    success: function (data) {
                        $('#ccm_make_id').html(data);
                        $('#ccm_make_id').attr("disabled", true);
                    }
                });

                //Show the required Divs
                $('#div_make').show('slow');
                $('#div_model').show('slow');
                //Hide Loaders
                $('#loader_catalogue_id').hide();
                $('#loader_make').hide();
            }
        });
    } else {
        $('#div_make').hide('slow');
        $('#div_model').hide('slow');
    }
});

$('#asset_add_popup-save').click(function () {
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


//function form_validate(){
$("#asset_add_popup").validate({
    rules: {
        catalogue_id_popup: {
            required: true,
           // alphanumeric: true
        },
        ccm_make_popup: {
            required: true,
           // alphanumeric: true
        },
        ccm_model_popup: {
            required: true,
          //  alphanumeric: true
        }
    },
    messages: {
        catalogue_id_popup: {
            required: "Please enter catalogue ID"
        },
        ccm_make_popup: {
            required: "Please enter make"
        },
        ccm_model_popup: {
            required: "Please enter model"
        }
    },
    submitHandler: function () {
        var form_data = $("#asset_add_popup").serialize();
        $.ajax({
            type: "POST",
            url: appName + "/cold-chain/add-new-make-model?" + form_data,
            data: {},
            dataType: 'html',
            success: function (data) {
                $("#asset_add_popup")[0].reset();
                $(".close").trigger("click");
                $("#catalogue_id").html(data);
            }
        });
    }
});