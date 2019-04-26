function showData(param){
	$('#data').html('');
	$.ajax({
		type: "POST",
		url: appName + "/reports/dashlet/ajax-reported-non-reported",
		data: {param: param},
		success: function(data) {
			$('#data').html(data);
		}
	});
}