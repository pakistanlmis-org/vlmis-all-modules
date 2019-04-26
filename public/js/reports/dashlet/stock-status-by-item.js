function showData(param){
	$('#data14').html('');
	$.ajax({
		type: "POST",
		url: appName + "/reports/dashlet/ajax-stock-status-by-item",
		data: {param: param},
		success: function(data) {
			$('#data14').html(data);
		}
	});
}