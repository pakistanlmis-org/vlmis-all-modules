
$(function () {
//    $("#date").datepicker({
//        minDate: "-100Y",
//        maxDate: "0",
//        dateFormat: 'dd/mm/yy',
//        changeMonth: true,
//        changeYear: true
//    });

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

    $("#birth_date").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,

        onSelect: function (dateText, inst) {

            d1_1_1 = $(this).val();
            var dateAr2 = d1_1_1.split('/');
            d1 = dateAr2[1] + '/' + dateAr2[0] + '/' + dateAr2[2].slice(-2);


            d2 = '18/1/2018';
            var usrDate = new Date(d1);
            var curDate = new Date();
            var usrYear, usrMonth = usrDate.getMonth() + 1;
            var curYear, curMonth = curDate.getMonth() + 1;
            if ((usrYear = usrDate.getFullYear()) < (curYear = curDate.getFullYear())) {
                curMonth += (curYear - usrYear) * 12;
            }
            var diffMonths = curMonth - usrMonth;
            if (usrDate.getDate() > curDate.getDate())
                diffMonths--;
            $('#age_month').val(diffMonths);

        }
    });
    $("#date_vaccine_given").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
    $("#date_aefi_onset").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
    $("#date_notification").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
    $("#date_Investigation").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
    form_clean = $("#register-form").serialize();
    setInterval(function () {
        var form_dirty = $("#register-form").serialize();
        //var form_data = $("#register-form").serialize();
        if (form_clean != form_dirty)
        {
            if ($('#aefi_id').val() == "") {
                if ($('#week').val() != "") {
                    $('#loader').show();
                    $.ajax({
                        type: "POST",
                        url: appName + "/surveillance/save-draft-aefi",
                        data: $('#register-form').serialize(),

                        success: function (data) {
                            $('#loader').hide();
                            var val = '6';
                            $('#notific8_show').trigger('click');
                        },
                        error: function (jqxhr, status, errorThrown) {


                        }

                    });
                }
            }
        }

    }, 20000);
});
$("#reset_form").click(function () {
    $("#register-form")[0].reset();
    $("#register-form")[0].scrollTop(1000);
});
$("#remove_dob").click(function () {
    $("#birth_date").val('');
});
$("#remove_exp").click(function () {
    $("#expiry_date").val('');
});
$("#remove_vac").click(function () {
    $("#date_vaccine_given").val('');
});
$("#remove_inv").click(function () {
    $("#date_Investigation").val('');
});
$("#remove_onset").click(function () {
    $("#date_aefi_onset").val('');
});
$("#remove_ent").click(function () {
    $("#date").val('');
});
$("#remove_noti").click(function () {
    $("#date_notification").val('');
});
$("#type_of_case").change(function () {

    if ($(this).val() == '0')
    {
        $('#div_case').hide();
    } else {
        $('#div_case').show();
    }
});
$('#district_case').change(function () {
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

            $('#sample_29').dataTable({
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
                        }]
                }
            });
            jQuery('#sample_29_wrapper .dataTables_filter input').addClass("form-control input-small input-inline"); // modify table search input
            jQuery('#sample_29_wrapper .dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
            jQuery('#sample_29_wrapper .dataTables_length select').select2(); // initialize select2 dropdown

            $('#sample_29_column_toggler input[type="checkbox"]').change(function () {
                /* Get the DataTables object again - this is not a recreation, just a get of the object */
                var iCol = parseInt($(this).attr("data-column"));
                var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
                oTable.fnSetColumnVis(iCol, (bVis ? false : true));
            });

        }
    });
});
$('#age_unit').change(function () {
    if ($(this).val() == 1) {
        $('#l_age').html('Age in Months');
    } else if ($(this).val() == 2) {
        $('#l_age').html('Age in Days');
    } else if ($(this).val() == 3) {
        $('#l_age').html('Age in Years');
    }
});

