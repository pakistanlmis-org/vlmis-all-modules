

$(function () {

    var h = $(".dashboard-header").css('height');
    if (h != "51px") {
        $(".sidebar-toggler").trigger("click");
    }
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
$(function () {
//    $("#date").datepicker({
//        minDate: "-100Y",
//        maxDate: "0",
//        dateFormat: 'dd/mm/yy',
//        changeMonth: true,
//        changeYear: true
//    });

    if ($("#t_o_case").val() == '246')
    {
        $('#has_vacc').html("Has Child Received OPV");
        $('#d1').text("Date of Stool Sample Sent1");
        $('#d2').text("Date of Stool Sample Sent2");
        $('#final_classification').html("");
        $('#final_classification').append($('<option />').val('').text('Select'));
        $('#final_classification').append($('<option />').val(259).text('Clinically Confirmed'));
        $('#final_classification').append($('<option />').val(261).text('Discarded'));
        $('#final_classification').append($('<option />').val(262).text('Epid to Lab Confirmed Case'));
        $('#final_classification').append($('<option />').val(305).text('Lab Confirmed'));
        $('#final_classification').append($('<option />').val(264).text('NA'));


    } else if ($("#t_o_case").val() == '247') {
        $('#has_vacc').html("Has Child Received BCG");
        $('#d1').text("Date of Sputum Sample Sent1");
        $('#d2').text("Date of Sputum Sample Sent2");
        $('#final_classification').html("");
        $('#final_classification').append($('<option />').val('').text('Select'));
        $('#final_classification').append($('<option />').val(259).text('Clinically Confirmed'));
        $('#final_classification').append($('<option />').val(261).text('Discarded'));
        $('#final_classification').append($('<option />').val(262).text('Epid to Lab Confirmed Case'));
        $('#final_classification').append($('<option />').val(305).text('Lab Confirmed'));
        $('#final_classification').append($('<option />').val(264).text('NA'));

    } else if ($("#t_o_case").val() == '248') {
        $('#has_vacc').html("Has Child Received Penta");
        $('#d1').text("Date of Throat-Swab Sample Sent1");
        $('#d2').text("Date of Naso-Pharyngeal Sent2");
        $('#final_classification').html("");
        $('#final_classification').append($('<option />').val('').text('Select'));
        $('#final_classification').append($('<option />').val(259).text('Clinically Confirmed'));
        $('#final_classification').append($('<option />').val(261).text('Discarded'));
        $('#final_classification').append($('<option />').val(262).text('Epid to Lab Confirmed Case'));
        $('#final_classification').append($('<option />').val(305).text('Lab Confirmed'));
        $('#final_classification').append($('<option />').val(264).text('NA'));

    } else if ($("#t_o_case").val() == '249') {
        $('#has_vacc').html("Has Child Received Measles");
        $('#d1').text("Date of Blood Sample Sent1");
        $('#d2').text("Date of Throat-Swab Sent2");
        $('#final_classification').html("");
        $('#final_classification').append($('<option />').val('').text('Select'));
        $('#final_classification').append($('<option />').val(259).text('Clinically Confirmed'));
        $('#final_classification').append($('<option />').val(261).text('Discarded'));
        $('#final_classification').append($('<option />').val(262).text('Epid to Lab Confirmed Case'));
        $('#final_classification').append($('<option />').val(305).text('Lab Confirmed'));
        $('#final_classification').append($('<option />').val(264).text('NA'));


    } else if ($("#t_o_case").val() == '250') {
        $('#has_vacc').html("Has Mother Received TT");
        $('#final_classification').empty().append("<option value=''>Select</option><option value='259'>Clinically Confirmed</option>")
        $('#d1').text("Date of Blood Sample Sent1");
        $('#d2').text("Date of Throat-Swab Sent2");
        $('input:radio[value^="1"]').prop("checked", false);
        $('input:radio[value^="0"]').prop("checked", true);
        $('input:radio[value^="0"]').addClass('active');
        $('input:radio[value^="1"]').addClass('');


    } else if ($("#t_o_case").val() == '252') {

    } else if ($("#t_o_case").val() == '251') {
        $('#has_vacc').html("Has Child Received Penta");
        $('#d1').text("Date of Throat-Swab Sample Sent1");
        $('#d2').text("Date of Naso-Pharyngeal Sent2");
        $('#final_classification').html("");
        $('#final_classification').append($('<option />').val('').text('Select'));
        $('#final_classification').append($('<option />').val(259).text('Clinically Confirmed'));
        $('#final_classification').append($('<option />').val(261).text('Discarded'));
        $('#final_classification').append($('<option />').val(262).text('Epid to Lab Confirmed Case'));
        $('#final_classification').append($('<option />').val(305).text('Lab Confirmed'));
        $('#final_classification').append($('<option />').val(264).text('NA'));


    } else {
        $('#has_vacc').html("");
    }


    if ($("#t_o_case").val() == '250')
    {

        $('#pab').show();
    } else {
        $('#pab').hide();
    }
    if ($(this).val() == '252')
    {
        $('#case_type').hide();
    } else {
        $('#case_type').show();
    }
    if ($(("#t_o_case")).val() == '246')
    {
        $('#Vaccine_Doses_Recived').html("");
        $('#Vaccine_Doses_Recived').append($('<option />').val(1).text(1));
        $('#Vaccine_Doses_Recived').append($('<option />').val(2).text(2));
        $('#Vaccine_Doses_Recived').append($('<option />').val(3).text(3));
        $('#Vaccine_Doses_Recived').append($('<option />').val(4).text(4));
        $('#Vaccine_Doses_Recived').append($('<option />').val(5).text(5));
        $('#Vaccine_Doses_Recived').append($('<option />').val(6).text(6));
        $('#Vaccine_Doses_Recived').append($('<option />').val(7).text(7));
        $('#Vaccine_Doses_Recived').append($('<option />').val(7).text('7+'));
        // show
        $("#div_StoolSampleDate1").show();
        $("#div_StoolSampleDate2").show();
        // hide
        $("#div_Blood_Sample_Date").hide();
        $("#div_Throad_Swab_Date").hide();
        $("#div_Throad_Swab_Date").hide();
        $("#div_Naso_Pharyngeal_Swab_Date").hide();
        $("#div_Sputum_Collection_Date_1").hide();
        $("#div_Sputum_Collection_Date_2").hide();
        $("#div_Throad_Swab_Date").hide();
        $("#div_Naso_Pharyngeal_Swab_Date").hide();
    } else if ($(("#t_o_case")).val() == '249')
    {
        $('#Vaccine_Doses_Recived').html("");
        $('#Vaccine_Doses_Recived').append($('<option />').val(1).text(0));
        $('#Vaccine_Doses_Recived').append($('<option />').val(2).text(1));
        $('#Vaccine_Doses_Recived').append($('<option />').val(3).text(2));
        $('#Vaccine_Doses_Recived').append($('<option />').val(4).text('>2'));

        // show
        $("#div_Blood_Sample_Date").show();
        $("#div_Throad_Swab_Date").show();
        // hide
        $("#div_StoolSampleDate1").hide();
        $("#div_StoolSampleDate2").hide();

        $("#div_Naso_Pharyngeal_Swab_Date").hide();
        $("#div_Sputum_Collection_Date_1").hide();
        $("#div_Sputum_Collection_Date_2").hide();

        $("#div_Naso_Pharyngeal_Swab_Date").hide();
    } else if ($(("#t_o_case")).val() == '251')
    {  //show
        $('#Vaccine_Doses_Recived').html("");
        $('#Vaccine_Doses_Recived').append($('<option />').val(1).text(0));
        $('#Vaccine_Doses_Recived').append($('<option />').val(2).text(1));
        $('#Vaccine_Doses_Recived').append($('<option />').val(3).text(2));
        $('#Vaccine_Doses_Recived').append($('<option />').val(4).text(3));
        $("#div_Throad_Swab_Date").show();
        $("#div_Naso_Pharyngeal_Swab_Date").show();
        // hide
        $("#div_StoolSampleDate1").hide();
        $("#div_StoolSampleDate2").hide();
        $("#div_Blood_Sample_Date").hide();

        $("#div_Sputum_Collection_Date_1").hide();
        $("#div_Sputum_Collection_Date_2").hide();


    } else if ($(("#t_o_case")).val() == '247')
    {   // show
        $('#Vaccine_Doses_Recived').html("");
        $('#Vaccine_Doses_Recived').append($('<option />').val(1).text(0));
        $('#Vaccine_Doses_Recived').append($('<option />').val(2).text(1));


        $("#div_Sputum_Collection_Date_1").show();
        $("#div_Sputum_Collection_Date_2").show();
        // hide
        $("#div_StoolSampleDate1").hide();
        $("#div_StoolSampleDate2").hide();
        $("#div_Blood_Sample_Date").hide();

        $("#div_Throad_Swab_Date").hide();
        $("#div_Naso_Pharyngeal_Swab_Date").hide();

        $("#div_Naso_Pharyngeal_Swab_Date").hide();
    } else if ($(("#t_o_case")).val() == '248')
    {   // show
        $('#Vaccine_Doses_Recived').html("");
        $('#Vaccine_Doses_Recived').append($('<option />').val(1).text(0));
        $('#Vaccine_Doses_Recived').append($('<option />').val(2).text(1));
        $('#Vaccine_Doses_Recived').append($('<option />').val(3).text(2));
        $('#Vaccine_Doses_Recived').append($('<option />').val(4).text(3));

        $("#div_Throad_Swab_Date").show();
        $("#div_Naso_Pharyngeal_Swab_Date").show();
        // hide
        $("#div_StoolSampleDate1").hide();
        $("#div_StoolSampleDate2").hide();
        $("#div_Blood_Sample_Date").hide();


        $("#div_Sputum_Collection_Date_1").hide();
        $("#div_Sputum_Collection_Date_2").hide();

    } else if ($(("#t_o_case")).val() == '250') {
        $('#Vaccine_Doses_Recived').html("");
        $('#Vaccine_Doses_Recived').append($('<option />').val(1).text(0));
        $('#Vaccine_Doses_Recived').append($('<option />').val(2).text(1));
        $('#Vaccine_Doses_Recived').append($('<option />').val(3).text(2));
        $('#Vaccine_Doses_Recived').append($('<option />').val(3).text(3));
        $('#Vaccine_Doses_Recived').append($('<option />').val(4).text(4));
        $('#Vaccine_Doses_Recived').append($('<option />').val(5).text(5));
        $('#Vaccine_Doses_Recived').append($('<option />').val(6).text('>5'));
        $("#div_Blood_Sample_Date").show();
        $("#div_Throad_Swab_Date").show();
        // hide
        $("#div_StoolSampleDate1").hide();
        $("#div_StoolSampleDate2").hide();

        $("#div_Naso_Pharyngeal_Swab_Date").hide();
        $("#div_Sputum_Collection_Date_1").hide();
        $("#div_Sputum_Collection_Date_2").hide();

        $("#div_Naso_Pharyngeal_Swab_Date").hide();

    } else {
        $("#div_Throad_Swab_Date").hide();
        $("#div_Naso_Pharyngeal_Swab_Date").hide();
        // hide
        $("#div_StoolSampleDate1").hide();
        $("#div_StoolSampleDate2").hide();
        $("#div_Blood_Sample_Date").hide();


        $("#div_Sputum_Collection_Date_1").hide();
        $("#div_Sputum_Collection_Date_2").hide();
    }
    form_clean = $("#register-form").serialize();
    setInterval(function () {
        var form_dirty = $("#register-form").serialize();
        //var form_data = $("#register-form").serialize();
        if (form_clean != form_dirty)
        {
            if ($('#vpd_id').val() == "") {
                if ($('#week').val() != "") {
                    $('#loader').show();
                    $.ajax({
                        type: "POST",
                        url: appName + "/surveillance/save-draft-vpd",
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
            form_clean = form_dirty;
        }

    }, 20000);




    $("#birth_date").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,

        onSelect: function (dateText, inst) {

            d1_1 = $(this).val();

            var dateAr = d1_1.split('/');
            d2 = dateAr[1] + '/' + dateAr[0] + '/' + dateAr[2].slice(-2);





            d2_1 = $('#datepicker_Onset').val();
            var dateAr_1 = d2_1.split('/');
            d1 = dateAr_1[1] + '/' + dateAr_1[0] + '/' + dateAr_1[2].slice(-2);

            var usrDate = new Date(d2);
            var curDate = new Date(d1);

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

    $("#datepicker_Onset").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
    $("#datepicker_Notification").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
    $("#datepicker_patient_visited").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
    $("#datepicker_Investigation").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
    $("#l_v_d_received").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        onSelect: function (dateText, inst) {

            d1_1_1 = $(this).val();

            var dateAr2 = d1_1_1.split('/');
            d1_1 = dateAr2[1] + '/' + dateAr2[0] + '/' + dateAr2[2].slice(-2);




            var usrDate1 = new Date(d1_1);
            var curDate1 = new Date();
            var usrYear1, usrMonth1 = usrDate1.getMonth() + 1;
            var curYear1, curMonth1 = curDate1.getMonth() + 1;
            if ((usrYear1 = usrDate1.getFullYear()) < (curYear1 = curDate1.getFullYear())) {
                curMonth1 += (curYear1 - usrYear1) * 12;
            }
            var diffMonths1 = curMonth1 - usrMonth1;
            if (usrDate1.getDate() > curDate1.getDate())
                diffMonths1--;
            $('#l_v_d_received_other').val(diffMonths1);


        }
    });
    $("#stool_sample_date2").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
    $("#stool_sample_date1").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
    $("#blood_sample_date").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
    $("#throad_swab_date").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
    $("#naso_pharyngeal_swab_date").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
    $("#sputum_collection_date_1").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
    $("#sputum_collection_date_2").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
    $("#date_of_specimen_sent").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
    $("#date_of_specimen_sent1").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
    $("#date_of_specimen_collection").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
    $("#date_of_lab_resulted").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });


    $("#StoolSampleDate1").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });

    $("#StoolSampleDate2").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });

    $("#Sputum_Collection_Date_1").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });

    $("#Sputum_Collection_Date_2").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
    $("#Throad_Swab_Date").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });

    $("#Naso_Pharyngeal_Swab_Date").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });

    $("#Blood_Sample_Date").datepicker({
        minDate: "-100Y",
        maxDate: "0",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });

});



