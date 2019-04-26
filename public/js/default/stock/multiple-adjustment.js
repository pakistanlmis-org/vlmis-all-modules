$(function () {
    $("#batch_no").select2();
    $('#available_div').hide();
    $('#product').change(function () {
        $("#batch_no").select2("val", "");
        $("#batch_no").empty();
        $("#location_id").empty();
        $("#available").val('');

        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-running-batches",
            data: {item_id: $('#product').val(), page: "adjustment", adjustment_type: $('#adjustment_type').val()},
            dataType: 'html',
            success: function (data) {
                $('#batch_no').html(data);
            }
        });

        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-product-batches",
            data: {item_id: $(this).val()},
            dataType: 'html',
            success: function (data) {
                $('#product-unit').html(data);
            }
        });

        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-product-vvm-stages",
            data: {product: $(this).val()},
            dataType: 'html',
            success: function (data) {
                $('#vvm_stage').html(data);
            }
        });
    });

    $("#btn-new-batch").click(function(){
        $("#new-batch-form").find("input[type=text], select").val("");
    });
    
    $("#adjustment_type").change(function () {
        $('#location_id').val("");
        $("#batch_no").select2("val", "");
        $('#product').val("");

        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-running-batches",
            data: {item_id: $('#product').val(), page: "adjustment", adjustment_type: $('#adjustment_type').val()},
            dataType: 'html',
            success: function (data) {
                $('#batch_no').html(data);
            }
        });

        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-check-adjustment-type",
            data: {type: $('#adjustment_type').val()},
            dataType: 'html',
            success: function (data) {
                if (data == 'negative') {
                    $('#batch_button_div').hide();
                    $('#batch_no_div').removeClass('col-md-2');
                    $('#batch_no_div').addClass('col-md-3');
                } else {
                    $('#batch_no_div').removeClass('col-md-3');
                    $('#batch_no_div').addClass('col-md-2');
                    $('#batch_button_div').show();
                }
            }
        });

        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-get-batch-locations",
            data: {batch_id: $("#batch_no").val(), item_id: $('#product').val(), type: $('#adjustment_type').val()},
            dataType: 'html',
            success: function (data) {
                var type = $('#adjustment_type').val();
                if(type == 8 || type == 12 || type == 15) {
                    $("#loc_vvm_qty").html("Location");
                } else {
                    $("#loc_vvm_qty").html("Location | VVM | Quantity");
                }
                $('#location_id').html(data);
            }
        });
    });

    $("#batch_no").change(function () {
        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-get-batch-locations",
            data: {batch_id: $("#batch_no").val(), item_id: $('#product').val(), type: $('#adjustment_type').val()},
            dataType: 'html',
            success: function (data) {
                var type = $('#adjustment_type').val();
                if(type == 8 || type == 12 || type == 15){
                    $("#loc_vvm_qty").html("Location");
                } else {
                    $("#loc_vvm_qty").html("Location | VVM | Quantity");
                }
                $('#location_id').html(data);
            }
        });
    });

    $('#quantity').priceFormat({
        prefix: '',
        thousandsSeparator: ',',
        suffix: '',
        centsLimit: 0,
        limit: 10
    });

    /*$('#quantity').keyup(function (e) {
     var ava_qty = $("#available").val();
     ava_qty = parseInt(ava_qty.replace(/,/g, ""), 10);
     
     var qty = $(this).val();
     qty = parseInt(qty.replace(/,/g, ""), 10);
     
     if (qty > ava_qty) {
     alert("Quantity should not be greater than " + ava_qty + ".");
     $(this).focus();
     }
     });*/

    /*$('#batch_search').submit(function (e) {
     var ava_qty = $("#available").val();
     ava_qty = parseInt(ava_qty.replace(/,/g, ""), 10);
     
     var qty = $('#quantity').val();
     qty = parseInt(qty.replace(/,/g, ""), 10);
     
     if (qty > ava_qty) {
     e.preventDefault();
     alert("Quantity should not be greater than " + ava_qty + ".");
     $('#quantity').focus();
     }
     });*/

    $('#batch_no').change(function () {
        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-running-batches",
            data: {number: $('#batch_no').val(), page: "adjustment"},
            dataType: 'html',
            success: function (data) {
                $('#available_div').fadeIn(1000);
                $('#itembatches').html(data);
            }
        });
    });

    $('#transaction_date').datepicker({
        minDate: "-2Y",
        maxDate: 0,
        dateFormat: "dd/mm/yy",
        constrainInput: false,
        changeMonth: true,
        changeYear: true
    });

    $('#transaction_date').mask('00/00/0000');

    /*$("#batch").autocomplete({
     source: function (request, response)
     {
     $.ajax({
     url: appName + "/stock-batch/ajax-get-existing-batches",
     dataType: "json",
     data: {term: request.term, product: $("#product_id").val()},
     success: function (data) {
     response(data);
     }
     });
     },
     appendTo : $("#new-batch-form"),
     select: function( event, ui ) {}
     });*/

});

