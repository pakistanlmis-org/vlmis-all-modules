$(function () {
    $('#reset').click(function () {
        window.location.href = appName + '/iadmin/manage-stores/routine';
    });

    $('#records').change(function () {
        var counter = $(this).val();

        document.location.href = appName + '/iadmin/manage-locations/location-type?counter=' + counter;
    });

    // validate signup form on keyup and submit
    $("#update-location-types").validate({
        rules: {
            location_type_name: "required",
            geo_level_id: "required"
        },
        messages: {
            location_type_name: "Please enter location type name.",
            geo_level_id: "Select Geo level."
        }
    });

    // validate signup form on keyup and submit
    $("#add-location-types").validate({
        rules: {
            location_type_name: "required",
            geo_level_id: "required"

        },
        messages: {
            location_type_name: "Please enter location type name.",
            geo_level_id: "Select Item."
        }
    });

    $(".update-location-type").click(function () {
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-locations/ajax-edit-loc-type",
            data: {location_type_id: $(this).attr('itemid')},
            dataType: 'html',
            success: function (data) {
                $('#modal-body-contents').html(data);
                $('#update-button').show();
            }
        });
    });

    $(document).on("click", "a.active", function () {
        var id = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-locations/ajax-change-status",
            data: {id: id, ajaxaction: 'active'},
            dataType: 'html',
            success: function (data) {
                $('#' + id).html(data);
                $('#' + id).removeClass("active");
                $('#' + id).addClass("deactive");
            }
        });
    });

    $(document).on("click", "a.deactive", function () {
        var id = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-locations/ajax-change-status",
            data: {id: id, ajaxaction: 'deactive'},
            dataType: 'html',
            success: function (data) {
                $('#' + id).html(data);
                $('#' + id).removeClass("deactive");
                $('#' + id).addClass("active");
            }
        });
    });


    $('th.sorting, th.sorting_asc, th.sorting_desc').click(function (e) {
        e.preventDefault();

        var self = $(this);

        var location_type_name = '';
        var order = '';
        var sort = '';
        var counter = '';
        var page = '';

        order = self.data('order');
        sort = self.data('sort');

        location_type_name = $('#location_type_name').val();
        counter = $('#records').val();
        page = $('#current').val();

        if (location_type_name.length > 1) {
            document.location = appName + '/iadmin/manage-locations/location-type/?order=' + order + '&sort=' + sort + '&location_type_name=' + location_type_name + '&counter=' + counter + '&page=' + page;
        } else {
            document.location = appName + '/iadmin/manage-locations/location-type/?order=' + order + '&sort=' + sort + '&counter=' + counter + '&page=' + page;
        }
    });
});