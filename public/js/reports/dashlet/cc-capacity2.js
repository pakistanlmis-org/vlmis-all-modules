var map2, province2, district2, vLMIS2, min, max, handler2, selectfeature2, prov, ext2;
var classesArrayCapacity = [];
var maxValue = [];
var data2 = [];

var province_style_label = new OpenLayers.StyleMap();
var lookup = {
    "0": {fillColor: "#E6E7E9", strokeColor: "red", strokeWidth: 0.8, label: "${province_name}", fontSize: "11px", cursor: "pointer", fontWeight: "bold", fillOpacity: 0},
    "1": {fillColor: "grey", strokeWidth: 1.5, strokeColor: "black", strokeOpacity: 1, fillOpacity: 0, label: "${province_name}", labelOutlineColor: "white", labelOutlineWidth: 0, fontColor: "black", fontSize: "11px", labelAlign: "cm", fontWeight: "bold"}
}
province_style_label.addUniqueValueRules("default", "class", lookup);

var province_style = new OpenLayers.StyleMap();
var lookup = {
    "0": {fillColor: "#E6E7E9", strokeColor: "red", strokeWidth: 0.8, fillOpacity: 0},
    "1": {fillColor: "grey", strokeWidth: 1.5, strokeColor: "black", strokeOpacity: 1, fillOpacity: 0}
}
province_style.addUniqueValueRules("default", "class", lookup);

var district_style = new OpenLayers.StyleMap({'default': {
        strokeColor: "#777777",
        strokeOpacity: 1,
        strokeWidth: 0.2,
        fillColor: "grey",
        fillOpacity: 0
    }});


var style = OpenLayers.Util.applyDefaults({
    fillColor: "${color}",
    fontColor: "black",
    fontSize: "9px",
    strokeColor: "white",
    strokeWidth: 0.6,
    fillOpacity: 1}, OpenLayers.Feature.Vector.style['default']);


var style_select = OpenLayers.Util.applyDefaults({
    fillColor: "${color}",
    fillOpacity: 1,
    strokeColor: "white",
    strokeWidth: 1,
    labelOutlineColor: "white",
    labelOutlineWidth: 0,
    fontColor: "black"
});


var vlMIS_style = new OpenLayers.StyleMap({
    "default": style,
    "select": style_select
});

var bounds = new OpenLayers.Bounds(60.13, 23.45, 80.03, 37.33);
bounds.transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913"));

var restricted = new OpenLayers.Bounds(60.13, 23.45, 80.03, 37.33);
restricted.transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913"));

OpenLayers.CANVAS_SUPPORTED = true;
OpenLayers.Layer.Vector.prototype.renderers = ["Canvas"];

$(function () {

    map2 = new OpenLayers.Map('dashletCCC', {
        projection: new OpenLayers.Projection("EPSG:900913"),
        displayProjection: new OpenLayers.Projection("EPSG:4326"),
        maxExtent: restricted,
        restrictedExtent: restricted,
        maxResolution: "auto",
        controls: [
            new OpenLayers.Control.Navigation({'zoomWheelEnabled': false, 'defaultDblClick': function (event) {
                    return;
                }}),
            new OpenLayers.Control.Zoom({'position': new OpenLayers.Pixel(10, 27)})
        ]
    });

    province2 = new OpenLayers.Layer.Vector(
            "Province", {
                protocol: new OpenLayers.Protocol.HTTP({
                    url: appName + "/js/province.geojson",
                    format: new OpenLayers.Format.GeoJSON({
                        internalProjection: new OpenLayers.Projection("EPSG:3857"),
                        externalProjection: new OpenLayers.Projection("EPSG:3857")})
                }),
                strategies: [new OpenLayers.Strategy.Fixed()],
                styleMap: province_style_label
            });

    district2 = new OpenLayers.Layer.Vector(
            "District", {
                protocol: new OpenLayers.Protocol.HTTP({
                    url: appName + "/js/district.geojson",
                    format: new OpenLayers.Format.GeoJSON({
                        internalProjection: new OpenLayers.Projection("EPSG:3857"),
                        externalProjection: new OpenLayers.Projection("EPSG:3857")})
                }),
                strategies: [new OpenLayers.Strategy.Fixed()],
                styleMap: district_style
            });

    vLMIS2 = new OpenLayers.Layer.Vector("ColdChain Capacity", {styleMap: vlMIS_style, isBaseLayer: true});
    map2.addLayers([province2, district2, vLMIS2]);
    province2.setZIndex(900);

    selectfeature2 = new OpenLayers.Control.SelectFeature([vLMIS2]);
    map2.addControl(selectfeature2);
    selectfeature2.activate();
    selectfeature2.handlers.feature.stopDown = false;

    vLMIS2.events.on({
        "featureselected": onCCFeatureSelect,
        "featureunselected": onCCFeatureUnselect
    });

    map2.zoomToExtent(bounds);
    getCapacityData();
});

