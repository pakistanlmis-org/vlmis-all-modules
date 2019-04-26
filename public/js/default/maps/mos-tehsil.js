/* The file is used for the Tehsil Map creation of month of stock */
$(window).load(function() {

    $('#prov').val(2);
    $('#dist').val(30);
    $('#prov').change(function() {
        getDistrictList($('#prov').val());
    });
    
    
    getDistrictList($('#prov').val());

    /* Initializing the map object */
    map = new OpenLayers.Map('map', {
        projection: new OpenLayers.Projection("EPSG:900913"),
        displayProjection: new OpenLayers.Projection("EPSG:4326"),
        maxExtent: restricted,
        restrictedExtent: restricted,
        maxResolution: "auto",
        allOverlays: true,
        controls: [
            new OpenLayers.Control.Navigation({
                'zoomWheelEnabled': false
            }),
            new OpenLayers.Control.MousePosition({
                prefix: 'coordinates: ',
                numDigits: 2,
                separator: ' | '
            }),
            new OpenLayers.Control.Zoom({
                zoomInId: "customZoomIn",
                zoomOutId: "customZoomOut"
            }),
            new OpenLayers.Control.ScaleLine()
        ]
    });

    /* Initializing Province Layer */
    province = new OpenLayers.Layer.Vector(
            "Province", {
                protocol: new OpenLayers.Protocol.HTTP({
                    url: appName + "/js/province.geojson",
                    format: new OpenLayers.Format.GeoJSON({
                        internalProjection: new OpenLayers.Projection("EPSG:3857"),
                        externalProjection: new OpenLayers.Projection("EPSG:3857")
                    })
                }),
                strategies: [new OpenLayers.Strategy.Fixed()],
                styleMap: province_style_label,
                isBaseLayer: true
            });

    /* Initializing District Layer */
    district = new OpenLayers.Layer.Vector(
            "District", {
                protocol: new OpenLayers.Protocol.HTTP({
                    url: appName + "/js/district.geojson",
                    format: new OpenLayers.Format.GeoJSON({
                        internalProjection: new OpenLayers.Projection("EPSG:3857"),
                        externalProjection: new OpenLayers.Projection("EPSG:3857")
                    })
                }),
                strategies: [new OpenLayers.Strategy.Fixed()],
                styleMap: district_label
            });

    /* Initializing tehsil Layer */
    tehsil = new OpenLayers.Layer.Vector(
            "Tehsil", {
                protocol: new OpenLayers.Protocol.HTTP({
                    url: appName + "/js/tehsil.geojson",
                    format: new OpenLayers.Format.GeoJSON({
                        internalProjection: new OpenLayers.Projection("EPSG:3857"),
                        externalProjection: new OpenLayers.Projection("EPSG:3857")
                    })
                }),
                strategies: [new OpenLayers.Strategy.Fixed()],
                styleMap: tehsil_style
            });

    /* Initializing vLMIS Layer */
    vLMIS = new OpenLayers.Layer.Vector("Months of Stock", {
        styleMap: vlMIS_style
    });

    /* Adding All layers and setting Index for priority */
    map.addLayers([province, district, tehsil, vLMIS]);
    district.setZIndex(950);
    tehsil.setZIndex(900);
    province.setZIndex(1001);

    /* Select Feature Control */
    selectfeature = new OpenLayers.Control.SelectFeature([vLMIS]);
    map.addControl(selectfeature);
    selectfeature.activate();
    selectfeature.handlers.feature.stopDown = false;

    /* Event for Feature Control */
    vLMIS.events.on({
        "featureselected": onFeatureSelect,
        "featureunselected": onFeatureUnselect
    });

    /* Zoom to Extent */
    map.zoomToExtent(bounds);

    /* Funtion for getting Legend data */
    getLegend('1', 'Months of Stock');

    handler = setInterval(readData, 3000);
});
/* End of Function */

/* Execute funtion on page load after the count of features is completed */
function readData() {
    if (province.features.length == "9" && district.features.length == "147" && tehsil.features.length > "450") {
        getData();
        clearInterval(handler);
    }
}
/* End of Function */

/* Execute function on button click */
$("#submit").click(function() {
    getData();
});
/* End of Function */

/* Execute funtion for filter data */
function getData() {
    clearData();

    year = $("#year").val();
    month = $('#month').val();
    region = $("#prov").val();
    product = $("#product").val();
    dist = $("#dist").val();
    product_name = $("#product option:selected").text();
    provinceName = $("#prov").val();
    districtName = $("#dist").val();

    mapTitle();

    $.ajax({
        url: appName + "/api/geo/get-mos-map-data",
        type: "GET",
        data: {
            year: year,
            month: month,
            province: region,
            product: product,
            district: dist,
            type: '5'
        },
        dataType: "json",
        success: callback,
        error: function(response) {
            /* Failure of ajax call,Stop the loader and enable the submit button */
            $("#loader").css("display", "none");
            $("#submit").attr("disabled", false);
            return;
        }
    });

    function callback(response) {
        data = response;
        drawFeature();
    }
}
/* End of Function */

