$(function() {

    
   if($("#old_warehouse").val() != "" ) {

        $.ajax({
            type: "POST",
            url: appName + "/cadmin/manage-health-facility/ajax-get-health-facility-type",
            data: {wh_id: $('#office_level').val()},
            dataType: 'html',
            success: function(data) {
                $('#health_facility_type_update').html(data);

            }
        });
        $.ajax({
            type: "POST",
            url: appName + "/cadmin/manage-health-facility/ajax-get-services-type",
            data: {wh_id: $('#old_warehouse_val').val()},
            dataType: 'html',
            success: function(data) {
                $('#services_type_update').html(data);

            }
        });
    }

$("#warehouse2").change(function() {

        $.ajax({
            type: "POST",
            url: appName + "/cadmin/manage-health-facility/ajax-get-health-facility-type",
            data: {wh_id: $('#office2').val()},
            dataType: 'html',
            success: function(data) {
                $('#health_facility_type_update').html(data);

            }
        });
        $.ajax({
            type: "POST",
            url: appName + "/cadmin/manage-health-facility/ajax-get-services-type",
            data: {wh_id: $(this).val()},
            dataType: 'html',
            success: function(data) {
                $('#services_type_update').html(data);

            }
        });
    });


   
});