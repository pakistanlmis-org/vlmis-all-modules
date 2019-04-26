$(function() {
    
    $('#office').change(function() {
        $('#transfer-asset-ajax-data').html('');
        $("#to_office").hide();
        
        
        
    });
    $('#combo1').change(function() {
        $('#transfer-asset-ajax-data').html('');
        $("#to_office").hide();
    });

    $('#warehouse').change(function() {
        if ($(this).val() != "") {
            $.ajax({
                type: "POST",
                url: appName + "/cold-chain/ajax-transfer-asset",
                data: {warehouse: $(this).val()},
                dataType: 'html',
                success: function(data) {
                    if (data == "No asset found in the selected store") {
                        $("#to_office").hide();
                    } else {
                        $("#to_office").show();
                    }
                    $('#transfer-asset-ajax-data').html(data);
                }
            });
        } else {
            $('#transfer-asset-ajax-data').html('');
            $("#to_office").hide();
        }
    });
});

$('#warehouse2').change(function() {
    if ($(this).val() != "") {
        $('#transfer_asset').show();
        $('#transfer_button').show();
    }
});

// validate signup form on keyup and submit
$("#transfer_asset").validate({
    rules: {
        'item_id': {
            required: true
        },
        'warehouse2': {
            required: true
        }
    },    
    messages: {
        'item_id': {
            required: "Please select assets to transfer"
        },
        'warehouse2': {
            required: "Please select store to which transfer the selected assets"
        }
    }
});