/* Function for Draw Feature */
function drawFeature() {
    if (vLMIS.features.length > 0) {
        vLMIS.removeAllFeatures();
    }
    FilterTehsilData();
    if (data.length <= 0) {
        alert("No Data Available");
        $("#loader").css("display", "none");
    }
    data.sort(SortByID);
    for (var i = 0; i < data.length; i++) {
        chkeArray(data[i].tehsil_id, Number(data[i].MOS));
    }
    drawGrid();
    districtCountGraph();
}
/* End of Function */

/* Function for connecting database result calls from ajax with district layer */
function chkeArray(tehsil_id, mos) {
    for (var i = 0; i < tehsil.features.length; i++) {
        if (tehsil_id == tehsil.features[i].attributes.tehsil_id) {
            vLMISdistrictLayer(tehsil.features[i].geometry, tehsil.features[i].attributes.province_name, tehsil.features[i].attributes.district_name, tehsil.features[i].attributes.tehsil_name, tehsil.features[i].attributes.tehsil_id, mos);
            break;
        }
    }
}
/* End of Function */

/* Function for renders feature and accociate attribute with it */
function vLMISdistrictLayer(wkt, province, district, tehsil, tehsil_id, value) {
    feature = new OpenLayers.Feature.Vector(wkt);

    if (value == classesArray[0].start_value && value == classesArray[0].end_value) {
        color = classesArray[0].color_code;
        NoData = Number(NoData) + 1;
        status = classesArray[0].description;
    }
    if (value > classesArray[1].start_value && value <= classesArray[1].end_value) {
        color = classesArray[1].color_code;
        StockOut = Number(StockOut) + 1;
        status = classesArray[1].description;
    }
    if (value >= classesArray[2].start_value && value <= classesArray[2].end_value) {
        color = classesArray[2].color_code;
        UnderStock = Number(UnderStock) + 1;
        status = classesArray[2].description;
    }
    if (value >= classesArray[3].start_value && value <= classesArray[3].end_value) {
        color = classesArray[3].color_code;
        Satisfactory = Number(Satisfactory) + 1;
        status = classesArray[3].description;
    }
    if (value >= classesArray[4].start_value) {
        color = classesArray[4].color_code;
        OverStock = Number(OverStock) + 1;
        status = classesArray[4].description;
    }

    feature.attributes = {
        prov: province,
        district: district,
        tehsil: tehsil,
        tehsilId: tehsil_id,
        product: product_name,
        status: status,
        value: value,
        color: color
    };
    vLMIS.addFeatures(feature);
    $("#loader").css("display", "none");
}
/* End of Function */

/* Function for Feature Select */
function onFeatureSelect(e) {
    $("#province").html(e.feature.attributes['prov']);
    $("#district").html(e.feature.attributes['district']);
    $("#tehsil").html(e.feature.attributes['tehsil']);
    $("#prod").html(e.feature.attributes['product']);
    $("#mos").html(e.feature.attributes['value']);
    getUcWiseMos(e.feature.attributes['tehsilId'], 'mos');
}
/* End of Function */

/* Function for Feature UnSelect */
function onFeatureUnselect(e) {
    $("#province").html("");
    $("#district").html("");
    $("#tehsil").html("");
    $("#prod").html("");
    $("#mos").html("");
    $("#ucStock").html("<p style='padding-top:120px;font-weight:bold;font-size:12px;text-align:center'>Click on tehsil for detail UC wise MOS</p>");
}
/* End of Function */

/* Execute funtion for clear all the table,graphs data  */
function clearData() {
    $("#loader").show();
    $('.radio-button').prop('checked', false);
    $("#mapTitle").html("");
    pieArray.length = 0;
    NoData = '0';
    StockOut = '0';
    UnderStock = '0';
    Satisfactory = '0';
    OverStock = '0';
    onFeatureUnselect();
}
/* End of Function */

