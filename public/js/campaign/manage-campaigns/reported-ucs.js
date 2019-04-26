$(function() {
    $('#records').change(function() {
        var counter = $(this).val();
        document.location.href = appName + '/campaign/manage-campaigns/reported-ucs/?counter=' + counter;
    });

   
});

$("#reported_uc").validate({
    rules: {
        campaign_search_id: 'required'

    }
});

 $('#reset').click(function() {
     
        window.location.href = window.location.href;
    });