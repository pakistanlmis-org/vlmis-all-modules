function getLegend(value, id) {
    $.ajax({
        url: appName + "/api/geo/get-color-classes",
        type: "GET",
        data: "id=" + value,
        dataType: "json",
        success: callback
    });

    function callback(response) {
        if (id == "wastageLegend") {

            wastagesClassesArray = response;
            var classes = parseInt(wastagesClassesArray.length) - 1;
            var legend = document.getElementById(id);

            var row = legend.insertRow(0);
            var cell = row.insertCell(0);
            cell.colSpan = "3";
            cell.align = "right";
            cell.className = "hide_td";
            cell.innerHTML = "<a class='undo' onclick='getFullColor()'>Reset</a>";

            for (var i = 0; i >= 0; i--) {
                var row = legend.insertRow(0);
                var cell1 = row.insertCell(0);
                var cell2 = row.insertCell(1);
                cell1.align = "center";
                cell2.align = "left";
                cell1.innerHTML = "<div onclick='getColorName(\"" + i + "\")' style='cursor:pointer;width:20px;height:15px;background-color:" + wastagesClassesArray[i].color_code + "'></div>";
                cell2.innerHTML = wastagesClassesArray[i].description;

            }

           for (var i = classes; i >= 1; i--) {
                var row = legend.insertRow(0);
                var cell1 = row.insertCell(0);
                var cell2 = row.insertCell(1);
                cell1.align = "center";
                cell2.align = "left";
                cell1.innerHTML = "<div onclick='getColorName(\"" + i + "\")' style='cursor:pointer;width:20px;height:15px;background-color:" + wastagesClassesArray[i].color_code + "'></div>";
                cell2.innerHTML = wastagesClassesArray[i].description;

            }
            var row = legend.insertRow(0);
            var cell = row.insertCell(0);
            cell.colSpan = "2";
            cell.align = "left";
            cell.innerHTML = "<font size='1' color='green'><b>Wastages Rate</b></font><br/>";

            $("#L1").css("display", "block");
        } else {
            reportingClassesArray = response;
            var classes = parseInt(reportingClassesArray.length) - 1;
            var legend1 = document.getElementById(id);

            var row = legend1.insertRow(0);
            var cell = row.insertCell(0);
            cell.colSpan = "3";
            cell.align = "right";
            cell.className = "hide_td";
            cell.innerHTML = "<a class='undo' onclick='getFullColor()'>Reset</a>";

             for (var i = 0; i >= 0; i--) {
                var row = legend1.insertRow(0);
                var cell1 = row.insertCell(0);
                var cell2 = row.insertCell(1);
                cell1.align = "center";
                cell2.align = "left";
                cell1.innerHTML = "<div onclick='getColorName(\"" + i + "\")' style='cursor:pointer;width:20px;height:15px;background-color:" + reportingClassesArray[i].color_code + "'></div>";
                cell2.innerHTML = reportingClassesArray[i].description;

            }

            for (var i = classes; i >= 1; i--) {
                var row = legend1.insertRow(0);
                var cell1 = row.insertCell(0);
                var cell2 = row.insertCell(1);
                cell1.align = "center";
                cell2.align = "left";
                cell1.innerHTML = "<div onclick='getColorName(\"" + i + "\")' style='cursor:pointer;width:20px;height:15px;background-color:" + reportingClassesArray[i].color_code + "'></div>";
                cell2.innerHTML = reportingClassesArray[i].description;

            }
            var row = legend1.insertRow(0);
            var cell = row.insertCell(0);
            cell.colSpan = "2";
            cell.align = "left";
            cell.innerHTML = "<font size='1' color='green'><b>Reporting Rate</b></font><br/>";

            $("#L2").css("display", "block");
        }

    }

}


function getColorName(id) {

    var features = vLMIS1.features;
    var features2 = vLMIS2.features;
    for (var i = 0; i < features.length; i++) {
        features[i].style = '';
    }
    vLMIS1.redraw();

    for (var i = 0; i < features2.length; i++) {
        features2[i].style = '';
    }
    vLMIS2.redraw();

    for (var i = 0; i < features.length; i++) {
        if (features[i].attributes.color != wastagesClassesArray[id].color_code) {
            features[i].style = {
                display: 'none'
            };
        }

    }
    vLMIS1.redraw();

    for (var i = 0; i < features2.length; i++) {
        if (features2[i].attributes.color != reportingClassesArray[id].color_code) {
            features2[i].style = {
                display: 'none'
            };
        }

    }
    vLMIS2.redraw();
}

function getFullColor() {

    var features = vLMIS1.features;
    var features2 = vLMIS2.features;
    for (var i = 0; i < features.length; i++) {
        features[i].style = '';
    }
    vLMIS1.redraw();


    for (var i = 0; i < features2.length; i++) {
        features2[i].style = '';
    }
    vLMIS2.redraw();
}