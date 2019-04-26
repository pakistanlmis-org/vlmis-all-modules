function getLegend(value, title) {
    $.ajax({
        url: appName + "/api/geo/get-color-classes",
        type: "GET",
        data: "id=" + value,
        dataType: "json",
        success: callback
    });

    function callback(response) {

        classesArray = response;
        var classes = parseInt(classesArray.length) - 1;
        var legend = document.getElementById('legend');

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
            var cell3 = row.insertCell(2);
            cell1.align = "right";
            cell1.className = "hide_td";
            cell2.align = "right";
            cell2.width = "35px";
            cell3.align = "left";
            cell3.style.paddingLeft = "3px";

            cell1.innerHTML = "<input name='color' style='cursor:pointer' class='radio-button' type='radio' onclick='getColorName(\"" + classesArray[i].color_code + "\",\""+classesArray[i].description +"\")'/>";
            cell2.innerHTML = "<div style='width:30px;height:18px;background-color:" + classesArray[i].color_code + "'></div>";
            cell3.innerHTML = classesArray[i].description;
        }

        var row = legend.insertRow(0);
        var cell = row.insertCell(0);
        cell.colSpan = "3";
        cell.align = "center";
        cell.innerHTML = "<br/>";

        for (var i = classes; i >= 1; i--) {
            var row = legend.insertRow(0);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            cell1.align = "right";
            cell1.className = "hide_td";
            cell2.align = "right";
            cell2.width = "35px";
            cell3.align = "left";
            cell3.style.paddingLeft = "3px";

            cell1.innerHTML = "<input name='color' style='cursor:pointer' class='radio-button' type='radio' onclick='getColorName(\"" + classesArray[i].color_code + "\",\""+classesArray[i].description +"\")'/>";
            cell2.innerHTML = "<div style='width:30px;height:18px;background-color:" + classesArray[i].color_code + "'></div>";
            cell3.innerHTML = classesArray[i].description;
        }
        var row = legend.insertRow(0);
        var cell = row.insertCell(0);
        cell.colSpan = "3";
        cell.align = "left";
        cell.innerHTML = "<font size='2' color='green'><b>" + title + "</b></font><br/>";
        $("#legendDiv").css("display", "block");
    }

}