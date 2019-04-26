var map, map2, baselayer, baselayer2, province, district, province2, district2, vLMIS1, vLMIS2, product_name, handler, selectfeature, selectfeature2;
var wastagesClassesArray = [];
var reportingClassesArray = [];

$(window).load(function() {

    var bounds = new OpenLayers.Bounds(60.52, 23.53, 79.81, 37.19);
    bounds.transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913"));

    var restricted = new OpenLayers.Bounds(60.52, 23.53, 79.81, 37.19);
    restricted.transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913"));

    map = new OpenLayers.Map('wastagesMap', {
        projection: new OpenLayers.Projection("EPSG:900913"),
        displayProjection: new OpenLayers.Projection("EPSG:4326"),
        maxExtent: restricted,
        restrictedExtent: restricted,
        maxResolution: "auto",
        controls: [
            new OpenLayers.Control.Navigation({
                'zoomWheelEnabled': false,
                'defaultDblClick': function(event) {
                    return;
                }
            }),
            new OpenLayers.Control.ScaleLine()
        ]
    });

    map2 = new OpenLayers.Map('reportingMap', {
        projection: new OpenLayers.Projection("EPSG:900913"),
        displayProjection: new OpenLayers.Projection("EPSG:4326"),
        maxExtent: restricted,
        restrictedExtent: restricted,
        maxResolution: "auto",
        controls: [
            new OpenLayers.Control.Navigation({
                'zoomWheelEnabled': false,
                'defaultDblClick': function(event) {
                    return;
                }
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


    province2 = new OpenLayers.Layer.Vector(
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

    district2 = new OpenLayers.Layer.Vector(
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

    vLMIS1 = new OpenLayers.Layer.Vector("vLMIS", {
        styleMap: vlMIS_style
    });
    vLMIS2 = new OpenLayers.Layer.Vector("vLMIS2", {
        styleMap: vlMIS_style
    });

    map.addLayers([vLMIS1, province, district]);
    map2.addLayers([vLMIS2, province2, district2]);
    province.setZIndex(800);
    province2.setZIndex(800);

    selectfeature = new OpenLayers.Control.SelectFeature([vLMIS1]);
    map.addControl(selectfeature);
    selectfeature.activate();
    selectfeature.handlers.feature.stopDown = false;

    selectfeature2 = new OpenLayers.Control.SelectFeature([vLMIS2]);
    map2.addControl(selectfeature2);
    selectfeature2.activate();
    selectfeature2.handlers.feature.stopDown = false;

    vLMIS1.events.on({
        "featureselected": onWastagesFeatureSelect,
        "featureunselected": onWastagesFeatureUnselect
    });
    vLMIS2.events.on({
        "featureselected": onReportingFeatureSelect,
        "featureunselected": onReportingFeatureUnselect
    });

    map.zoomToExtent(bounds);
    map2.zoomToExtent(bounds);

    map.events.register("zoomend", map, zoomLabel);
    map2.events.register("zoomend", map2, zoomLabel2);

    getLegend('5', 'wastageLegend');
    getLegend('4', 'reportingLegend');
    handler = setInterval(readData, 2000);
});

function readData() {
    if (province.features.length == "9" && district.features.length == "147" && province2.features.length == "9" && district2.features.length == "147") {
        getData();
        clearInterval(handler);
    }
}


$("#submit").click(function() {
    getData();
});

function getData() {
    $("#loader").css("display", "block");
    $("#loader2").css("display", "block");

    $("#info").html("");
    $("#info2").html("");
    $("#wastagesTitle").html("");
    $("#reportingTitle").html("");

    year = $("#year").val();
    month = $("#month").val();
    region = $("#province").val();
    product = $("#product").val();
    product_name = $("#product option:selected").text();
    provinceName = $("#province option:selected").text();
            
    mapTitle();

    $.ajax({
        url: appName + "/api/geo/get-wastages-vs-reporting",
        type: "GET",
        data: {
            year: year,
            month: month,
            province: region,
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
        FilterTwoFrame();
        if (vLMIS1.features.length > 0) {
            vLMIS1.removeAllFeatures();
        }
        if (vLMIS2.features.length > 0) {
            vLMIS2.removeAllFeatures();
        }
        if (data.length <= 0) {
            alert("No Data Available");
            $("#loader2").css("display", "none");
            $("#loader").css("display", "none");
            return;
        }

        for (var i = 0; i < data[0].length; i++) {
            chkeArray(data[0][i].districtId, Number(data[0][i].wastages_rate));  
        }
         for (var i = 0; i < data[1].length; i++) {
            chkeArray2(data[1][i].district_id, Number(data[1][i].reporting_rate));
        }
    }
}

function chkeArray(district_id, wastages_rate) {
    for (var i = 0; i < district.features.length; i++) {
        if (district_id == district.features[i].attributes.district_id) {
            vLMISWastagesLayer(district.features[i].geometry, district.features[i].attributes.province_name, product_name, district_id, district.features[i].attributes.district_name, wastages_rate);
            break;
        }
    }
}

function chkeArray2(district_id, reporting_rate) {
    for (var i = 0; i < district2.features.length; i++) {
        if (district_id == district2.features[i].attributes.district_id) {
            vLMISReportingLayer(district2.features[i].geometry, district2.features[i].attributes.province_name, product_name, district_id, district2.features[i].attributes.district_name, reporting_rate);
            break;
        }
    }
}


function vLMISWastagesLayer(wkt, province, product, district_id, district_name, wastages_rate) {
    var feature = new OpenLayers.Feature.Vector(wkt);

    if (wastages_rate == wastagesClassesArray[0].start_value && wastages_rate == wastagesClassesArray[0].end_value) {
        color = wastagesClassesArray[0].color_code;
    }
    if (wastages_rate > wastagesClassesArray[1].start_value && wastages_rate <= wastagesClassesArray[1].end_value) {
        color = wastagesClassesArray[1].color_code;
    }
    if (wastages_rate > wastagesClassesArray[2].start_value && wastages_rate <= wastagesClassesArray[2].end_value) {
        color = wastagesClassesArray[2].color_code;
    }
    if (wastages_rate > wastagesClassesArray[3].start_value && wastages_rate <= wastagesClassesArray[3].end_value) {
        color = wastagesClassesArray[3].color_code;
    }
    if (wastages_rate > wastagesClassesArray[4].start_value && wastages_rate <= wastagesClassesArray[4].end_value) {
        color = wastagesClassesArray[4].color_code;
    }
    if (wastages_rate > wastagesClassesArray[5].start_value && wastages_rate <= wastagesClassesArray[5].end_value) {
        color = wastagesClassesArray[5].color_code;
    }

    feature.attributes = {
        district: district_name,
        province: province,
        product: product,
        wastages_rate: wastages_rate,
        color: color
    };
    vLMIS1.addFeatures(feature);

    $("#loader").css("display", "none");
}


function vLMISReportingLayer(wkt, province, product, district_id, district_name, reporting_rate) {
    var feature = new OpenLayers.Feature.Vector(wkt);

    if (reporting_rate == reportingClassesArray[0].start_value && reporting_rate == reportingClassesArray[0].end_value) {
        color = reportingClassesArray[0].color_code;
    }
    if (reporting_rate > reportingClassesArray[1].start_value && reporting_rate <= reportingClassesArray[1].end_value) {
        color = reportingClassesArray[1].color_code;
    }
    if (reporting_rate > reportingClassesArray[2].start_value && reporting_rate <= reportingClassesArray[2].end_value) {
        color = reportingClassesArray[2].color_code;
    }
    if (reporting_rate > reportingClassesArray[3].start_value && reporting_rate <= reportingClassesArray[3].end_value) {
        color = reportingClassesArray[3].color_code;
    }
    if (reporting_rate > reportingClassesArray[4].start_value && reporting_rate <= reportingClassesArray[4].end_value) {
        color = reportingClassesArray[4].color_code;
    }
    if (reporting_rate > reportingClassesArray[5].start_value && reporting_rate <= reportingClassesArray[5].end_value) {
        color = reportingClassesArray[5].color_code;
    }
    
    feature.attributes = {
        district: district_name,
        province: province,
        product: product,
        reporting_rate: reporting_rate,
        color: color
    };
    vLMIS2.addFeatures(feature);
    $("#loader2").css("display", "none");
}


function onWastagesFeatureSelect(e) {
    var popuphtml = "<table id='infoStyle' border='1'> <tr> <td align='left'><b>Province:</b></td><td align='center'><span>" + e.feature.attributes['province'] + "</td></tr><tr><td align='left'><b>District:</b></td><td colspan='2' align='center'><span>" + e.feature.attributes['district'] + "</td>  </tr> <tr> <td align='left' ><b>Product:</b></td><td align='center'>" + e.feature.attributes['product'] + "</td></tr><tr><td align='left' ><b>Wastages(%):</b></td><td align='center'>" + e.feature.attributes['wastages_rate'] + "</td></tr></table>";
    $("#info").html(popuphtml);
}

function onWastagesFeatureUnselect(e) {
    $("#info").html("");
}

function onReportingFeatureSelect(e) {
    var popuphtml = "<table id='infoStyle' border='1'> <tr> <td align='left'><b>Province:</b></td><td align='center'><span>" + e.feature.attributes['province'] + "</td>  </tr> <tr> <td align='left'><b>District:</b></td><td colspan='2' align='center'><span>" + e.feature.attributes['district'] + "</td></tr><tr> <td align='left' ><b>Product:</b></td><td align='center'>" + e.feature.attributes['product'] + "</td></tr><td align='left' ><b>Reporting(%):</b></td><td align='center'>" + e.feature.attributes['reporting_rate'] + "</td></tr></table>";
    $("#info2").html(popuphtml);
}

function onReportingFeatureUnselect(e) {
    $("#info2").html("");
}

function mapTitle() {
    prov_name = $("#province option:selected").text();
    year_name = $("#year option:selected").text();
    month_value = ($("#month").val()) - 1;

    $("#wastagesTitle").html("<font color='white' size='2'><b>Wastages Rate - (" + monthNames[month_value] + " " + year_name + ")&nbsp;&nbsp;with </b>  " + product_name + "</font>");
    $("#reportingTitle").html("<font color='white' size='2'><b> Reporting Rate  - (" + monthNames[month_value] + " " + year_name + ") &nbsp;&nbsp with </b>" + product_name + "</font>");
}