function getCapacityData()
{
    $("#ccInfoDiv").html("");
    classesArrayCapacity.length = 0;
    maxValue.length = 0;

    var office = $("#office").val();

    if (office == "1" || typeof office == 'undefined') {
        region = "all";
    } else {
        region = $("#combo1").val();
    }

    var type = $("#coldchain_type1").val();
    cccTitle();

    $.ajax({
        url: appName + "/api/geo/get-coldchain-capacity",
        type: "GET",
        data: {province: region, type: type},
        dataType: "json",
        success: callback,
        error: function (response) {
            $("#loader").css("display", "none");
            return;
        }
    });

    function callback(response)
    {
        data2 = response;
        for (var i = 0; i < data2.length; i++)
        {
            maxValue.push(Number(data2[i].capacity));
        }

        max = Math.max.apply(Math, maxValue);
        min = Math.min.apply(Math, maxValue);

        getLegend('8', max, min, 'NO. of Facilities');
    }
}


function getLegend(val, max, min, title)
{
    $.ajax({
        url: appName + "/api/geo/get-color-classes",
        type: "GET",
        data: "id=" + val,
        dataType: "json",
        success: callback
    });


    function callback(response)
    {
        var colors2 = [];
        colors2 = response;

        $("#legendCapacity").empty();

        if (colors2[2].description == "null")
        {
            var range = (max - min) / 5;

            var interval = min + range;
            var interval2 = interval + range;
            var interval3 = interval2 + range;
            var interval4 = interval3 + range;
            var interval5 = interval4 + range;


            if (min == max || max == "-Infinity")
            {
                miniLegend(max, colors2, title);
                return;
            }


            if (range < 1 && max > 1)
            {
                interval = Number(interval).toPrecision(2);
                interval2 = Number(interval2).toPrecision(2);
                interval3 = Number(interval3).toPrecision(2);
                interval4 = Number(interval4).toPrecision(2);
                interval5 = Number(interval5).toPrecision(2);
            } else if (range < 1 && max < 1)
            {
                interval = Number(interval).toPrecision(2);
                interval2 = Number(interval2).toPrecision(2);
                interval3 = Number(interval3).toPrecision(2);
                interval4 = Number(interval4).toPrecision(2);
                interval5 = Number(interval5).toPrecision(2);
            } else
            {
                interval = Math.round(Number(interval));
                interval2 = Math.round(Number(interval2));
                interval3 = Math.round(Number(interval3));
                interval4 = Math.round(Number(interval4));
                interval5 = Math.round(Number(interval5));
            }

            for (var i = 0; i < 6; i++)
            {
                if (i == "0") {
                    classesArrayCapacity.push({
                        "start_value": "0",
                        "end_value": "0",
                        "description": colors2[0].description,
                        "color_code": colors2[0].color_code
                    });
                }
                if (i == "1") {
                    if (interval == "0") {
                        description = "0-0";
                    } else {
                        if (min == "0") {
                            min = 1;
                        }
                        description = min.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "   -   " + interval.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }
                    classesArrayCapacity.push({
                        "start_value": "0",
                        "end_value": interval,
                        "description": description,
                        "color_code": colors2[1].color_code
                    });
                }
                if (i == "2") {
                    classesArrayCapacity.push({
                        "start_value": interval,
                        "end_value": interval2,
                        "description": interval.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "   -   " + interval2.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),
                        "color_code": colors2[2].color_code
                    });
                }
                if (i == "3") {
                    classesArrayCapacity.push({
                        "start_value": interval2,
                        "end_value": interval3,
                        "description": interval2.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "   -   " + interval3.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),
                        "color_code": colors2[3].color_code
                    });
                }
                if (i == "4") {
                    classesArrayCapacity.push({
                        "start_value": interval3,
                        "end_value": interval4,
                        "description": interval3.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "   -   " + interval4.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),
                        "color_code": colors2[4].color_code
                    });
                }
                if (i == "5") {
                    classesArrayCapacity.push({
                        "start_value": interval4,
                        "end_value": interval5,
                        "description": interval4.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "   -   " + interval5.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),
                        "color_code": colors2[5].color_code
                    });
                }

            }
        }

        var classes = parseInt(classesArrayCapacity.length) - 1;
        var legend2 = document.getElementById('legendCapacity');

        for (var i = 0; i >= 0; i--)
        {
            var row = legend2.insertRow(0);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            cell1.align = "center";
            cell2.align = "left";
            cell1.innerHTML = "<div style='width:20px;height:15px;background-color:" + classesArrayCapacity[i].color_code + "'></div>";
            cell2.innerHTML = classesArrayCapacity[i].description;

        }

        for (var i = classes; i >= 1; i--)
        {
            var row = legend2.insertRow(0);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            cell1.align = "center";
            cell2.align = "left";
            cell1.innerHTML = "<div style='width:20px;height:15px;background-color:" + classesArrayCapacity[i].color_code + "'></div>";
            cell2.innerHTML = classesArrayCapacity[i].description;

        }


        var row = legend2.insertRow(0);
        var cell = row.insertCell(0);
        cell.colSpan = "2";
        cell.align = "left";
        cell.innerHTML = "<font size='1' color='green'><b>" + title + "</font></b><br/>";

        $("#legendDivCapacity").css("display", "block");
        drawLayer();
    }
}



