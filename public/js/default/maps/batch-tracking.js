var batch;

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

    vLMIS = new OpenLayers.Layer.Vector("Month of Stock", {
        styleMap: vlMIS_style
    });
    map.addLayers([province, district, vLMIS]);
    vLMIS.setZIndex(800);
    district.setZIndex(900);
    province.setZIndex(900);

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


$("#submit").click(function() {getData();});
function readData() {
    if (province.features.length == "9" && district.features.length == "147") {
        getData();
        clearInterval(handler);
    }
}
function getData() {
    
    mapTitle();
    batch = $("#batch_no").val();

    $.ajax({
        url: appName + "/api/geo/get-batch-map-data",
        type: "GET",
        data: {
            batch: batch
        },
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
    if (data.length <= 0) {
        alert("No Data Available");
        $("#loader").css("display", "none");
        return;
    }
    for (var i = 0; i < data.length; i++) {
        chkeArray(data[i].district_id,data[i].batch_number,data[i].quantity,data[i].status,data[i].expiry_date);
    }
   drawGrid();
}

function chkeArray(district_id,batch_number,quantity,status,expiry_date) {
   
    for (var i = 0; i < district.features.length; i++) {
        if (district_id == district.features[i].attributes.district_id) {
            vLMISdistrictLayer(district.features[i].geometry, district.features[i].attributes.province_name, district.features[i].attributes.district_id, district.features[i].attributes.district_name, batch_number,quantity,status,expiry_date);
        }
    }
   
}



function vLMISdistrictLayer(wkt, province, district_id, district_name, batch_number,quantity,status,expiry_date) {
    
    feature = new OpenLayers.Feature.Vector(wkt);
    color = "#119300";
    feature.attributes = {
        district_id : district_id,
        province: province,
        district: district_name,
        color: color,
        batch : batch_number,
        quantity : quantity,
        status : status,
        expiry : expiry_date
    };
    vLMIS.addFeatures(feature);

   $("#loader").hide();
}


function onFeatureSelect(e) {
    $("#districtDetails").html("");
    record.length = 0;
    var d_id = e.feature.attributes['district_id'];
    $.ajax({
        url: appName + "/api/geo/get-batch-detail-data",
        type: "GET",
        data: {
            batch: batch,
            district:d_id
        },
        dataType: "json",
        success: callback,
        error: function(response) {
            alert("No Data Available");
            $("#loader").css("display", "none");
            return;
        }
    });
    
    function callback(response){
       
        record = response;
        
       tab = "<table class='table table-condensed table-hover'>";
       tab +="<thead><th>Store</th><th>Quantity</th><th>Status</th></thead>";
        for (var i = 0; i < record.length; i++) {
            tab +="<tr><td align='center'>"+record[i].warehouse_name+"</td><td align='center'>"+record[i].quantity+"</td><td align='center'>"+record[i].status+"</td></tr>";
         }
       tab += "</table>";
       
      $("#districtDetails").append(tab); 
        
    }

}

function onFeatureUnselect(e) { 
}


function drawGrid() {
    $("#attributeGrid").html(""); 
    dataDownload.length = 0;
    var features = vLMIS.features;
    table = "<table>";
    table += "<tr><td>Sr.No</td><td>Province</td><td>District</td><td>Batch Number </td><td>Quantity</td><td>Status</td><td>Expiry Date</td></tr>";
    for (var i = 0; i < features.length; i++) {
        table += "<tr><td>" + (i+1) + "</td><td>" + features[i].attributes.province + "</td><td>" + features[i].attributes.district + "</td><td align='right'>" + features[i].attributes.batch + "</td><td align='right'>" + features[i].attributes.quantity + "</td><td align='right'>" + features[i].attributes.status + "</td><td align='right'>" + features[i].attributes.expiry + "</td></tr>";
        dataDownload.push({
                 province: features[i].attributes.province,
                 district: features[i].attributes.district,
                 batch_number : features[i].attributes.batch,
                 quantity : features[i].attributes.quantity,
                 status : features[i].attributes.status,
                 expiry_Date : features[i].attributes.expiry
        });
    }
    table += "</table>";
    $("#attributeGrid").append(table);
}

function mapTitle() {
    
    $("#loader").show();
    $("#mapTitle").html("<font color='green' size='6'><b>Batch Tracking Map </font>");
    
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