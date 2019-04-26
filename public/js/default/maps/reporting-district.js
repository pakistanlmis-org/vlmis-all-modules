/* The file is used for the District Map creation of month of stock */
$(window).load(function() {

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
            styleMap: district_style
        });
        
/* Initializing vLMIS Layer */
    vLMIS = new OpenLayers.Layer.Vector("Reporting Rate", {
        styleMap: vlMIS_style
    });
    
/* Adding All layers and setting Index for priority */
    map.addLayers([province, district, vLMIS]);
    district.setZIndex(900);
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
    getLegend('4', 'Reporting Rate');   
    
 /* Function for getting map data
  *  inititally Selected Value */
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
$("#submit").click(function() {getData();});
/* End of Function */

/* Execute funtion for filter data */
function getData() {
   
    clearData();
    
    year = $("#year").val();
    month = $('#month').val();
    region = $("#province").val();
    provinceName = $("#province option:selected").text();
    mapTitle();
    

    $.ajax({
        url: appName + "/api/geo/get-reporting-rate",
        type: "GET",
        data: {
            year: year,
            month: month,
            province: region,
            level : '4'
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
        var data = [];
        data = response;
        FilterData();

        if (vLMIS.features.length > 0) {
            vLMIS.removeAllFeatures();
        }
        if (data.length <= 0){
            alert("No Data Available");
            $("#loader").css("display", "none");
            $("#submit").attr("disabled", false);
        }
        data.sort(SortByID);
        for (var i = 0; i < data.length; i++) {
            chkeArray(data[i].district_id, data[i].district_name, data[i].reported, data[i].total_warehouse, Number(data[i].reporting_rate));
        }
        drawGrid();
        districtCountGraph();
        $("#submit").attr("disabled", false);
    }
}
/* End of Function */

 /* Function for connecting database result calls from ajax with district layer */
function chkeArray(district_id, district_name, reported, total_warehouse, reporting_rate) {
    for (var i = 0; i < district.features.length; i++) {
        if (district_id == district.features[i].attributes.district_id) {
            vLMISLayer(district.features[i].geometry, district.features[i].attributes.province_id,district.features[i].attributes.province_name, district_id, district.features[i].attributes.district_name, reported, total_warehouse, reporting_rate);
            break;
        }
    }
}
/* End of Function */

/* Function for renders feature and accociate attribute with it */
function vLMISLayer(wkt,provinceId, province,district_id, district_name, reported, total_warehouse, reporting_rate) {
    feature = new OpenLayers.Feature.Vector(wkt);

    if (reporting_rate == classesArray[0].start_value && reporting_rate == classesArray[0].end_value) {
        color = classesArray[0].color_code;
        NoData = Number(NoData) + 1;
        status = classesArray[0].description;
    }
    if (reporting_rate > classesArray[1].start_value && reporting_rate <= classesArray[1].end_value) {
        color = classesArray[1].color_code;
        class1 = Number(class1) + 1;
        status = classesArray[1].description;
    }
    if (reporting_rate > classesArray[2].start_value && reporting_rate <= classesArray[2].end_value) {
        color = classesArray[2].color_code;
        class2 = Number(class2) + 1;
        status = classesArray[2].description;
    }
    if (reporting_rate > classesArray[3].start_value && reporting_rate <= classesArray[3].end_value) {
        color = classesArray[3].color_code;
        class3 = Number(class3) + 1;
        status = classesArray[3].description;
    }
    if (reporting_rate > classesArray[4].start_value && reporting_rate <= classesArray[4].end_value) {
        color = classesArray[4].color_code;
        class4 = Number(class4) + 1;
        status = classesArray[4].description;
    }
    if (reporting_rate > classesArray[5].start_value && reporting_rate <= classesArray[5].end_value) {
        color = classesArray[5].color_code;
        class5 = Number(class5) + 1;
        status = classesArray[5].description;
    }
    
    feature.attributes = {
        district_id: district_id,
        district: district_name,
        provinceId : provinceId,
        province: province,
        reported: reported,
        total_warehouse: total_warehouse,
        reporting_rate: reporting_rate,
        status : status,
        color: color
    };
    vLMIS.addFeatures(feature);
    $("#loader").css("display", "none");
}
/* End of Function */

/* Function for Feature Select */
function onFeatureSelect(e) {
    $("#prov").html(e.feature.attributes['province']);
    $("#district").html(e.feature.attributes['district']);
    $("#total_ucs").html(e.feature.attributes['total_warehouse']);
    $("#reported_ucs").html(e.feature.attributes['reported']);
    $("#non_reported_ucs").html((e.feature.attributes['total_warehouse'] - e.feature.attributes['reported']));
    $("#reporting_rate").html(e.feature.attributes['reporting_rate']);
    getNonReportedUCs(e.feature.attributes['district_id'], e.feature.attributes['provinceId']);
}
/* End of Function */

/* Function for Feature UnSelect */
function onFeatureUnselect(e) {
    $("#prov").html("");
    $("#district").html("");
    $("#total_ucs").html("");
    $("#reported_ucs").html("");
    $("#non_reported_ucs").html("");
    $("#reporting_rate").html("");
}
/* End of Function */

/* Execute funtion for map title ceation */
function mapTitle() {

    prov_name = $("#province option:selected").text();
    if(prov_name == "Select"){prov_name = "Pakistan"};
    year_name = $("#year option:selected").text();
    month_value = ($('#month').val()) - 1;
    var month_name = monthNames[month_value];
    month_year = month_name + " " + year_name;
    download = month_year;
    
    $("#mapTitle").html("<font color='green' size='4'><b> Reporting Rate (" + month_year + ")</b></font> <br/> By UC");

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

/* Execute funtion for clear all the table,graphs data  */
function clearData(){
    $("#loader").show();
    $("#mapTitle").html("");
    $("#attributeGrid").html("");
    $("#districtRanking").html("");
    $("#ucs_list").html("<h5 class='center' style='margin:70px auto;'>Click any district for Non Reported <br/> UCs list</h5>");
    $("#submit").attr("disabled", true);
    $('.radio-button').prop('checked', false);
    pieArray.length = 0;
    NoData = '0';
    DataProblem = '0';
    class1 = '0';
    class2 = '0';
    class3 = '0';
    class4 = '0';
    class5 = '0';
    onFeatureUnselect();
}
/* End of Function */

 /* Sorting JSON result in ascending order */
function SortByID(x, y) {return x.reporting_rate - y.reporting_rate;}
/* End of Function */

 /* Funtion for Attribute table creation */
function drawGrid(){
    $("#attributeGrid").html("");
    $("#districtRanking").html("");
    dataDownload.length = 0;
    jsonData.length = 0;
    var features = vLMIS.features;
    table = "<table class='table table-condensed table-bordered table-hover'>";
    table += "<thead><th>Province</th><th>District</th><th class='center'>Total UCs</th><th class='center'>Reported UCs</th><th class='center' colspan='2'>Reporting Rate(%)</th></thead>";
    for (var i = 0; i < features.length; i++) {
        table += "<tr><td>" + features[i].attributes.province + "</td><td>" + features[i].attributes.district + "</td><td class='center'>" + features[i].attributes.total_warehouse + "</td><td class='center'>" + features[i].attributes.reported + "</td><td class='center'>" + features[i].attributes.reporting_rate + "</td><td align='left'><div style='width:30px;height:18px;background-color:" + features[i].attributes.color + "'></div></td></tr>";
        jsonData.push({
            label: features[i].attributes.district,
            value: features[i].attributes.reporting_rate,
            color: features[i].attributes.color
        });
        dataDownload.push({
            province: features[i].attributes.province,
            district_name: features[i].attributes.district,
            total_UCs: features[i].attributes.total_warehouse,
            reported_UCs: features[i].attributes.reported,
            Status:features[i].attributes.status,
            reporting_rate: features[i].attributes.reporting_rate
        });
    }
    table += "</table>";
    $("#attributeGrid").append(table);
    maximum = vLMIS.features.length;
    districtRanking(jsonData,"");
}
/* End of Function */

/* Function for District wise ranking for Reporting Rate */
function districtRanking(records,title) {

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
                "caption": prov_name+" - District Wise Reporting Rate Ranking "+ title,
                "subcaption": download,
                "showLabels": "1",
                "slantLabels": '1',
                "enableLink": '0',
                "showValues": '1',
                "rotateValues": '1',
                "placeValuesInside": '1',
                "xAxisName": "",
                "yAxisName": "Reporting Rate",
                "numberSuffix": "%",
                "adjustDiv":'0',
                "numDivLines":'3',
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
function gridFilter(color){
    $("#attributeGrid").html("");
    dataDownload.length = 0;
    var features = vLMIS.features;
    table = "<table class='table table-condensed table-bordered table-hover'>";
    table += "<thead><th>Province</th><th>District</th><th>Total UCs</th><th>Reported UCs</th><th colspan='2' class='center'>Reporting Rate (%)</th></thead>";
    for (var i = 0; i < features.length; i++) {
        if (features[i].attributes.color == color) {
            table += "<tr><td>" + features[i].attributes.province + "</td><td>" + features[i].attributes.district + "</td><td class='right'>" + features[i].attributes.total_warehouse + "</td><td class='right'>" + features[i].attributes.reported + "</td><td class='right'>" + features[i].attributes.reporting_rate + "</td><td><div style='width:30px;height:18px;background-color:" + features[i].attributes.color + "'></div></td></tr>";
            dataDownload.push({
                province: features[i].attributes.province,
                district_name: features[i].attributes.district,
                total_UCs: features[i].attributes.total_warehouse,
                reported_UCs: features[i].attributes.reported,
                Status:features[i].attributes.status,
                reporting_rate: features[i].attributes.reporting_rate
            });
        }
    }
    table += "</table>";
    $("#attributeGrid").append(table);
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

    pieArray.push({
        label: classesArray[0].description,
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


    var revenueChart = new FusionCharts({
        type: 'column3D',
        renderAt: 'chart-container',
        width: '100%',
        height: '265px',
        dataFormat: 'json',
        dataSource: {
            "chart": {
                "caption": prov_name+" - Reporting Rate Status",
                "subcaption":download,
                "showLabels": "1",
                "showlegend":"1",
                "slantLabels": '1',
                "labelDisplay":'Rotate',
                "enableLink": '1',
                "showValues": '1',
                "xAxisName": "",
                "numberSuffix": "%",
                "exportEnabled": "1",
                "theme": "fint"
            },
            "data": pieArray
        }
    });
    revenueChart.render("pie");
}
/* End of Function */

/* Function for Getting list of Non Reported UCs  */
function getNonReportedUCs(district_id,province_id){
     $("#ucs_list").html("");
     $("#ucs_list").html("<img src='"+appName+"/images/ajax-loader.gif' style='display:block;width:100px;height:100px;margin:50px auto'>");
     
       $.ajax({
        url: appName + "/api/geo/get-non-reported-ucs",
        type: "GET",
        data: {
            year: year,
            month: month,
            province:province_id,
            district:district_id
        },
        dataType: "JSON",
        error: function(response) {
             $("#ucs_list").html("");
             $("#ucs_list").html("No Non-Reported UCs Found ");
        },
        success: callback
    });

        function callback(response) {

        var chart = [];
        chart = response;
        $("#ucs_list").html("");

        table = "<table class='table table-condensed table-bordered table-hover'>";
        table += "<thead><th>Sr.No</th><th>District</th><th>UC Name</th></thead>";
        for (var i = 0; i < chart.length; i++) {
            table += "<tr><td>" + (i+1) + "</td><td>" + chart[i].district_name + "</td><td>" + chart[i].location_name + "</td>";
        }
        table += "</table>";
        $("#ucs_list").append(table);     
    }   
}
/* End of Function */