function miniLegend(max, colors2, title) {

    for (var i = 0; i < 3; i++)
    {
        if (i == "0")
        {
            classesArrayCapacity.push({
                "start_value": "null",
                "end_value": "0",
                "description": "Data Problem",
                "color_code": colors2[0].color_code
            });
        }
        if (i == "1")
        {
            classesArrayCapacity.push({
                "start_value": "0",
                "end_value": "0",
                "description": "No Data Available",
                "color_code": colors2[1].color_code
            });
        }
        if (i == "2")
        {
            classesArrayCapacity.push({
                "start_value": max,
                "end_value": max,
                "description": max.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),
                "color_code": colors2[6].color_code
            });
        }
    }

    if (max == "0" || max == "-Infinity") {
        classes = parseInt(classesArrayCapacity.length) - 2;
    } else {
        classes = parseInt(classesArrayCapacity.length) - 1;
    }
    var legend2 = document.getElementById('legendCapacity');

    for (var i = classes; i >= 0; i--)
    {
        var row = legend2.insertRow(0);
        var cell = row.insertCell(0);
        var cell = row.insertCell(1);
        cell.align = "left";
        cell.align = "left";
        cell.innerHTML = "<div style='width:40px;height:20px;border:1px solid darkgray;background-color:" + classesArrayCapacity[i].color_code + "'></div>";
        cell.innerHTML = classesArrayCapacity[i].description;
    }
    var row = legend2.insertRow(0);
    var cell = row.insertCell(0);
    cell.colSpan = "2";
    cell.align = "left";
    cell.innerHTML = "<font size='2' color='green'><b>" + title + "</b></font><br/>";
    $("#legendDivCapacity").css("display", "block");
    drawLayer();
}




function drawLayer()
{
    if (vLMIS2.features.length > 0) {
        vLMIS2.removeAllFeatures();
    }
    FilterCCData();
    if (data2.length <= 0) {
        alert("No Data Available");
        $("#loader").css("display", "none");
        return;
    }
    for (var i = 0; i < data2.length; i++)
    {
        chkeArray2(data2[i].district_id, Math.round(Number(data2[i].capacity)));
    }
}


function chkeArray2(district_id, capacity)
{
    for (var i = 0; i < district2.features.length; i++) {
        if (district_id == district2.features[i].attributes.district_id)
        {
            if (min == max)
            {
                vLMISMiniLayer(district2.features[i].geometry, district2.features[i].attributes.province_name, district_id, district2.features[i].attributes.district_name, capacity);
                break;
            } else
            {
                vLMISLayer(district2.features[i].geometry, district2.features[i].attributes.province_name, district_id, district2.features[i].attributes.district_name, capacity);
                break;
            }
        }
    }
}


function vLMISLayer(wkt, province, id, district, value)
{
    feature2 = new OpenLayers.Feature.Vector(wkt);

    if (value == classesArrayCapacity[0].start_value && value == classesArrayCapacity[0].end_value) {
        color = classesArrayCapacity[0].color_code;
    }
    if (value > classesArrayCapacity[1].start_value && value <= classesArrayCapacity[1].end_value) {
        color = classesArrayCapacity[1].color_code;
    }
    if (value > classesArrayCapacity[2].start_value && value <= classesArrayCapacity[2].end_value) {
        color = classesArrayCapacity[2].color_code;
    }
    if (value > classesArrayCapacity[3].start_value && value <= classesArrayCapacity[3].end_value) {
        color = classesArrayCapacity[3].color_code;
    }
    if (value > classesArrayCapacity[4].start_value && value <= classesArrayCapacity[4].end_value) {
        color = classesArrayCapacity[4].color_code;
    }
    if (value > classesArrayCapacity[5].start_value && value <= classesArrayCapacity[5].end_value) {
        color = classesArrayCapacity[5].color_code;
    }

    feature2.attributes = {district: district, province: province, capacity: value, color: color};
    vLMIS2.addFeatures(feature2);
}


