
$(function() {
    printCont();

    $("#print").click(function() {
        printCont();
    });
    calculateSumView('pregenant_women', 'pregenant_women_total_view');
    calculateSumView('non_pregenant_women', 'non_pregenant_women_total_view');

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

function calculateSumView(field, total) {

    var sum = 0;

    //iterate through each textboxes and add the values
    $("." + field).each(function() {
        var pregenant_women = $(this).val();


        if (!isNaN(pregenant_women) && pregenant_women.length != 0) {
            sum += parseFloat(pregenant_women);
        }
        else if (pregenant_women.length != 0) {
        }
    });


    $("#" + total).html(sum);
}