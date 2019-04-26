
$(window).load(function() {

    map = new OpenLayers.Map('mapped', {
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

    vLMIS = new OpenLayers.Layer.Vector("Demographic Map", {
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
    downloadExtent = bounds;
    handler = setInterval(readData, 2000);
});

function readData() {
    if (province.features.length == "9" && district.features.length == "147") {
        getData();
        clearInterval(handler);
    }
}

$("#submit").click(function() {getData();});

function getData() {
    
    clearData();
    mapTitle();
    
   
    $.ajax({
        url: appName + "/api/geo/get-demographic-map-data",
        type: "GET",
        dataType: "json",
        success: callback,
        error: function(response) {
            alert("No Data Available");
            $("#loader").css("display", "none");
            return;
        }
    });

    function callback(response) {
        data = response;
        drawFeature();
    }
}

function drawFeature() {
    if (vLMIS.features.length > 0) {
        vLMIS.removeAllFeatures();
    }
    if (data[0].length <= 0) {
            alert("No Data Available");
            $("#loader").css("display", "none");
    }
    data[0].sort(SortByID);
    $("#pilot_population").html(data[1][0].total_population.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
    $("#pilot_province").html(data[1][0].total_province);
    $("#pilot_district").html(data[1][0].total_districts);
    for (var i = 0; i < data[0].length; i++) {
        chkeArray(data[0][i].district_id, data[0][i].total_ucs,data[0][i].total_users,data[0][i].total_facility,data[0][i].total_tehsils,data[0][i].population);
    }
   // drawTable();
}

function chkeArray(district_id,ucs,users,facility,tehsils,population) {
    for (var i = 0; i < district.features.length; i++) {
        if (district_id == district.features[i].attributes.district_id) {
            vLMISdistrictLayer(district.features[i].geometry,district_id,district.features[i].attributes.province_name,district.features[i].attributes.district_name,ucs,users,facility,tehsils,population);
            break;
        }
    }
}

function vLMISdistrictLayer(wkt,district_id,province_name,district_name, ucs,users,facility,tehsils,population) {
    feature = new OpenLayers.Feature.Vector(wkt);
    color = "#119300";
    feature.attributes = {
        district_id : district_id,
        province: province_name,
        district: district_name,
        color: color,
        ucs : ucs,
        users : users,
        facility : facility,
        tehsils : tehsils,
        population : population
    };
    vLMIS.addFeatures(feature);

    $("#loader").css("display", "none");
}

function onFeatureSelect(e) {
    
    $("#prov").html(e.feature.attributes['province']);
    $("#dist").html(e.feature.attributes['district']);
    $("#tehsils").html("<a style='cursor:pointer' id='th' onclick='getId(this.id,"+e.feature.attributes['district_id']+")'>"+e.feature.attributes['tehsils']+"</a>");
    $("#ucs").html("<a style='cursor:pointer' id='uc' onclick='getId(this.id,"+e.feature.attributes['district_id']+")'>"+e.feature.attributes['ucs']+"</a>");
    $("#users").html("<a style='cursor:pointer' id='us' onclick='getId(this.id,"+e.feature.attributes['district_id']+")'>"+e.feature.attributes['users']+"</a>");
    $("#facility").html("<a style='cursor:pointer' id='fac' onclick='getId(this.id,"+e.feature.attributes['district_id']+")'>"+e.feature.attributes['facility']+"</a>");
    $("#pop").html(e.feature.attributes['population'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
    
}

function onFeatureUnselect(e) {
    $("#prov").html("");
    $("#dist").html("");
    $("#tehsils").html("");
    $("#ucs").html("");
    $("#users").html("");
    $("#facility").html("");
    $("#pop").html("");
    $("#detailInfo").html("<p style='padding-top:150px;font-weight:bold;font-size:12px;text-align:center'>Click on district information table row for more details</p>");
}

function clearData(){
    $("#loader").show();
    $("#attributeGrid").html("");
    $("#mapTitle").html("");
    onFeatureUnselect();
}

function mapTitle() {
    $("#mapTitle").html("<font color='green' size='6'><b>Demographic Map </font>");
    
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

function SortByID(x, y) {return x.district_id - y.district_id;}

function drawTable(){
    $("#attributeGrid").html(""); 
    dataDownload.length = 0;
    jsonData.length = 0;
    var features = vLMIS.features;
    table = "<table>";
    table += "<tr><td>Province</td><td>District</td><td>Total Tehsils</td><td>Total UCs</td><td>Total Users</td><td>Total Facility</td><td>Total Population</td></tr>";
    for (var i = 0; i < features.length; i++) {
        table += "<tr><td>" + features[i].attributes.province + "</td><td>" + features[i].attributes.district + "</td><td align='right'>" + features[i].attributes.tehsils + "</td><td align='right'>" + features[i].attributes.ucs + "</td><td align='right'>" + features[i].attributes.users + "</td><td align='right'>" + features[i].attributes.facility + "</td><td align='right'>" + features[i].attributes.population.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "</td></tr>";
        dataDownload.push({
            province: features[i].attributes.province,
            district_name: features[i].attributes.district,
            total_tehsils: features[i].attributes.tehsils,
            total_ucs:features[i].attributes.ucs,
            total_users: features[i].attributes.users,
            total_facility: features[i].attributes.facility,
            population: features[i].attributes.population
        });
    }
    table += "</table>";
    $("#attributeGrid").append(table);
}

function getId(id,district_id){
    $("#detailInfo").html("");
     $.ajax({
         url: appName + "/api/geo/get-demographic-map-data",
        type: "GET",
        dataType: "json",
         data: {
            id: id,
            districtId : district_id
        },
        success: callback,
        error: function(response) {
            alert("No Data Available");
            $("#loader").css("display", "none");
            return;
        }
    });
    
    function callback(response){
       var array = [];
       array = response;
        
       if (array.length <= 0) {
            alert("No Results Found");
            return;
         }
    
        table = "<table class='table table-condensed table-hover'>";
        table += "<thead><th>Sr.No</th><th>Name</th></thead>";
        for (var i = 0; i < array.length; i++) {
            table += "<tr><td>" + (i+1) + "</td><td>" + array[i].name + "</td></tr>";
        }
        table += "</table>";
        $("#detailInfo").append(table);
    }
    
}

$("#excel").click(function() {
        var title = $(".page-title").html();
        var split = title.split("Map");
        if(split[0] == "Cold Chain Capacity"){title = split[0];}
        else{title = split[0]+"("+month_year+")";}
        JSONToCSVConvertor(dataDownload, title, true);
    });

function JSONToCSVConvertor(JSONData, ReportTitle, ShowLabel) {
        var arrData = typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData;
        var CSV = '';
        CSV += ReportTitle + '\r\n\n';
        if (ShowLabel) {
            var row = "";
            for (var index in arrData[0]) {
                row += index + ',';
            }
            row = row.slice(0, -1);
            CSV += row + '\r\n';
        }

        for (var i = 0; i < arrData.length; i++) {
            var row = "";
            for (var index in arrData[i]) {
                row += '"' + arrData[i][index] + '",';
            }
            row.slice(0, row.length - 1);
            CSV += row + '\r\n';
        }

        if (CSV == '') {
            alert("Invalid data");
            return;
        }

        var fileName = "";
        fileName += ReportTitle.replace(/ /g, "_");
        var uri = 'data:text/csv;charset=utf-8,' + escape(CSV);
        var link = document.createElement("a");
        link.href = uri;
        link.style = "visibility:hidden";
        link.download = fileName + ".csv";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }