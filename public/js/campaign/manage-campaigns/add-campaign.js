$(function() {
    // Form validate
    $("#form-add-campaign").validate({
        rules: {
            campaign_type_id: 'required',
            campaign_name: 'required',
            district_id: 'required',
            'item_id': 'required',
            date_from: 'required',
            date_to: 'required',
            catch_up_days: {
                required: true,
                digits: true
            }
        },
        messages:{
          catch_up_days:{
            required: "Please enter a value greater than or equal to 0"  
          }  
        },
        submitHandler: function(form) {
            if ($('#district_id').val() == '')
            {
                $('label#distirct_id').show();
                return false;
            }
            else
            {
                $('label#distirct_id').hide();
                $(form).submit();
            }
        }
    });

    // Date pickers
    var start_date = $('#date_from');
    var end_date = $('#date_to');

    start_date.datepicker({
        minDate: "-3Y",
        //maxDate: 0,
        dateFormat: 'dd/mm/yy',
        onClose: function(dateText, inst) {
            if (end_date.val() != '') {
                var tmp_start_date = start_date.datepicker('getDate');
                var tmp_end_date = end_date.datepicker('getDate');
                if (tmp_start_date > tmp_end_date)
                    end_date.datepicker('setDate', tmp_start_date);
            }
            
        },
        onSelect: function(selectedDateTime) {
         check_campaign_name();
            //end_date.datepicker('option', 'minDate', start_date.datepicker('getDate'));
           
        }
    });
    end_date.datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        onClose: function(dateText, inst) {
            if (start_date.val() != '') {
                var tmp_start_date = start_date.datepicker('getDate');
                var tmp_end_date = end_date.datepicker('getDate');
                if (tmp_start_date > tmp_end_date)
                    start_date.datepicker('setDate', tmp_end_date);
            }
           
        },
        onSelect: function(selectedDateTime) {
          //  start_date.datepicker('option', 'maxDate', end_date.datepicker('getDate'));
            check_campaign_name();
        }
    });

    // Generate compaign name
    $('#campaign_type_id, #item_id, #date_from').change(function(e) {

        if ($('#campaign_type_id option:selected').text() == 'Other')
        {
            $('#campaign_name').removeAttr('readonly');
            $('#campaign_name').val('');
        }
        else
        {
            $('#campaign_name').attr('readonly', 'readonly');
            check_campaign_name();
        }
    });

    $('#check_all').click(function(event) {  //on click
        if (this.checked) { // check select status
            $('input:checkbox').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"              
            });
        } else {
            $('input:checkbox').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                      
            });
        }
    });
//    // In Edit case pre-select districts
//    var districts = $("#district_id").val();
//    var valNew = districts.split(',');
//
//    if (valNew.length > 0)
//    {
//
//        for (var i = 0; i < valNew.length; i++) {
//            $('#district_id_' + valNew[i]).attr('checked', 'checked');
//        }
//    }

    $('#btn-select-district').click(function(e) {
        // Get values of the checked districts
        e.preventDefault();
        var district_ids = $('input[name="districts[]"]:checked').map(function() {
            return $(this).val();
        }).toArray();
        $("#modal-district-close").trigger('click');
        $("#district_id").val(district_ids);
    });

    // Multiselect    
    if ($("#ms").length > 0)
        $("#ms").multiSelect({
            afterSelect: function(value, text) {
                //notify('Multiselect','Selected: '+text+'['+value+']');
            },
            afterDeselect: function(value, text) {
                //notify('Multiselect','Deselected: '+text+'['+value+']');
            }
        });


    if ($("#msc").length > 0 || $("#compliance").length > 0) {
        $("#msc").multiSelect({
            selectableHeader: "<div class='multipleselect-header'>Districts</div>",
            selectedHeader: "<div class='multipleselect-header'>Selected Districts</div>",
            afterSelect: function(value, text) {
                // notify('Multiselect','Selected: '+text+'['+value+']');
            },
            afterDeselect: function(value, text) {
                //notify('Multiselect','Deselected: '+text+'['+value+']');
            }
        });
    }


});
function check_campaign_name() {
    
    var campaign_type = $('#campaign_type_id').val();
    var campaign_typeText = $('#campaign_type_id option:selected').text();
    var item_id = $('#item_id').val();
    var date_from = $('#date_from').val();
    var date_to = $('#date_to').val();
    
    if (campaign_type != '' && campaign_typeText != 'Other' && item_id != '' && date_from != '' && date_to != '')
    {
        $.ajax({
            type: "POST",
            url: appName + "/campaign/manage-campaigns/ajax-for-campaign",
            data: {campaign_type_id: campaign_type, item_id: item_id, date_from: date_from, date_to: date_to},
            dataType: 'html',
            success: function(data) {
                $('#campaign_name').val(data);
            }
        });
    }
}

