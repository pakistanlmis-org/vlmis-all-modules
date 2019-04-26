$(function() {
       if($('#catalogue_id').val()!="") {
        if ($('#catalogue_id').val() != "") {
            $('#loader_catalogue_id').show();
            $.ajax({
                url: appName + "/cold-chain/ajax-get-data-by-catalogue-id",
                data: {catalogue_id: $('#catalogue_id').val()},
                dataType: 'json',
                success: function(data) {
                    if (data['cfcFree'] == 1) {
                        $('#cfc_free-1').attr("checked", "checked");
                    } else {
                        $('#cfc_free-0').attr("checked", "checked");
                    }
                    $('#cfc_free-0').attr("disabled", true);
                    $('#cfc_free-1').attr("disabled", true);
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
                    //Set values of html elements and make them readonly
                    $('#ccm_asset_type_id').val(data['ccm_asset_type']);
                    $('#ccm_asset_type_id').attr("disabled", true);

                    $('#net_capacity_4').val(data['netCapacity4']);
                    $('#net_capacity_4').attr("disabled", true);

                    $('#net_capacity_20').val(data['netCapacity20']);
                    $('#net_capacity_20').attr("disabled", true);

                    $('#gross_capacity_4').val(data['grossCapacity4']);
                    $('#gross_capacity_4').attr("disabled", true);

                    $('#gross_capacity_20').val(data['grossCapacity20']);
                    $('#gross_capacity_20').attr("disabled", true);

                    $('#asset_dimension_height').val(data['assetDimensionHeight']);
                    $('#asset_dimension_height').attr("disabled", true);

                    $('#asset_dimension_width').val(data['assetDimensionWidth']);
                    $('#asset_dimension_width').attr("disabled", true);

                    $('#asset_dimension_length').val(data['assetDimensionLength']);
                    $('#asset_dimension_length').attr("disabled", true);

                    $('#ccm_make_id').val(ccm_make_id);
                    $('#ccm_make_id_hidden').val(ccm_make_id);
                    $('#ccm_make_id').attr("disabled", true);


                    //Show the required Divs
                    $('#div_make').show('slow');
                    $('#div_model').show('slow');
                    $('#div_asset_type_ccf_free').show('slow');
                    $('#div_dimension_capacity').show('slow');
                    //Hide Loaders
                    $('#loader_catalogue_id').hide();
                    $('#loader_make').hide();
                }
            });
        } else {
            $('#div_make').hide('slow');
            $('#div_model').hide('slow');
            $('#div_asset_type_ccf_free').hide('slow');
            $('#div_dimension_capacity').hide('slow');
        }
    }
  
});

