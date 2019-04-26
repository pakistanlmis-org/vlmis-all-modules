$(function(){
	printCont();
})
function printCont()
{
    $("a").hide();
	$('#printButt').hide();
	window.print();
	setTimeout(function() {
		// Do something after 5 seconds
		$('#printButt').show();
                $("a").show();
	}, 5000);
}
