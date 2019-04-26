var initTable2 = function () {
    var oTable = $('#priority-vaccine-distribution').dataTable({
        "aoColumnDefs": [
            {"sType": 'date-uk', "aTargets": [1]},
            {"bSortable": false, "aTargets": [0]}
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

    jQuery('#priority-vaccine-distribution_wrapper .dataTables_filter input').addClass("form-control input-small input-inline"); // modify table search input
    jQuery('#priority-vaccine-distribution_wrapper .dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
    jQuery('#priority-vaccine-distribution_wrapper .dataTables_length select').select2(); // initialize select2 dropdown

    $('#priority-vaccine-distribution_column_toggler input[type="checkbox"]').change(function () {
        /* Get the DataTables object again - this is not a recreation, just a get of the object */
        var iCol = parseInt($(this).attr("data-column"));
        var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
        oTable.fnSetColumnVis(iCol, (bVis ? false : true));
    });
}

initTable2();




$("input[type=radio]").change(function (e) {
    var option = $(this).val();
    distribution(option);
});

distribution(2);

function distribution(option) {
    Metronic.startPageLoading('Please wait...');
    $.ajax({
        type: "POST",
        url: appName + "/stock/ajax-priority-vaccine-distribution",
        data: {detail_summary: option},
        dataType: 'html',
        success: function (data) {
            $('#priority-vaccine').html(data);
            $("#priority-vaccine-distribution").floatThead();
            Metronic.stopPageLoading();

            $('#print').click(function () {
                window.open('priority-vaccines-distribution-print', '_blank', 'scrollbars=1,width=860,height=595');
            }
            );
        }
    });
}
 