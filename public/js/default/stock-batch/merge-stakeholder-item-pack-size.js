var flag = 0;
$("#stakeholders").validate({
    rules: {
        product: {
            required: true
        },
        stakeholder: {
            required: true
        }
    },
    messages: {
        product: {
            required: "Please select product"
        },
        stakeholder: {
            required: "Please select office"
        }
    }
});

$('#submit').click(function (e) {
    if ($("#stakeholders").valid()) {
        e.preventDefault();
        Metronic.startPageLoading('Please wait...');
        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-merge-stakeholder-item-pack-size",
            data: {product: $("#product").val(), stakeholder: $("#stakeholder").val()},
            dataType: 'html',
            success: function (data) {
                $('#merge_sips').html(data);
                Metronic.stopPageLoading();

                $('#merge').click(function () {
                    $.notyfy.closeAll();
                    var selected = '';
                    $('.mergecheck:checked').each(function () {
                        selected = selected + $(this).val() + '|';
                    });

                    var mergeinto = $(".mergeinto:checked").val();

                    if (!$(".mergeinto:checked").length) {
                        alert("Please select merge into stakeholder");
                        return false;
                    }

                    if ($('.mergecheck:checked').length < 2) {
                        alert("You must select 2 stakeholders to merge");
                        return false;
                    }

                    $.ajax({
                        type: "POST",
                        url: appName + "/stock-batch/ajax-validate-merge-stakeholders",
                        data: {data: selected, merge: mergeinto},
                        dataType: 'html',
                        success: function (data) {
                            if (data >= 1) {
                                notyfy({
                                    force: true,
                                    text: '<strong>There are ' + data + ' stakeholder name(s) is not similar. Do you want to continue?<strong>',
                                    type: 'error',
                                    layout: 'top',
                                    buttons: [
                                        {
                                            addClass: 'btn btn-success btn-small btn-icon glyphicons ok_2',
                                            text: '<i></i> Continue?',
                                            onClick: function ($notyfy) {
                                                $notyfy.close();
                                                mergeStakeholderItemSizes(selected, mergeinto);
                                            }
                                        },
                                        {
                                            addClass: 'btn btn-danger btn-small btn-icon glyphicons remove_2',
                                            text: '<i></i> Cancel',
                                            killer: true,
                                            onClick: function ($notyfy) {
                                                $notyfy.close();
                                            }
                                        }
                                    ]
                                });
                            } else {
                                mergeStakeholderItemSizes(selected, mergeinto);
                            }
                        }
                    });
                });
            }
        });
    }
});

function mergeStakeholderItemSizes(selected, mergeinto) {
    if (confirm("Are you sure?")) {
        Metronic.startPageLoading('Please wait...');
        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-merge-stakeholder-item-pack-size-id",
            data: {data: selected, merge: mergeinto},
            dataType: 'html',
            success: function (data) {
                if (data == 1) {
                    notyfy({
                        force: true,
                        text: '<strong>Merged successfully!<strong>',
                        type: 'success',
                        layout: 'top'
                    });
                } else {
                    notyfy({
                        force: true,
                        text: '<strong>An error occur!<strong>',
                        type: 'error',
                        layout: 'top'
                    });
                }
                Metronic.stopPageLoading();
                $("#submit").trigger("click");
            }
        });
    }
}