$(document).ready(function () {
    $(window).on("load", function () {
        $("#div_other_cash_reported_from_h_f").hide();
        $("#div_other_case_vaccinated_epi").hide();
        $("#div_other_batch_no").hide();
        $("#div_other_aefi").hide();
    });
    $("#no").click(function () {
        $("#report").hide();
    });
    $("#yes").click(function () {
        $("#report").show();
    });
    $("#dno").click(function () {
        $("#data_difficulty").hide();
    });
    $("#dyes").click(function () {
        $("#data_difficulty").show();
    });
    if ($("#type_of_case").val() == '0')
    {
        $('#div_case').hide();
    } else {
        $('#div_case').show();
    }


    $("#check_cash_reported_from_h_f").click(function () {
        // alert("1");

        if (this.checked) {
            //Do stuff
            $("#other_case_health_facility").prop("readonly", false);
            $("#cash_reported_from_h_f").val("");
            $("#cash_reported_from_h_f").attr("disabled", true);

        } else
        {
            $("#cash_reported_from_h_f").attr("disabled", false);
            $("#other_case_health_facility").prop("readonly", true);
        }
    });
    $("#check_case_vaccinated_epi").click(function () {
        // alert("1");

        if (this.checked) {
            //Do stuff

            $("#other_case_vaccinated_epi").prop("readonly", false);
            $("#case_vaccinated_epi").val("");
            $("#case_vaccinated_epi").attr("disabled", true);
        } else
        {
            $("#case_vaccinated_epi").attr("disabled", false);
            $("#other_case_vaccinated_epi").prop("readonly", true);
        }
    });
    $("#check_batch_no").click(function () {
        // alert("1");

        if (this.checked) {
            //Do stuff

            $("#other_batch_no").prop("readonly", false);
            $("#batch_no").val("");
            $("#batch_no").attr("disabled", true);
        } else
        {
            $("#batch_no").attr("disabled", false);
            $("#other_batch_no").prop("readonly", true);
        }
    });
    $("#check_aefi").click(function () {
        // alert("1");

        if (this.checked) {
            //Do stuff

            $("#other_aefi").prop("readonly", false);
            $("#aefi").val("");
            $("#aefi").attr("disabled", true);
        } else
        {
            $("#aefi").attr("disabled", false);
            $("#other_aefi").prop("readonly", true);
        }
    });



});

$("#expiry_date").datepicker({
    minDate: 0,
    maxDate: "+10Y",
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true
});/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$("#register-form").validate({//"id=register-form" in servay.php
    rules: {

        district_admin: {
            required: true
        },
        week: {
            required: true
        },
        address: {
            required: true
        },
        vaccinator: {
            required: true
        },
        desig: {
            required: true
        },
        date_vaccine_given: {
            required: true,
            date: true
        },
        date_aefi_onset: {
            required: true,
            date: true
        },
        date_Investigation: {
            required: true,
            date: true
        },
        district: {
            required: true
        },
        address_town: {
            required: true,
            maxLength: 255
        },
        child_name: {
            required: true,
            maxLength: 255
        },
        father_name: {
            required: true
        },
        age_month: {
            required: true

        },
        cash_reported_from_h_f: {
            required: true
        },
        address1: {
            required: true,
            maxLength: 255
        },
        Gender: {
            required: true
        },
        aefi: {
            required: true
        },

        date_notification: {
            required: true
        },

//                l_v_d_received:{
//                                required: "#check_l_v_d_received:checked"
//                          }
        other_case_vaccinated_epi: {
            required: "#check_case_vaccinated_epi:checked"
        },
        other_cash_reported_from_h_f: {
            required: "#check_cash_reported_from_h_f:checked"
        },
        other_batch_no: {
            required: "#check_batch_no:checked"
        },
        other_aefi: {
            required: "#check_aefi:checked"
        }
    },
    errorPlacement: function (error, element) {
        if (element.attr("name") == "date" || element.attr("name") == "birth_date" || element.attr("name") == "date_vaccine_given" || element.attr("name") == "date_aefi_onset" || element.attr("name") == "date_Investigation" || element.attr("name") == "other_case_health_facility" || element.attr("name") == "other_case_vaccinated_epi" || element.attr("name") == "other_aefi" || element.attr("name") == "other_batch_no" || element.attr("name") == "age_months") {
            error.insertAfter(".btn default");
        } else {
            error.insertAfter(element);
        }
    }

});
$('#province').change(function () {
    $('#loader').show();
    $('#district').html('<option value="">Loading...</option>');

    $.ajax({
        type: "POST",
        url: appName + "/reports/inventory-management/ajax-combos",
        data: {SkOfcLvl: 4, provId: $(this).val(), report: 'rep'},
        dataType: 'html',
        success: function (data) {
            $('#loader').hide();
            $('#district_case').html(data);

        }
    });
})
$('#district_case').change(function () {
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

    $.ajax({
        type: "POST",
        url: appName + "/index/all-level-combos-five-district",
        data: {combo3: $(this).val(), office: 6},
        dataType: 'html',
        success: function (data) {

            $('#cash_reported_from_h_f').html(data);
            $('#case_vaccinated_epi').html(data);

        }
    });
});

