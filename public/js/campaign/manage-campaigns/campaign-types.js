$(function() {
    $('#records').change(function() {
        var counter = $(this).val();
        document.location.href = appName + '/campaign/manage-campaigns/campaign-types/?counter=' + counter;
    });

    $("#add-campaign-type").validate({
        rules: {
            campaign_type_name: {
                required: true,
                alphanumeric: true,
                remote: {
                    url: appName + "/campaign/manage-campaigns/check-campaign-type"

                }
            }
        },
        messages: {
            campaign_type_name: {
                remote: jQuery.format("{0} is already exits")
            }
        }
    });


    // validate signup form on keyup and submit
    $("#update-campaign-type").validate({
        rules: {
            campaign_type_name: {
                required: true,
                alphanumeric: true,
                remote: {
                    url: appName + "/campaign/manage-campaigns/check-campaign-type"

                }
            }
        },
        messages: {
            campaign_type_name: {
                remote: jQuery.format("{0} is already exits")
            }
        }
    });

    $(".update-campaign-type").click(function() {
        $.ajax({
            type: "POST",
            url: appName + "/campaign/manage-campaigns/ajax-campaign-type-edit",
            data: {campaign_type_id: $(this).attr('id')},
            dataType: 'html',
            success: function(data) {
                $('#modal-body-contents').html(data);
                $('#update-button').show();
            }
        });
    });

    $('th.sorting, th.sorting_asc, th.sorting_desc').click(function(e) {
        e.preventDefault();

        var self = $(this);

        var campaign_type_name = '';
        var order = '';
        var sort = '';
        var counter = '';
        var page = '';

        order = self.data('order');
        sort = self.data('sort');

        campaign_type_name = $('#campaign_type_name').val();
        counter = $('#records').val();
        page = $('#current').val();

        if (campaign_type_name.length > 1) {
            document.location = appName + '/campaign/manage-campaigns/campaign-types/?order=' + order + '&sort=' + sort + '&campaign_type_name=' + campaign_type_name + '&counter=' + counter + '&page=' + page;
        }
        else {
            document.location = appName + '/campaign/manage-campaigns/campaign-types/?order=' + order + '&sort=' + sort + '&counter=' + counter + '&page=' + page;
        }
    });
});