function vLMISMiniLayer(wkt, province2, product, id, district, value)
{
    feature2 = new OpenLayers.Feature.Vector(wkt);

    if (value < parseInt(classesArrayCapacity[0].end_value)) {
        color = classesArrayCapacity[0].color_code;
    } else if (value == parseInt(classesArrayCapacity[1].start_value) && value == parseInt(classesArrayCapacity[1].end_value)) {
        color = classesArrayCapacity[1].color_code;

    } else {
        color = classesArrayCapacity[2].color_code;
    }

    feature2.attributes = {district: district, province: province, capacity: value, color: color};
    vLMIS2.addFeatures(feature2);
}

$(function () {
    $('#coldchain_type1').change(function (e) {
        getCapacityData();
    });
});

function FilterCCData() {

    var office = $("#office").val();
    if (office == "1" || typeof office == 'undefined') {
        prov = "National";
    } else {
        prov = $("#combo1 option:selected").text();
    }

    prov = prov.replace(/\s+/, "");

    var features = province2.features;
    var districtfeatures = district2.features;

    for (var i = 0; i < features.length; i++)
    {
        features[i].style = '';
    }
    province2.redraw();

    for (var i = 0; i < districtfeatures.length; i++)
    {
        districtfeatures[i].style = '';
    }
    district2.redraw();

    if (prov == "National")
    {
        map2.setOptions({maxExtent: restricted});
        map2.setOptions({restrictedExtent: restricted});
        map2.events.register("zoomend", map2, zoomRestrictCC);
        map2.events.register("zoomend", map2, zoomChangedCC);
        map2.zoomToExtent(bounds);
    } else {

        map2.setOptions({maxExtent: null});
        map2.setOptions({restrictedExtent: null});
        map2.events.register("zoomend", map2, zoomRestrictCC);
        map2.events.register("zoomend", map2, zoomChangedCC);

        for (var i = 0; i < features.length; i++)
        {
            if (features[i].attributes.province_name != prov)
            {
                features[i].style = {display: 'none'};

            } else
            {
                map2.zoomToExtent(features[i].geometry.getBounds());
            }
        }


        if (features[3].attributes.province_name == prov)
        {
            map2.events.remove("zoomend", map2, zoomRestrictCC);
            map2.events.remove("zoomend", map2, zoomChangedCC);
            var isb = new OpenLayers.Bounds(72.6601, 33.472, 73.4703, 33.9187);
            isb.transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913"));
            map2.zoomToExtent(isb);
        }


        province2.redraw();

        for (var i = 0; i < districtfeatures.length; i++)
        {
            if (districtfeatures[i].attributes.province_name != prov)
            {
                districtfeatures[i].style = {display: 'none'};
            }
        }
        district2.redraw();
    }
}


function zoomRestrictCC()
{
    var x = map2.getZoom();

    if (x == 3) {
        ext2 = map2.getExtent();
    }
    if (x > 3)
    {
        map2.zoomToExtent(ext2);
    }
}

function zoomChangedCC() {
    var zoom = map2.getZoom();
    if (zoom >= 1)
    {
        province2.styleMap = province_style;
        province2.redraw();
    }
    if (zoom == "0")
    {
        province2.styleMap = province_style_label;
        province2.redraw()
    }
}

function cccTitle()
{
    var type = $("#coldchain_type1 option:selected").text();
    $("#ccInfoDiv").html("<font color='white' size='1'><b>No. of Facilities</b></font><div id='infoCC'></div>");
}

function onCCFeatureSelect(e) {
    var popuphtml = "<table border='1' style='font-size:11px'><tr style='background-color:white;color:black'> <td align='left' style='padding:2px;90px;'><b>District:</b></td><td align='center' style='padding:2px;90px;'>" + e.feature.attributes['district'] + "</td></tr><tr style='background-color:white;color:black'> <td align='left' style='padding:2px;90px;'><b>No. of Facilities</b></td><td align='center' style='padding:2px;90px;'>" + e.feature.attributes['capacity'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "</td></tr></table>";
    $("#infoCC").html(popuphtml);
}

function onCCFeatureUnselect(e) {
    $("#infoCC").html("");
}
                