/* Execute funtion for map title ceation */
function mapTitle() {
    prov_name = $("#prov option:selected").text();
    if (prov_name == "Select") {
        prov_name = "Pakistan"
    }
    ;
    year_name = $("#year option:selected").text();
    month_value = ($('#month').val()) - 1;
    var month_name = monthNames[month_value];
    month_year = month_name + " " + year_name;
    download = product_name + "->" + month_year;
    $("#mapTitle").html("<font color='green' size='4'><b>Months of Stock   (" + month_year + ")</b></font> <br/> " + product_name + " at Tehsil level");

    var date = new Date();
    var d = date.getDate();
    var day = (d < 10) ? '0' + d : d;
    var m = date.getMonth() + 1;
    var month = (m < 10) ? '0' + m : m;
    var yy = date.getYear();
    var year = (yy < 1000) ? yy + 1900 : yy;

    var printdate = "Printed Date: " + day + "/" + month + "/" + year;
    $("#printedDate").html("<b>" + printdate + "</b>");
}
/* End of Function */

/* Funtion for getting Legend data */
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
        $('#legendDiv').append('<table id="legend">');

        $('#legendDiv').append("<tr><td colspan='3'><font size='2' color='green'><b>" + title + "</b></font></td></tr>");
        for (var i = 1; i <= classes; i++) {
            $('#legendDiv').append("<tr><td align='right' valign='middle' class='hide_td'><input name='color' class='radio-button' type='radio' onclick='getColorName(\"" + classesArray[i].color_code + "\",\"" + classesArray[i].description + "\")'/></td><td align='right' valign='middle' style='width:35px'><div style='cursor:pointer;width:30px;height:18px;background-color:" + classesArray[i].color_code + "'></div></td><td align='left' valign='middle' style='padding-left:3px'>" + classesArray[i].description + "</td></tr>");
        }
        $('#legendDiv').append("<br/>");

        for (var i = 0; i < 1; i++) {
            $('#legendDiv').append("<tr><td align='right' valign='middle' class='hide_td'><input name='color' class='radio-button' type='radio' onclick='getColorName(\"" + classesArray[i].color_code + "\",\"" + classesArray[i].description + "\")'/></td><td align='right' valign='middle' style='width:35px'><div style='cursor:pointer;width:30px;height:18px;background-color:" + classesArray[i].color_code + "'></div></td><td align='left' valign='middle' style='padding-left:3px'>" + classesArray[i].description + "</td></tr>");
        }
        $('#legendDiv').append("<tr><td colspan='3' align='right'><a class='undo' onclick='getFullColor()'>Reset</a><td></tr>");
        $('#legendDiv').append('</table>');

        $("#legendDiv").css("display", "block");

        /* Create table for MOS Scale */
        var defination = document.getElementById('mosRanges');
        for (var i = classes; i >= 1; i--) {

            var row = defination.insertRow(0);
            var cell0 = row.insertCell(0);
            var cell1 = row.insertCell(1);
            var cell2 = row.insertCell(2);
            cell0.width = "15%";
            cell0.align = "center";
            cell1.align = "left";
            cell2.align = "center";
            cell0.innerHTML = "<div style='cursor:pointer;width:45px;height:18px;background-color:" + classesArray[i].color_code + "'></div>";
            cell1.innerHTML = classesArray[i].description;
            cell2.innerHTML = classesArray[i].interval;

        }
        $("#mosDefination").css("display", "block");
    }
}
/* End of Function */

/* Sorting JSON result in ascending order */
function SortByID(x, y) {
    return x.MOS - y.MOS;
}
/* End of Function */

/* Funtion for Attribute table creation */
function drawGrid() {
    $("#attributeGrid").html("");
    dataDownload.length = 0;
    jsonData.length = 0;
    var features = vLMIS.features;
    table = "<table class='table table-condensed table-bordered table-hover'>";
    table += "<thead><th>Province</th><th>District</th><th>Tehsil</th><th>Product</th><th class='center' colspan='2'>MOS</th></thead>";
    for (var i = 0; i < features.length; i++) {
        table += "<tr><td>" + features[i].attributes.prov + "</td><td>" + features[i].attributes.district + "</td><td>" + features[i].attributes.tehsil + "</td><td>" + features[i].attributes.product + "</td><td align='right'>" + features[i].attributes.value + "</td><td><div style='width:30px;height:18px;background-color:" + features[i].attributes.color + "'></div></td></tr>";
        jsonData.push({
            label: features[i].attributes.tehsil,
            value: features[i].attributes.value,
            color: features[i].attributes.color
        });
        dataDownload.push({
            province: features[i].attributes.province,
            district: features[i].attributes.district,
            tehsil: features[i].attributes.tehsil,
            product: features[i].attributes.product,
            Status: features[i].attributes.status,
            MOS: features[i].attributes.value
        });
    }
    table += "</table>";
    $("#attributeGrid").append(table);
    maximum = vLMIS.features.length;
}
/* End of Function */

