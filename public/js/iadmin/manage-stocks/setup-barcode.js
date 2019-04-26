$(function() {

    var complete;
    var isValid = false;
    $("#setup_barcode").validate({
        rules: {
            item_pack_size_id: {
                required: true
            },
            stakeholder_id: {
                required: true
            },
            packaging_level: {
                required: true
            },
            batch_length: {
                required: true,
                number: true
            },
            item_gtin: {
                required: true,
                alphanumeric: true
            },
            length: {
                required: true,
                number: true
            },
            width: {
                required: true,
                number: true
            },
            height: {
                required: true,
                number: true
            },
            quantity_per_pack: {
                required: true,
                number: true
            },
            volume_per_unit_net: {
                required: true,
                number: true
            }
        },
        messages: {
            item_pack_size_id: {
                required: "Select Product."
            },
            stakeholder_id: {
                required: "Select Manufacturer."
            },
            packaging_level: {
                required: "Select Packaging Level."
            },
            item_gtin: {
                required: "Enter Item GTIN."
            },
            batch_length: {
                required: "Select Batch Length."
            },
            length: {
                required: "Enter length."
            },
            width: {
                required: "Enter width."
            },
            height: {
                required: "Enter height."
            },
            quantity_per_pack: {
                required: "Enter Vials/Pcs."
            },
            volume_per_unit_net: {
                required: "Enter Volume(CM3)."
            }
        },
        submitHandler: function(form) {
            $.ajax({
                type: "POST",
                url: appName + "/iadmin/manage-stocks/check-setup-barcode-combination",
                data: {item_pack_size_id: $('#item_pack_size_id').val(), stakeholder_id: $('#stakeholder_id').val(), packaging_level: $('#packaging_level').val()},
                dataType: 'html',
                success: function(data) {
                    isValid = data;
                    complete = true;//mark ajax as complete
                    runOnComplete();//callback
                }
            });
            function runOnComplete() {
                if (complete) {//run when ajax completes and flag is true
                    if (isValid == 't')
                    {
                        form.submit();
                    }
                    else
                    {
                        alert('Item Pack Size Already Exits');
                        return false;
                    }
                } else {
                    setTimeout(runOnComplete, 25);//when ajax is not complete then loop
                }
            }

        }

    });
    $('#barcode_type').change(function() {
        $('body').attr('class', 'custom');
        $('#ajax_barcode').html('');
        var id = $("#barcode_type").val();
        if (id != "") {
            $.ajax({
                type: "POST",
                url: appName + "/iadmin/manage-stocks/ajax-setup-barcode",
                data: {barcode_type_id: $('#barcode_type').val()},
                dataType: 'html',
                success: function(data) {
                    $("#update-button").show();
                    $('#ajax_barcode').html(data);
                }
            });
        }
        $('body').attr('class', '');
    });

    $(".detail-barcode").click(function() {
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-stocks/detail-barcode",
            data: {barcode_id: $(this).attr('editid')},
            dataType: 'html',
            success: function(data) {
                $('#modal-body-contents-detail').html(data);

            }
        });
    });
    $('#sample_2').on('click','.update-barcode',function () {
    $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-stocks/update-barcode",
            data: {barcode_id: $(this).attr('editid')},
            //data: {barcode_type_id: bar_id, number: $('#item_pack_size_id').val()},
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
                            url: appName + "/iadmin/manage-stocks/delete-stakeholder-item-pack-size",
                            data: {barcode_id: self.data('bind')},
                            dataType: 'html',
                            success: function(data) {

                                if (data == "true") {
                                    notyfy({
                                        force: true,
                                        text: 'Item barcode has been deleted!',
                                        type: 'success',
                                        layout: self.data('layout')
                                    });
                                    self.closest("tr").remove();
                                } else {
                                    notyfy({
                                        force: true,
                                        text: 'Stakeholder can not be deleted because this id using in stock batch!',
                                        type: 'error',
                                        layout: self.data('layout')
                                    });
                                }

                            }
                        });
                    }
                }, {
                    addClass: 'btn btn-danger btn-small btn-icon glyphicons remove_2',
                    text: '<i></i> Cancel',
                    onClick: function($notyfy) {
                        $notyfy.close();
//                        notyfy({
//                            force: true,
//                             text: '<strong>You clicked "Cancel" button<strong>',
//                            type: 'error',
//                            layout: self.data('layout')
//                        });
                    }
                }]
        });
        return false;
    });
    $("#item_pack_size_id").change(function() {
        $('#stakeholder_id').html('');
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-stocks/ajax-get-manufacturer-by-product",
            data: {item_id: $(this).val()},
            dataType: 'html',
            success: function(data) {
                $('#stakeholder_id').html(data);
            }
        });
    });
});
