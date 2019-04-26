function showData16(param){
	$('#data16').html('');
	$.ajax({
		type: "POST",
		url: appName + "/reports/dashlet/ajax-expiry-schedule",
		data: {param: param},
		success: function(data) {
			$('#data16').html(data);
		}
	});
}