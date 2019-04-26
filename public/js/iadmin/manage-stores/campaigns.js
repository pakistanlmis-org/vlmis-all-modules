$(function() {
    $('#reset').click(function () {
    window.location.href = appName + '/iadmin/manage-stores/campaigns';
});

    
     $("#starting_on").datepicker({
        minDate: "-3Y",
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });

    $("#from_edit").datepicker({
        minDate: "-3Y",
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
    
    
    $('#records').change(function() {
        var counter = $(this).val();

        document.location.href = appName + '/iadmin/manage-stores/routine/?counter=' + counter;
    });

    // validate signup form on keyup and submit
    $("#update-stores").validate({
        rules: {
            store_name_update: {
                required: true,
                remote: {
                    url: appName + "/iadmin/manage-stores/check-stores-update",
                    type: "post",
                    data: {
                        district: function() {
                            return $("#combo2_edit").val();
                        },
                        province: function() {
                            return $("#combo1_edit").val();
                        },
                        locid: function() {
                            return $("#combo3_edit").val();
                        },
                        stk_id: function() {
                            return '10';
                        },
                        locid_uc: function() {
                            return $("#combo4_edit").val();
                        }

                    }
                }
            },
            combo1_edit: {
                required: true
            },
            combo2_edit: {
                required: true
            },
            combo3_edit: {
                required: true
            },
            combo4_edit: {
                required: true
            }


        },
        store_name_update: {
            location_name_update: "Store Already Available."

        }

    });

    $("#add-stores").validate({
        rules: {
            store_name_add: {
                required: true,
                remote: {
                    url: appName + "/iadmin/manage-stores/check-stores",
                    type: "post",
                    data: {
                        district: function() {
                            return $("#combo2_add").val();
                        },
                        province: function() {
                            return $("#combo1_add").val();
                        },
                        locid: function() {
                            return $("#combo3_add").val();
                        },
                        stk_id: function() {
                            return '10';
                        },
                        locid_uc: function() {
                            return $("#combo4_add").val();
                        }

                    }
                }
            },
            combo1_add: {
                required: true
            },
            combo2_add: {
                required: true
            },
            combo3_add: {
                required: true
            },
            combo4_add: {
                required: true
            }
        },
        store_name_update: {
            store_name_add: "Store Already Available."

        }

    });


    $(".update-stores").click(function() {
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-stores/ajax-campaigns-edit",
            data: {wh_id: $(this).attr('itemid')},
            dataType: 'html',
            success: function(data) {
                $('#modal-body-contents').html(data);

                $('#update-button').show();
                //   alert($('#location_type').val());
                //  $('#location_level_edit').val($('#location_type').val());
                setTimeout(function() {

                    $('#combo1_edit').val($('#province_id_edit').val());


                    if ($('#combo1_edit').val() != "") {
                        $('#loader').show();
                        $('#combo2_edit').empty();

                        $('#div_combo2_edit').hide();

                        $.ajax({
                            type: "POST",
                            url: appName + "/index/locations-combos-two",
                            data: {combo1: $('#province_id_edit').val(), office: 6},
                            dataType: 'html',
                            success: function(data) {
                                $('#loader').hide();

                                var val = '6';

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

                    if ($('#combo2_edit').val() != "") {
                        $('#loader').show();
                        $.ajax({
                            type: "POST",
                            url: appName + "/index/locations-combos-three",
                            data: {combo2: $('#district_id_edit').val(), office: 6},
                            dataType: 'html',
                            success: function(data) {
                                $('#loader').hide();
                                var val = '6';
                                switch (val)
                                {
                                    case '5':
                                        $('#div_combo3_edit').show();
                                        $('#combo3_edit').html(data);
                                        $('#combo3_edit').val($('#tehsil_id_edit').val());
                                        break;

                                    case '6':
                                        $('#div_combo3_edit').show();
                                        $('#combo3_edit').html(data);
                                        $('#combo3_edit').val($('#tehsil_id_edit').val());
                                        break;

                                }
                            }
                        });
                    }

                    if ($('#combo3_edit').val() != "") {
                        $('#loader').show();
                        $.ajax({
                            type: "POST",
                            url: appName + "/index/locations-combos-four",
                            data: {combo3: $('#tehsil_id_edit').val(), office: 6},
                            dataType: 'html',
                            success: function(data) {
                                $('#loader').hide();
                                var val = '6';
                                switch (val)
                                {
                                    case '6':
                                        $('#div_combo4_edit').show();
                                        $('#combo4_edit').html(data);
                                        $('#combo4_edit').val($('#parent_id_edit').val());
                                        break;

                                }
                            }
                        });
                    }


                }, 1000);
            }

        });


    });

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
                            url: appName + "/iadmin/manage-stores/delete",
                            data: {warehouse_id: self.data('bind')},
                            dataType: 'html',
                            success: function(data) {
                                notyfy({
                                    force: true,
                                    text: 'Store has been deleted!',
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

    $('th.sorting, th.sorting_asc, th.sorting_desc').click(function(e) {
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
        }
        else {
            document.location = appName + '/cadmin/manage-makes/?order=' + order + '&sort=' + sort + '&counter=' + counter + '&page=' + page;
        }
    });
});