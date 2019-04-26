$(function () {
    //printPage('C:\\wamp\\www\\pak-vlmis-zr1\\public\\prints\\123.htm');
    
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
    }, 5000);
}

function printPage(sPath) {
    alert("Here1");
    var fso = new XMLHttpRequest();
    alert("Here2");
    var fileDest = fso.CreateTextFile(sPath, true);
    alert("Here3");
    if (fileDest) {
        alert("Here4");
        fileDest.Write(document.documentElement.outerHTML);
        fileDest.close();
    } else {
        alert("5 unable to create file " + sPath);
    }
}