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
    vLMIS = new OpenLayers.Layer.Vector("Months of Stock", {
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
    getLegend('6', 'Stock Expiry Rate'); 

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
    
    region = $("#prov").val();
    dist = $("#dist").val();
    product = $("#product").val();
    product_name = $("#product option:selected").text();
    provinceName = $("#prov").val();
    districtName = $("#dist").val();
    
    mapTitle();
    

     $.ajax({
        url: appName + "/api/geo/get-expiry-alert",
        type: "GET",
        data: {
            province: region,
            district : dist,
            product: product,
            level : '5'
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
        FilterTehsilData();

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
            chkeArray(data[i].tehsil_id,data[i].tehsil_name,data[i].quantity, data[i].ExpiringIn6Months,Number(data[i].expiry_rate));
        }
        drawGrid();
        districtCountGraph();
        $("#submit").attr("disabled", false);
    }
}
/* End of Function */

 /* Function for connecting database result calls from ajax with district layer */
function chkeArray(tehsil_id,tehsil_name, quantity, quantity6Months, expiryRate) {
    for (var i = 0; i < tehsil.features.length; i++) {
        if (tehsil_id == tehsil.features[i].attributes.tehsil_id) {
            vLMISLayer(tehsil.features[i].geometry, tehsil.features[i].attributes.district_name, product_name, tehsil_id, tehsil_name, quantity, quantity6Months,expiryRate);
            break;
        }
    }
}
/* End of Function */

/* Function for renders feature and accociate attribute with it */
function vLMISLayer(wkt, district, product, tehsil_id, tehsil_name, quantity, quantity6Months, expiryRate) {
    feature = new OpenLayers.Feature.Vector(wkt);

     if (expiryRate == classesArray[0].start_value && expiryRate == classesArray[0].end_value) {
        color = classesArray[0].color_code;
        NoData = Number(NoData) + 1;
        status = classesArray[0].description;
    }
    if (expiryRate > classesArray[1].start_value && expiryRate <= classesArray[1].end_value) {
        color = classesArray[1].color_code;
        class1 = Number(class1) + 1;
        status = classesArray[1].description;
    }
    if (expiryRate > classesArray[2].start_value && expiryRate <= classesArray[2].end_value) {
        color = classesArray[2].color_code;
        class2 = Number(class2) + 1;
        status = classesArray[2].description;
    }
    if (expiryRate > classesArray[3].start_value && expiryRate <= classesArray[3].end_value) {
        color = classesArray[3].color_code;
        class3 = Number(class3) + 1;
        status = classesArray[3].description;
    }
    if (expiryRate > classesArray[4].start_value && expiryRate <= classesArray[4].end_value) {
        color = classesArray[4].color_code;
        class4 = Number(class4) + 1;
        status = classesArray[4].description;
    }
    if (expiryRate > classesArray[5].start_value) {
        color = classesArray[5].color_code;
        class5 = Number(class5) + 1;
        status = classesArray[5].description;
    }

    
    feature.attributes = {
        district: district,
        tehsilId: tehsil_id,
        tehsilName: tehsil_name,
        product: product,
        quantity: quantity,
        quantity6Months: quantity6Months,
        expiryRate: expiryRate,
        color: color,
        status : status 
    };

    vLMIS.addFeatures(feature);
    $("#loader").css("display", "none");
}
/* End of Function */

/* Function for Feature Select */
function onFeatureSelect(e) {
    $("#district").html(e.feature.attributes['district']);
    $("#tehsil").html(e.feature.attributes['tehsilName']);
    $("#total_quantity").html(e.feature.attributes['quantity']);
    $("#expire_quantity").html(e.feature.attributes['quantity6Months']);
    $("#expiry_rate").html(e.feature.attributes['expiryRate']);
    lastMonthsStats(e.feature.attributes['tehsilId'], product);
}
/* End of Function */

/* Function for Feature UnSelect */
function onFeatureUnselect(e) {
    $("#district").html("");
    $("#tehsil").html("");
    $("#total_quantity").html("");
    $("#expire_quantity").html("");
    $("#expiry_rate").html("");
}
/* End of Function */

/* Execute funtion for map title ceation */
function mapTitle() {
    prov_name = $("#province option:selected").text();
    if(prov_name == "Select"){prov_name = "Pakistan"};
    
    $("#mapTitle").html("<font color='green' size='4'><b>Stock Expiry in Six Months</font> <br/> " + product_name);

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
    $("#mapTitle").html("");
    $('.radio-button').prop('checked', false);
    $("#submit").attr("disabled", true);
    $("#batch_list").html("<h5 align='center' style='margin:70px auto;'>Click any district for Detail Batches Quantity list</h5>");
    dataDownload.length = 0;
    $("#attributeGrid").html("");
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
function SortByID(x, y) {return x.expiry_rate - y.expiry_rate;}
/* End of Function */

 /* Funtion for Attribute table creation */
function drawGrid(){
    $("#attributeGrid").html("");
    dataDownload.length = 0;
    var features = vLMIS.features;
    table = "<table class='table table-condensed table-bordered table-hover'>";
    table += "<thead><th>District</th><th>Tehsil</th><th>Total Quantity(vials)</th><th>Expire within 6 Months(vials)</th><th class='center' colspan='2'>Expiry Rate(%)</th></thead>";
    for (var i = 0; i < features.length; i++) {
        table += "<tr><td>" + features[i].attributes.district + "</td><td>" + features[i].attributes.tehsilName + "</td><td align='right'>" + features[i].attributes.quantity + "</td><td align='right'>" + features[i].attributes.quantity6Months + "</td><td align='right'>" + features[i].attributes.expiryRate + "</td><td><div style='width:30px;height:18px;background-color:" + features[i].attributes.color + "'></div></td></tr>";
        jsonData.push({
            label: features[i].attributes.tehsilName,
            value: features[i].attributes.expiryRate,
            color: features[i].attributes.color
        });
         dataDownload.push({
                district: features[i].attributes.district,
                tehsil: features[i].attributes.tehsilName,
                Total_Quantity_vials: features[i].attributes.quantity,
                Quantity_Expire_6Months_vials: features[i].attributes.quantity6Months,
                Percent_Expire_6Months: features[i].attributes.expiryRate
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
        type: 'pie2d',
        renderAt: 'chart-container',
        width: '100%',
        height: '100%',
        dataFormat: 'json',
        dataSource: {
            "chart": {
                "caption": name+" - Expiry Rate Status",
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

/* Function for legend filter with Attribute table and district ranking graph */
function gridFilter(color){
    $("#attributeGrid").html("");
    dataDownload.length = 0;
    var features = vLMIS.features;
    table = "<table class='table table-condensed table-bordered table-hover'>";
    table += "<thead><th>District</th><th>Tehsil</th><th>Total Quantity(vials)</th><th>Expire within 6 Months(vials)</th><th class='center' colspan='2'>Expiry Rate(%)</th></thead>";
    for (var i = 0; i < features.length; i++) {
        if (features[i].attributes.color == color) {
           table += "<tr><td>" + features[i].attributes.district + "</td><td>" + features[i].attributes.tehsilName + "</td><td align='right'>" + features[i].attributes.quantity + "</td><td align='right'>" + features[i].attributes.quantity6Months + "</td><td align='right'>" + features[i].attributes.expiryRate + "</td><td><div style='width:30px;height:18px;background-color:" + features[i].attributes.color + "'></div></td></tr>";
           dataDownload.push({
                district: features[i].attributes.district,
                tehsil: features[i].attributes.tehsilName,
                Total_Quantity_vials: features[i].attributes.quantity,
                Quantity_Expire_6Months_vials: features[i].attributes.quantity6Months,
                Percent_Expire_6Months: features[i].attributes.expiryRate
            });
        }
    }
    table += "</table>";
    $("#attributeGrid").append(table);
}
/* End of Function */

/* Function for Getting list expiry batches */
function lastMonthsStats(tehsil_id,product){
    
     $("#batch_list").html("");
     $("#batch_list").html("<img src='"+appName+"/images/ajax-loader.gif' style='display:block;width:100px;height:100px;margin:50px auto'>");
     
       $.ajax({
        url: appName + "/api/geo/get-stock-batch-detail",
        type: "GET",
        data: {
            tehsil:tehsil_id,
            product:product
        },
        dataType: "JSON",
        error: function(response) {
             $("#batch_list").html("");
             $("#batch_list").html("No Batches Found");
        },
        success: callback
    });

        function callback(response) {

        var chart = [];
        chart = response;
        $("#batch_list").html("");

        table = "<table class='table table-condensed table-bordered table-hover'>";
        table += "<thead><th>S.No</th><th>Tehsil</th><th>Batch</th><th>Quantity</th><th>Expiry</th></thead>";
        for (var i = 0; i < chart.length; i++) {
            table += "<tr><td>" + (i+1) + "</td><td>" + chart[i].tehsil_name + "</td><td>" + chart[i].number + "</td><td align='center'>" + chart[i].quantity + "</td><td align='center'>" + chart[i].expiry_date + "</td>";
        }
        table += "</table>";
        $("#batch_list").append(table);     
    }   
}
/* End of Function */

