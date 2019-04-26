function showDataProvince(param){
	$('#data12').html('');
	$.ajax({
		type: "POST",
		url: appName + "/reports/dashlet/ajax-reported-non-reported-province",
		data: {param: param},
		success: function(data) {
			$('#data12').html(data);
		}
	});
}