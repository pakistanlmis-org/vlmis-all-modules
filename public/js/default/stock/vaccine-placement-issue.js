$(document).on('keydown keyup', function(e) {
    if(e.ctrlKey && e.keyCode == 80){
        $("a").hide();
        $('#print').hide();
        setTimeout(function () {
            // Do something after 5 seconds
            $('#print').show();
            $("a").show();
        }, 10000);
    }
});

$(function () {
    printCont();
    $("#print").click(function () {
        printCont();
    });
});

function printCont()
{
    $("a").hide();
    $('#print').hide();
    window.print();
    setTimeout(function () {
        // Do something after 5 seconds
        $('#print').show();
        $("a").show();
    }, 10000);
}
