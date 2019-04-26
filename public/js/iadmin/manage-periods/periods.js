$(function ()
{
    $('#reset').click(function () {
        window.location.href = appName + '/iadmin/manage-periods/periods';
    });


    $('#records').change(function ()
    {
        var counter = $(this).val();
        document.location.href = appName + '/iadmin/manage-periods/periods/?counter=' + counter;
    });

    $('#add-period').validate(
            {
                rules: {
                    period_code: {
                        required: true,
                        remote: {
                            url: appName + "/iadmin/manage-periods/check-period-code",
                            type: "post",
                            data:
                                    {
                                        period_code: function () {
                                            return $("#period_code").val();
                                        }
                                    }
                        }
                    }
                },
                messages: {
                    period_code: {
                        required: "Please enter period code",
                        remote: "Period code already axists"
                    }
                }
            });

    $(".update-asset-sub-type").click(function () {
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-periods/ajax-period-edit",
            data: {period_id: $(this).attr('id')},
            dataType: 'html',
            success: function (data)
            {
                $('#modal-body-contents').html(data);
                $('#update-button').show();
            }
        });
    });

    $('th.sorting, th.sorting_asc, th.sorting_desc').click(function (e) {
        e.preventDefault();

        var self = $(this);

        var asset_sub_type = '';
        var order = '';
        var sort = '';
        var counter = '';
        var page = '';

        order = self.data('order');
        sort = self.data('sort');

        asset_sub_type = $('#name').val();
        counter = $('#records').val();
        page = $('#current').val();

        if (asset_sub_type.length > 1) {
            document.location = appName + '/cadmin/manage-asset-sub-types/?order=' + order + '&sort=' + sort + '&name=' + asset_sub_type + '&counter=' + counter + '&page=' + page;
        } else {
            document.location = appName + '/cadmin/manage-asset-sub-types/?order=' + order + '&sort=' + sort + '&counter=' + counter + '&page=' + page;
        }
    });
});