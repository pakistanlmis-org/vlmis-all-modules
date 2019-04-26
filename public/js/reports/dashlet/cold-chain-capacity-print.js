$(function() {
    printCont();
    $("#print").click(function() {
        printCont();
    });
});

function printCont()
{
    $("a").hide();
    $('#print').hide();
    window.print();
    setTimeout(function() {
        // Do something after 5 seconds
        $('#print').show();
        $("a").show();
    }, 5000);
}
