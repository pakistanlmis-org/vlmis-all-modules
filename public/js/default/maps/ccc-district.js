
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

    vLMIS = new OpenLayers.Layer.Vector("Cold Chain Capacity", {
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

    var region = $("#province").val();
    type = $("#coldchain_type").val();
    product_name = $("#product option:selected").text();
    type_name = $("#coldchain_type option:selected").text();
    provinceName = $("#province option:selected").text();
        
    mapTitle();

    $.ajax({
        url: appName + "/api/geo/get-coldchain-capacity",
        type: "GET",
        data: {
            province: region,
            type: type,
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
        data = response;

        for (var i = 0; i < data.length; i++) {
            maxValue.push(Number(data[i].capacity));
        }

        max = Math.max.apply(Math, maxValue);
        min = Math.min.apply(Math, maxValue);

        getLegend('8', max, min, 'Cold Chain Capacity');

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
    for (var i = 0; i < data.length; i++) {
        chkeArray(data[i].district_id, Math.round(Number(data[i].capacity)));
    }
    drawGrid();
    districtCountGraph();
}
/* End of Function */

 /* Function for connecting database result calls from ajax with district layer */
function chkeArray(district_id, capacity) {
    for (var i = 0; i < district.features.length; i++) {
        if (district_id == district.features[i].attributes.district_id) {
            if (min == max) {
                vLMISMiniLayer(district.features[i].geometry, district.features[i].attributes.province_name, district_id, district.features[i].attributes.district_name, capacity);
                break;
            } else {
                vLMISLayer(district.features[i].geometry, district.features[i].attributes.province_name, district_id, district.features[i].attributes.district_name, capacity);
                break;
            }
        }
    }
}
/* End of Function */

/* Function for renders feature and accociate attribute with it */
function vLMISLayer(wkt, province, district_id, district, value) {
    feature = new OpenLayers.Feature.Vector(wkt);

    if (value == classesArray[0].start_value && value == classesArray[0].end_value) {
        color = classesArray[0].color_code;
        NoData = Number(NoData) + 1;
        status = classesArray[0].description;
    }
    if (value > classesArray[1].start_value && value <= classesArray[1].end_value) {
        color = classesArray[1].color_code;
        class1 = Number(class1) + 1;
        status = classesArray[1].description;
    }
    if (value > classesArray[2].start_value && value <= classesArray[2].end_value) {
        color = classesArray[2].color_code;
        class2 = Number(class2) + 1;
        status = classesArray[2].description;
    }
    if (value > classesArray[3].start_value && value <= classesArray[3].end_value) {
        color = classesArray[3].color_code;
        class3 = Number(class3) + 1;
        status = classesArray[3].description;
    }
    if (value > classesArray[4].start_value && value <= classesArray[4].end_value) {
        color = classesArray[4].color_code;
        class4 = Number(class4) + 1;
        status = classesArray[4].description;
    }
    if (value > classesArray[5].start_value && value <= classesArray[5].end_value) {
        color = classesArray[5].color_code;
        class5 = Number(class5) + 1;
        status = classesArray[5].description;
    }

    feature.attributes = {
        district_id: district_id,
        district: district,
        province: province,
        capacity: value,
        status : status,
        color: color
    };
    vLMIS.addFeatures(feature);
    $("#loader").css("display", "none");
}
/* End of Function */

/* Function for renders feature and accociate attribute with it */
function vLMISMiniLayer(wkt, province, district_id, district, value) {
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
        district: district,
        province: province,
        capacity: value,
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
    $("#capacity").html(e.feature.attributes['capacity'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
    lastMonthsStats(e.feature.attributes['district_id']);
}
/* End of Function */

/* Function for Feature UnSelect */
function onFeatureUnselect(e) {
    $("#prov").html("");
    $("#district").html("");
    $("#capacity").html("");
}
/* End of Function */

function lastMonthsStats(district_id){
   
     $("#_list").html("");
     $("#_list").html("<img src='"+appName+"/images/ajax-loader.gif' style='display:block;width:100px;height:100px;margin:50px auto'>");
     
       $.ajax({
        url: appName + "/api/geo/get-cold-chain-asset-detail",
        type: "GET",
        data: {
            district:district_id,
            type : type
        },
        dataType: "JSON",
        error: function(response) {
             $("#_list").html("");
             $("#_list").html("No Details Found");
        },
        success: callback
    });

        function callback(response) {

        var chart = [];
        chart = response;
        $("#_list").html("");

        table = "<table class='table table-condensed table-bordered table-hover'>";
        table += "<thead><th>S.No</th><th>District</th><th>Asset Type</th><th>Capacity</th></thead>";
        for (var i = 0; i < chart.length; i++) {
            table += "<tr><td>" + (i+1) + "</td><td>" + chart[i].district_name + "</td><td>" + chart[i].asset_type_name + "</td><td align='center'>" + chart[i].capacity + "</td>";
        }
        table += "</table>";
        $("#_list").append(table);     
    }   
}
/* End of Function */

/* Execute funtion for map title ceation */
function mapTitle() {

    prov_name = $("#province option:selected").text();
    if(prov_name == "Select"){prov_name = "Pakistan"};
    year_name = $("#year option:selected").text();
    type_name = $("#coldchain_type option:selected").text();

    $("#mapTitle").html("<font color='green' size='4'><b> Cold Chain Capacity </b></font> <br/> " + type_name);

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
function clearData() {
    $("#loader").show();
    $("#legendDiv").css("display", "none");
    $("#legend").html("");
    $('.radio-button').prop('checked', false);
    $("#attributeGrid").html("");
    $("#districtRanking").html("");
    $("#_list").html("<h5 align='center' style='margin:80px auto;'>Click any district for Asset type details</h5>");
    $("#mapTitle").html("");
    classesArray.length = 0;
    pieArray.length = 0;
    maxValue.length = 0;
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
function SortByID(x, y) {return x.capacity - y.capacity;}
/* End of Function */

 /* Funtion for Attribute table creation */
function drawGrid() {
    $("#attributeGrid").html("");
    $("#districtRanking").html("");
    dataDownload.length = 0;
    jsonData.length = 0;
    var features = vLMIS.features;
    table = "<table class='table table-condensed table-bordered table-hover'>";
    table += "<thead><th>Province</th><th>District</th><th class='center' colspan='2'>Capacity</th></thead>";
    for (var i = 0; i < features.length; i++) {
        table += "<tr><td>" + features[i].attributes.province + "</td><td>" + features[i].attributes.district + "</td><td align='right'>" + features[i].attributes.capacity.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "</td><td><div style='width:30px;height:18px;background-color:" + features[i].attributes.color + "'></div></td></tr>";
        jsonData.push({
            label: features[i].attributes.district,
            value: features[i].attributes.capacity,
            color: features[i].attributes.color
        });
        dataDownload.push({
            province: features[i].attributes.province,
            district_name: features[i].attributes.district,
            Status:features[i].attributes.status,
            Capacity: features[i].attributes.capacity.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        });
    }
    table += "</table>";
    $("#attributeGrid").append(table);
    maximum = vLMIS.features.length;
    var pageTitle = $(".page-title").html();
    var title = pageTitle.split("Map");
    districtRanking(jsonData,"- "+title[0]);
}
/* End of Function */

/* Function for District wise ranking for Capacity */
function districtRanking(records,title) {

    records.sort(SortByRankingID);

    if(records.length > 52) {
        width = '280%';
    } 
    else {
        width = '150%';
    }

    var revenueChart = new FusionCharts({
        type: 'column2d',
        renderAt: 'chart-container',
        width: width,
        height: '98%',
        dataFormat: 'json',
        dataSource: {
            "chart": {
                "caption": prov_name+" - District wise " + type_name + " Ranking"+title,
                "showLabels": "1",
                "showlegend":"1",
                "slantLabels": '1',
                "showValues": '1',
                "adjustDiv":'0',
                "numDivLines":'3',
                "rotateValues": '1',
                "placeValuesInside": '1',
                "yAxisName": type_name,
                "exportEnabled": "1",
                "theme": "fint"
            },
             "styles":{
                "definition":[{
                    "name":"myValuesFont",
                    "type":"font",
                    "size":"10",
                    "color":"666666",
                    "bold":"1"
                  }
                ],
                "application":[{
                    "toobject":"DataValues",
                    "styles":"myValuesFont"
                  }
                ]
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
    table += "<thead><th>Province</th><th>District</th><th class='center' colspan='2'>Capacity</th></thead>";
    for (var i = 0; i < features.length; i++) {
        if (features[i].attributes.color == color) {
            table += "<tr><td>" + features[i].attributes.province + "</td><td>" + features[i].attributes.district + "</td><td align='right'>" + features[i].attributes.capacity.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "</td><td><div style='width:30px;height:18px;background-color:" + features[i].attributes.color + "'></div></td></tr>";
            dataDownload.push({
            province: features[i].attributes.province,
            district_name: features[i].attributes.district,
            Status:features[i].attributes.status,
            Capacity: features[i].attributes.capacity.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
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

    if (min == max) {
       if(min == "0" && max == "0"){
                pieArray.push({
                  label: classesArray[0].description,
                  value: ND,
                  color: classesArray[0].color_code
              }); 
       }else{ 
                pieArray.push({
                    label: classesArray[1].description,
                    value: cls1,
                    color: classesArray[1].color_code
                });
        }
    } else {
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
    }

    var revenueChart = new FusionCharts({
        type: 'pie2D',
        renderAt: 'chart-container',
        width: '100%',
        height: '270px',
        dataFormat: 'json',
        dataSource: {
            "chart": {
                "caption": prov_name+"-"+type_name + " Status",
                "showLabels": "0",
                "showlegend":"1",
                "slantLabels": '1',
                "labelDisplay":'Rotate',
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