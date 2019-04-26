

var initTable2 = function () {
    var oTable = $('#physicalStock').dataTable({
        "aoColumnDefs": [
            {"sType": 'date-uk', "aTargets": [1]},
            {"bSortable": false, "aTargets": [0,1,2,3,4,5,6]}
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
        "sDom": "<'row'<'col-md-12 col-sm-12 right'T>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
        //"sDom ": 'T<"clear ">lfrtip',
        // set the initial value
        "bDestroy": true,
        "iDisplayLength": 1000,
        "oTableTools": {
            "sSwfPath": appName + "/common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf",
            "aButtons": [{
                    "sExtends": "xls",
                    "sButtonText": "<img src=../images/excel-32.png width=24> Export to Excel"
                }, {
                    "sExtends": "copy",
                    "sButtonText": "<img src=../images/copy.png width=24> Copy Table"
                }]
        }
    });

    jQuery('#physicalStock_wrapper .dataTables_filter input').addClass("form-control input-small input-inline"); // modify table search input
    jQuery('#physicalStock_wrapper .dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
    jQuery('#physicalStockr_wrapper .dataTables_length select').select2(); // initialize select2 dropdown

    $('#physicalStock_column_toggler input[type="checkbox"]').change(function () {
        /* Get the DataTables object again - this is not a recreation, just a get of the object */
        var iCol = parseInt($(this).attr("data-column"));
        var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
        oTable.fnSetColumnVis(iCol, (bVis ? false : true));
    });
}

$("#ledger").validate({
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

$('#submit').click(function (e) {
    if ($("#ledger").valid()) {
        e.preventDefault();
        var formdata = $("#ledger").serialize();
        Metronic.startPageLoading('Please wait...');
        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-physical-stock-taking-report",
            data: {data: formdata},
            dataType: 'html',
            success: function (data) {
                $('#product_ledger').html(data);
                Metronic.stopPageLoading();
initTable2();
            }
        });
    }

});


