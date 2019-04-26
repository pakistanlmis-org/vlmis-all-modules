$(function () {
    $('#reset').click(function () {
        window.location.href = appName + '/iadmin/manage-locations/location-population';
    });


    $('#records').change(function () {
        var counter = $(this).val();

        document.location.href = appName + '/iadmin/manage-locations/?counter=' + counter;
    });

    $("#add-asset-sub-type").click(function () {
        $('#population').empty();
    });

    $("#year,#location_level1").change(function () {
        $('#location_name').empty();
        $.ajax({
            type: "POST",
            url: appName + "/index/ajax-locations-by-year-level",
            data: {
                level: $('#location_level1').val(),
                year: $('#year').val(),
            },
            dataType: 'html',
            success: function (data) {
                $("#location_name").html(data);
            }
        });
    });

    $('#location_level_add').change(function () {
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-locations/get-location-types",
            data: {geo_level_id: $(this).val()},
            dataType: 'html',
            success: function (data) {
                $('#location_type_id').html(data);
            }
        });
    });




    $("#locations-add").validate({
        rules: {
            population: {
                number: true,
                required: true
            },
            location_name: {
                required: true
            }


        },
        messages: {
            population: {
                required: "Please enter population"
            },
            location_name: {
                required: "Please select location name"

            }
        }


    });

    $("#update-locations").validate({
        rules: {
            population: {
                number: true,
                required: true
            }


        },
        messages: {
            population: {
                required: "Please enter population"
            }
        }


    });

    $('#sample_2').on('click', '.update-locations', function () {

        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-locations/ajax-population-edit",
            data: {
                location_id: $(this).attr('itemid'),
                prov: $(this).attr('prov'),
                geolevelname: $(this).attr('geolevelname'),
                population: $(this).attr('population'),
                locPopId: $(this).attr('locPopId'),
                locPopEstimationDate: $(this).attr('estimationDate')
            },
            dataType: 'html',
            success: function (data) {
                $('#modal-body-contents').html(data);


            }

        });


    });

    $('[data-toggle="notyfy"]').click(function ()
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
                    onClick: function ($notyfy) {
                        $notyfy.close();
                        $.ajax({
                            type: "POST",
                            url: appName + "/iadmin/manage-locations/delete",
                            data: {location_id: self.data('bind')},
                            dataType: 'html',
                            success: function (data) {
                                notyfy({
                                    force: true,
                                    text: 'Location has been deleted!',
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
                        /*  notyfy({
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
});/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