/* Function for Percentage covered with selected province of classes */
function districtCountGraph() {

    var ND = CalculatePercent(NoData, maximum);
    var SO = CalculatePercent(StockOut, maximum);
    var US = CalculatePercent(UnderStock, maximum);
    var SAT = CalculatePercent(Satisfactory, maximum);
    var OS = CalculatePercent(OverStock, maximum);

    pieArray.push({
        label: 'No Data',
        value: ND,
        color: classesArray[0].color_code
    });
    pieArray.push({
        label: 'Stock Out',
        value: SO,
        color: classesArray[1].color_code
    });
    pieArray.push({
        label: 'Under Stock',
        value: US,
        color: classesArray[2].color_code
    });
    pieArray.push({
        label: 'Satisfactory',
        value: SAT,
        color: classesArray[3].color_code
    });
    pieArray.push({
        label: 'Over Stock',
        value: OS,
        color: classesArray[4].color_code
    });
    if ($('#dist').val() != "all") {
        name = $("#dist option:selected").text()
    }
    else {
        name = $("#prov option:selected").text()
    }
    var revenueChart = new FusionCharts({
        type: 'column2d',
        renderAt: 'chart-container',
        width: '100%',
        height: '98%',
        dataFormat: 'json',
        dataSource: {
            "chart": {
                "caption": name + " - MOS Status",
                "subcaption": download,
                "showLabels": '1',
                "showlegend": '1',
                "slantLabels": '1',
                "showValues": '1',
                "labelDisplay": 'Rotate',
                "numberSuffix": '%',
                "exportEnabled": '1',
                "theme": 'fint'
            },
            "data": pieArray
        }
    });
    revenueChart.render("tehsilPie");
}
/* End of Function */

/* Function for legend filter with Attribute table and district ranking graph */
function gridFilter(color) {
    $("#attributeGrid").html("");
    dataDownload.length = 0;
    var features = vLMIS.features;
    table = "<table class='table table-condensed table-bordered table-hover'>";
    table += "<thead><th>Province</th><th>District</th><th>Tehsil</th><th>Product</th><th class='center' colspan='2'>MOS</th>/thead>";
    for (var i = 0; i < features.length; i++) {
        if (features[i].attributes.color == color) {
            table += "<tr><td>" + features[i].attributes.prov + "</td><td>" + features[i].attributes.district + "</td><td>" + features[i].attributes.tehsil + "</td><td>" + features[i].attributes.product + "</td><td align='right'>" + features[i].attributes.value + "</td><td><div style='width:30px;height:18px;background-color:" + features[i].attributes.color + "'></div></td></tr>";
            dataDownload.push({
                province: features[i].attributes.province,
                district: features[i].attributes.district,
                tehsil: features[i].attributes.tehsil,
                product: features[i].attributes.product,
                Status: features[i].attributes.status,
                MOS: features[i].attributes.value
            });
        }
    }
    table += "</table>";
    $("#attributeGrid").append(table);
}
/* End of Function */

/* Function for Getting UC wise MOS */
function getUcWiseMos(id, param) {
    $("#ucStock").html("");
    $("#ucStock").html("<img src='" + appName + "/images/ajax-loader.gif' style='display:block;width:120px;height:220px;margin-left:auto;margin-right: auto;padding-top:100px;'>");
    $.ajax({
        url: appName + "/api/geo/get-uc-wise-mos",
        type: "GET",
        data: {
            year: year,
            month: month,
            product: product,
            tehsilId: id,
            param: param
        },
        dataType: "json",
        success: callback,
        error: function(response) {
            $("#ucStock").html("");
            $("#ucStock").html("<p style='padding-top:120px;font-weight:bold;font-size:12px;text-align:center'>No Data Found</p>");
            return;
        }
    });

    function callback(response) {
        var array = [];
        array = response;

        if (array.length <= 0) {
            $("#ucStock").html("");
            $("#ucStock").html("<p style='padding-top:120px;font-weight:bold;font-size:12px;text-align:center'>No Results Found</p>");
            return;
        }
        $("#ucStock").html("");

        table = "<table class='table table-condensed table-bordered table-hover'>";
        table += "<thead><th>Sr.No</th><th>UC Name</th><th class='right'>MOS</th></thead>";
        var val = '';
        for (var i = 0; i < array.length; i++) {
            if(array[i].MOS){
                val = array[i].MOS;
            }else{
                val = '';
            }
            table += "<tr><td>" + (i + 1) + "</td><td>" + array[i].uc_name + "</td><td class='right'>" + val + "</td></tr>";
        }
        table += "</table>";
        $("#ucStock").append(table);
    }



}
/* End of Function */