$(document).ready(function () {
    $(window).on("load", function () {
        $("#div_other_case_health_facility").hide();
        $("#div_other_l_v_d_received").hide();
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



    $("#check_l_v_d_received").click(function () {
//          alert("1");

        if (this.checked) {
            //Do stuff
            $("#l_v_d_received_other").prop("readonly", false);


        } else
        {
            $("#l_v_d_received_other").prop("readonly", true);
        }
    });


    $("#check").click(function () {


        if (this.checked) {
            //Do stuff
            $("#other_case_health_facility").prop("readonly", false);
            $("#case_health_facility").val("");
            $("#case_health_facility").attr("disabled", true);
            //      $("#other_case_health_facility").show();
        } else
        {
            $("#case_health_facility").attr("disabled", false);
            $("#other_case_health_facility").prop("readonly", true);
            //  $("#other_case_health_facility").hide();
        }
    });
    $("#district").change(function () {
        if ($('#district').val() == 87) {
            $('#tehsil').rules('add', 'required');
        }
        if ($('#district').val() != 87) {
            $('#tehsil').rules('remove', 'required');
            $('#epid_number').val('');
            $("#epid_number").last().addClass("spinner");

            $.ajax({
                type: "POST",
                url: appName + "/surveillance/ajax-get-case-no",
                data: {district_id: $(this).val()},
                dataType: 'html',
                success: function (data) {
                    $('#case_no').val(data);
                }
            });
            if ($('#t_o_case').val() == '246') {
                var t_o = 'AFP';
            } else if ($('#t_o_case').val() == '247') {
                var t_o = 'CT';
            } else if ($('#t_o_case').val() == '248') {
                var t_o = 'Diph';
            } else if ($('#t_o_case').val() == '249') {
                var t_o = 'MSL';
            } else if ($('#t_o_case').val() == '250') {
                var t_o = 'NNT';
            } else if ($('#t_o_case').val() == '251') {
                var t_o = 'Pert';
            }

            $.ajax({
                type: "POST",
                url: appName + "/surveillance/ajax-get-district-code",
                data: {district_id: $(this).val()},
                dataType: 'html',
                success: function (data) {

                    $('#district_code').val(data);
                    var selDay = (new Date()).getFullYear();
                    var district_code = $('#district_code').val();
                    var case_no = +$('#case_no').val() + +1;

                    if (case_no < 10) {
                        case_no = '000' + case_no;

                    } else if (case_no <= 10 && case_no >= 99) {
                        case_no = '00' + case_no;
                    } else if (case_no >= 100) {
                        case_no = '0' + case_no;
                    } else {
                        case_no = case_no;
                    }
                    var epid_number = 'PK/SD/' + district_code + '/' + selDay + '/' + t_o + '/' + case_no;
                    $('#epid_number').val(epid_number);
                    $("#epid_number").last().removeClass("spinner");

                }
            });

        }
    });


    $("#tehsil").change(function () {
        if ($('#district').val() == 87) {
            $('#epid_number').val('');
            $("#epid_number").last().addClass("spinner");

            $.ajax({
                type: "POST",
                url: appName + "/surveillance/ajax-get-case-no",
                data: {district_id: $(this).val()},
                dataType: 'html',
                success: function (data) {
                    $('#case_no').val(data);
                }
            });
            if ($('#t_o_case').val() == '246') {
                var t_o = 'AFP';
            } else if ($('#t_o_case').val() == '247') {
                var t_o = 'CT';
            } else if ($('#t_o_case').val() == '248') {
                var t_o = 'Diph';
            } else if ($('#t_o_case').val() == '249') {
                var t_o = 'MSL';
            } else if ($('#t_o_case').val() == '250') {
                var t_o = 'NNT';
            } else if ($('#t_o_case').val() == '251') {
                var t_o = 'Pert';
            }

            $.ajax({
                type: "POST",
                url: appName + "/surveillance/ajax-get-district-code",
                data: {district_id: $(this).val()},
                dataType: 'html',
                success: function (data) {

                    $('#district_code').val(data);
                    var selDay = (new Date()).getFullYear();
                    var district_code = $('#district_code').val();
                    var case_no = +$('#case_no').val() + +1;

                    if (case_no < 10) {
                        case_no = '000' + case_no;

                    } else if (case_no <= 10 && case_no >= 99) {
                        case_no = '00' + case_no;
                    } else if (case_no >= 100) {
                        case_no = '0' + case_no;
                    }
                    var epid_number = 'PK/SD/' + district_code + '/' + selDay + '/' + t_o + '/' + case_no;
                    $('#epid_number').val(epid_number);
                    $("#epid_number").last().removeClass("spinner");

                }
            });

        }
    });


    $(document).on('change', 'input:radio[name^="Specimen_Collection"]', function (event) {
        if ($(this).val() == 1) {
            if ($("#t_o_case").val() == '246')
            {

                // show
                $("#div_StoolSampleDate1").show();
                $("#div_StoolSampleDate2").show();
                // hide
                $("#place_birth_div").hide();
                $("#birth_attendee_div").hide();
                $("#div_Blood_Sample_Date").hide();
                $("#div_Throad_Swab_Date").hide();
                $("#div_Throad_Swab_Date").hide();
                $("#div_Naso_Pharyngeal_Swab_Date").hide();
                $("#div_Sputum_Collection_Date_1").hide();
                $("#div_Sputum_Collection_Date_2").hide();
                $("#div_Throad_Swab_Date").hide();
                $("#div_Naso_Pharyngeal_Swab_Date").hide();
            } else if ($("#t_o_case").val() == '249')
            {


                // show
                $("#div_Blood_Sample_Date").show();
                $("#div_Throad_Swab_Date").show();
                // hide
                $("#div_StoolSampleDate1").hide();
                $("#div_StoolSampleDate2").hide();
                $("#place_birth_div").hide();
                $("#birth_attendee_div").hide();
                $("#div_Naso_Pharyngeal_Swab_Date").hide();
                $("#div_Sputum_Collection_Date_1").hide();
                $("#div_Sputum_Collection_Date_2").hide();

                $("#div_Naso_Pharyngeal_Swab_Date").hide();
            } else if ($("#t_o_case").val() == '251')
            {  //show

                $("#div_Throad_Swab_Date").show();
                $("#div_Naso_Pharyngeal_Swab_Date").show();
                // hide
                $("#div_StoolSampleDate1").hide();
                $("#div_StoolSampleDate2").hide();
                $("#div_Blood_Sample_Date").hide();
                $("#place_birth_div").hide();
                $("#birth_attendee_div").hide();
                $("#div_Sputum_Collection_Date_1").hide();
                $("#div_Sputum_Collection_Date_2").hide();


            } else if ($("#t_o_case").val() == '247')
            {   // show



                $("#div_Sputum_Collection_Date_1").show();
                $("#div_Sputum_Collection_Date_2").show();
                // hide
                $("#div_StoolSampleDate1").hide();
                $("#div_StoolSampleDate2").hide();
                $("#div_Blood_Sample_Date").hide();
                $("#place_birth_div").hide();
                $("#birth_attendee_div").hide();
                $("#div_Throad_Swab_Date").hide();
                $("#div_Naso_Pharyngeal_Swab_Date").hide();

                $("#div_Naso_Pharyngeal_Swab_Date").hide();
            } else if ($("#t_o_case").val() == '248')
            {   // show


                $("#div_Throad_Swab_Date").show();
                $("#div_Naso_Pharyngeal_Swab_Date").show();
                // hide
                $("#div_StoolSampleDate1").hide();
                $("#div_StoolSampleDate2").hide();
                $("#div_Blood_Sample_Date").hide();

                $("#place_birth_div").hide();
                $("#birth_attendee_div").hide();
                $("#div_Sputum_Collection_Date_1").hide();
                $("#div_Sputum_Collection_Date_2").hide();

            } else if ($("#t_o_case").val() == '250') {
                $("#div_Blood_Sample_Date").show();
                $("#div_Throad_Swab_Date").show();
                $("#place_birth_div").show();
                $("#birth_attendee_div").show();

                // hide
                $("#div_StoolSampleDate1").hide();
                $("#div_StoolSampleDate2").hide();

                $("#div_Naso_Pharyngeal_Swab_Date").hide();
                $("#div_Sputum_Collection_Date_1").hide();
                $("#div_Sputum_Collection_Date_2").hide();

                $("#div_Naso_Pharyngeal_Swab_Date").hide();

            } else {
                $("#div_Throad_Swab_Date").hide();
                $("#div_Naso_Pharyngeal_Swab_Date").hide();
                // hide
                $("#div_StoolSampleDate1").hide();
                $("#div_StoolSampleDate2").hide();
                $("#div_Blood_Sample_Date").hide();

                $("#place_birth_div").hide();
                $("#birth_attendee_div").hide();
                $("#div_Sputum_Collection_Date_1").hide();
                $("#div_Sputum_Collection_Date_2").hide();
            }
            $('#div_specimen_sent').show();
            $('#div_lab_result').show();
            $('#div_specimen_sent1').show();
        } else {
            $('#div_specimen_sent').hide();
            $('#div_specimen_sent1').hide();
            $('#div_lab_result').hide();
            $('#div_StoolSampleDate1').hide();
            $('#div_StoolSampleDate2').hide();
            $("#div_Sputum_Collection_Date_1").hide();
            $("#div_Sputum_Collection_Date_2").hide();
            $("#div_Throad_Swab_Date").hide();
            $("#div_Naso_Pharyngeal_Swab_Date").hide();
            $("#div_Blood_Sample_Date").hide();
        }
    });
//if ($('input:radio[name^="Specimen_Collection"]').val()==0 ){
//     $('#d1').hide();
//     $('#d2').hide();
//     
//}

    $(document).on('change', 'input:radio[name^="has_child_received"]', function (event) {
        if ($(this).val() == 1) {
            $('#vacc_dose_rec').show();
            $('#vacc_div').show();
        } else {
            $('#vacc_dose_rec').hide();
            $('#vacc_div').hide();
        }
    });
    $("#lab_result_test").change(function () {
        if ($(this).val() == '257')
        {
            $('#final_classification').html("");
            $('#final_classification').append($('<option />').val(1).text('Waiting for lab result'));


        } else {
            $('#final_classification').html("");
            $('#final_classification').append($('<option />').val('').text('Select'));
            $('#final_classification').append($('<option />').val(259).text('Clinically Confirmed'));
            $('#final_classification').append($('<option />').val(261).text('Discarded'));
            $('#final_classification').append($('<option />').val(262).text('Epid to Lab Confirmed Case'));
            $('#final_classification').append($('<option />').val(305).text('Lab Confirmed'));
            $('#final_classification').append($('<option />').val(264).text('NA'));
        }
    });

    if ($("#t_o_case").val() == '252')
    {
        $('#case_type').hide();
    } else {
        $('#case_type').show();
    }


    $("#t_o_case").change(function () {

        $('#t_o_case1').val($("#t_o_case").val());

        if ($("#t_o_case").val() == '246')
        {
            $('#has_vacc').html("Has Child Received OPV");
            $('#d1').text("Date of Stool Sample Sent1");
            $('#d2').text("Date of Stool Sample Sent2");
            $('#final_classification').html("");
            $('#final_classification').append($('<option />').val('').text('Select'));
            $('#final_classification').append($('<option />').val(259).text('Clinically Confirmed'));
            $('#final_classification').append($('<option />').val(261).text('Discarded'));
            $('#final_classification').append($('<option />').val(262).text('Epid to Lab Confirmed Case'));
            $('#final_classification').append($('<option />').val(305).text('Lab Confirmed'));
            $('#final_classification').append($('<option />').val(264).text('NA'));


        } else if ($("#t_o_case").val() == '247') {
            $('#has_vacc').html("Has Child Received BCG");
            $('#d1').text("Date of Sputum Sample Sent1");
            $('#d2').text("Date of Sputum Sample Sent2");
            $('#final_classification').html("");
            $('#final_classification').append($('<option />').val('').text('Select'));
            $('#final_classification').append($('<option />').val(259).text('Clinically Confirmed'));
            $('#final_classification').append($('<option />').val(261).text('Discarded'));
            $('#final_classification').append($('<option />').val(262).text('Epid to Lab Confirmed Case'));
            $('#final_classification').append($('<option />').val(305).text('Lab Confirmed'));
            $('#final_classification').append($('<option />').val(264).text('NA'));

        } else if ($("#t_o_case").val() == '248') {
            $('#has_vacc').html("Has Child Received Penta");
            $('#d1').text("Date of Throat-Swab Sample Sent1");
            $('#d2').text("Date of Naso-Pharyngeal Sent2");
            $('#final_classification').html("");
            $('#final_classification').append($('<option />').val('').text('Select'));
            $('#final_classification').append($('<option />').val(259).text('Clinically Confirmed'));
            $('#final_classification').append($('<option />').val(261).text('Discarded'));
            $('#final_classification').append($('<option />').val(262).text('Epid to Lab Confirmed Case'));
            $('#final_classification').append($('<option />').val(305).text('Lab Confirmed'));
            $('#final_classification').append($('<option />').val(264).text('NA'));

        } else if ($("#t_o_case").val() == '249') {
            $('#has_vacc').html("Has Child Received Measles");
            $('#d1').text("Date of Blood Sample Sent1");
            $('#d2').text("Date of Throat-Swab Sent2");
            $('#final_classification').html("");
            $('#final_classification').append($('<option />').val('').text('Select'));
            $('#final_classification').append($('<option />').val(259).text('Clinically Confirmed'));
            $('#final_classification').append($('<option />').val(261).text('Discarded'));
            $('#final_classification').append($('<option />').val(262).text('Epid to Lab Confirmed Case'));
            $('#final_classification').append($('<option />').val(305).text('Lab Confirmed'));
            $('#final_classification').append($('<option />').val(264).text('NA'));


        } else if ($("#t_o_case").val() == '250') {
            $('#has_vacc').html("Has Mother Received TT");
            $('#final_classification').empty().append("<option value=''>Select</option><option value='259'>Clinically Confirmed</option>")
            $('#d1').text("Date of Blood Sample Sent1");
            $('#d2').text("Date of Throat-Swab Sent2");
            $('input:radio[value^="1"]').prop("checked", false);
            $('input:radio[value^="0"]').prop("checked", true);
            $('input:radio[value^="0"]').addClass('active');
            $('input:radio[value^="1"]').addClass('');


        } else if ($("#t_o_case").val() == '252') {

        } else if ($("#t_o_case").val() == '251') {
            $('#has_vacc').html("Has Child Received Penta");
            $('#d1').text("Date of Throat-Swab Sample Sent1");
            $('#d2').text("Date of Naso-Pharyngeal Sent2");
            $('#final_classification').html("");
            $('#final_classification').append($('<option />').val('').text('Select'));
            $('#final_classification').append($('<option />').val(259).text('Clinically Confirmed'));
            $('#final_classification').append($('<option />').val(261).text('Discarded'));
            $('#final_classification').append($('<option />').val(262).text('Epid to Lab Confirmed Case'));
            $('#final_classification').append($('<option />').val(305).text('Lab Confirmed'));
            $('#final_classification').append($('<option />').val(264).text('NA'));


        } else {
            $('#has_vacc').html("");
        }


        if ($("#t_o_case").val() == '250')
        {

            $('#pab').show();
        } else {
            $('#pab').hide();
        }
        if ($(this).val() == '252')
        {
            $('#case_type').hide();
        } else {
            $('#case_type').show();
        }
        if ($(this).val() == '246')
        {
            $('#Vaccine_Doses_Recived').html("");
            $('#Vaccine_Doses_Recived').append($('<option />').val(1).text(1));
            $('#Vaccine_Doses_Recived').append($('<option />').val(2).text(2));
            $('#Vaccine_Doses_Recived').append($('<option />').val(3).text(3));
            $('#Vaccine_Doses_Recived').append($('<option />').val(4).text(4));
            $('#Vaccine_Doses_Recived').append($('<option />').val(5).text(5));
            $('#Vaccine_Doses_Recived').append($('<option />').val(6).text(6));
            $('#Vaccine_Doses_Recived').append($('<option />').val(7).text(7));
            $('#Vaccine_Doses_Recived').append($('<option />').val(7).text('7+'));
            // show
            $("#div_StoolSampleDate1").show();
            $("#div_StoolSampleDate2").show();
            // hide
            $("#div_Blood_Sample_Date").hide();
            $("#div_Throad_Swab_Date").hide();
            $("#div_Throad_Swab_Date").hide();
            $("#place_birth_div").hide();
            $("#birth_attendee_div").hide();
            $("#div_Naso_Pharyngeal_Swab_Date").hide();
            $("#div_Sputum_Collection_Date_1").hide();
            $("#div_Sputum_Collection_Date_2").hide();
            $("#div_Throad_Swab_Date").hide();
            $("#div_Naso_Pharyngeal_Swab_Date").hide();
        } else if ($(this).val() == '249')
        {
            $('#Vaccine_Doses_Recived').html("");
            $('#Vaccine_Doses_Recived').append($('<option />').val(1).text(0));
            $('#Vaccine_Doses_Recived').append($('<option />').val(2).text(1));
            $('#Vaccine_Doses_Recived').append($('<option />').val(3).text(2));
            $('#Vaccine_Doses_Recived').append($('<option />').val(4).text('>2'));

            // show
            $("#div_Blood_Sample_Date").show();
            $("#div_Throad_Swab_Date").show();
            // hide
            $("#div_StoolSampleDate1").hide();
            $("#div_StoolSampleDate2").hide();
            $("#place_birth_div").hide();
            $("#birth_attendee_div").hide();
            $("#div_Naso_Pharyngeal_Swab_Date").hide();
            $("#div_Sputum_Collection_Date_1").hide();
            $("#div_Sputum_Collection_Date_2").hide();

            $("#div_Naso_Pharyngeal_Swab_Date").hide();
        } else if ($(this).val() == '251')
        {  //show
            $('#Vaccine_Doses_Recived').html("");
            $('#Vaccine_Doses_Recived').append($('<option />').val(1).text(0));
            $('#Vaccine_Doses_Recived').append($('<option />').val(2).text(1));
            $('#Vaccine_Doses_Recived').append($('<option />').val(3).text(2));
            $('#Vaccine_Doses_Recived').append($('<option />').val(4).text(3));
            $("#div_Throad_Swab_Date").show();
            $("#div_Naso_Pharyngeal_Swab_Date").show();
            // hide
            $("#div_StoolSampleDate1").hide();
            $("#div_StoolSampleDate2").hide();
            $("#div_Blood_Sample_Date").hide();
            $("#place_birth_div").hide();
            $("#birth_attendee_div").hide();
            $("#div_Sputum_Collection_Date_1").hide();
            $("#div_Sputum_Collection_Date_2").hide();


        } else if ($(this).val() == '247')
        {   // show
            $('#Vaccine_Doses_Recived').html("");
            $('#Vaccine_Doses_Recived').append($('<option />').val(1).text(0));
            $('#Vaccine_Doses_Recived').append($('<option />').val(2).text(1));


            $("#div_Sputum_Collection_Date_1").show();
            $("#div_Sputum_Collection_Date_2").show();
            // hide
            $("#div_StoolSampleDate1").hide();
            $("#div_StoolSampleDate2").hide();
            $("#div_Blood_Sample_Date").hide();
            $("#place_birth_div").hide();
            $("#birth_attendee_div").hide();
            $("#div_Throad_Swab_Date").hide();
            $("#div_Naso_Pharyngeal_Swab_Date").hide();

            $("#div_Naso_Pharyngeal_Swab_Date").hide();
        } else if ($(this).val() == '248')
        {   // show
            $('#Vaccine_Doses_Recived').html("");
            $('#Vaccine_Doses_Recived').append($('<option />').val(1).text(0));
            $('#Vaccine_Doses_Recived').append($('<option />').val(2).text(1));
            $('#Vaccine_Doses_Recived').append($('<option />').val(3).text(2));
            $('#Vaccine_Doses_Recived').append($('<option />').val(4).text(3));

            $("#div_Throad_Swab_Date").show();
            $("#div_Naso_Pharyngeal_Swab_Date").show();
            // hide
            $("#div_StoolSampleDate1").hide();
            $("#div_StoolSampleDate2").hide();
            $("#div_Blood_Sample_Date").hide();
            $("#place_birth_div").hide();
            $("#birth_attendee_div").hide();

            $("#div_Sputum_Collection_Date_1").hide();
            $("#div_Sputum_Collection_Date_2").hide();

        } else if ($(this).val() == '250') {
            $('#Vaccine_Doses_Recived').html("");
            $('#Vaccine_Doses_Recived').append($('<option />').val(1).text(0));
            $('#Vaccine_Doses_Recived').append($('<option />').val(2).text(1));
            $('#Vaccine_Doses_Recived').append($('<option />').val(3).text(2));
            $('#Vaccine_Doses_Recived').append($('<option />').val(3).text(3));
            $('#Vaccine_Doses_Recived').append($('<option />').val(4).text(4));
            $('#Vaccine_Doses_Recived').append($('<option />').val(5).text(5));
            $('#Vaccine_Doses_Recived').append($('<option />').val(6).text('>5'));
            $("#div_Blood_Sample_Date").show();
            $("#div_Throad_Swab_Date").show();
            $("#place_birth_div").show();
            $("#birth_attendee_div").show();
            // hide
            $("#div_StoolSampleDate1").hide();
            $("#div_StoolSampleDate2").hide();

            $("#div_Naso_Pharyngeal_Swab_Date").hide();
            $("#div_Sputum_Collection_Date_1").hide();
            $("#div_Sputum_Collection_Date_2").hide();

            $("#div_Naso_Pharyngeal_Swab_Date").hide();

        } else {
            $("#div_Throad_Swab_Date").hide();
            $("#div_Naso_Pharyngeal_Swab_Date").hide();
            // hide
            $("#div_StoolSampleDate1").hide();
            $("#div_StoolSampleDate2").hide();
            $("#div_Blood_Sample_Date").hide();
            $("#place_birth_div").hide();
            $("#birth_attendee_div").hide();
            $("#div_Sputum_Collection_Date_1").hide();
            $("#div_Sputum_Collection_Date_2").hide();
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

$(function () {
    jQuery.validator.setDefaults({
        debug: true,
        success: "valid"
    });





});


$("#remove_lab").click(function () {
    $("#date_of_lab_resulted").val('');
});
$("#remove_t2").click(function () {
    $("#Throad_Swab_Date").val('');
});
$("#remove_bt1").click(function () {
    $("#Blood_Sample_Date").val('');
});
$("#remove_st2").click(function () {
    $("#StoolSampleDate2").val('');
});
$("#remove_st1").click(function () {
    $("#StoolSampleDate1").val('');
});
$("#remove_sp_2").click(function () {
    $("#date_of_specimen_sent1").val('');
});
$("#remove_sp_1").click(function () {
    $("#date_of_specimen_sent").val('');
});
$("#remove_inv").click(function () {
    $("#datepicker_Investigation").val('');
});
$("#remove_spt2").click(function () {
    $("#Sputum_Collection_Date_2").val('');
});
$("#remove_spt1").click(function () {
    $("#Sputum_Collection_Date_1").val('');
});
$("#remove_onset").click(function () {
    $("#datepicker_Onset").val('');
});
$("#remove_n1").click(function () {
    $("#Naso_Pharyngeal_Swab_Date").val('');
});
$("#remove_noti").click(function () {
    $("#datepicker_Notification").val('');
});
$("#remove_pt").click(function () {
    $("#datepicker_patient_visited").val('');
});
$("#reset_form").click(function () {
    $("#register-form")[0].reset();
    $("#register-form")[0].scrollTop(1000);
});
$("#remove_date").click(function () {
    $("#l_v_d_received").val('');
});
$("#remove_dob").click(function () {
    $("#birth_date").val('');
});
$("#register-form").validate({//"id=register-form" in servay.php
    rules: {
        date: {
            required: true
        },
        district_admin: {
            required: true
        },
        datepicker_Onset: {
            required: true
        },
        datepicker_Notification: {
            required: true
        },
        datepicker_Investigation: {

            required: true
        },

        district_ucs: {
            required: true
        },

        stool_sample_date2: {

            required: true
        },
        stool_sample_date1: {

            required: true
        },

        throad_swab_date: {

            required: true
        },
        l_v_d_received_other: {
            required: "#check_l_v_d_received:checked"
        },
        other_case_health_facility: {
            required: "#check:checked"
        },
        blood_sample_date: {

            required: true
        },
        week: {
            required: true
        },
        naso_pharyngeal_swab_date: {

            required: true
        },
        sputum_collection_date_1: {

            required: true
        },
        sputum_collection_date_2: {

            required: true
        },
        district: {
            required: true
        },
        t_o_case: {
            required: true
        },
        child_name: {
            required: true,
            maxLength: 255
        },
        father_name: {
            required: true,
            maxLength: 255
        },
        address1: {
            required: true,
            maxLength: 255
        },
        address2: {
            required: true,
            maxLength: 255
        },
        address_town: {
            required: true,
            maxLength: 255
        },

        age_month: {
            required: true
        },

        "clinical_presentation[]": {
            required: true
        },
        outcome: {
            required: true
        },

        final_classification: {
            required: true
        }



    },
    errorPlacement: function (error, element) {
        if (element.attr("name") == "date" || element.attr("name") == "birth_date" || element.attr("name") == "date_of_lab_resulted" || element.attr("name") == "datepicker_Onset" || element.attr("name") == "datepicker_Notification" || element.attr("name") == "datepicker_Investigation" || element.attr("name") == "datepickerl_v_d_received_Onset" || element.attr("name") == "date_of_specimen_sent" || element.attr("name") == "date_of_specimen_sent1" || element.attr("name") == "l_v_d_received" || element.attr("name") == "other_case_health_facility" || element.attr("name") == "l_v_d_received_other" || element.attr("name") == "age_month" || element.attr("name")) {
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
            $('#district').html(data);

        }
    });
})
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

    $.ajax({
        type: "POST",
        url: appName + "/index/all-level-combos-five-district",
        data: {combo3: $(this).val(), office: 6},
        dataType: 'html',
        success: function (data) {

           // $('#case_health_facility').html(data);

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
//$('#clinical_presentation').select2({
//    placeholder: "Select Sign&Symptoms",
//   // allowClear: true
//});
$('#age_unit').change(function () {
    if ($(this).val() == 1) {
        $('#l_age').html('Age in Months');
    } else if ($(this).val() == 2) {
        $('#age_month').val('');
        $('#l_age').html('Age in Days');
    } else if ($(this).val() == 3) {
        $('#age_month').val('');
        $('#l_age').html('Age in Years');
    }
});


if ($('#district').val() != "") {

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

          //  $('#case_health_facility').html(data);
          //  $('#case_health_facility').val($('#hf_id_hidden').val());

        }
    });


}
if ($('#tehsil_id_hidden').val() != "") {
    $('#loader').show();
    $('#ucs').html('<option value="">Loading...</option>');

    $.ajax({
        type: "POST",
        url: appName + "/index/all-level-combos-four",
        data: {combo3: $('#tehsil_id_hidden').val(), office: 6},
        dataType: 'html',
        success: function (data) {
            $('#loader').hide();

            $('#ucs').html(data);
            $('#ucs').val($('#uc_id_hidden').val());

        }
    });
}


//function form_validate(){
$("#asset_add_popup").validate({

    rules: {
        sign: {
            required: true
        }
    },
    messages: {
        'sign': {
            required: "Please enter Sign & Symptoms"
        }

    },
    submitHandler: function () {
        var form_data = $("#asset_add_popup").serialize();
        $.ajax({
            type: "POST",
            url: appName + "/surveillance/add-new-sign-symptoms?" + form_data,
            data: {},
            dataType: 'html',
            success: function (data) {
                $("#asset_add_popup")[0].reset();
                $(".close").trigger("click");
                $("#clinical_presentation").html(data);
            }
        });
    }
});


$(function () {



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



$('#search').click(function () {
    $(".reload").trigger("click");
    $(this).button('loading');

    $.ajax({
        type: "POST",
        url: appName + "/surveillance/ajax-get-vpd",
        data: $('#search-vpd').serialize(),
        dataType: 'html',
        success: function (data) {
            $('#search').button('reset');
            $('#ajax-table').html(data);

            $("#print_vpd").on("click", function () {
                var id = $('#hdn_vpd_id').val();
                window.open(appName + '/surveillance/print-vpd?id=' + id, '_blank', 'scrollbars=1,width=860,height=595');
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
function printCont()
{
    $("a").hide();
    $('#print').hide();
    window.print();
    setTimeout(function () {
        // Do something after 5 seconds
        $('#print').show();
        $("a").show();
    }, 5000);
}
$("#t_o_case").change(function () {


});
