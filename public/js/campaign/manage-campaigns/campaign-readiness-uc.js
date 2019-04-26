$(function() {

    $(function() {
        $('#records').change(function() {
            var counter = $(this).val();
            document.location.href = appName + '/campaign/manage-campaigns/campaign-readiness-uc/?counter=' + counter;
        });
    });

    $("#campaign_id").change(function() {
        $.ajax({
            type: "POST",
            url: appName + "/campaign/manage-campaigns/ajax-for-campaign-uc",
            data: {condition: 003, campaign_id: $('#campaign_id').val()},
            dataType: 'html',
            success: function(data) {
                $('#uc_id').html(data);
            }
        });
    });


    if ($("#campaign_id").val() != "") {
        $.ajax({
            type: "POST",
            url: appName + "/campaign/manage-campaigns/ajax-for-campaign-uc",
            data: {condition: 003, campaign_id: $('#campaign_id').val()},
            dataType: 'html',
            success: function(data) {

                $('#uc_id').html(data);

                $('#uc_id').val($('#uc_id_hidden').val());
            }
        });
    }

    $("#uc_add_id").change(function() {
        $.ajax({
            type: "POST",
            url: appName + "/campaign/manage-campaigns/ajax-campaign-target-uc",
            data: {uc_id: $(this).val(), campaign_id: $('#campaign_add_id').val()},
            dataType: 'html',
            success: function(data) {

                $('#target').val(data);

            }
        });
    });


    $(".update-user").click(function() {
        $.ajax({
            type: "POST",
            url: appName + "/campaign/manage-campaigns/ajax-edit-readiness-uc",
            data: {item_id: $(this).attr('itemid')},
            dataType: 'html',
            success: function(data) {
                $('#modal-body-contents').html(data);
                $('#update-button').show();
                $(function() {

                    if ($("#campaign_edit_id").val() != "") {
                        $.ajax({
                            type: "POST",
                            url: appName + "/campaign/manage-campaigns/ajax-for-campaign-uc",
                            data: {condition: 003, campaign_id: $('#campaign_edit_id').val()},
                            dataType: 'html',
                            success: function(data) {

                                $('#uc_edit_id').html(data);
                                $('#uc_edit_id').val($('#uc_edit_id_hidden').val());
                            }
                        });
                    }

                    if ($("#uc_edit_id_hidden").val() != "") {

                        $.ajax({
                            type: "POST",
                            url: appName + "/campaign/manage-campaigns/ajax-campaign-target-uc",
                            data: {uc_id: $('#uc_edit_id_hidden').val(), campaign_id: $('#campaign_edit_id').val()},
                            dataType: 'html',
                            success: function(data) {

                                $('#target_uc').val(data);

                            }
                        });
                    }
                });

                $("#date_upec_meeting").datepicker({
                    minDate: "-3Y",
                    maxDate: 0,
                    dateFormat: 'dd/mm/yy',
                    changeMonth: true,
                    changeYear: true
                });
            }
        });
    });

    $(".add-user").click(function() {
        var arrItem = $(this).attr('itemid').split(',');
        $.ajax({
            type: "POST",
            url: appName + "/campaign/manage-campaigns/ajax-add-readiness-uc",
            data: {campaign_id: arrItem[0], warehouse_id: arrItem[1]},
            dataType: 'html',
            success: function(data) {
                $('#modal-body-add').html(data);

                $("#date_upec_meeting").datepicker({
                    minDate: "-3Y",
                    maxDate: 0,
                    dateFormat: 'dd/mm/yy',
                    changeMonth: true,
                    changeYear: true
                });

                $("#add-user").validate({
                    rules: {
                        campaign_add_id: {
                            required: true
                        },
                        uc_add_id: {
                            required: true
                        },
                        inaccessible_children: {
                            required: true,
                            number: true,
                            min: 0
                        },
                        no_of_mobile_teams: {
                            required: true,
                            number: true,
                            min: 0
                        },
                        inaccessible_area: {
                            required: true,
                            number: true,
                            min: 0
                        },
                        no_of_fixed_teams: {
                            required: true,
                            number: true,
                            min: 0
                        },
                        area_in_charge: {
                            required: true
                        },
                        no_of_transist_points: {
                            required: true,
                            number: true,
                            min: 0
                        },
                        aics_trained: {
                            required: true,
                            number: true,
                            min: 0
                        },
                        no_of_teams_trainedno_of_teams_trained: {
                            required: true,
                            number: true,
                            min: 0
                        },
                        area_mobile_population: {
                            required: true,
                            number: true,
                            min: 0
                        },
                        date_upec_meeting: {
                            required: true
                        }
                    }

                });
            }
        });
    });

    $("#update-user").validate({
        rules: {
            campaign_add_id: {
                required: true
            },
            uc_add_id: {
                required: true
            },
            inaccessible_children: {
                required: true
            },
            no_of_mobile_teams: {
                required: true,
                number: true,
                min: 0
            },
            inaccessible_area: {
                required: true,
                number: true,
                min: 0
            },
            no_of_fixed_teams: {
                required: true,
                number: true,
                min: 0
            },
            area_in_charge: {
                required: true
            },
            no_of_transist_points: {
                required: true,
                number: true,
                min: 0
            },
            aics_trained: {
                required: true,
                number: true,
                min: 0
            },
            no_of_teams_trainedno_of_teams_trained: {
                required: true,
                number: true,
                min: 0
            },
            area_mobile_population: {
                required: true,
                number: true,
                min: 0
            },
            date_upec_meeting: {
                required: true
            }
        }

    });

});