$('#tehsil').change(function () {
    $('#loader').show();
    $('#uc').html('<option value="">Loading...</option>');

    $.ajax({
        type: "POST",
        url: appName + "/index/all-level-combos-four",
        data: {combo3: $(this).val(), office: 6},
        dataType: 'html',
        success: function (data) {
            $('#loader').hide();

            $('#uc').html(data);

        }
    });
});

$('#uc').change(function () {
    $('#loader').show();
    $('#case_health_facility').html('<option value="">Loading...</option>');
    $.ajax({
        type: "POST",
        url: appName + "/index/all-level-combos-five",
        data: {combo3: $(this).val(), office: 6},
        dataType: 'html',
        success: function (data) {

            $('#cash_reported_from_h_f').html(data);
            $('#case_vaccinated_epi').html(data);

        }
    });
});

$('#vaccine_antigen').change(function () {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!

    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd;
    }
    if (mm < 10) {
        mm = '0' + mm;
    }
    var today = dd + '/' + mm + '/' + yyyy;

    $.ajax({
        type: "POST",
        url: appName + "/stock-batch/ajax-running-batches-aefi",
        data: {item_id: $(this).val()},
        dataType: 'html',
        success: function (data) {

            $('#batch_no').html(data);
        }
    });
});


if ($('#district_case').val() != "") {

    $('#loader').show();
    $('#tehsil').html('<option value="">Loading...</option>');
    $.ajax({
        type: "POST",
        url: appName + "/index/all-level-combos-three",
        data: {combo2: $('#district').val(), office: 6},
        dataType: 'html',
        success: function (data) {
            // alert(data);

            $('#tehsil').html(data);

            $('#tehsil').val($('#tehsil_id_hidden').val());
        }
    });

    $.ajax({
        type: "POST",
        url: appName + "/index/all-level-combos-five-district",
        data: {combo3: $('#district').val(), office: 6},
        dataType: 'html',
        success: function (data) {

            $('#cash_reported_from_h_f').html(data);
            $('#cash_reported_from_h_f').val($('#hf_id_hidden').val());
            $('#case_vaccinated_epi').html(data);
            $('#case_vaccinated_epi').val($('#hf_id_hidden').val());

        }
    });

}

if ($('#tehsil_id_hidden').val() != "") {
    $('#loader').show();
    $('#uc').html('<option value="">Loading...</option>');

    $.ajax({
        type: "POST",
        url: appName + "/index/all-level-combos-four",
        data: {combo3: $('#tehsil_id_hidden').val(), office: 6},
        dataType: 'html',
        success: function (data) {
            $('#loader').hide();

            $('#uc').html(data);
            $('#uc').val($('#uc_id_hidden').val());

        }
    });
}
if ($('#uc_id_hidden').val() != "") {
    $('#loader').show();
    $('#case_health_facility').html('<option value="">Loading...</option>');
    $.ajax({
        type: "POST",
        url: appName + "/index/all-level-combos-five",
        data: {combo3: $('#uc_id_hidden').val(), office: 6},
        dataType: 'html',
        success: function (data) {

            $('#cash_reported_from_h_f').html(data);
            $('#cash_reported_from_h_f').val($('#hf_id_hidden').val());
            $('#case_vaccinated_epi').html(data);
            $('#case_vaccinated_epi').val($('#hf_id_hidden').val());

        }
    });
}

if ($('#vaccine_antigen').val() != "") {
    $('#loader').show();
    $('#case_health_facility').html('<option value="">Loading...</option>');
    $.ajax({
        type: "POST",
        url: appName + "/stock-batch/ajax-running-batches-aefi",
        data: {item_id: $('#vaccine_antigen').val()},
        dataType: 'html',
        success: function (data) {

            $('#batch_no').html(data);
            $('#batch_no').val($('#batch_no_hidden').val());
        }
    });
}