$(function() {

    $('#office3').val($('#office_id').val());


    if ($('#office3').val() != "") {
        $('#loader3').show();
        $('#combo13').empty();
        $('#combo23').empty();
        $('#warehouse3').empty();
        $('#div_combo13').hide();
        $('#div_combo23').hide();
        $('#wh_combo3').hide();
        $.ajax({
            type: "POST",
            url: appName + "/index/all-level-combos-one",
            data: {office: $('#office_id').val()},
            dataType: 'html',
            success: function(data) {
                $('#loader3').hide();
                var val1 = $('#office3').val();
                switch (val1) {
                    case '1':
                        $('#wh_l3').html('Warehouse');
                        $('#wh_combo3').show();
                        $('#warehouse3').html(data);
                        $('#warehouse3').val($('#warehouse_id').val());
                        break;
                    case '2':
                        $('#lblcombo13').text('Province');
                        $('#div_combo13').show();
                        $('#combo13').html(data);
                        $('#combo13').val($('#combo1_id').val());
                        break;
                    case '3':
                        $('#lblcombo13').text('Province');
                        $('#div_combo13').show();
                        $('#combo13').html(data);
                        $('#combo13').val($('#combo1_id').val());
                        break;
                    case '4':
                        $('#lblcombo13').text('Province');
                        $('#div_combo13').show();
                        $('#combo13').html(data);
                        $('#combo13').val($('#combo1_id').val());
                        break;
                    case '5':
                        $('#lblcombo13').text('Province');
                        $('#div_combo13').show();
                        $('#combo13').html(data);
                        $('#combo13').val($('#combo1_id').val());
                        break;
                    case '6':
                        $('#lblcombo13').text('Province');
                        $('#div_combo13').show();
                        $('#combo13').html(data);
                        $('#combo13').val($('#combo1_id').val());
                        break;
                }
            }
        });
    }


    if ($('#combo13').val() != "") {
        $('#loader3').show();
        $('#combo23').empty();

        $('#warehouse3').empty();

        $('#div_combo23').hide();
        $('#wh_combo3').hide();

        $.ajax({
            type: "POST",
            url: appName + "/index/all-level-combos-two",
            data: {combo1: $('#combo1_id').val(), office: $('#office_id').val()},
            dataType: 'html',
            success: function(data) {
                $('#loader3').hide();

                var val = $('#office3').val();
                switch (val)
                {
                    case '2':
                        $('#wh_combo3').show();
                        $('#warehouse3').html(data);
                        $('#warehouse3').val($('#warehouse_id').val());
                        break;
                    case '3':
                        $('#wh_combo3').show();
                        $('#warehouse3').html(data);
                        $('#warehouse3').val($('#warehouse_id').val());
                        break;
                    case '4':
                        $('#wh_combo3').show();
                        $('#warehouse3').html(data);
                        $('#warehouse3').val($('#warehouse_id').val());
                        break;
                    case '5':
                        $('#lblcombo23').text('Districts');
                        $('#div_combo23').show();
                        $('#combo23').show();
                        $('#combo23').html(data);
                        $('#combo23').val($('#combo2_id').val());
                        break;
                    case '6':
                        $('#lblcombo23').text('Districts');
                        $('#div_combo23').show();
                        $('#combo23').show();
                        $('#combo23').html(data);
                        $('#combo23').val($('#combo2_id').val());
                        break;


                }
            }
        });
    }



    // validate signup form on keyup and submit
    $("#add-user").validate({
        rules: {
            health_facility_type: {
                required: true

            },
            routine_immunization_ice_pack: {
                required: true

            },
            snid_nid_ice_pack: {
                required: true

            },
            vaccine_supply_mode: {
                required: true

            },
            grid_electricity_availibility: {
                required: true

            },
            'epi_vaccination_staff[]':
                    {
                        required: true,
                        minlength: 1
                    },
            'services_type[]': {
                required: true,
                minlength: 1
            },
            'solar_energy[]': {
                required: true,
                minlength: 1
            },
            office: {
                required: true

            },
            combo1: {
                required: true

            },
            warehouse: {
                required: true

            }
        }

    });

    // validate signup form on keyup and submit
    $("#update-user").validate({
        rules: {
            health_facility_type: {
                required: true

            },
            routine_immunization_ice_pack: {
                required: true

            },
            snid_nid_ice_pack: {
                required: true

            },
            vaccine_supply_mode: {
                required: true

            },
            grid_electricity_availibility: {
                required: true

            },
            'epi_vaccination_staff[]':
                    {
                        required: true,
                        minlength: 1
                    },
            'services_type[]': {
                required: true,
                minlength: 1
            },
            'solar_energy[]': {
                required: true,
                minlength: 1
            }
        }

    });

    $(".update-user").click(function() {
        $.ajax({
            type: "POST",
            url: appName + "/cadmin/manage-health-facility/ajax-edit",
            data: {ccm_warehouse_id: $(this).attr('itemid')},
            dataType: 'html',
            success: function(data) {
                $('#modal-body-contents').html(data);
                $('#update-button').show();
            }
        });
    });
//    $("#warehouse").change(function() {
//
//        $.ajax({
//            type: "POST",
//            url: appName + "/cadmin/manage-health-facility/ajax-get-health-facility-type",
//            data: {wh_id: $('#office').val()},
//            dataType: 'html',
//            success: function(data) {
//                $('#health_facility_type').html(data);
//
//            }
//        });
//        
//    });


    $('[data-toggle="notyfy"]').click(function()
    {
        $.notyfy.closeAll();
        var self = $(this);

        notyfy({
            text: 'Do you want to continue?',
            type: self.data('type'),
            dismissQueue: true,
            layout: self.data('layout'),
            buttons: (self.data('type') != 'confirm') ? false : [{
                    addClass: 'btn btn-success btn-small btn-icon glyphicons ok_2',
                    text: '<i></i> Ok',
                    onClick: function($notyfy) {
                        $notyfy.close();
                        $.ajax({
                            type: "POST",
                            url: appName + "/cadmin/manage-users/delete",
                            data: {user_id: self.data('bind')},
                            dataType: 'html',
                            success: function(data) {
                                notyfy({
                                    force: true,
                                    text: 'User has been deleted!',
                                    type: 'success',
                                    layout: self.data('layout')
                                });
                                self.closest("tr").remove();
                            }
                        });
                    }
                }, {
                    addClass: 'btn btn-danger btn-small btn-icon glyphicons remove_2',
                    text: '<i></i> Cancel',
                    onClick: function($notyfy) {
                        $notyfy.close();
                     /*   notyfy({
                            force: true,
                            text: '<strong>You clicked "Cancel" button<strong>',
                            type: 'error',
                            layout: self.data('layout')
                        }); */
                    }
                }]
        });
        return false;
    });
    $(function() {

        $("#estimation_year").datepicker({
            dateFormat: 'dd/mm/yy',
            constrainInput: false,
            changeMonth: true,
            changeYear: true
        });

    });
    // GRID Sorting Start
    $('th.sorting, th.sorting_asc, th.sorting_desc').click(function(e) {
        e.preventDefault();

        var self = $(this);

        var login_id = '';
        var role = '';
        var order = '';
        var sort = '';
        var counter = '';
        var page = '';

        order = self.data('order');
        sort = self.data('sort');

        login_id = $('#login_id').val();
        role = $('#role').val();
        counter = $('#records').val();
        page = $('#current').val();

        if (login_id.length > 0 && role.length > 0) {
            document.location = appName + '/cadmin/manage-users/?order=' + order + '&sort=' + sort + '&login_id=' + login_id + '&role=' + role + '&counter=' + counter + '&page=' + page;
        } else if (login_id.length > 0) {
            document.location = appName + '/cadmin/manage-users/?order=' + order + '&sort=' + sort + '&login_id=' + login_id + '&counter=' + counter + '&page=' + page;
        } else if (role.length > 0) {
            document.location = appName + '/cadmin/manage-users/?order=' + order + '&sort=' + sort + '&role=' + role + '&counter=' + counter + '&page=' + page;
        } else {
            document.location = appName + '/cadmin/manage-users/?order=' + order + '&sort=' + sort + '&counter=' + counter + '&page=' + page;
        }
    });
    // GRID Sorting End

    // GRID Counter Start
    $('#records').change(function(e) {
        e.preventDefault();

        var login_id = '';
        var role = '';

        login_id = $('#login_id').val();
        role = $('#role').val();
        counter = $(this).val();
        page = $('#current').val();

        if (login_id.length > 0 && role.length > 0) {
            document.location = appName + '/cadmin/manage-users/?login_id=' + login_id + '&role=' + role + '&counter=' + counter + '&page=' + page;
        } else if (login_id.length > 0) {
            document.location = appName + '/cadmin/manage-users/?login_id=' + login_id + '&counter=' + counter + '&page=' + page;
        } else if (role.length > 0) {
            document.location = appName + '/cadmin/manage-users/?role=' + role + '&counter=' + counter + '&page=' + page;
        } else {
            document.location = appName + '/cadmin/manage-users/?counter=' + counter + '&page=' + page;
        }
    });
    // GRID Counter End
});