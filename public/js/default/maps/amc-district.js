
/* The file is used for the District Map creation of month of stock */
$(window).load(function() {

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
                styleMap: district_style
            });

    vLMIS = new OpenLayers.Layer.Vector("Consumption", {
        styleMap: vlMIS_style
    });
    map.addLayers([province, district, vLMIS]);
    district.setZIndex(900);
    province.setZIndex(1001);

    selectfeature = new OpenLayers.Control.SelectFeature([vLMIS]);
    map.addControl(selectfeature);
    selectfeature.activate();
    selectfeature.handlers.feature.stopDown = false;

    vLMIS.events.on({
        "featureselected": onFeatureSelect,
        "featureunselected": onFeatureUnselect
    });
    map.zoomToExtent(bounds);
    handler = setInterval(readData, 2000);
});
/* End of Function */

/* Execute funtion on page load after the count of features is completed */
function readData() {
    if (province.features.length == "9" && district.features.length == "147") {
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
    product = $("#product").val();
    region = $("#province").val();
    type = $("#amc_type").val();
    product_name = $("#product option:selected").text();
    type_name = $("#amc_type option:selected").text();
    provinceName = $("#province option:selected").text();

    mapTitle();

    $.ajax({
        url: appName + "/api/geo/get-amc-map-data",
        type: "GET",
        data: {
            year: year,
            month: month,
            province: region,
            product: product,
            type: type,
            level: '4'
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
        var amc_type = $("#amc_type").val();
        for (var i = 0; i < data.length; i++) {
            if (amc_type == 'C') {
                maxValue.push(Math.round(data[i].Vaccinated));
            } else if (amc_type == 'A') {
                maxValue.push(Math.round(data[i].AMC));
            }
        }

        max = Math.max.apply(Math, maxValue);
        min = Math.min.apply(Math, maxValue);

        getLegend('3', max, min, type_name);
    }
}
/* End of Function */

/* Function for Draw Feature */
function drawLayer() {

    if (vLMIS.features.length > 0) {
        vLMIS.removeAllFeatures();
    }

    FilterData();
    if (data.length <= 0) {
        alert("No Data Available");
        $("#loader").css("display", "none");
        $("#submit").attr("disabled", false);
    }
    data.sort(SortByID);
    var vaccinated = 0;
    var amc_type = $("#amc_type").val();
    for (var i = 0; i < data.length; i++) {
        if (amc_type == 'C') {
            vaccinated = Number(data[i].Vaccinated);
        } else if (amc_type == 'A') {
            vaccinated = Number(data[i].AMC);
        }
        chkeArray(data[i].district_id, vaccinated);
    }
    drawGrid();
    districtCountGraph();
    $("#submit").attr("disabled", false);
}
/* End of Function */

/* Function for connecting database result calls from ajax with district layer */
function chkeArray(district_id, value) {
    for (var i = 0; i < district.features.length; i++) {
        if (district_id == district.features[i].attributes.district_id) {
            if (min == max) {
                vLMISMiniLayer(district.features[i].geometry, district.features[i].attributes.province_id, district.features[i].attributes.province_name, product_name, district_id, district.features[i].attributes.district_name, value);
                break;
            } else {
                vLMISLayer(district.features[i].geometry, district.features[i].attributes.province_id, district.features[i].attributes.province_name, product_name, district_id, district.features[i].attributes.district_name, value);
                break;
            }
        }
    }
}
/* End of Function */

/* Function for renders feature and accociate attribute with it */
function vLMISLayer(wkt, province_id, province, product, district_id, district, value) {
    feature = new OpenLayers.Feature.Vector(wkt);

    if (value == parseInt(classesArray[0].start_value) && value == parseInt(classesArray[0].end_value)) {
        color = classesArray[0].color_code;
        NoData = Number(NoData) + 1;
        status = classesArray[0].description;

    }
    if (value > parseInt(classesArray[1].start_value) && value <= parseInt(classesArray[1].end_value)) {
        color = classesArray[1].color_code;
        class1 = Number(class1) + 1;
        status = classesArray[1].description;
    }
    if (value > parseInt(classesArray[2].start_value) && value <= parseInt(classesArray[2].end_value)) {
        color = classesArray[2].color_code;
        class2 = Number(class2) + 1;
        status = classesArray[2].description;
    }
    if (value > parseInt(classesArray[3].start_value) && value <= parseInt(classesArray[3].end_value)) {
        color = classesArray[3].color_code;
        class3 = Number(class3) + 1;
        status = classesArray[3].description;
    }
    if (value > parseInt(classesArray[4].start_value) && value <= parseInt(classesArray[4].end_value)) {
        color = classesArray[4].color_code;
        class4 = Number(class4) + 1;
        status = classesArray[4].description;
    }
    if (value > parseInt(classesArray[5].start_value) && value <= parseInt(classesArray[5].end_value)) {
        color = classesArray[5].color_code;
        class5 = Number(class5) + 1;
        status = classesArray[5].description;
    }
    feature.attributes = {
        district_id: district_id,
        province_id: province_id,
        district: district,
        province: province,
        product: product,
        status: status,
        value: value,
        color: color
    };
    vLMIS.addFeatures(feature);
    $("#loader").css("display", "none");
}
/* End of Function */

/* Function for renders feature and accociate attribute with it */
function vLMISMiniLayer(wkt, province_id, province, product, district_id, district, value) {
    feature = new OpenLayers.Feature.Vector(wkt);
    if (value == parseInt(classesArray[0].start_value) && value == parseInt(classesArray[0].end_value)) {
        color = classesArray[0].color_code;
        NoData = Number(NoData) + 1;
    } else {
        color = classesArray[1].color_code;
        class1 = Number(class1) + 1;
    }
    feature.attributes = {
        district_id: district_id,
        province_id: province_id,
        district: district,
        province: province,
        product: product,
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
    $("#c_title").html($("#amc_type option:selected").text());
    $("#prov").html(e.feature.attributes['province']);
    $("#district").html(e.feature.attributes['district']);
    $("#prod").html(e.feature.attributes['product']);
    $("#amc").html(e.feature.attributes['value'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
}
/* End of Function */

/* Function for Feature UnSelect */
function onFeatureUnselect(e) {
    $("#prov").html("");
    $("#district").html("");
    $("#prod").html("");
    $("#amc").html("");
}
/* End of Function */

/* Execute funtion for clear all the table,graphs data  */
function clearData() {
    $("#loader").show();
    $("#legendDiv").css("display", "none");
    $("#legend").html("");
    $('.radio-button').prop('checked', false);
    $("#attributeGrid").html("");
    $("#districtRanking").html("");
    $("#submit").attr("disabled", true);
    $("#mapTitle").html("");
    classesArray.length = 0;
    pieArray.length = 0;
    maxValue.length = 0;
    NoData = '0';
    class1 = '0';
    class2 = '0';
    class3 = '0';
    class4 = '0';
    class5 = '0';
    onFeatureUnselect();
}
/* End of Function */

/* Execute funtion for map title ceation */
function mapTitle() {

    prov_name = $("#province option:selected").text();
    if (prov_name == "Select") {
        prov_name = "Pakistan"
    }
    ;
    year_name = $("#year option:selected").text();
    month_value = ($('#month').val()) - 1;
    var month_name = monthNames[month_value];
    month_year = month_name + " " + year_name;
    download = product_name + "->" + month_year;
    if (type == "C") {
        $("#mapTitle").html("<font color='green' size='4'><b>" + type_name + "  (" + month_year + ")</b></font> <br/> " + product_name);
    }
    else {
        $("#mapTitle").html("<font color='green' size='4'><b>" + type_name + " <br/> (" + month_year + ")</b></font> <br/> " + product_name);
    }

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

/* Sorting JSON result in ascending order */
function SortByID(x, y) {
    var amc_type = $('#amc_type').val();
    if (amc_type == 'C') {
        return x.Vaccinated - y.Vaccinated;
    } else if (amc_type == 'A') {
        return x.AMC - y.AMC;
    }
}
/* End of Function */

/* Funtion for Attribute table creation */
function drawGrid() {
    $("#attributeGrid").html("");
    $("#districtRanking").html("");
    dataDownload.length = 0;
    jsonData.length = 0;
    var features = vLMIS.features;
    table = "<table class='table table-condensed table-bordered table-hover'>";
    table += "<thead><th>Province</th><th>District</th><th class='center'>Product</th><th class='center' colspan='2'>" + type_name + "</th></thead>";
    for (var i = 0; i < features.length; i++) {
        table += "<tr><td>" + features[i].attributes.province + "</td><td>" + features[i].attributes.district + "</td><td class='center'>" + features[i].attributes.product + "</td><td class='right'>" + features[i].attributes.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "</td><td><div style='width:30px;height:18px;background-color:" + features[i].attributes.color + "'></div></td></tr>";
        jsonData.push({
            label: features[i].attributes.district,
            value: features[i].attributes.value,
            color: features[i].attributes.color
        });
        if (type_name == "Consumption") {
            dataDownload.push({
                province: features[i].attributes.province,
                district_name: features[i].attributes.district,
                product: features[i].attributes.product,
                Status: features[i].attributes.status,
                Consumption: features[i].attributes.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            });
        } else {
            dataDownload.push({
                province: features[i].attributes.province,
                district_name: features[i].attributes.district,
                product: features[i].attributes.product,
                Status: features[i].attributes.status,
                Avg_monthly_consumption: features[i].attributes.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            });
        }

    }
    table += "</table>";
    $("#attributeGrid").append(table);
    maximum = vLMIS.features.length;
    districtRanking(jsonData, "");
}
/* End of Function */

/* Function for Percentage covered with selected province of classes */
function districtCountGraph() {

    var ND = CalculatePercent(NoData, maximum);
    var cls1 = CalculatePercent(class1, maximum);
    var cls2 = CalculatePercent(class2, maximum);
    var cls3 = CalculatePercent(class3, maximum);
    var cls4 = CalculatePercent(class4, maximum);
    var cls5 = CalculatePercent(class5, maximum);

    if (min == max) {

        if (min == "0" && max == "0") {
            pieArray.push({
                label: classesArray[0].description,
                value: ND,
                color: classesArray[0].color_code
            });
        } else {
            pieArray.push({
                label: classesArray[1].description,
                value: cls1,
                color: classesArray[1].color_code
            });
        }
    } else {

        pieArray.push({
            label: 'No Data',
            value: ND,
            color: classesArray[0].color_code
        });
        pieArray.push({
            label: classesArray[1].description,
            value: cls1,
            color: classesArray[1].color_code
        });
        pieArray.push({
            label: classesArray[2].description,
            value: cls2,
            color: classesArray[2].color_code
        });
        pieArray.push({
            label: classesArray[3].description,
            value: cls3,
            color: classesArray[3].color_code
        });
        pieArray.push({
            label: classesArray[4].description,
            value: cls4,
            color: classesArray[4].color_code
        });
        pieArray.push({
            label: classesArray[5].description,
            value: cls5,
            color: classesArray[5].color_code
        });
    }

    var revenueChart = new FusionCharts({
        type: 'column3D',
        renderAt: 'chart-container',
        width: '100%',
        height: '350px',
        dataFormat: 'json',
        dataSource: {
            "chart": {
                "caption": prov_name + "-" + type_name + " Status",
                "subcaption": download,
                "showLabels": "1",
                "showlegend": "1",
                "slantLabels": '1',
                "labelDisplay": 'Rotate',
                "enableLink": '1',
                "showValues": '1',
                "xAxisName": "",
                "numberSuffix": "%",
                "yAxisName": type_name,
                "exportEnabled": "1",
                "theme": "fint"
            },
            "data": pieArray
        }
    });
    revenueChart.render("pie");
}
/* End of Function */

/* Function for District wise ranking for Consumption */
function districtRanking(records, title) {

    records.sort(SortByRankingID);
    if (records.length > 52) {
        width = '280%';
    }
    else {
        width = '150%';
    }
    var revenueChart = new FusionCharts({
        type: 'column2D',
        renderAt: 'chart-container',
        width: width,
        height: '98%',
        dataFormat: 'json',
        dataSource: {
            "chart": {
                "caption": prov_name + " - District wise " + type_name + " Ranking " + title,
                "subcaption": download,
                "slantLabels": '1',
                "enableLink": '1',
                "showValues": '1',
                "rotateValues": '1',
                "placeValuesInside": '1',
                "formatnumberscale": "1",
                "adjustDiv": '0',
                "numDivLines": '3',
                "xAxisName": "",
                "yAxisName": type_name + " (Doses)",
                "exportEnabled": "1",
                "theme": "fint"
            },
            "data": records
        }
    });
    revenueChart.render("districtRanking");
}
/* End of Function */

/* Function for legend filter with Attribute table and district ranking graph */
function gridFilter(color) {
    $("#attributeGrid").html("");
    dataDownload.length = 0;
    var features = vLMIS.features;
    table = "<table class='table table-condensed table-bordered table-hover'>";
    table += "<thead><th>Province</th><th>District</th><th class='center'>Product</th><th class='center'>" + type_name + "</th><th></th></thead>";
    for (var i = 0; i < features.length; i++) {
        if (features[i].attributes.color == color) {
            table += "<tr><td>" + features[i].attributes.province + "</td><td>" + features[i].attributes.district + "</td><td class='center'>" + features[i].attributes.product + "</td><td class='right'>" + features[i].attributes.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "</td><td><div style='width:30px;height:18px;background-color:" + features[i].attributes.color + "'></div></td></tr>";
            if (type_name == "Consumption") {
                dataDownload.push({
                    province: features[i].attributes.province,
                    district_name: features[i].attributes.district,
                    product: features[i].attributes.product,
                    Status: features[i].attributes.status,
                    Consumption: features[i].attributes.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                });
            } else {
                dataDownload.push({
                    province: features[i].attributes.province,
                    district_name: features[i].attributes.district,
                    product: features[i].attributes.product,
                    Status: features[i].attributes.status,
                    Avg_monthly_consumption: features[i].attributes.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                });
            }
        }
    }
    table += "</table>";
    $("#attributeGrid").append(table);
}
/* End of Function */