$(function() {
    $('#records').change(function() {
        var counter = $(this).val();
        document.location.href = appName + '/campaign/manage-campaigns/reported-districts/?counter=' + counter;
    });
     });