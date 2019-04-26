$(function() {
 $('#reset').click(function () {
    window.location.href = appName + '/iadmin/manage-geo-levels/index';
});
    // validate form on keyup and submit
    $("#form-add-geo-level").validate({
        rules: {
            geo_level_name: {
                required: true,
                remote: {
                    url: appName + "/iadmin/manage-geo-levels/check-geo-level",
                    type: "post",
                    data: {
                        geo_level_name_hidden: function () {
                            return '';
                        }
                    }

                }
            },
            geo_level_description: {
                required: true
            }
        },
        messages: {
            geo_level_name: {
                required: "Please enter geo level name.",
                remote: "Geo level name already exists."
            },
            geo_level_description: {
                required: "Please enter geo level description."
            }
        }
    });

    // validate form on keyup and submit
    $("#form-update-geo-level").validate({
        rules: {
            geo_level_name: {
                required: true,
                remote: {
                    url: appName + "/iadmin/manage-geo-levels/check-geo-level",
                    type: "post",
                    data: {
                        geo_level_name_hidden: function () {
                            return $("#geo_level_name_hidden").val();
                        }
                    }

                }
            },
            geo_level_description: {
                required: true
            }
        },
        messages: {
            geo_level_name: {
                required: "Please enter geo level name.",
                remote: "Geo level name already exists."
            },
            geo_level_description: {
                required: "Please enter geo level description."
            }
        }
    });
    $('#sample_2').on('click', '.btn-update-geo-level', function() {
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-geo-levels/ajax-geo-level-edit",
            data: {geo_level_id: $(this).attr('levelid')},
            dataType: 'html',
            success: function(data) {
                $('#modal-body-contents').html(data);
                $('#update-button').show();
            }
        });
    });

$( document ).on( "click", "a.active", function() {
        var id = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-geo-levels/ajax-change-status",
            data: {id: id, ajaxaction: 'active'},
            dataType: 'html',
            success: function(data) {
                $('#' + id).html(data);
                $('#' + id).removeClass("active");
                $('#' + id).addClass("deactive");
            }
        });
    });

     $( document ).on( "click", "a.deactive", function() {
        var id = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-geo-levels/ajax-change-status",
            data: {id: id, ajaxaction: 'deactive'},
            dataType: 'html',
            success: function(data) {
                $('#' + id).html(data);
                $('#' + id).removeClass("deactive");
                $('#' + id).addClass("active");
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
                            url: appName + "/iadmin/manage-geo-levels/delete-country",
                            data: {country_id: self.data('bind')},
                            dataType: 'html',
                            success: function(data) {
                                notyfy({
                                    force: true,
                                    text: 'Country has been deleted!',
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
                        });*/
                    }
                }]
        });
        return false;
    });

});