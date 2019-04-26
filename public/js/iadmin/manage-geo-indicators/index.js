$(function() {

        $('#reset').click(function () {
            window.location.href = appName + '/iadmin/manage-geo-indicators/index';
        });

    // validate form on keyup and submit
    $("#form-add-geo-indicator").validate({
        rules: {
            geo_indicator_name: {
                required: true,
                remote: {
                    url: appName + "/iadmin/manage-geo-indicators/check-geo-indicator",
                    type: "post",
                    data: {
                        geo_indicator_name_hidden: function() {
                            return '';
                        }
                    }

                }
            }
        },
        messages: {
            geo_indicator_name: {
                required: "Please enter geo indicator name.",
                remote: "Geo indicator name already exists."
            }
        }
    });

    // validate form on keyup and submit
    $("#form-update-geo-indicator").validate({
        rules: {
            geo_indicator_name: {
                required: true,
                remote: {
                    url: appName + "/iadmin/manage-geo-indicators/check-geo-indicator",
                    type: "post",
                    data: {
                        geo_indicator_name_hidden: function() {
                            return $("#geo_indicator_name_hidden").val();
                        }
                    }

                }
            }
        },
        messages: {
            geo_indicator_name: {
                required: "Please enter geo indicator name.",
                remote: "Geo indicator name already exists."
            }
        }
    });
    $('#sample_2').on('click', '.btn-update-geo-indicator', function() {
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-geo-indicators/ajax-geo-indicator-edit",
            data: {geo_indicator_id: $(this).attr('indicatorid')},
            dataType: 'html',
            success: function(data) {
                $('#modal-body-contents').html(data);
                $('#update-button').show();
            }
        });
    });

//$( document ).on( "click", "a.active", function() {
//        var id = $(this).attr('id');
//        $.ajax({
//            type: "POST",
//            url: appName + "/iadmin/manage-geo-levels/ajax-change-status",
//            data: {id: id, ajaxaction: 'active'},
//            dataType: 'html',
//            success: function(data) {
//                $('#' + id).html(data);
//                $('#' + id).removeClass("active");
//                $('#' + id).addClass("deactive");
//            }
//        });
//    });
//
//     $( document ).on( "click", "a.deactive", function() {
//        var id = $(this).attr('id');
//        $.ajax({
//            type: "POST",
//            url: appName + "/iadmin/manage-geo-levels/ajax-change-status",
//            data: {id: id, ajaxaction: 'deactive'},
//            dataType: 'html',
//            success: function(data) {
//                $('#' + id).html(data);
//                $('#' + id).removeClass("deactive");
//                $('#' + id).addClass("active");
//            }
//        });
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
                            url: appName + "/iadmin/manage-geo-indicators/delete-indicator",
                            data: {country_id: self.data('bind')},
                            dataType: 'html',
                            success: function(data) {
                                notyfy({
                                    force: true,
                                    text: 'Indicator has been deleted!',
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
    
    $("#btn-add-geo-indicator").click(function() {
        var form = $("#form-add-geo-indicator");
        form.validate();
    });
    
    $("#btn-update-geo-indicator").click(function() {
        var form = $("#form-update-geo-indicator");
        form.validate();
    });

});