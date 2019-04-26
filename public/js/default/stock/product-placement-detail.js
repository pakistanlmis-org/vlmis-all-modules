var batchManagement = function() {
    var initTable2 = function() {
        var oTable = $('#sample_2').dataTable({
            "aoColumnDefs": [
                {"sType": 'date-uk', "aTargets": [0]}
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
                        "sFileName": " *.csv",
                        "sButtonText": "<img src=" + appName + "/images/excel-32.png width=24> Export to Excel",
                        "mColumns": [0, 1, 2, 3, 4, 5] //set which columns here
                    },
                    {
                        "sExtends": "copy",
                        "sButtonText": "<img src=" + appName + "/images/copy.png width=24> Copy Data",
                        "mColumns": [0, 1, 2, 3, 4, 5] //set which columns here
                    }
                ]
            }
        });

        jQuery('#sample_2_wrapper .dataTables_filter input').addClass("form-control input-small input-inline"); // modify table search input
        jQuery('#sample_2_wrapper .dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
        jQuery('#sample_2_wrapper .dataTables_length select').select2(); // initialize select2 dropdown

        $('#sample_2_column_toggler input[type="checkbox"]').change(function() {
            /* Get the DataTables object again - this is not a recreation, just a get of the object */
            var iCol = parseInt($(this).attr("data-column"));
            var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
            oTable.fnSetColumnVis(iCol, (bVis ? false : true));
        });
    };

    initTable2();
}

$("#product_plac").validate({
    rules: {
        product: {
            required: true
        }
    },
    messages: {
        product: {
            required: "Please select product"
        }
    }
});

$('#submit').click(function(e) {
    if ($("#product_plac").valid()) {
        e.preventDefault();
        var formdata = $("#product_plac").serialize();

        Metronic.startPageLoading('Please wait...');
        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-product-placement-detail?" + formdata,
            data: {},
            dataType: 'html',
            success: function(data) {
                $('#product_placement').html(data);
                Metronic.stopPageLoading();
                batchManagement();

                $("#ToolTables_product_placement_detail_1").click(function() {
                    window.print();
                });
            }
        });
    }

});