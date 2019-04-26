$(function () {
    $('#reset').click(function () {
        window.location.href = appName + '/iadmin/manage-products/transaction-type';
    });

    $('#records').change(function () {
        var counter = $(this).val();

        document.location.href = appName + '/iadmin/manage-products/transaction-type?counter=' + counter;
    });

    // validate signup form on keyup and submit
    $("#update-transaction-types").validate({
        rules: {
            transaction_type_name: {
                required: true
            }

        },
        messages: {
            transaction_type_name: {
                required: "Please enter transaction type name."
            }

        }
    });

    // validate signup form on keyup and submit
    $("#add-transaction-types").validate({
        rules: {
            transaction_type_name: {
                required: true
            }

        },
        messages: {
            transaction_type_name: {
                required: "Please enter transaction type name."
            }

        }
    });

    $(".update-transaction-type").click(function () {
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-products/ajax-edit-tran",
            data: {transaction_type_id: $(this).attr('itemid')},
            dataType: 'html',
            success: function (data) {
                $('#modal-body-contents').html(data);
                $('#update-button').show();
            }
        });
    });

    $(document).on("click", "a.positive", function () {
        var idData = $(this).attr('id');
        var id = idData.substr(7);
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-products/ajax-change-status-nature",
            data: {idData: id, ajaxactionnature: 'positive'},
            success: function (data) {
                $('#' + idData).html(data);
                $('#' + idData).removeClass("positive");
                $('#' + idData).addClass("negative");
            }
        });
    });


    $(document).on("click", "a.negative", function () {
        var idData = $(this).attr('id');
        var id = idData.substr(7);
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-products/ajax-change-status-nature",
            data: {idData: id, ajaxactionnature: 'negative'},
            success: function (data) {
                $('#' + idData).html(data);
                $('#' + idData).removeClass("negative");
                $('#' + idData).addClass("positive");
            }
        });
    });

    $(document).on("click", "a.active", function () {
        var id = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-products/ajax-change-status-tran",
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
            url: appName + "/iadmin/manage-products/ajax-change-status-tran",
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

        var transaction_type_name = '';
        var order = '';
        var sort = '';
        var counter = '';
        var page = '';

        order = self.data('order');
        sort = self.data('sort');

        transaction_type_name = $('#transaction_type_name').val();
        counter = $('#records').val();
        page = $('#current').val();

        if (make_name.length > 1) {
            document.location = appName + '/iadmin/manage-products/transaction-type/?order=' + order + '&sort=' + sort + '&transaction_type_name=' + transaction_type_name + '&counter=' + counter + '&page=' + page;
        } else {
            document.location = appName + '/iadmin/manage-products/transaction-type/?order=' + order + '&sort=' + sort + '&counter=' + counter + '&page=' + page;
        }
    });
});