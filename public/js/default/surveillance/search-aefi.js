$(function () {
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
            } else {
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
            } else {
                startDateTextBox.val(dateText);
            }
        },
        onSelect: function (selectedDateTime) {
            startDateTextBox.datepicker('option', 'maxDate', endDateTextBox.datepicker('getDate'));
        }
    });
});

$('#district').change(function () {
    $('#loader').show();
    $('#tehsil').html('<option value="">Loading...</option>');

    $.ajax({
        type: "POST",
        url: appName + "/index/all-level-combos-three",
        data: {combo2: $(this).val(), office: 6},
        dataType: 'html',
        success: function (data) {
            $('#loader').hide();
            $('#tehsil').html(data);

        }
    });
});

$('#tehsil').change(function () {
    $('#loader').show();
    $('#ucs').html('<option value="">Loading...</option>');

    $.ajax({
        type: "POST",
        url: appName + "/index/all-level-combos-four",
        data: {combo3: $(this).val(), office: 6},
        dataType: 'html',
        success: function (data) {
            $('#loader').hide();

            $('#ucs').html(data);

        }
    });
});

$('#ucs').change(function () {
    $('#loader').show();
    $('#case_health_facility').html('<option value="">Loading...</option>');
    $.ajax({
        type: "POST",
        url: appName + "/index/all-level-combos-five",
        data: {combo3: $(this).val(), office: 6},
        dataType: 'html',
        success: function (data) {

            $('#case_health_facility').html(data);

        }
    });
});

$('#search').click(function () {
    $(".reload").trigger("click");
    $(this).button('loading');
    $.ajax({
        type: "POST",
        url: appName + "/surveillance/ajax-get-aefi",
        data: $('#search-vpd').serialize(),
        dataType: 'html',
        success: function (data) {
            $('#search').button('reset');
            $('#ajax-table').html(data);
            $("#print_aefi").click(function () {
                var id = $('#hdn_aefi_id').val();
                window.open(appName + '/surveillance/print-aefi?id=' + id, '_blank', 'scrollbars=1,width=860,height=595');
            });
            $(function () {
                //printCont();
                $("#print").click(function () {
                    printCont();
                });
            });

            $('#sample_2').dataTable({
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
    });
});