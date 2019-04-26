$(function () { 
    $('#date_to').css('position','relative');
    $('#date_to').css('zIndex',3000);
    
    $('#item_id').change(function () {
        $("#available_quantity").val('');
        $("#expiry_date").val('');
        $('#number').html('');
        $('#product-unit').html('');
        var activity_id = $('#activity_id').val();

        if ($(this).val() != "") {
            $.ajax({
                type: "POST",
                url: appName + "/stock-batch/ajax-running-batches",
                data: {item_id: $(this).val()},
                dataType: 'html',
                success: function (data) {
                    $('#number').html(data);
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
                url: appName + "/stock-batch/ajax-product-category",
                data: {
                    item_id: $(this).val()
                },
                dataType: 'html',
                success: function (data) {
                    if (data == '2') {
                        $("#expiry_div").hide();
                    } else {
                        $("#expiry_div").show();
                    }
                }
            });
            if (activity_id == 2) {
                $.ajax({
                    type: "POST",
                    url: appName + "/stock/ajax-get-campaigns-by-product",
                    data: {
                        item_id: $(this).val()
                    },
                    dataType: 'html',
                    success: function (data) {
                        $('#campaign_id').html(data);
                        $('#campaign_id').css('backgroundColor', 'Green');
                        $.cookie('blink_div_background_color', "campaign_id");
                        setTimeout(changeColor, 500);
                    }
                });
            }
        }
    });

    var startDateTextBox = $('#date_from');
    var endDateTextBox = $('#date_to');

    startDateTextBox.datepicker({
        minDate: "01/01/2013",
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        onClose: function (dateText, inst) {
            if (endDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datepicker('getDate');
                var testEndDate = endDateTextBox.datepicker('getDate');
                if (testStartDate > testEndDate)
                    endDateTextBox.datepicker('setDate', testStartDate);
            }
            else {
                endDateTextBox.val(dateText);
            }
        },
        onSelect: function (selectedDateTime) {
            endDateTextBox.datepicker('option', 'minDate', startDateTextBox.datepicker('getDate'));
        }
    });
    endDateTextBox.datepicker({
        minDate: "01/01/2013",
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        onClose: function (dateText, inst) {
            if (startDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datepicker('getDate');
                var testEndDate = endDateTextBox.datepicker('getDate');
                if (testStartDate > testEndDate)
                    startDateTextBox.datepicker('setDate', testEndDate);
            }
            else {
                startDateTextBox.val(dateText);
            }
        },
        onSelect: function (selectedDateTime) {
            startDateTextBox.datepicker('option', 'maxDate', endDateTextBox.datepicker('getDate'));
        }
    });


    /* DataTables */
//    if ($('.issueSearch').size() > 0)
//    {
//        var datatable = $('.issueSearch').dataTable({
//            "sPaginationType": "bootstrap",
//            //"sDom": 'W<"clear">lfrtip',
//         //   "sDom": '<"clear">lfrtipT',
//          "sDom": 'T<"clear">lfrtip',
//            "oLanguage": {
//                "sLengthMenu": "_MENU_ records per page"
//            },
//            "oColumnFilterWidgets": {
//                "aiExclude": [0, 5, 6, 7, 8, 9]
//            },
//            "oTableTools": {
//                "aButtons": [
//                    {
//                        "sExtends": "copy",
//                        "sButtonText": "Copy",
//                        "mColumns": [0, 1, 2, 3, 4, 5, 6, 7, 8]
//                    }
//                ],
//                "sSwfPath": appName +"/common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf"
//            }
//
//        });
//
//    }
});

$('#print_vaccine_issue').click(
        function () {
            var searchby, number, warehouses, product, date_from, date_to, all_arguments;
            searchby = $('#searchby').val();
            number = $('#number').val();
            warehouses = $('#warehouses').val();
            product = $('#product').val();
            date_from = $('#date_from').val();
            date_to = $('#date_to').val();
            activity_id = $('#activity_id').val();
            all_arguments = "searchby=" + searchby + "&number=" + number + "&warehouses=" + warehouses + "&product=" + product + "&date_from=" + date_from + "&date_to=" + date_to + "&activity_id=" + activity_id;

            var val = $('input[name="groupBy"]:checked').val();
            window.open('vaccine-placement-issue?grpBy=' + val + '&' + all_arguments, '_blank', 'scrollbars=1,width=860,height=595');
        }
);
$('#print_vaccine_summary').click(
        function () {

            var searchby, number, warehouses, product, date_from, date_to, all_arguments;
            searchby = $('#searchby').val();
            number = $('#number').val();
            warehouses = $('#warehouses').val();
            product = $('#product').val();
            date_from = $('#date_from').val();
            date_to = $('#date_to').val();
            activity_id = $('#activity_id').val();
            all_arguments = "searchby=" + searchby + "&number=" + number + "&warehouses=" + warehouses + "&product=" + product + "&date_from=" + date_from + "&date_to=" + date_to + "&activity_id=" + activity_id;


            var val = $('input[name="summary"]:checked').val();
            window.open('vaccine-placement-issue-summary?type=' + val + '&' + all_arguments, '_blank', 'scrollbars=1,width=860,height=595');
        }
);
$('[data-toggle="notyfy"]').click(function () {
    $.notyfy.closeAll();
    var self = $(this);

    notyfy({
        text: notification[self.data('type')],
        type: self.data('type'),
        dismissQueue: true,
        layout: self.data('layout'),
        buttons: (self.data('type') != 'confirm') ? false : [
            {
                addClass: 'btn btn-success btn-small btn-icon glyphicons ok_2',
                text: '<i></i> Ok',
                onClick: function ($notyfy) {
                    Metronic.startPageLoading('Please wait...');
                    var id = self.attr("id");
                    $notyfy.close();
                    $.ajax({
                        type: "POST",
                        url: appName + "/stock/delete-issue",
                        data: {p: 'stock', id: id},
                        success: function (data) {
                            Metronic.stopPageLoading();
                            if (data == 1) {
                                self.closest("tr").remove();
                                notyfy({
                                    force: true,
                                    text: '<strong>Deleted successfully!<strong>',
                                    type: 'success',
                                    layout: self.data('layout')
                                });
                            } else {
                                notyfy({
                                    force: true,
                                    text: '<strong>An error occur! Try later.<strong>',
                                    type: 'error',
                                    layout: self.data('layout')
                                });
                            }
                        }
                    });

                    //window.location.href = appName + '/stock/delete-issue?p=stock&id=' + id;
                }
            },
            {
                addClass: 'btn btn-danger btn-small btn-icon glyphicons remove_2',
                text: '<i></i> Cancel',
                onClick: function ($notyfy) {
                    $notyfy.close();
                    /*   notyfy({
                     force: true,
                     text: '<strong>You clicked "Cancel" button<strong>',
                     type: 'error',
                     layout: self.data('layout')
                     });*/
                }
            }
        ]
    });
    return false;
});

var notification = [];
notification['confirm'] = 'Do you want to continue?';

$('#reset').click(function () {
    window.location.href = appName + '/stock/issue-search';
});

$("a[id$='-stockedit']").click(function () {
    var value = $(this).attr("id");
    var id = value.replace("-stockedit", "");
    window.location = appName + "/stock/issue/id/" + id + "/t/s";
});

// validate signup form on keyup and submit
$("#issue_form").validate({
    rules: {
        item_id: {
            required: true
        },
        number: {
            required: true
        },
        quantity: {
            required: true
        }
    },
    messages: {
        item_id: {
            required: "Please select product"
        },
        number: {
            required: "Please enter batch number"
        },
        quantity: {
            required: "Please enter quantity"
        }
    }
});

$('#quantity, #item_id').change(function (e) {

    var quantity = $('#quantity').val();
    var itemId = $('#item_id').val();

    $('#product-doses').css('display', 'none');

    if (quantity != 0 && quantity != '' && itemId != '')
    {
        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-product-cat-and-doses",
            data: 'quantity=' + quantity + '&itemId=' + itemId,
            success: function (doses) {
                if (doses != '')
                {
                    $('#product-doses').css('display', 'table-row');
                    $('#product-doses').html(doses);
                }
            }
        });
    }
});

