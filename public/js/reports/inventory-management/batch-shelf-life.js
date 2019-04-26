$(function() {


    var initTable2 = function() {
        var oTable = $('#stkledger').dataTable({
            "aoColumnDefs": [
                {"sType": 'date-uk', "aTargets": [1]},
                {"bSortable": false, "aTargets": [0, 1, 2, 3, 4, 5, 6, 7, 8]}
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
            "sDom": "<'row'<'col-md-12 col-sm-12 right'T>r><'table-scrollable't>>", // horizobtal scrollable datatable
            //"sDom ": 'T<"clear ">lfrtip',
            // set the initial value
            "bDestroy": true,
            "iDisplayLength": 1000,
            "oTableTools": {
                "sSwfPath": appName + "/common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf",
                "aButtons": [{
                        "sExtends": "xls",
                        "sButtonText": "<img src="+ appName + "/images/excel-32.png width=24> Export to Excel"
                    }, {
                        "sExtends": "copy",
                        "sButtonText": "<img src="+ appName + "/images/copy.png width=24> Copy Table"
                    }]
            }
        });

        jQuery('#stkledger_wrapper .dataTables_filter input').addClass("form-control input-small input-inline"); // modify table search input
        jQuery('#stkledger_wrapper .dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
        jQuery('#stkledger_wrapper .dataTables_length select').select2(); // initialize select2 dropdown

        $('#stkledger_column_toggler input[type="checkbox"]').change(function() {
            /* Get the DataTables object again - this is not a recreation, just a get of the object */
            var iCol = parseInt($(this).attr("data-column"));
            var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
            oTable.fnSetColumnVis(iCol, (bVis ? false : true));
        });
    }
    initTable2();



    $("#time_period").datepicker({
        minDate: "-3Y",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });

    if ($('#office').val() != "") {

        $('#loader').show();
        $('#combo1').empty();
        $('#combo2').empty();
        $('#warehouse').empty();
        $('#div_combo1').hide();
        $('#div_combo2').hide();
        $('#wh_combo').hide();
        $.ajax({
            type: "POST",
            url: appName + "/index/all-level-combos-one",
            data: {office: $('#office').val()},
            dataType: 'html',
            success: function(data) {
                $('#loader').hide();
                var val1 = $('#office').val();
                $('#wh_l').html('Store');
                switch (val1) {
                    case '1':
                        $('#wh_combo').show();
                        $('#warehouse').html(data);
                        $('#warehouse').val($('#warehouse_hidden').val());
                        break;
                    case '2':
                        $('#lblcombo1').text('Province');
                        $('#div_combo1').show();
                        $('#combo1').html(data);
                        $('#combo1').val($('#combo1_hidden').val());
                        break;
                    case '3':
                        $('#lblcombo1').text('Province');
                        $('#div_combo1').show();
                        $('#combo1').html(data);
                        $('#combo1').val($('#combo1_hidden').val());
                        break;
                    case '4':
                        $('#lblcombo1').text('Province');
                        $('#div_combo1').show();
                        $('#combo1').html(data);
                        $('#combo1').val($('#combo1_hidden').val());
                        break;
                    case '5':
                        $('#lblcombo1').text('Province');
                        $('#div_combo1').show();
                        $('#combo1').html(data);
                        $('#combo1').val($('#combo1_hidden').val());
                        break;
                    case '6':
                        $('#wh_l').html('Health Facility');
                        $('#lblcombo1').text('Province');
                        $('#div_combo1').show();
                        $('#combo1').html(data);
                        $('#combo1').val($('#combo1_hidden').val());
                        break;
                    case '60':
                        $('#wh_combo').show();
                        $('#warehouse').html(data);
                        $('#warehouse').val($('#warehouse_hidden').val());
                        break;
                }
            }
        });
    }

    if ($('#combo1_hidden').val() != "") {
        $('#loader').show();
        $('#combo2').empty();

        $('#warehouse').empty();

        $('#div_combo2').hide();
        $('#wh_combo').hide();

        $.ajax({
            type: "POST",
            url: appName + "/index/all-level-combos-two",
            data: {combo1: $('#combo1_hidden').val(), office: $('#office').val()},
            dataType: 'html',
            success: function(data) {
                $('#loader').hide();

                var val = $('#office').val();
                switch (val)
                {
                    case '2':
                        $('#wh_combo').show();
                        $('#warehouse').html(data);
                        $('#warehouse').val($('#warehouse_hidden').val());
                        break;
                    case '3':
                        $('#wh_combo').show();
                        $('#warehouse').html(data);
                        $('#warehouse').val($('#warehouse_hidden').val());
                        break;
                    case '4':
                        $('#wh_combo').show();
                        $('#warehouse').html(data);
                        $('#warehouse').val($('#warehouse_hidden').val());
                        break;
                    case '5':
                        $('#lblcombo2').text('Districts');
                        $('#div_combo2').show();
                        $('#combo2').show();
                        $('#combo2').html(data);
                        $('#combo2').val($('#combo1_hidden').val());
                        break;
                    case '6':
                        $('#lblcombo2').text('Districts');
                        $('#div_combo2').show();
                        $('#combo2').show();
                        $('#combo2').html(data);
                        $('#combo2').val($('#combo1_hidden').val());
                        break;


                }
            }
        });
    }

    if ($('#combo2_hidden').val() != "") {
        $('#loader').show();
        $.ajax({
            type: "POST",
            url: appName + "/index/all-level-combos-three",
            data: {combo2: $('#combo2').val(), office: $('#office').val()},
            dataType: 'html',
            success: function(data) {
                $('#loader').hide();
                $('#wh_combo').show();
                $('#wh_1').html('Store');
                $('#warehouse').html(data);
                $('#warehouse').val($('#warehouse_hidden').val());
            }
        });
    }
});