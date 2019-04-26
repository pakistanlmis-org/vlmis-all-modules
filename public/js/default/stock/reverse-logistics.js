/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(function () {
    $("#expiry_date").datepicker({
        minDate: 0,
        maxDate: "+10Y",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });

    $("#date").datepicker({
        minDate: "-1Y",
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });

    $('#batch').change(function () {
        $('#expiry_date').val();
        $('#expiry_date').removeAttr('disabled');
    });

    $("#batch").autocomplete({
        source: function (request, response) {
            $.getJSON(appName + "/ajax/ajax-get-all-batches", {item_id: $('#product').val(), batch: $("#batch").val()}, response);
        },
        select: function (event, ui) {
            $.ajax({
                type: "POST",
                url: appName + "/ajax/ajax-get-batch-expiry",
                data: {item_id: $('#product').val(), number: ui.item.value},
                dataType: 'json',
                success: function (data) {
                    $('#expiry_date').val(data.expiry);
                    $('#batch_id').val(data.batch_id);
                    $('#expiry_date').attr('disabled', 'disabled');
                }
            });
        }
    });

    $('#expiry_date').mask('00/00/0000');

    $.validator.addMethod('positiveNumber',
            function (value) {
                return Number(value) > 0;
            }, 'Enter a positive number.');

    $("#reverse_batch").validate({
        rules: {
            healthfacilities: {
                required: true
            },
            product: {
                required: true
            },
            batch: {
                required: true
            },
            expiry_date: {
                required: true
            },
            quantity: {
                required: true,
                positiveNumber: true
            }
        },
        messages: {
            healthfacilities: {
                required: "Please select Health Facilities"
            },
            product: {
                required: "Please select Product"
            },
            batch: {
                required: "Please enter batch number"
            },
            expiry_date: {
                required: "Please enter expiry date"
            },
            quantity: {
                required: "Please enter quantity"
            }
        }
    });
});