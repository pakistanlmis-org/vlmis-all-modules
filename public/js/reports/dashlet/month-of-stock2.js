var map, province, district, vLMIS, prov, selectfeature, ext, year, month, product_name;
var classesArray = [];

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
        strokeColor: "#000000",
        strokeOpacity: 3,
        strokeWidth: 0.4,
        fillColor: "grey",
        fillOpacity: 0
    }});

var style = OpenLayers.Util.applyDefaults({
    fillColor: "${color}",
    fontColor: "black",
    fontSize: "9px",
    strokeColor: "black",
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

var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];


var bounds = new OpenLayers.Bounds(60.87, 23.54, 80.2, 37.4);
bounds.transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913"));

var restricted = new OpenLayers.Bounds(60.87, 23.54, 80.2, 37.4);
restricted.transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913"));

OpenLayers.CANVAS_SUPPORTED = true;
OpenLayers.Layer.Vector.prototype.renderers = ["Canvas"];

$(function () {

    map = new OpenLayers.Map('dashletMOS', {
        projection: new OpenLayers.Projection("EPSG:900913"),
        displayProjection: new OpenLayers.Projection("EPSG:4326"),
        maxExtent: restricted,
        restrictedExtent: restricted,
        maxResolution: "auto",
        controls: [
            new OpenLayers.Control.Navigation({'zoomWheelEnabled': false, 'defaultDblClick': function (event) {
                    return;
                }}),
            new OpenLayers.Control.Zoom()
        ]
    });

    province = new OpenLayers.Layer.Vector(
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

    district = new OpenLayers.Layer.Vector(
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

    vLMIS = new OpenLayers.Layer.Vector("MOS", {styleMap: vlMIS_style, isBaseLayer: true});
    map.addLayers([province, district, vLMIS]);
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
    Legend('1', 'Months of Stock');
});


function getData()
{
    $("#infoDiv").html("");
    product_name = $("#items option:selected").text();
    var reporting_month = $("#date").val();
    var mon = $("#period").val();
    split = reporting_month.split("-");
    if (split.length > 1) {
        $("#inputForm").hide();
        year = split[0];
        month = split[1];
        getFeatures();
    } else {
        year = split[0];
        $.ajax({
            url: appName + "/api/geo/get-month-range",
            type: "GET",
            data: {month: mon},
            dataType: "json",
            success: function callback(response) {
                var data = [];
                data = response;
                for (var i = data[0].begin_month; i <= data[0].end_month; i++) {
                    AddItem(i, "month_range");
                }
                getFeatures();
            }
        });
    }
}


function getFeatures() {
    $("#loader").css("display", "block");
    if (split.length <= 1) {
        month = $("#month_range").val();

    }
    var office = $("#office").val();
    if (office == 6) {
        district_id = $("#combo2").val();
    } else {
        district_id = 1;
    }

    if (office == "1")
    {
        region = "all";
    } else
    {
        region = $("#combo1").val();
    }
    var product = $("#items").val();
    mosTitle();
    $.ajax({
        url: appName + "/api/geo/get-mos-map-data",
        type: "GET",
        data: {year: year, month: month, province: region, district: district_id, type: office, product: product, level: 'all'},
        dataType: "json",
        success: callback
    });

    function callback(response)
    {
        var data = [];
        data = response;

        if (vLMIS.features.length > 0) {
            vLMIS.removeAllFeatures();
        }
        FilterData();
        if (data.length <= 0) {
            alert("No Data Available");
            $("#loader").css("display", "none");
            return;
        }

        for (var i = 0; i < data.length; i++)
        {
            chkeArray(data[i].district_id, Number(data[i].total_MOS));
        }
    }
}



function chkeArray(district_id, mos)
{
    for (var i = 0; i < district.features.length; i++) {
        if (district_id == district.features[i].attributes.district_id)
        {
            vLMISdistrictLayer(district.features[i].geometry, district.features[i].attributes.province_name, district_id, district.features[i].attributes.district_name, mos);
            break;
        }
    }
}

function vLMISdistrictLayer(wkt, province, district_id, district_name, value)
{
    feature = new OpenLayers.Feature.Vector(wkt);
    if (value == classesArray[0].start_value && value == classesArray[0].end_value) {
        color = classesArray[0].color_code;
    }
    if (value > classesArray[1].start_value && value <= classesArray[1].end_value) {
        color = classesArray[1].color_code;
    }
    if (value >= classesArray[2].start_value && value <= classesArray[2].end_value) {
        color = classesArray[2].color_code;
    }
    if (value >= classesArray[3].start_value && value <= classesArray[3].end_value) {
        color = classesArray[3].color_code;
    }
    if (value >= classesArray[4].start_value) {
        color = classesArray[4].color_code;
    }


    feature.attributes = {district: district_name, province: province, product: product_name, value: value, color: color};
    vLMIS.addFeatures(feature);
    $("#loader").css("display", "none");
}

function Legend(value, title)
{
    $.ajax({
        url: appName + "/api/geo/get-color-classes",
        type: "GET",
        data: "id=" + value,
        dataType: "json",
        success: callback
    });

    function callback(response)
    {
        classesArray = response;
        $("#legend").empty();

        var classes = parseInt(classesArray.length) - 1;
        var legend = document.getElementById('legend');

        for (var i = classes; i >= 1; i--)
        {
            var row = legend.insertRow(0);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            cell1.align = "center";
            cell1.style.width = "20px";
            cell2.align = "left";
            cell1.innerHTML = "<div style='width:20px;height:15px;background-color:" + classesArray[i].color_code + "'></div>";
            cell2.innerHTML = classesArray[i].description+' ('+classesArray[i].interval+')';

        }
        var row = legend.insertRow(0);
        var cell = row.insertCell(0);
        cell.colSpan = "2";
        cell.align = "left";
        cell.innerHTML = "<font size='1' color='green'><b>" + title + "</font></b><br/>";
        $("#legendDiv").css("display", "block");
        getData();
    }
}

$(function () {
    $('#product').change(function (e) {
        getData();
    });
});

function FilterData() {
    var office = $("#office").val();
    if (office == "1") {
        prov = "National";
    } else {
        prov = $("#combo1 option:selected").text();
    }
    prov = prov.replace(/\s+/, "");

    var features = province.features;
    var districtfeatures = district.features;

    for (var i = 0; i < features.length; i++)
    {
        features[i].style = '';
    }
    province.redraw();

    for (var i = 0; i < districtfeatures.length; i++)
    {
        districtfeatures[i].style = '';
    }
    district.redraw();

    if (prov == "National")
    {
        map.setOptions({maxExtent: restricted});
        map.setOptions({restrictedExtent: restricted});
        map.events.register("zoomend", map, zoomRestrict);
        map.events.register("zoomend", map, zoomChanged);
        map.zoomToExtent(bounds);
    } else
    {
        map.setOptions({maxExtent: null});
        map.setOptions({restrictedExtent: null});
        map.events.register("zoomend", map, zoomRestrict);
        map.events.register("zoomend", map, zoomChanged);

        for (var i = 0; i < features.length; i++)
        {
            if (features[i].attributes.province_name != prov)
            {
                features[i].style = {display: 'none'};
            } else
            {
                map.zoomToExtent(features[i].geometry.getBounds());
            }
        }


        if (features[3].attributes.province_name == prov)
        {
            map.events.remove("zoomend", map, zoomRestrict);
            map.events.remove("zoomend", map, zoomChanged);
            var isb = new OpenLayers.Bounds(72.6601, 33.472, 73.4703, 33.9187);
            isb.transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913"));
            map.zoomToExtent(isb);
        }


        province.redraw();

        for (var i = 0; i < districtfeatures.length; i++)
        {
            if (districtfeatures[i].attributes.province_name != prov)
            {
                districtfeatures[i].style = {display: 'none'};
            }
        }
        district.redraw();
    }
}

function zoomRestrict() {
    var x = map.getZoom();

    if (x == 3) {
        ext = map.getExtent();
    }
    if (x >= 3)
    {
        map.zoomToExtent(ext);
    }
}


function zoomChanged() {
    var zoom = map.getZoom();
    if (zoom >= 1)
    {
        province.styleMap = province_style;
        province.redraw();
    }
    if (zoom == "0")
    {
        province.styleMap = province_style_label;
        province.redraw()
    }
}

function mosTitle()
{
    var month_name = monthNames[month - 1];
    $("#infoDiv").html("<font color='white' size='1'><b>MOS (" + month_name + " " + year + ")</b></font><div id='info'></div>");
}

function AddItem(Value, ID)
{
    var opt = document.createElement("option");
    opt.style.width = "100%";
    document.getElementById(ID).options.add(opt);
    opt.text = monthNames[Value - 1];
    opt.value = Value;

}

function onFeatureSelect(e) {
    var popuphtml = "<table border='1' style='font-size:11px'><tr style='background-color:white;color:black'> <td align='left' style='padding:2px;90px;'><b>District:</b></td><td align='center' style='padding:2px;90px;'>" + e.feature.attributes['district'] + "</td></tr><tr style='background-color:white;color:black'> <td align='left' style='padding:2px;90px;'><b>Product:</b></td><td align='center' style='padding:2px;90px;'>" + e.feature.attributes['product'] + "</td></tr><tr style='background-color:white;color:black'> <td align='left' style='padding:2px;90px;'><b>MOS:</b></td><td align='center' style='padding:2px;90px;'>" + e.feature.attributes['value'] + "</td></tr></table>";
    $("#info").html(popuphtml);
}

function onFeatureUnselect(e) {
    $("#info").html("");
}