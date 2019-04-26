
$(function () {
    $('#office3').val($('#office_id').val());
     $('#warehouse3').val($('#warehouse_id').val());
    if ($('#office3').val() != "") {
        $('#loader3').show();
        $('#combo13').empty();
        $('#combo23').empty();
        //$('#warehouse3').empty();
        $('#div_combo13').hide();
        $('#div_combo23').hide();
        $('#wh_combo3').hide();
        $.ajax({
            type: "POST",
            url: appName + "/index/all-level-combos-one",
            data: {office: $('#office_id').val()},
            dataType: 'html',
            success: function (data) {
                $('#loader3').hide();
                var val1 = $('#office3').val();
                switch (val1) {
                    case '1':
                        $('#wh_l3').html('Warehouse');
                        $('#wh_combo3').show();
                        $('#warehouse3').html(data);
                        $('#warehouse3').val($('#warehouse_id').val());
                        break;
                    case '2':
                        $('#lblcombo13').text('Province');
                        $('#div_combo13').show();
                        $('#combo13').html(data);
                        $('#combo13').val($('#combo1_id').val());
                        break;
                    case '3':
                        $('#lblcombo13').text('Province');
                        $('#div_combo13').show();
                        $('#combo13').html(data);
                        $('#combo13').val($('#combo1_id').val());
                        break;
                    case '4':
                        $('#lblcombo13').text('Province');
                        $('#div_combo13').show();
                        $('#combo13').html(data);
                        $('#combo13').val($('#combo1_id').val());
                        break;
                    case '5':
                        $('#lblcombo13').text('Province');
                        $('#div_combo13').show();
                        $('#combo13').html(data);
                        $('#combo13').val($('#combo1_id').val());
                        break;
                    case '6':
                        $('#lblcombo13').text('Province');
                        $('#div_combo13').show();
                        $('#combo13').html(data);
                        $('#combo13').val($('#combo1_id').val());
                        break;
                }
            }
        });
    }


    if ($('#combo13').val() != "") {
        $('#loader3').show();
        $('#combo23').empty();

        //$('#warehouse3').empty();

        $('#div_combo23').hide();
        $('#wh_combo3').hide();

        $.ajax({
            type: "POST",
            url: appName + "/index/all-level-combos-two",
            data: {combo1: $('#combo1_id').val(), office: $('#office_id').val()},
            dataType: 'html',
            success: function (data) {
                $('#loader3').hide();

                var val = $('#office3').val();
                switch (val)
                {
                    case '2':
                        $('#wh_combo3').show();
                        $('#warehouse3').html(data);
                        $('#warehouse3').val($('#warehouse_id').val());
                        break;
                    case '3':
                        $('#wh_combo3').show();
                        $('#warehouse3').html(data);
                        $('#warehouse3').val($('#warehouse_id').val());
                        break;
                    case '4':
                        $('#wh_combo3').show();
                        $('#warehouse3').html(data);
                        $('#warehouse3').val($('#warehouse_id').val());
                        break;
                    case '5':
                        $('#lblcombo23').text('Districts');
                        $('#div_combo23').show();
                        $('#combo23').show();
                        $('#combo23').html(data);
                        $('#combo23').val($('#combo2_id').val());
                        break;
                    case '6':
                        $('#lblcombo23').text('Districts');
                        $('#div_combo23').show();
                        $('#combo23').show();
                        $('#combo23').html(data);
                        $('#combo23').val($('#combo2_id').val());
                        break;


                }
            }
        });
    }

    if ($('#combo23').val() != "") {

        $('#loader3').show();
        $.ajax({
            type: "POST",
            url: appName + "/index/all-level-combos-three",
            data: {combo2: $('#combo2_id').val(), office: $('#office_id').val()},
            dataType: 'html',
            success: function (data) {
                $('#loader3').hide();
                $('#wh_combo3').show();
                $('#wh_13').html('Store');
                $('#warehouse3').html(data);
                $('#warehouse3').val($('#warehouse_id').val());
            }
        });
    }

    // validate form on keyup and submit
    $("#update-model").validate({
        rules: {
            'asset': {
                required: true
            },
            'ccm_asset_type_id_add': {
                required: true
            },
            'ccm_asset_sub_type': {
                required: true
            },
            'gross': {
                required: true
            },
            'net': {
                required: true
            }
        }
    });
    
    // validate form on keyup and submit
    $("#search_form").validate({
        rules: {
            'office': {
                required: true
            },
            'combo1': {
                required: true
            },
            'combo2': {
                required: true
            },
            'warehouse3': {
                required: true
            }
        }
    });

    $(document).on("click", ".update-model", function () {

        $.ajax({
            type: "POST",
            url: appName + "/cadmin/manage-capacity/ajax-edit",
            data: {model_id: $(this).attr('itemid')},
            dataType: 'html',
            success: function (data) {
                $('#modal-body-contents').html(data);
                $('#update-button').show();
                $('#ccm_asset_type_id_add').change(function () {
                    $('#ccm_asset_sub_type').html('');
                    if ($(this).val() != "") {
                        $('#loader_asset_type').show();
                        $.ajax({
                            type: "POST",
                            url: appName + "/cadmin/manage-models/ajax-get-asset-subtypes-by-asset-type",
                            data: {type_id: $(this).val()},
                            dataType: 'html',
                            success: function (data) {
                                $('#loader_asset_type').hide();
                                $('#ccm_asset_sub_type').html(data);


                            }
                        });
                    }
                });


            }
        });
    });

});

