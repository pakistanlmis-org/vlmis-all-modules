$(function() {
    if ($('#update-asset-sub-types #item_category').val() == 1) {
        if ($('#update-asset-sub-types #item_category').val() == 1) {
            $('#update-asset-sub-types #lbl_no_of_doses').show()
            $('#update-asset-sub-types #number_of_doses').show();
        }
        else {
            $('#update-asset-sub-types #lbl_no_of_doses').hide()
            $('#update-asset-sub-types #number_of_doses').hide();
        }
    }

    $('#update-asset-sub-types #item_category').change(function() {
        if ($('#update-asset-sub-types #item_category').val() == 1) {
            $('#update-asset-sub-types #lbl_no_of_doses').show()
            $('#update-asset-sub-types #number_of_doses').show();
        }
        else {
            $('#update-asset-sub-types #lbl_no_of_doses').hide()
            $('#update-asset-sub-types #number_of_doses').hide();
        }
    });


});