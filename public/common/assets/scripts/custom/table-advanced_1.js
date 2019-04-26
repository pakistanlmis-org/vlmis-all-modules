var TableAdvanced = function () {
    var initTable1 = function () {

        /* Formatting function for row details */
        function fnFormatDetails(oTable, nTr)
        {
            var aData = oTable.fnGetData(nTr);
            var sOut = '<table>';
            sOut += '<tr><td>Platform1(s):</td><td>' + aData[2] + '</td></tr>';
            sOut += '<tr><td>Engine version:</td><td>' + aData[3] + '</td></tr>';
            sOut += '<tr><td>CSS grade:</td><td>' + aData[4] + '</td></tr>';
            sOut += '<tr><td>Others:</td><td>Could provide a link here</td></tr>';
            sOut += '</table>';

            return sOut;
        }

        /*
         * Insert a 'details' column to the table
         */
        var nCloneTh = document.createElement('th');
        var nCloneTd = document.createElement('td');
        nCloneTd.innerHTML = '<span class="row-details row-details-close"></span>';

        $('#sample_1 thead tr').each(function () {
            this.insertBefore(nCloneTh, this.childNodes[0]);
        });

        $('#sample_1 tbody tr').each(function () {
            this.insertBefore(nCloneTd.cloneNode(true), this.childNodes[0]);
        });

        /*
         * Initialize DataTables, with no sorting on the 'details' column
         */
        var oTable = $('#sample_1').dataTable({
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [0]}
            ],
            "aaSorting": [[0, 'asc']],
            "aLengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "All"] // change per page values here
            ],

            // set the initial value
            "iDisplayLength": 10,
        });

        jQuery('#sample_1_wrapper .dataTables_filter input').addClass("form-control input-small input-inline"); // modify table search input
        jQuery('#sample_1_wrapper .dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
        jQuery('#sample_1_wrapper .dataTables_length select').select2(); // initialize select2 dropdown

        /* Add event listener for opening and closing details
         * Note that the indicator for showing which row is open is not controlled by DataTables,
         * rather it is done here
         */
        $('#sample_1').on('click', ' tbody td .row-details', function () {
            var nTr = $(this).parents('tr')[0];
            if (oTable.fnIsOpen(nTr))
            {
                /* This row is already open - close it */
                $(this).addClass("row-details-close").removeClass("row-details-open");
                oTable.fnClose(nTr);
            } else
            {
                /* Open this row */
                $(this).addClass("row-details-open").removeClass("row-details-close");
                oTable.fnOpen(nTr, fnFormatDetails(oTable, nTr), 'details');
            }
        });
    }

    var initTable2 = function () {
        var oTable = $('#sample_2').dataTable({
            "aoColumnDefs": [
                {"sType": 'date-uk', "aTargets": [0]},
                {"bSortable": false, "aTargets": [-1]}
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
                    }, {
                        "sExtends": "copy",
                        "sButtonText": "<img src=" + appName + "/images/copy.png width=24> Copy Data",
                        "mColumns": "sortable"
                    }]
            }
        });

        jQuery('#sample_2_wrapper .dataTables_filter input').addClass("form-control input-small input-inline"); // modify table search input
        jQuery('#sample_2_wrapper .dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
        jQuery('#sample_2_wrapper .dataTables_length select').select2(); // initialize select2 dropdown

        $('#sample_2_column_toggler input[type="checkbox"]').change(function () {
            /* Get the DataTables object again - this is not a recreation, just a get of the object */
            var iCol = parseInt($(this).attr("data-column"));
            var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
            oTable.fnSetColumnVis(iCol, (bVis ? false : true));
        });
    }

    var initTable3 = function () {
        var oTable = $('#sample_3').dataTable({
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
                        "sButtonText": "<img src=" + appName + "/images/excel-32.png width=24> Export to Excel",
                        "mColumns": "sortable"
                    }, {
                        "sExtends": "copy",
                        "sButtonText": "<img src=" + appName + "/images/copy.png width=24> Copy Data",
                        "mColumns": "sortable"
                    }]
            }
        });

        jQuery('#sample_3_wrapper .dataTables_filter input').addClass("form-control input-small input-inline"); // modify table search input
        jQuery('#sample_3_wrapper .dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
        jQuery('#sample_3_wrapper .dataTables_length select').select2(); // initialize select2 dropdown

        $('#sample_3_column_toggler input[type="checkbox"]').change(function () {
            /* Get the DataTables object again - this is not a recreation, just a get of the object */
            var iCol = parseInt($(this).attr("data-column"));
            var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
            oTable.fnSetColumnVis(iCol, (bVis ? false : true));
        });
    }


    var initTablePR = function () {

        /* Formatting function for row details */
        function fnFormatDetails(oTable, nTr)
        {


            var aData = oTable.fnGetData(nTr);
            var listitems;
            var data = [{"pk_id": "93031", "asset_name": "CR04-WH1 (HURREE)"}, {"pk_id": "93032", "asset_name": "CR05-WH1 (ColdCraft)"}, {"pk_id": "93033", "asset_name": "CR06-WH1 (ColdCraft)"}, {"pk_id": "93034", "asset_name": "FR07-WH1 (HURREE)"}, {"pk_id": "93035", "asset_name": "CR08-WH1 (HURREE)"}, {"pk_id": "93036", "asset_name": "CR15-WH1 (HURREE)"}, {"pk_id": "93057", "asset_name": "CR17-WH1 (HURREE)"}, {"pk_id": "93058", "asset_name": "CR19-WH1 (HURREE)"}, {"pk_id": "93059", "asset_name": "CR21-WH1 (HURREE)"}, {"pk_id": "93060", "asset_name": "CR22-WH2 (HURREE)"}, {"pk_id": "93061", "asset_name": "CR23-WH2 (HURREE)"}, {"pk_id": "93062", "asset_name": "CR09-WH1 (HURREE)"}, {"pk_id": "93063", "asset_name": "CR24-WH2 (HURREE)"}, {"pk_id": "93064", "asset_name": "FR14-WH1 (HURREE)"}, {"pk_id": "93065", "asset_name": "CR25-WH2 (HURREE)"}, {"pk_id": "93066", "asset_name": "FR16-WH1 (HURREE)"}, {"pk_id": "93067", "asset_name": "CR26-WH2 (HURREE)"}, {"pk_id": "93068", "asset_name": "FR18-WH1 (HURREE)"}, {"pk_id": "93069", "asset_name": "CR28-WH2 (HURREE)"}, {"pk_id": "93070", "asset_name": "FR20-WH1 (HURREE)"}, {"pk_id": "93071", "asset_name": "CR29-WH2 (HURREE)"}, {"pk_id": "93072", "asset_name": "CR30-WH2 (HURREE)"}, {"pk_id": "93073", "asset_name": "CR31-WH2 (HURREE)"}, {"pk_id": "93074", "asset_name": "FR27-WH2 (HURREE)"}]
            var add_row = 3;

            $.each(data, function (i, item) {
                // value='+$('#0-' + aData[3]+'-quantity').val() + '

                if (data[i].pk_id == '93032')
                {
                    var selected = 'selected=selected';
                } else {
                    var selected = '';
                }

                listitems += '<option value=' + data[i].pk_id + '  ' + selected + '>' + data[i].asset_name + '</option>';
            });
           var  sel;
           
            for (i = 0; i < add_row; i++) {
                sel = '<select class="form-control" name=' + i + '-' + aData[3] + '-locations id=' + i + '-' + aData[3] + '-locations">' + listitems
                        + '</select>';
            }



            var sOut = '<table align=center>';
            sOut += '<thead><tr><th>Quantity</th><th>VVM Stage</th><th>Placement Locations</th></tr></thead>';
            sOut += '<tbody><tr><td><input class="form-control" type=text name=0-' + aData[3] + '-quantity id=0-' + aData[3] + '-quantity value=' + $('#0-' + aData[3] + '-quantity').val() + '></td><td><select class="form-control" name=0-' + aData[3] + '-vvm_stage id=0-' + aData[3] + '-vvm_stage><option value=1>1</option><option value=2>2</option></select></td><td>' + sel + '</td></tr>';
            sOut += '<tr><td><input class="form-control" type=text name=1-' + aData[3] + '-quantity id=1-' + aData[3] + '-quantity value=' + $('#1-' + aData[3] + '-quantity').val() + '></td><td><select class="form-control" name=1-' + aData[3] + '-vvm_stage id=1-' + aData[3] + '-vvm_stage><option value=1>1</option><option value=2>2</option></select></td><td>' + sel + '</td></tr>';
            sOut += '<tr><td><input class="form-control" type=text name=2-' + aData[3] + '-quantity id=2-' + aData[3] + '-quantity value=' + $('#2-' + aData[3] + '-quantity').val() + '></td><td><select class="form-control" name=2-' + aData[3] + '-vvm_stage id=2-' + aData[3] + '-vvm_stage><option value=1>1</option><option value=2>2</option></select></td><td>' + sel + '</td></tr></tbody>';

            sOut += '</table><a id="click" style="margin-left:850px;" href=#>Add row<a>';

            return sOut;
        }

        /*
         * Insert a 'details' column to the table
         */
        var nCloneTh = document.createElement('th');
        var nCloneTd = document.createElement('td');
        nCloneTd.innerHTML = '<span class="row-details row-details-close"></span>';

        $('#sample_PR thead tr').each(function () {
            this.insertBefore(nCloneTh, this.childNodes[0]);
        });

        $('#sample_PR tbody tr').each(function () {
            this.insertBefore(nCloneTd.cloneNode(true), this.childNodes[0]);
        });

        /*
         * Initialize DataTables, with no sorting on the 'details' column
         */
        var oTable = $('#sample_PR').dataTable({
            "bPaginate": false,
            "aoColumnDefs": [{"bSortable": false, "aTargets": [0, 1, 2, 3, 4, 5, 6]},
                {"bSearchable": false, "aTargets": [0, 1, 2, 3]}],
            "bFilter": false,
            "bInfo": false
        });

        jQuery('#sample_PR_wrapper .dataTables_filter input').addClass("form-control input-small input-inline"); // modify table search input
        jQuery('#sample_PR_wrapper .dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
        jQuery('#sample_PR_wrapper .dataTables_length select').select2(); // initialize select2 dropdown

        /* Add event listener for opening and closing details
         * Note that the indicator for showing which row is open is not controlled by DataTables,
         * rather it is done here
         */


        $('#sample_PR').on('click', ' tbody td .row-details', function () {
            var nTr = $(this).parents('tr')[0];
            if (oTable.fnIsOpen(nTr))
            {
                /* This row is already open - close it */
                $(this).addClass("row-details-close").removeClass("row-details-open");
                oTable.fnClose(nTr);
            } else
            {
                /* Open this row */
                $(this).addClass("row-details-open").removeClass("row-details-close");
                oTable.fnOpen(nTr, fnFormatDetails(oTable, nTr), 'details');
            }
        });
    }


    return {
        //main function to initiate the module
        init: function () {

            if (!jQuery().dataTable) {
                return;
            }

            initTable1();
            initTable2();
            initTable3();
            initTablePR();
        }

    };

}();