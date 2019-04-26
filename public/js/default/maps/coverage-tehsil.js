/* The file is used for the Tehsil Map creation of month of stock */   
$(window).load(function() {
    
    $('#prov').change(function(){getDistrictList($('#prov').val());});getDistrictList($('#prov').val());
    
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
    vLMIS = new OpenLayers.Layer.Vector("Vaccine Coverage", {
        styleMap: vlMIS_style
    });

/* Adding All layers and setting Index for priority */
    map.addLayers([province,district,tehsil,vLMIS]);
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
 
    getLegend('7', 'Vaccine Coverage'); 
  
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
     dist   = $("#dist").val();
     product = $("#product").val();
     product_name = $("#product option:selected").text();
     provinceName = $("#prov").val();
     districtName = $("#dist").val();
     
     mapTitle();

     $.ajax({
        url: appName + "/api/geo/get-vaccine-coverage",
        type: "GET",
        data: {
            year: year,
            month: month,
            province: region,
            district: dist,
            product: product
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

        if (vLMIS.features.length > 0) {
            vLMIS.removeAllFeatures();
        }
        FilterTehsilData();
        if (data.length <= 0) {
            alert("No Data Available");
            $("#loader").css("display", "none");
            $("#submit").attr("disabled", false);
        }
        data.sort(SortByID);
        for (var i = 0; i < data.length; i++) {
            chkeArray(data[i].tehsil_id, Number(data[i].population), Number(data[i].target), Number(data[i].consumption), Number(data[i].vaccine_coverage));
        }
        drawGrid();
        districtCountGraph();
        $("#submit").attr("disabled", false);
    }
}
/* End of Function */

/* Function for connecting database result calls from ajax with district layer */
function chkeArray(tehsil_id, population,target, consumption, coverage) {
    for (var i = 0; i < tehsil.features.length; i++) {
        if (tehsil_id == tehsil.features[i].attributes.tehsil_id) {
            vLMISLayer(tehsil.features[i].geometry, tehsil.features[i].attributes.province_name,tehsil.features[i].attributes.district_name,tehsil.features[i].attributes.tehsil_name,product_name, population,target, consumption, coverage);
            break;
        }
    }
}
/* End of Function */

/* Function for renders feature and accociate attribute with it */
function vLMISLayer(wkt, provinceName,districtName,tehsilName,productName, population,target, consumption, value) {
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
    if (value > classesArray[5].start_value) {
        color = classesArray[5].color_code;
        class5 = Number(class5) + 1;
        status = classesArray[5].description;
    }
    feature.attributes = {
        province: provinceName,
        district: districtName,
        tehsil : tehsilName,
        product: productName,
        population: population.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),
        target: target.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),
        consumption: consumption.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),
        coverage: value,
        status : status,
        color: color
    };
    vLMIS.addFeatures(feature);

    $("#loader").css("display", "none");
}
/* End of Function */

/* Function for Feature Select */
function onFeatureSelect(e){
    $("#province").html(e.feature.attributes['province']);
    $("#district").html(e.feature.attributes['district']);
    $("#tehsil").html(e.feature.attributes['tehsil']);
    $("#consumption").html(e.feature.attributes['consumption']);
    $("#population").html(e.feature.attributes['population']);
    $("#target").html(e.feature.attributes['target']);
    $("#vaccine_coverage").html(e.feature.attributes['coverage']);
}
/* End of Function */

/* Function for Feature UnSelect */
function onFeatureUnselect(e) {
    $("#province").html("");
    $("#district").html("");
    $("#tehsil").html("");
    $("#consumption").html("");
    $("#population").html("");
    $("#target").html("");
    $("#vaccine_coverage").html("");
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
    
    $("#mapTitle").html("<font color='green' size='4'><b> Vaccine Coverage <br/> (" + month_year + ")</b></font> <br/> " + product_name);

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
    $("#loader").css("display", "block");
    $("#info").html("");
    $("#mapTitle").html("");
    $('.radio-button').prop('checked', false);
    $("#submit").attr("disabled", true);
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
function SortByID(x, y) {
    return x.vaccine_coverage - y.vaccine_coverage;
}
/* End of Function */

 /* Funtion for Attribute table creation */
function drawGrid(){
    $("#attributeGrid").html("");
    dataDownload.length = 0;
    jsonData.length = 0;
    var features = vLMIS.features;
    table = "<table class='table table-condensed table-bordered table-hover'>";
    table += "<thead><th>District</th><th>Tehsil</th><th>Consumption</th><th>Target</th><th class='center' colspan='2'>Coverage(%)</th></thead>";
    for (var i = 0; i < features.length; i++) {
        table += "<tr><td>" + features[i].attributes.district + "</td><td>" + features[i].attributes.tehsil + "</td><td align='right'>" + features[i].attributes.consumption.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "</td><td align='right'>" + features[i].attributes.target.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "</td><td align='right'>" + features[i].attributes.coverage + "</td><td><div style='width:30px;height:18px;background-color:" + features[i].attributes.color + "'></div></td></tr>";
        jsonData.push({
            label: features[i].attributes.tehsil,
            value: features[i].attributes.coverage,
            color: features[i].attributes.color
        });
        dataDownload.push({
            district: features[i].attributes.district,
            tehsil: features[i].attributes.tehsil,
            Consumption: features[i].attributes.consumption,
            Population: features[i].attributes.population,
            target: features[i].attributes.target,
            Vaccine_coverage: features[i].attributes.coverage
        });
    }
    table += "</table>";
    $("#attributeGrid").append(table);
    maximum = vLMIS.features.length;
}
/* End of Function */

/* Function for legend filter with Attribute table and district ranking graph */
function gridFilter(color){
    $("#attributeGrid").html("");
    dataDownload.length = 0;
    var features = vLMIS.features;
    table = "<table class='table table-condensed table-bordered table-hover'>";
    table += "<thead><th>District</th><th>Tehsil</th><th>Consumption</th><th>Target</th><th class='center' colspan='2'>Coverage(%)</th></thead>";
    for (var i = 0; i < features.length; i++) {
        if (features[i].attributes.color == color) {
           table += "<tr><td>" + features[i].attributes.district + "</td><td>" + features[i].attributes.tehsil + "</td><td align='right'>" + features[i].attributes.consumption.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "</td><td align='right'>" + features[i].attributes.target.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "</td><td align='right'>" + features[i].attributes.coverage + "</td><td><div style='width:30px;height:18px;background-color:" + features[i].attributes.color + "'></div></td></tr>";
           dataDownload.push({
                district: features[i].attributes.district,
                tehsil: features[i].attributes.tehsil,
                Consumption: features[i].attributes.consumption,
                Population: features[i].attributes.population,
                target: features[i].attributes.target,
                Vaccine_coverage: features[i].attributes.coverage
            });
        }
    }
    table += "</table>";
    $("#attributeGrid").append(table);
}
/* End of Function */

/* Function for legend filter with Attribute table and district ranking graph */
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

    if($('#dist').val()!="all"){name = $("#dist option:selected").text() }
    else{name = $("#prov option:selected").text()}
    var revenueChart = new FusionCharts({
        type: 'column2D',
        renderAt: 'chart-container',
        width: '100%',
        height: '98%',
        dataFormat: 'json',
        dataSource: {
            "chart": {
                "caption": name+" - Vaccine Coverage Status",
                "subcaption":download,
                "showLabels": "1",
                "showlegend":"1",
                "slantLabels": '1',
                "labelDisplay":'Rotate',
                "rotateValues": '1',
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
    revenueChart.render("tehsilPie");
}
/* End of Function */