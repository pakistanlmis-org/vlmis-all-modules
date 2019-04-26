$(function () {


    var initTable20 = function () {
        var oTable20 = $('#sample_20').dataTable({
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
            "sDom": "<'row'<'col-md-3 col-sm-12'l><'col-md-6 col-sm-12'T><'col-md-3 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
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
                        "sExtends": "pdf",
                        "sFileName": "vLMIS - User Feedback.pdf",
                        "sTitle": "vLMIS - User Feedback",
                        "sButtonText": "<img src=" + appName + "/images/pdf-32.png width=24> Export to Pdf",
                        "mColumns": [0, 1, 2, 3, 4, 5, 6] //set which columns here
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