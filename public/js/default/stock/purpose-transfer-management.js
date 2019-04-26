$(function () {

    $("#transfer_date").datepicker({
        minDate: "-3Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });

    $("#batch").select2();

    $("#product").change(function () {
        Metronic.startPageLoading('Please wait...');
        $("#batch").select2("val", "");
        $('#transfer-management-div').hide();
        $('#history-div').hide();
        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-running-batches",
            data: {item_id: $('#product').val(), page: 'vvm-management', transaction_date: $('#today').val()},
            dataType: 'html',
            success: function (data) {
                $('#batch').html(data);
                $("#batch").select2("val", $("#sel_batch").val());
                Metronic.stopPageLoading();
            }
        });
    });

    if ($("#product").val() != '') {
        $("#product").trigger("change");
        $('#transfer-management-div').show();
        $('#transfer-management-msg-div').show();
        $('#history-div').show();
    } else
    {
        $('#transfer-management-msg-div').hide();
    }

    $("#batch").change(function () {
        $('#transfer-management-div').hide();
        $('#transfer-management-msg-div').hide();
        $('#history-div').hide();
    });

    $("#transfer-management-filter").validate({
        rules: {
            product: {
                required: true
            },
            batch: {
                required: true
            }
        },
        messages: {
            product: {
                required: "Please select product."
            },
            batch: {
                required: "Please select batch number."
            }
        }
    });

    $("#updatetransfer").click(function () {

        var form = true;
        var valid = 0;

        $(document).find("input[id$='_quantity']").each(function () {

            if ($(this).val() != "" && $(this).val() != 0) {
                valid += 1;
            }
        });


        if (valid == 0) {
            alert('You must input at least one quantity to proceed!');
            form = false;
        }

        $("select[id$='_purpose']").each(function () {
            var value = $(this).attr("id");
            var id = value.replace("_purpose", "");
            var qty = parseInt($("#" + id + "_quantity").val());

            var purpose = $(this).val();
            if (purpose == '' && qty > 0) {
                alert("Purpose should be selected");
                $("#" + id + "_purpose").focus();
                form = false;
            }
        });

        $("select[id$='_toproduct']").each(function () {
            var value = $(this).attr("id");
            var id = value.replace("_toproduct", "");
            var qty = parseInt($("#" + id + "_quantity").val());

            var product = $(this).val();

            if (product == '' && qty > 0) {
                alert("Product should be selected");
                $("#" + id + "_toproduct").focus();
                form = false;
            }
            if (product == null) {
                alert("Product should be selected");
                $("#" + id + "_toproduct").focus();
                form = false;
            }

            if (isNaN(product)) {
                alert("Product should be selected");
                $("#" + id + "_quantity").focus();
                form = false;
            }
        });

        $("input[id$='_quantity']").each(function () {
            var value = $(this).attr("id");
            var id = value.replace("_quantity", "");
            var quantity = parseInt($(this).val());
            var placed_qty = $("#" + id + "_placed_quantity").html();
            var placed_qty1 = parseInt(placed_qty.replace(",", ""));

            if (quantity == '' && quantity == 0) {
                alert("Quantity should be greater than 0");
                $("#" + id + "_quantity").focus();
                form = false;
            }
//            if (isNaN(quantity)) {
//                alert("Quantity should be empty");
//                $("#" + id + "_quantity").focus();
//                form = false;
//            }

            if (quantity > placed_qty1) {
                alert("Quantity should be less than or equal to " + placed_qty);
                $("#" + id + "_quantity").focus();
                form = false;
            }
        });

        if (form) {
            $("#transfer-management").submit();
        }

    });

    $("select[id$='_purpose']").change(function () {
        var value = $(this).attr("id");
        var id = value.replace("_purpose", "");
        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-get-products-by-purpose",
            data: {
                params: value, purpose: $(this).val()
            },
            dataType: 'html',
            success: function (data) {
                $('#' + id + '_toproduct').html(data);
            }
        });
    });

    $('.quantity').mask("9999999999");


    var initTable20 = function () {
        var oTable20 = $('#sample_20').dataTable({
            "bInfo": false,
            "aoColumnDefs": [
                {"sType": 'date-uk', "aTargets": [0]},
                {"bSortable": false, "aTargets": [0]},
                {"sWidth": "30%", "aTargets": [5]}
                /*{
                 "aTargets": [-1],
                 "bVisible": false
                 }*/
            ],
            "aaSorting": [[0, 'asc']],
            "aLengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "All"] // change per page values here
            ],
            "sDom": "<'row'<'col-md-4 col-sm-12'l><'col-md-4 col-sm-12'T><'col-md-4 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
            //"sDom ": 'T<"clear ">lfrtip',
            // set the initial value
            "bDestroy": true,
            "iDisplayLength": 10,
            "oTableTools": {
                "sSwfPath": appName + "/common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf",
                "aButtons": [{
                        "sExtends": "xls",
                        "sButtonText": "<img src=" + appName + "/images/excel-32.png width=24> Export to Excel",
                        "mColumns": "sortable"
                    },
                    {
                        "sExtends": "copy",
                        "sButtonText": "<img src=" + appName + "/images/copy.png width=24> Copy Data",
                        "mColumns": "sortable"
                    }]
            }
        });

        jQuery('#sample_20_wrapper .dataTables_filter input').addClass("form-control input-small input-inline"); // modify table search input
        jQuery('#sample_20_wrapper .dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
        jQuery('#sample_20_wrapper .dataTables_length select').select2(); // initialize select2 dropdown

        $('#sample_20_column_toggler input[type="checkbox"]').change(function () {
            /* Get the DataTables object again - this is not a recreation, just a get of the object */
            var iCol = parseInt($(this).attr("data-column"));
            var bVis = oTable20.fnSettings().aoColumns[iCol].bVisible;
            oTable20.fnSetColumnVis(iCol, (bVis ? false : true));
        });
    };

    initTable20();

});