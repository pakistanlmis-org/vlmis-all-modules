$(function () {
    $('#reset').click(function () {
        window.location.href = appName + '/iadmin/manage-users/policy-users';
    });


    $('#records').change(function () {
        var counter = $(this).val();

        document.location.href = appName + '/iadmin/manage-users/policy-users/?counter=' + counter;
    });

    $.validator.addMethod("alphanumeric", function (value, element) {
        return this.optional(element) || /^[a-z0-9\_\s]+$/i.test(value);
    }, "Username must contain only letters, numbers and underscore.");

    // validate signup form on keyup and submit
    $('#office_type_add').change(function () {
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-users/get-policy-roles",
            data: {office_id: $('#office_type_add').val()},
            dataType: 'html',
            success: function (data) {
                $('#role_policy').html(data);
            }
        });

        if ($('#office_type_add').val() == 1) {
            $.ajax({
                type: "POST",
                url: appName + "/iadmin/manage-users/get-default-warehouse-by-level",
                data: {geo_level_id: $('#office_type_add').val()},
                dataType: 'html',
                success: function (data) {
                    $('#default_warehouse').html(data);
                }
            });
        }
        if ($('#office_type_add').val() == 2) {
            $('#combo1_add').change(function () {
                $.ajax({
                    type: "POST",
                    url: appName + "/iadmin/manage-users/get-default-warehouse-by-level",
                    data: {geo_level_id: $('#office_type_add').val(), province_id: $('#combo1_add').val()},
                    dataType: 'html',
                    success: function (data) {
                        $('#default_warehouse').html(data);
                    }
                });
            });
        }
        if ($('#office_type_add').val() == 3) {

            $('#combo1_add').change(function () {
                $.ajax({
                    type: "POST",
                    url: appName + "/iadmin/manage-users/get-default-warehouse-by-level",
                    data: {geo_level_id: $('#office_type_add').val(), province_id: $('#combo1_add').val()},
                    dataType: 'html',
                    success: function (data) {
                        $('#default_warehouse').html(data);
                    }
                });
            });
        }
        if ($('#office_type_add').val() == 4 || $('#office_type_add').val() == 3) {
            $('#combo2_add').change(function () {
                $.ajax({
                    type: "POST",
                    url: appName + "/iadmin/manage-users/get-default-warehouse-by-level",
                    data: {geo_level_id: $('#office_type_add').val(), province_id: $('#combo1_add').val(), district_id: $('#combo2_add').val()},
                    dataType: 'html',
                    success: function (data) {
                        $('#default_warehouse').html(data);
                    }
                });
            });
        }
        if ($('#office_type_add').val() == 5) {
            $('#combo3_add').change(function () {
                $.ajax({
                    type: "POST",
                    url: appName + "/iadmin/manage-users/get-default-warehouse-by-level",
                    data: {geo_level_id: $('#office_type_add').val(), province_id: $('#combo1_add').val(), district_id: $('#combo2_add').val(), tehsil_id: $('#combo3_add').val()},
                    dataType: 'html',
                    success: function (data) {
                        $('#default_warehouse').html(data);
                    }
                });
            });
        }
    });
    $("#update-stores").validate({
        rules: {
            login_id_update: {
                required: true,
                alphanumeric: true,
                remote: {
                    url: appName + "/iadmin/manage-users/check-users-update-policy",
                    type: "post",
                    data: {
                        user_name_update: function () {
                            return $("#login_id_update").val();
                        },
                        user_name_update_hidden: function () {
                            return $("#user_name_update_hidden").val();
                        }


                    }

                }
            },
            old_password: {
                required: true,
                remote: {
                    url: appName + "/iadmin/manage-users/check-old-password",
                    type: "post",
                    data: {
                        user_id: function () {
                            return $("#user_id").val();
                        }


                    }

                }
            },
            user_name_update: {
                required: true

            },
            email_update: {
                required: true,
                email: true
            },
            phone_update: {
                required: true,
                number: true
            },
            new_password: {
                required: true,
            },
            office_type_edit: {
                required: true
            },
            combo1_edit: {
                required: true
            },
            combo2_edit: {
                required: true
            }


        },
        user_name_update: {
            user_name_update: "Please enter valid Username"

        },
        email_update: {
            required: "Enter email"
        },
        phone_update: {
            required: "Enter phone number",
            number: jQuery.format("Enter Correct Format")

        },
        old_password: {
            required: "Enter old password"
        },
        new_password: {
            required: "Enter confirm password",
            equalTo: jQuery.format("Please enter the same password")
        }

    });

    $("#add-stores").validate({
        rules: {
            login_id_add: {
                required: true,
                alphanumeric: true,
                remote: {
                    url: appName + "/iadmin/manage-users/check-users-policy",
                    type: "post",
                    data: {
                        login_id_add: function () {
                            return $("#login_id_add").val();
                        }


                    }

                }
            },
            user_name_add: {
                required: true

            },
            email: {
                required: true,
                email: true
            },
            phone: {
                required: true,
                number: true
            },
            password: {
                required: true
            },
            confirm_password: {
                required: true,
                equalTo: "#password"
            },
            office_type_add: {
                required: true
            },
            combo1_add: {
                required: true
            },
            combo2_add: {
                required: true
            }

        },
        messages: {
            login_id_add: {
                required: "Enter a login ID",
                alphanumeric: jQuery.format("Enter Correct Format"),
                remote: jQuery.format("{0} is already in use")
            },
            email: {
                required: "Enter email"
            },
            phone: {
                required: "Enter phone number",
                number: jQuery.format("Enter Correct Format")

            },
            password: {
                required: "Enter password"
            },
            confirm_password: {
                required: "Enter confirm password",
                equalTo: jQuery.format("Please enter the same password")
            }
        }

    });




    $('#sample_2').on('click', '.update-stores', function () {
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-users/ajax-edit-policy",
            data: {wh_id: $(this).attr('itemid')},
            dataType: 'html',
            success: function (data) {
                $('#modal-body-contents').html(data);

                $('#update-button').show();
                //   alert($('#location_type').val());
                //  $('#location_level_edit').val($('#location_type').val());
                setTimeout(function () {

                    $('#office_type_edit').val($('#office_id_edit').val());

                    if ($('#office_type_edit').val() != "") {

                        $('#loader').show();
                        $('#combo1_edit').empty();
                        $('#combo2_edit').empty();
                        $('#combo3_edit').empty();
                        $('#combo4_edit').empty();

                        $('#div_combo1_edit').hide();
                        $('#div_combo2_edit').hide();
                        $('#div_combo3_edit').hide();
                        $('#div_combo4_edit').hide();

                        $("#office_type_edit").change(function () {
                            $.ajax({
                                type: "POST",
                                url: appName + "/index/locations-combos-one",
                                data: {
                                    office: $('#office_type_edit').val(),
                                },
                                dataType: 'html',
                                success: function (data) {
                                    $('#loader').hide();
                                    var val1 = $('#office_type_edit').val();
                                    switch (val1) {
                                        case '1':
                                            $('#div_combo1_edit').hide();
                                            $('#div_combo2_edit').hide();
                                            $('#div_combodiv_edit').hide();
                                            $('#div_combo3_edit').hide();
                                            break;
                                        case '2':
                                            $('#div_combo2_edit').hide();
                                            $('#div_combodiv_edit').hide();
                                            $('#div_combo3_edit').hide();
                                            $('#lblcombo1_edit').html('Province <span class="red">*</span>');
                                            $('#div_combo1_edit').show();
                                            $('#combo1_edit').html(data);
                                            $('#combo1_edit').val($('#province_id_edit').val());
                                            break;
                                        case '3':
                                            $('#div_combo2_edit').hide();
                                            $('#div_combo3_edit').hide();
                                            $('#lblcombo1_edit').html('Province <span class="red">*</span>');
                                            $('#div_combo1_edit').show();
                                            $('#combo1_edit').html(data);
                                            $('#combo1_edit').val($('#province_id_edit').val());
                                        case '4':
                                            $('#div_combodiv_edit').hide();
                                            $('#div_combo3_edit').hide();
                                            $('#lblcombo1_edit').html('Province <span class="red">*</span>');
                                            $('#div_combo1_edit').show();
                                            $('#combo1_edit').html(data);
                                            $('#combo1_edit').val($('#province_id_edit').val());
                                            break;
                                        case '5':
                                            $('#div_combodiv_edit').hide();
                                            break;
                                    }
                                }
                            });
                        });

                        $("#combo1_edit").change(function () {

//                        Province to District
                            $.ajax({
                                type: "POST",
                                url: appName + "/reports/inventory-management/prov-2dist",
                                data: {
                                    prov_sel: $('#combo1_edit').val(),
                                    combo: 2
                                },
                                dataType: 'html',
                                success: function (data) {
                                    $('#loader').hide();
                                    var val1 = $('#office_type_edit').val();
                                    switch (val1) {
                                        case '1':
                                            $('#div_combo1_edit').hide();
                                            $('#div_combodiv_edit').hide();
                                            $('#div_combo2_edit').hide();
                                            break;
                                        case '2':
                                            $('#div_combodiv_edit').hide();
                                            $('#div_combo2_edit').hide();
                                            break;
                                        case '3':
                                            break;
                                        case '4':
                                            $('#div_combodiv_edit').hide();
                                            $('#lblcombo2_edit').html('District <span class="red">*</span>');
                                            $('#div_combo2_edit').show();
                                            $('#combo2_edit').html(data);
                                            break;
                                        case '5':
                                            $('#lblcombo2_edit').html('District <span class="red">*</span>');
                                            $('#div_combo2_edit').show();
                                            $('#combo2_edit').html(data);
                                            break;
                                    }
                                }
                            });
                        });

                        $.ajax({
                            type: "POST",
                            url: appName + "/index/locations-combos-one",
                            data: {office: $('#office_type_edit').val()},
                            dataType: 'html',
                            success: function (data) {
                                $('#loader').hide();
                                var val1 = $('#office_type_edit').val();
                                switch (val1) {
                                    case '2':
                                        $('#lblcombo1_edit').text('Province');
                                        $('#div_combo1_edit').show();
                                        $('#combo1_edit').html(data);
                                        $('#combo1_edit').val($('#province_id_edit').val());
                                        break;
                                    case '3':
                                        $('#lblcombo1_edit').text('Province');
                                        $('#div_combo1_edit').show();
                                        $('#combo1_edit').html(data);
                                        $('#combo1_edit').val($('#province_id_edit').val());
                                        break;
                                    case '4':
                                        $('#lblcombo1_edit').text('Province');
                                        $('#div_combo1_edit').show();
                                        $('#combo1_edit').html(data);
                                        $('#combo1_edit').val($('#province_id_edit').val());
                                        break;
                                    case '5':
                                        $('#lblcombo1_edit').text('Province');
                                        $('#div_combo1_edit').show();
                                        $('#combo1_edit').html(data);
                                        $('#combo1_edit').val($('#province_id_edit').val());
                                        break;
                                    case '6':
                                        $('#lblcombo1_edit').text('Province');
                                        $('#div_combo1_edit').show();
                                        $('#combo1_edit').html(data);
                                        $('#combo1_edit').val($('#province_id_edit').val());
                                        break;
                                }
                            }
                        });
                    }

                    if ($('#combo1_edit').val() != "") {
                        $('#loader').show();
                        $('#combo2_edit').empty();

                        $('#div_combo2_edit').hide();

                        $.ajax({
                            type: "POST",
                            url: appName + "/index/locations-combos-two",
                            data: {combo1: $('#province_id_edit').val(), office: $('#office_type_edit').val()},
                            dataType: 'html',
                            success: function (data) {
                                $('#loader').hide();

                                var val = $('#office_type_edit').val();

                                switch (val)
                                {
                                    case '4':
                                        $('#div_combo2_edit').show();
                                        $('#combo2_edit').html(data);
                                        $('#combo2_edit').val($('#district_id_edit').val());
                                        break;
                                    case '5':
                                        $('#div_combo2_edit').show();
                                        $('#combo2_edit').html(data);
                                        $('#combo2_edit').val($('#district_id_edit').val());
                                        break;
                                    case '6':
                                        $('#div_combo2_edit').show();
                                        $('#combo2_edit').html(data);
                                        $('#combo2_edit').val($('#district_id_edit').val());
                                        break;
                                }
                            }
                        });
                    }

//                    if ($('#combo2_edit').val() != "") {
//                        $('#loader').show();
//                        $.ajax({
//                            type: "POST",
//                            url: appName + "/index/locations-combos-three",
//                            data: {combo2: $('#district_id_edit').val(), office: $('#office_type_edit').val()},
//                            dataType: 'html',
//                            success: function (data) {
//                                $('#loader').hide();
//                                var val = $('#office_type_edit').val();
//                                switch (val)
//                                {
//                                    case '5':
//                                        $('#div_combo3_edit').show();
//                                        $('#combo3_edit').html(data);
//                                        $('#combo3_edit').val($('#tehsil_id_edit').val());
//                                        break;
//
//                                    case '6':
//                                        $('#div_combo3_edit').show();
//                                        $('#combo3_edit').html(data);
//                                        $('#combo3_edit').val($('#tehsil_id_edit').val());
//                                        break;
//
//                                }
//                            }
//                        });
//                    }
//
//                    if ($('#combo3_edit').val() != "") {
//                        $('#loader').show();
//                        $.ajax({
//                            type: "POST",
//                            url: appName + "/index/locations-combos-four",
//                            data: {combo3: $('#tehsil_id_edit').val(), office: $('#office_type_edit').val()},
//                            dataType: 'html',
//                            success: function (data) {
//                                $('#loader').hide();
//                                var val = $('#office_type_edit').val();
//                                switch (val)
//                                {
//                                    case '6':
//                                        $('#div_combo4_edit').show();
//                                        $('#combo4_edit').html(data);
//                                        $('#combo4_edit').val($('#parent_id_edit').val());
//                                        break;
//
//                                }
//                            }
//                        });
//                    }


                }, 1000);
            }

        });


    });
    $('#sample_2').on('click', '[data-toggle="notyfy"]', function () {

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
                    onClick: function ($notyfy) {
                        $notyfy.close();
                        $.ajax({
                            type: "POST",
                            url: appName + "/cadmin/manage-makes/delete",
                            data: {make_id: self.data('bind')},
                            dataType: 'html',
                            success: function (data) {
                                notyfy({
                                    force: true,
                                    text: 'Make has been deleted!',
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
                    onClick: function ($notyfy) {
                        $notyfy.close();
//                        notyfy({
//                            force: true,
//                            text: '<strong>You clicked "Cancel" button<strong>',
//                            type: 'error',
//                            layout: self.data('layout')
//                        });
                    }
                }]
        });
        return false;
    });

    $('th.sorting, th.sorting_asc, th.sorting_desc').click(function (e) {
        e.preventDefault();

        var self = $(this);

        var make_name = '';
        var order = '';
        var sort = '';
        var counter = '';
        var page = '';

        order = self.data('order');
        sort = self.data('sort');

        make_name = $('#name').val();
        counter = $('#records').val();
        page = $('#current').val();

        if (make_name.length > 1) {
            document.location = appName + '/cadmin/manage-makes/?order=' + order + '&sort=' + sort + '&name=' + make_name + '&counter=' + counter + '&page=' + page;
        } else {
            document.location = appName + '/cadmin/manage-makes/?order=' + order + '&sort=' + sort + '&counter=' + counter + '&page=' + page;
        }
    });
});