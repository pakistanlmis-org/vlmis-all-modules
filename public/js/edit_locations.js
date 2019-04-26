$(function() {
    $("#update-locations").validate({
        rules: {
            location_name_update: {
                required: true,
                remote: {
                    url: appName + "/iadmin/manage-locations/check-location",
                    type: "post",
                    data: {
                        district: function() {
                            return $("#combo2_edit").val();
                        },
                        province: function() {
                            return $("#combo1_edit").val();
                        },
                        locLvl: function() {
                            return $("#location_level_edit").val();
                        },
                        locid: function() {
                            return $("#location_level_edit").val();
                        }

                    }
                }
            },
            combo1_edit: {
                required: true
            },
            combo2_edit: {
                required: true
            },
            combo3_edit: {
                required: true
            }


        }

    });

   
});