$("#product").change(function () {

    var item_id = $('#product').val();

    $.ajax({
        type: "POST",
        url: appName + "/stock/ajax-check-product-category",
        data: {
            item_id: item_id
        },
        dataType: 'html',
        success: function (data) {
            var adj_t = $('#adjustment_type').val();
            if (data == 1) {
                //  $("#location_vvm_quantity").show();
                $("#location_vvm_quantity").css('visibility', 'visible');
                if (adj_t == 8 || adj_t == 12 || adj_t == 15 || adj_t == 16) {
                    $("#vvm_stage_div").show();
                } else {
                    $("#vvm_stage_div").hide();
                }
                //$("#old_vvm_div").show();
            } else {
                $("#location_vvm_quantity").css('visibility', 'hidden');
                $("#vvm_stage_div").hide();
                //$("#old_vvm_div").hide();
                // $("#location_vvm_quantity").hide();
            }
        }
    });
});


$("#batch_search").validate({
    rules: {
        product: {
            required: true
        },
        batch_no: {
            required: true
        },
        quantity: {
            required: true
        },
        adjustment_type: {
            required: true
        },
        vvm_stage: {
            required: true
        },
        location_id: {
            required: true
        }
    },
    messages: {
        product: {
            required: "Please select product."
        },
        batch_no: {
            required: "Please select batch number."
        },
        quantity: {
            required: "Please enter quantity."
        },
        adjustment_type: {
            required: "Please select adjustment type."
        },
        vvm_stage: {
            required: "Please select VVM stage."
        },
        location_id: {
            required: "Please select batch location."
        }
    }
});

$("#new-batch-form").validate({
    rules: {
        product_id: {
            required: true
        },
        batch: {
            required: true
        },
        manufacturer: {
            required: true
        },
        expiry_date: {
            required: true
        },
        vvm_type_id: {
            required: true
        }
    },
    messages: {
        product_id: {
            required: "Please select product."
        },
        batch: {
            required: "Please enter batch number."
        },
        manufacturer: {
            required: "Please select manufacturer."
        },
        expiry_date: {
            required: "Please select expiry date."
        },
        vvm_type_id: {
            required: "Please select vvm type."
        }
    }
});

$('#modal').on('show', function () {
    $.fn.modal.Constructor.prototype.enforceFocus = function () {
    };
});

$('#expiry_date').datepicker({
    minDate: 0,
    maxDate: "+10Y",
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true
});
$('#production_date').datepicker({
    minDate: "-3Y",
    maxDate: 0,
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true
});

$("#product_id").change(function () {
    $.ajax({
        type: "POST",
        url: appName + "/stock/ajax-get-manufacturer-by-product",
        data: {
            item_id: $(this).val()
        },
        dataType: 'html',
        success: function (data) {
            $('#manufacturer').html(data);
        }
    });
});

$("#add-new-batch").click(function (e) {
    if ($("#new-batch-form").valid()) {
        Metronic.startPageLoading('Please wait...');
        $.ajax({
            type: "POST",
            url: appName + "/stock/add-new-batch",
            data: $("#new-batch-form").serialize(),
            dataType: 'html',
            success: function (data) {
                $.ajax({
                    type: "POST",
                    url: appName + "/stock-batch/ajax-running-batches",
                    data: {item_id: $('#product').val(), page: "adjustment"},
                    dataType: 'html',
                    success: function (data) {
                        $('#batch_no').html(data);
                    }
                });
                $(".close").trigger("click");
                Metronic.stopPageLoading();
            }
        });
    }
});

$('#batch').keyup(function () {
    $(this).val($(this).val().toUpperCase());
});

$("#btn-loading").click(function (e) {
    e.preventDefault();
    var validator = $("#batch_search").validate();
    if ($("#batch_search").valid()) {
        if ($("#quantity").val() <= 0) {
            validator.showErrors({
                "quantity": "Quantity should greater then 0."
            });
        } else {
            $("#batch_search").submit();
        }
    }
});

$("#reset").click(function () {
    $('#available_div').fadeOut(1000);
    $('#batch_no').empty();
});