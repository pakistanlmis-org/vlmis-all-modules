
$(function() {
    printCont();

    $("#print-p").click(function() {
        printCont();
    });
});

function printCont()
{
    $("a").hide();
    $('#print-p').hide();
    window.print();
    setTimeout(function() {
        // Do something after 5 seconds
        $('#print-p').show();
        $("a").show();
    }, 5000);
}
