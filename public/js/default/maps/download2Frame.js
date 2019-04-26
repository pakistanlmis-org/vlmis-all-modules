$("#image").click(function() {

    selectfeature.unselectAll();
    selectfeature2.unselectAll();

    $("#wastagesInfoDiv").css("display", "none");
    $("#reportingInfoDiv").css("display", "none");
    $('.hide_td').hide();

    html2canvas(document.getElementById("capture"), {
        useCORS: false,
        onrendered: function(canvas) {
            var imgprint = canvas.toDataURL('image/JPEG');
            var link = document.createElement('a');
            link.href = imgprint;
            link.download = 'map.jpeg';
            document.body.appendChild(link);
            link.click();
            setTimeout(drawControls, 100);
        }
    });

});

function drawControls() {
    $("#wastagesInfoDiv").css("display", "block");
    $("#reportingInfoDiv").css("display", "block");
    $('.hide_td').show();
}


var img, urljpg;

$("#print").click(function() {

    map.removeControl(map.getControlsByClass('OpenLayers.Control.ScaleLine')[0]);
    map2.removeControl(map2.getControlsByClass('OpenLayers.Control.ScaleLine')[0]);
    $("#wastagesInfoDiv").css("display", "none");
    $("#reportingInfoDiv").css("display", "none");

    html2canvas(document.getElementById("capture"), {
        useCORS: true,
        onrendered: function(canvas) {
            img = canvas.toDataURL('image/JPEG');
            var image = document.createElement("img");
            image.src = img;
            urljpg = image.src.replace(/^data:image\/[^;]/, 'data:image/jpeg');

            var printWindow = window.open();
            printWindow.document.write('<html><head><title></title>');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<div id="content" style="width:100%;height:600px;position:absolute;top:100px;left:0px">');
            printWindow.document.write('<img style="width:100%;height:80%" src="' + urljpg + '"');
            printWindow.document.write('</div>');
            printWindow.document.write('</body></html>');
            printWindow.document.close();

            $("#wastagesInfoDiv").css("display", "block");
            $("#reportingInfoDiv").css("display", "block");
            map.addControl(map.getControlsByClass('OpenLayers.Control.ScaleLine')[0]);
            map2.addControl(map2.getControlsByClass('OpenLayers.Control.ScaleLine')[0]);

            printWindow.print();
        }
    });
});