$(function() {
    $('#ccm_asset_type_id').change(function() {
        $('#ccm_make_id').html('');
        if ($(this).val() != "") {
            $('#loader_make').show();
            $.ajax({
                type: "POST",
                url: appName + "/cadmin/manage-models/ajax-get-all-makes-by-asset-type",
                data: {type_id: $(this).val()},
                dataType: 'html',
                success: function(data) {
                    $('#loader_make').hide();
                    $('#ccm_make_id').html(data);
                    $('#ccm_make_id').css('backgroundColor', 'Green');
                    $.cookie('blink_div_background_color', "ccm_make_id");
                    setTimeout(changeColor, 1000);
                }
            });
        }
    });

    $('#ccm_asset_type_id_popup').change(function() {
        $('#ccm_asset_sub_type').html('');
        if ($(this).val() != "") {
            $('#ccm_asset_type_id_popup').removeClass("span3");
            $('#ccm_asset_type_id_popup').addClass("span2");
            $('#loader_asset_type').show();
            $.ajax({
                type: "POST",
                url: appName + "/cadmin/manage-models/ajax-get-asset-subtypes-by-asset-type",
                data: {type_id: $(this).val()},
                dataType: 'html',
                success: function(data) {
                    $('#loader_asset_type').hide();
                    $('#ccm_asset_type_id_popup').removeClass("span2");
                    $('#ccm_asset_type_id_popup').addClass("span3");
                    $('#ccm_asset_sub_type').html(data);
                    $('#ccm_asset_sub_type').css('backgroundColor', 'Green');
                    $.cookie('blink_div_background_color', "ccm_asset_sub_type");
                    setTimeout(changeColor, 1000);
                }
            });
        }
    });

    $('#ccm_status_list_id').change(function() {
        $('#reason').html("");
        $('#utilization').html("");

        if ($(this).val() == "3") {
            $('#loader_reason').show();
            $.ajax({
                type: "POST",
                url: appName + "/cadmin/manage-models/ajax-get-reasons",
                data: {working_status: $(this).val()},
                dataType: 'html',
                success: function(data) {
                    $('#reason').html(data);
                    $('#reason').show();
                    $('#reason_div').show();
                    $('#reason_div').removeClass("hidden");
                }
            });

            $.ajax({
                type: "POST",
                url: appName + "/cadmin/manage-models/ajax-get-utilizations",
                data: {working_status: $(this).val()},
                dataType: 'html',
                success: function(data) {
                    $('#utilization').show();
                    $('#utilization_div').show("slow");
                    $('#utilization').html(data);
                    $('#utilization_div').removeClass("hidden");
                    $('#loader_reason').hide();
                }
            });
        } else {
            $('#reason_div').hide("");
            $('#utilization_div').hide("");
            $('#reason_div').addClass("hidden");
            $('#utilization_div_div').addClass("hidden");
        }
    });

    // validate form on keyup and submit
    $("#add-model").validate({
        rules: {
            'ccm_asset_type_id_popup': {
                required: true
            },
            /*'ccm_asset_sub_type': {
             required: true
             },*/
            'ccm_make_id': {
                required: true
            },
            'ccm_model_name': {
                required: true
            }
        },
        messages: {
            ccm_asset_type_id_popup: "Please select asset type",
            //ccm_asset_sub_type: "Please select asset sub type",
            ccm_make_id: "Please select make",
            ccm_model_name: "Please enter model name"
        }
    });

    $(".update-model").click(function() {
        $.ajax({
            type: "POST",
            url: appName + "/cadmin/manage-models/ajax-edit",
            data: {model_id: $(this).attr('itemid')},
            dataType: 'html',
            success: function(data) {
                $('#modal-body-contents').html(data);
                $('#update-button').show();
            }
        });
    });

    $(".detail-model").click(function() {
        $.ajax({
            type: "POST",
            url: appName + "/cadmin/manage-models/ajax-detail",
            data: {model_id: $(this).attr('itemid')},
            dataType: 'html',
            success: function(data) {
                $('#modal-body-contents').html(data);
                $('#update-button').show();
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
                            url: appName + "/cadmin/manage-models/delete",
                            data: {user_id: self.data('bind')},
                            dataType: 'html',
                            success: function(data) {
                                notyfy({
                                    force: true,
                                    text: 'Model has been deleted!',
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
                 /*       notyfy({
                            force: true,
                            text: '<strong>You clicked "Cancel" button<strong>',
                            type: 'error',
                            layout: self.data('layout')
                        });*/
                    }
                }]
        });
        return false;
    });

    //var val = $('input:radio[name=status]:checked').val();
    //$("#status-all").attr("checked", true);

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

    $( document ).on( "click", "a.active", function() {
        var id = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: appName + "/cadmin/manage-models/ajax-change-status",
            data: {id: id, ajaxaction: 'deactive'},
            dataType: 'html',
            success: function(data) {
                $('#' + id).html(data);
                $('#' + id).removeClass("active");
                $('#' + id).addClass("deactivate");
            }
        });
    });

    $( document ).on( "click", "a.deactivate", function() {
        var id = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: appName + "/cadmin/manage-models/ajax-change-status",
            data: {id: id, ajaxaction: 'active'},
            dataType: 'html',
            success: function(data) {
                $('#' + id).html(data);
                $('#' + id).removeClass("deactivate");
                $('#' + id).addClass("active");
            }
        });
    });


});