$("#add_issue").click(function (e) {
    e.preventDefault();
    var validator = $("#issue_form").validate();
    var aval_qty = $("#available_quantity").val();
    var quantity = $("#quantity").val();
    aval_qty = aval_qty.replace(/,/g, "");
    quantity = quantity.replace(/,/g, "");
    if (parseInt(quantity) <= 0) {
        alert("Quantity should greater then 0");
        validator.showErrors({
            "quantity": "Quantity should greater then 0"
        });
    } else if (parseInt(aval_qty) < parseInt(quantity)) {
        alert("Quantity not available");
        validator.showErrors({
            "quantity": "Quantity not available"
        });
    } else {
        $("#issue_form").submit();
    }
});

$('#item_id').change(function () {
    $("#available_quantity").val('');
    $("#expiry_date").val('');
    $('#number').html('');
    $('#product-unit').html('');

    if ($(this).val() != "") {
        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-running-batches",
            data: {item_id: $(this).val()},
            dataType: 'html',
            success: function (data) {
                $('#number').html(data);
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
            url: appName + "/stock-batch/ajax-product-category",
            data: {
                item_id: $(this).val()
            },
            dataType: 'html',
            success: function (data) {
                if (data == '2') {
                    $("#expiry_div").hide();
                } else {
                    $("#expiry_div").show();
                }
            }
        });
    }
});

$("#transaction_date").datepicker({
    dateFormat: 'dd/mm/yy',
    constrainInput: false,
    changeMonth: true,
    changeYear: true
});

$("#whchange").click(function () {
    $("#whcancel").show();
    $("#whchange").hide();
    $("#wh_change_div").show();
});

$("#whcancel").click(function () {
    $("#whchange").show();
    $("#whcancel").hide();
    $("#wh_change_div").hide();
});

$('#quantity').priceFormat({
    prefix: '',
    thousandsSeparator: ',',
    suffix: '',
    centsLimit: 0,
    limit: 10
});

$(".delete-issue").click(function () {
    $('#modal-body-contents').html("<div style='text-align: center; '><img src='" + appName + "/images/loader.gif' style='margin:30px;'  /></div>");
    $.ajax({
        type: "POST",
        url: appName + "/stock/ajax-get-placement-locations",
        data: {p: 'stock', id: $(this).attr('id')},
        dataType: 'html',
        success: function (data) {
            $('#modal-body-contents').html(data);
            //validation
            $("#form_delete_issue").validate({
                rules: {
                    'loc-id': {
                        required: true
                    }
                },
                messages: {
                    'loc-id': {
                        required: "Please select location"
                    }
                }
            });

        }
    });
});

$("#delete-issue").click(function (e) {

    Metronic.startPageLoading('Please wait...');
    e.preventDefault();
    var flag = true;

    $('#loc-id').val();

    if ($('#loc-id').val() == "") {
        alert("Please select location.");
        $(this).focus();
        flag = false;
        return false;
    }
    else{
        flag = true;
    }
    if (flag == true) {
        $("#form_delete_issue").submit();
    } else {
        Metronic.stopPageLoading();
    }

});

