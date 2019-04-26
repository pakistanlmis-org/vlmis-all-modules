$(window).load(function () {
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
                    url: basePath + "js/maps/province.geojson",
                    format: new OpenLayers.Format.GeoJSON({
                        internalProjection: new OpenLayers.Projection("EPSG:900913"),
                        externalProjection: new OpenLayers.Projection("EPSG:900913")
                    })
                }),
                strategies: [new OpenLayers.Strategy.Fixed()],
                styleMap: province_style_label,
                isBaseLayer: true
            });

    district = new OpenLayers.Layer.Vector(
            "District", {
                protocol: new OpenLayers.Protocol.HTTP({
                    url: basePath + "js/maps/district.geojson",
                    format: new OpenLayers.Format.GeoJSON({
                        internalProjection: new OpenLayers.Projection("EPSG:900913"),
                        externalProjection: new OpenLayers.Projection("EPSG:900913")
                    })
                }),
                strategies: [new OpenLayers.Strategy.Fixed()],
                styleMap: district_style
            });

    cLMIS = new OpenLayers.Layer.Vector("Month Of Stock", {
        styleMap: clMIS_style
    });
    map.addLayers([province, district, cLMIS]);
    district.setZIndex(900);
    province.setZIndex(1001);

    selectfeature = new OpenLayers.Control.SelectFeature([cLMIS]);
    map.addControl(selectfeature);
    selectfeature.activate();
    selectfeature.handlers.feature.stopDown = false;

    cLMIS.events.on({
        "featureselected": onFeatureSelect,
        "featureunselected": onFeatureUnselect
    });
    map.zoomToExtent(bounds);
    handler = setInterval(readData, 2000);
});

function readData() {
    if (province.features.length == "9" && district.features.length == "147") {
        getData();
        clearInterval(handler);
    }
}

function getData() {


    $.ajax({
        type: "POST",
        url: appPath + "/api/get-districts.php",
        data: {prov_sel: "all"},
        dataType: 'html',
        success: function (data) {

            $('#dist_data').html(data);
        }
    });

    $.ajax({
        type: "POST",
        url: appPath + "/api/get-tehsil.php",
        data: {dist_sel: 'all'},
        dataType: 'html',
        success: function (data) {

            $('#dist').html(data);
        }
    });
    clearData();
    mapTitle();
    onFeatureUnselect();
    level = 1;
    if (level == 3 || level == "all") {
        getLegend('1', 'Legend');
    } else {
        getLegend('2', 'Legend');
    }
}

function getDataTeh() {

    $.ajax({
        type: "POST",
        url: appPath + "/api/get-teh.php",
        data: {dist_sel: 'all', prov_sel: 'all'},
        dataType: 'html',
        success: function (data) {

            $('#dist_data').html(data);
        }
    });


    clearData();
    mapTitle();
    onFeatureUnselect();
    level = 1;
    if (level == 3 || level == "all") {
        //    getLegendTeh('1', 'Legend');
    } else {
        //   getLegendTeh('2', 'Legend');
    }
}

function getDataTeh1() {




    clearData();
    mapTitle();
    onFeatureUnselect();
    level = 1;
    if (level == 3 || level == "all") {
        //  getLegendTeh('1', 'Legend');
    } else {
        //  getLegendTeh('2', 'Legend');
    }
}

function getData1() {


    clearData();
    mapTitle();
    onFeatureUnselect();
    level = 1;
    if (level == 3 || level == "all") {
        getLegend('1', 'Legend');
    } else {
        getLegend('2', 'Legend');
    }
}

function drawFeature() {

    year = '2017';
    month = '01';
    sector = '1';
    stk = '1';
    prov = 'all';
    product = '1';


    var multi_members = '';
    var multi_members_id = '';
    var myarray = '{"26":"","27":"","28":"","29":"","30":"","31":"","32":"","33":"","34":"","36":"","37":"","38":"","39":"","40":"","41":"","42":"","43":"","44":"","45":"","46":"","47":"","48":"","49":"","50":"","51":"","52":"","53":"","54":"","55":"","56":"","57":"","58":"","59":"","61":"","62":"","63":"","64":"","65":"","66":"","68":"","69":"","70":"","71":"","72":"","73":"","74":"","76":"","77":"","78":"","79":"","80":"","81":"","82":"","83":"","84":"","86":"","87":"","92":"","93":"","94":"","95":"","96":"","97":"","98":""…134":"","136":"","137":"","138":"","139":"","140":"","141":"","142":"","143":"","144":"","145":"","146":"","147":"","148":"","149":"","150":"","151":"","152":"","153":"","154":"","155":"","156":"","157":"","158":"","159":"","160":"","161":"","162":"","163":"","164":"","165":"","166":"","167":"","168":"","169":"","170":"","171":"","172":"","173":"","174":"","175":"","176":"","177":"","178":"","179":"","180":"","181":"","182":"","4256":"","4279":"","5019":"","7276":"","7277":"","7278":"","8304":"","8305":""}';

    $.ajax({
        url: appPath + "/api/get-c-mos-map-data.php",
        type: "POST",
        data: {
            year: year,
            month: month,
            sector: sector,
            stk: stk,
            province: prov,
            product: product,
            level: level,
            dist: JSON.stringify(myarray)
        },
        dataType: "json",
        success: callback,
        error: function (response) {
            alert("No Data Available");
            $("#loader").css("display", "none");
            return;
        }
    });

    function callback(response) {

        var data = [];
        data = response;
        if (cLMIS.features.length > 0) {
            cLMIS.removeAllFeatures();
        }
        FilterData();
        if (data.length <= 0) {
            alert("No Data Available");
            $("#loader").css("display", "none");
            return;
        }
        data.sort(SortByID);
        for (var i = 0; i < data.length; i++) {
            chkeArray(data[i].district_id, data[i].mapping_id, Number(data[i].mos), Number(data[i].pop_male), Number(data[i].pop_female), Number(data[i].area), Number(data[i].dhq), Number(data[i].thq), Number(data[i].rhc), Number(data[i].bhu), Number(data[i].gd), Number(data[i].epi_center), Number(data[i].lhw_house), Number(data[i].rhs_a), Number(data[i].msu), Number(data[i].fwc), Number(data[i].mch_centers), Number(data[i].cmws), Number(data[i].color), Number(data[i].d_pwd), Number(data[i].d_lhw), Number(data[i].d_static_hf), Number(data[i].d_mnch), Number(data[i].hf_pwd), Number(data[i].hf_lhw), Number(data[i].hf_static_hf), Number(data[i].hf_mnch), Number(data[i].c_district_id));
        }
        drawGrid();
        //  districtCountGraph();

    }
}




function districtRanking(records, title) {

    records.sort(SortByRankingID);

    if (records.length > 100) {
        width = '480%';
    } else {
        width = '130%';
    }
    var maximum = records[0].value;
    var minMaxPercent = (maximum * 10 / 100);
    maximum = maximum + minMaxPercent;

    var revenueChart = new FusionCharts({
        type: 'column2d',
        renderAt: 'chart-container',
        width: width,
        height: '100%',
        dataFormat: 'json',
        dataSource: {
            "chart": {
                "caption": prov_name + " - District wise Stock Ranking " + title,
                "subcaption": download,
                "yAxisMaxValue": maximum,
                "slantLabels": '1',
                "showValues": '1',
                "rotateValues": '1',
                "placeValuesInside": '1',
                "adjustDiv": '0',
                "numDivLines": '3',
                "xAxisName": "",
                "yAxisName": "No.of Months",
                "exportEnabled": "1",
                "theme": "fint"
            },
            "data": records
        }
    });
    revenueChart.render("districtRanking");
}

function chkeArray1(district_id, mapping_id, mos) {


    for (var i = 0; i < tehsil.features.length; i++) {


        if (district_id == tehsil.features[i].attributes.tehsil_id) {

            cLMISLayer(tehsil.features[i].geometry, tehsil.features[i].attributes.province_id, tehsil.features[i].attributes.province_name, product_name, StkHolder, mapping_id, tehsil.features[i].attributes.tehsil_name, mos);
        }
    }
}

function chkeArray(district_id, mapping_id, mos, pop_m, pop_f, area, dhq, thq, rhc, bhu, gd, epi_center, lhw_house, rhs_a, msu, fwc, mch_centers, cmws, color, d_pwd, d_lhw, d_static_hf, d_mnch, hf_pwd, hf_lhw, hf_static_hf, hf_mnch, c_district_id) {

    for (var i = 0; i < district.features.length; i++) {
        if (district_id == district.features[i].attributes.district_id) {


            cLMISLayer1(district.features[i].geometry, district.features[i].attributes.province_id, district.features[i].attributes.province_name, product_name, StkHolder, mapping_id, district.features[i].attributes.district_name, mos, pop_m, pop_f, area, dhq, thq, rhc, bhu, gd, epi_center, lhw_house, rhs_a, msu, fwc, mch_centers, cmws, color, d_pwd, d_lhw, d_static_hf, d_mnch, hf_pwd, hf_lhw, hf_static_hf, hf_mnch, c_district_id);
        }
    }
}
function cLMISLayer1(wkt, province_id, province, product, StkHolder, district_id, district_name, value, pop_m, pop_f, area, dhq, thq, rhc, bhu, gd, epi_center, lhw_house, rhs_a, msu, fwc, mch_centers, cmws, color, d_pwd, d_lhw, d_static_hf, d_mnch, hf_pwd, hf_lhw, hf_static_hf, hf_mnch, c_district_id) {
    var feature = new OpenLayers.Feature.Vector(wkt);

    if (value == classesArray[0].start_value && value == classesArray[0].end_value) {
        color = classesArray[0].color_code;
        NoData = Number(NoData) + 1;
        status = classesArray[0].description;
    }
    if (value > classesArray[1].start_value && value <= classesArray[1].end_value) {
        color = classesArray[1].color_code;
        StockOut = Number(StockOut) + 1;
        status = classesArray[1].description;
    }
    if (value >= classesArray[2].start_value && value <= classesArray[2].end_value) {
        color = classesArray[2].color_code;
        UnderStock = Number(UnderStock) + 1;
        status = classesArray[2].description;
    }
    if (value >= classesArray[3].start_value && value <= classesArray[3].end_value) {
        color = classesArray[3].color_code;
        Satisfactory = Number(Satisfactory) + 1;
        status = classesArray[3].description;
    }
    if (value >= classesArray[4].start_value) {
        color = classesArray[4].color_code;
        OverStock = Number(OverStock) + 1;
        status = classesArray[4].description;
    }

    feature.attributes = {
        district_id: district_id,
        province_id: province_id,
        district: district_name,
        province: province,
        product: product,
        StkHolder: StkHolder,
        status: status,
        value: value,
        color: color,
        pop_m: pop_m,
        pop_f: pop_f,
        area: area,
        dhq: dhq,
        thq: thq,
        rhc: rhc,
        bhu: bhu,
        gd: gd,
        epi_center: epi_center,
        lhw_house: lhw_house,
        rhs_a: rhs_a,
        msu: msu,
        fwc: fwc,
        mch_centers: mch_centers,
        cmws: cmws,
        color: color,
        d_pwd: d_pwd,
        d_lhw: d_lhw,
        d_static_hf: d_static_hf,
        d_mnch: d_mnch,
        hf_pwd: hf_pwd,
        hf_lhw: hf_lhw,
        hf_static_hf: hf_static_hf,
        hf_mnch: hf_mnch,
        c_district_id: c_district_id


    };
    cLMIS.addFeatures(feature);
    $("#loader").hide();
}

function cLMISLayer(wkt, province_id, province, product, StkHolder, district_id, district_name, value) {
    var feature = new OpenLayers.Feature.Vector(wkt);

    if (value == classesArray[0].start_value && value == classesArray[0].end_value) {
        color = classesArray[0].color_code;
        NoData = Number(NoData) + 1;
        status = classesArray[0].description;
    }
    if (value > classesArray[1].start_value && value <= classesArray[1].end_value) {
        color = classesArray[1].color_code;
        StockOut = Number(StockOut) + 1;
        status = classesArray[1].description;
    }
    if (value >= classesArray[2].start_value && value <= classesArray[2].end_value) {
        color = classesArray[2].color_code;
        UnderStock = Number(UnderStock) + 1;
        status = classesArray[2].description;
    }
    if (value >= classesArray[3].start_value && value <= classesArray[3].end_value) {
        color = classesArray[3].color_code;
        Satisfactory = Number(Satisfactory) + 1;
        status = classesArray[3].description;
    }
    if (value >= classesArray[4].start_value) {
        color = classesArray[4].color_code;
        OverStock = Number(OverStock) + 1;
        status = classesArray[4].description;
    }

    feature.attributes = {
        district_id: district_id,
        province_id: province_id,
        district: district_name,
        province: province,
        product: product,
        StkHolder: StkHolder,
        status: status,
        value: value,
        color: color
    };



    cLMIS.addFeatures(feature);
    $("#loader").hide();
}

function onFeatureSelect(e) {
    $("#prov").html(e.feature.attributes['province']);
    $("#district").html(e.feature.attributes['district']);
    $("#pop_m").html(addCommas(e.feature.attributes['pop_m']));
    $("#pop_f").html(addCommas(e.feature.attributes['pop_f']));
    $("#pop_t").html(addCommas(e.feature.attributes['pop_f'] + e.feature.attributes['pop_m']));
    $("#area").html(addCommas(e.feature.attributes['area']));
    $("#dhq").html(e.feature.attributes['dhq']);
    $("#thq").html(e.feature.attributes['thq']);
    $("#rhc").html(e.feature.attributes['rhc']);
    $("#bhu").html(e.feature.attributes['bhu']);
    $("#gd").html(e.feature.attributes['gd']);
    $("#epi_center").html(e.feature.attributes['epi_center']);

    $("#lhw_house").html(e.feature.attributes['lhw_house']);
    $("#rhs_a").html(e.feature.attributes['rhs_a']);
    $("#msu").html(e.feature.attributes['msu']);
    $("#fwc").html(e.feature.attributes['fwc']);
    $("#mch_centers").html(e.feature.attributes['mch_centers']);
    $("#cmws").html(e.feature.attributes['cmws']);



    $("#rr_d_pwd").html(e.feature.attributes['d_pwd'] + '%');
    $("#rr_d_lhw").html(e.feature.attributes['d_lhw'] + '%');
    $("#rr_d_mnch").html(e.feature.attributes['d_mnch'] + '%');
    $("#rr_d_static").html(e.feature.attributes['d_static_hf'] + '%');

    $("#rr_hf_pwd").html(e.feature.attributes['hf_pwd'] + '%');
    $("#rr_hf_lhw").html(e.feature.attributes['hf_lhw'] + '%');
    $("#rr_hf_mnch").html(e.feature.attributes['hf_static_hf'] + '%');
    $("#rr_hf_static").html(e.feature.attributes['hf_mnch'] + '%');
    lastMonthsStats(e.feature.attributes['c_district_id'], e.feature.attributes['district'] + '%');
}
function addCommas(nStr)
{
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function onFeatureUnselect(e) {
    $("#prov").html("");
    $("#district").html("");
    $("#pop_m").html("");
    $("#pop_f").html("");
    $("#pop_t").html("");
    $("#area").html("");
    $("#dhq").html("");
    $("#thq").html("");
    $("#rhc").html("");
    $("#bhu").html("");
    $("#gd").html("");
    $("#epi_center").html("");

    $("#lhw_house").html("");
    $("#rhs_a").html("");
    $("#msu").html("");
    $("#fwc").html("");
    $("#mch_centers").html("");
    $("#cmws").html("");



    $("#rr_d_pwd").html("--");
    $("#rr_d_lhw").html("--");
    $("#rr_d_mnch").html("--");
    $("#rr_d_static").html("--");

    $("#rr_hf_pwd").html("--");
    $("#rr_hf_lhw").html("--");
    $("#rr_hf_mnch").html("--");
    $("#rr_hf_static").html("--");
}



function  drawGrid() {

    $("#attributeGrid").html("");
    $("#districtRanking").html("");
    dataDownload.length = 0;
    jsonData.length = 0;
    var features = cLMIS.features;

    table = "<table class='table table-condensed table-hover'>";
    table += "<thead><th>Province</th><th>District</th><th>StakeHolder</th><th>MOS</th></thead>";
    for (var i = 0; i < features.length; i++) {

        table += "<tr><td>" + features[i].attributes.province + "</td><td>" + features[i].attributes.district + "</td><td>" + features[i].attributes.StkHolder + "</td><td align='right'>" + features[i].attributes.value + "</td><td><div style='width:30px;height:18px;background-color:" + features[i].attributes.color + "'></div></td></tr>";
        jsonData.push({
            label: features[i].attributes.district,
            value: features[i].attributes.value,
            color: features[i].attributes.color
        });
        dataDownload.push({
            province: features[i].attributes.province,
            district_name: features[i].attributes.district,
            Stakeholder: features[i].attributes.StkHolder,
            product: features[i].attributes.product,
            Status: features[i].attributes.status,
            MOS: features[i].attributes.value
        });
    }
    table += "</table>";
    $("#attributeGrid").append(table);
    maximum = cLMIS.features.length;
    var pageTitle = $(".page-title").html();
    var title = pageTitle.split("Map");
    districtRanking(jsonData, "- " + title[0]);
}
function drawGrid1() {
    $("#attributeGrid").html("");
    dataDownload.length = 0;
    jsonData.length = 0;
    var features = cLMIS.features;
    table = "<table class='table table-condensed table-bordered table-hover'>";
    table += "<thead><th>District</th><th>Tehsil</th><th class='center'>Total UCs</th><th class='center'>Reported UCs</th><th class='center' colspan='2'>Reporting Rate(%)</th></thead>";
    for (var i = 0; i < features.length; i++) {
        table += "<tr><td>" + features[i].attributes.district + "</td><td>" + features[i].attributes.tehsil_name + "</td><td class='center'>" + features[i].attributes.total_warehouse + "</td><td class='center'>" + features[i].attributes.reported + "</td><td class='center'>" + features[i].attributes.reporting_rate + "</td><td align='left'><div style='width:30px;height:18px;background-color:" + features[i].attributes.color + "'></div></td></tr>";
        jsonData.push({
            label: features[i].attributes.tehsil_name,
            value: features[i].attributes.value,
            color: features[i].attributes.color
        });
        dataDownload.push({
            province: features[i].attributes.province,
            district_name: features[i].attributes.district,
            tehsil_name: features[i].attributes.tehsil_name,
            total_UCs: features[i].attributes.total_warehouse,
            reported_UCs: features[i].attributes.reported,
            Status: features[i].attributes.status,
            MOS: features[i].attributes.value
        });
    }
    table += "</table>";
    $("#attributeGrid").append(table);
    maximum = cLMIS.features.length;

}

function lastMonthsStats(district_id, district_name) {

    $("#graph").html("");
    $.ajax({
        url: appPath + "/api/get-district-chart.php",
        type: "GET",
        data: {
            district_id: district_id

        },
        dataType: "json",
        success: callback
    });

    function callback(response) {

        var data = [];
        data = response;

        const dataSource = {
            "chart": {
                "caption": district_name + " - Reporting Rate (Nov-2018)",
                "xaxisname": "Stakeholders",
                "yaxisname": "Reporting Rate",
                "formatnumberscale": "%",
                "yAxisMaxValue": 100,
                "yAxisMinValue": 0,
                "theme": "fint",

            },
            "categories": [
                {
                    "category": [
                        {
                            "label": "PWD"
                        },
                        {
                            "label": "DOH (LHW)"
                        }, {
                            "label": "DOH (MNCH)"
                        }, {
                            "label": "DOH (Static HF)"
                        }

                    ]
                }
            ],
            "dataset": [
                {
                    "seriesname": "District",
                    "data": [
                        {
                            "value": data[0].d_pwd,
                            
                        },
                        {
                            "value": data[0].d_lhw,
                            
                        }, {
                            "value": data[0].d_mnch,
                           
                        },
                        {
                            "value": data[0].d_static_hf,
                           
                        }
                    ]
                },
                {
                    "seriesname": "SDPs",
                    "data": [
                        {
                            "value": data[0].hf_pwd,
                            "link": "n-http://beta.lmis.gov.pk/clmisapp/application/reports/compliance_hf.php?ending_month=11&year_sel=2018&stk_sel=1&prov_sel="+data[0].prov_id+"&district="+data[0].dist_id+"&submit=Go"
                        },
                        {
                            "value": data[0].hf_lhw,
                            "link": "n-http://beta.lmis.gov.pk/clmisapp/application/reports/compliance_hf.php?ending_month=11&year_sel=2018&stk_sel=2&prov_sel="+data[0].prov_id+"&district="+data[0].dist_id+"&submit=Go"
                        }, {
                            "value": data[0].hf_mnch,
                            "link": "http://beta.lmis.gov.pk/clmisapp/application/reports/compliance_hf.php?ending_month=11&year_sel=2018&stk_sel=73&prov_sel="+data[0].prov_id+"&district="+data[0].dist_id+"&submit=Go"
                        },
                        {
                            "value": data[0].hf_static_hf,
                            "link": "n-http://beta.lmis.gov.pk/clmisapp/application/reports/compliance_hf.php?ending_month=11&year_sel=2018&stk_sel=7&prov_sel="+data[0].prov_id+"&district="+data[0].dist_id+"&submit=Go"
                        }
                    ]
                }
            ]
        };


        var myChart = new FusionCharts({
            type: "mscolumn2d",
            renderAt: "chart-container",
            width: "100%",
            height: "300",
            dataFormat: "json",
            dataSource

        });
        myChart.render("graph");


    }
}

function mapTitle() {
    product_name = $("#prod_sel option:selected").text();
    StkHolder = $("#stk_sel option:selected").text();
    if (StkHolder == "All" && sector == "0") {
        StkHolder = $("#sector option:selected").text();
    } else if (StkHolder == "All" && sector == "1") {
        StkHolder = $("#sector option:selected").text();
    } else {
    }
    prov_name = $("#prov_sel option:selected").text();
    var year_name = $("#year_sel option:selected").text();
    var month_value = ($('#slider').slider("option", "value")) - 1;

    var month_name = monthNames[month_value];
    month_year = month_name + " " + year_name;
    if (prov_name == "All") {
        prov_name = "Pakistan";
    }
    download = StkHolder + "->" + product_name + "->" + month_year;

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

function clearData() {
    $("#loader").show();
    $("#legendDiv").css("display", "none");
    $("#legend").html("");
    $('.radio-button').prop('checked', false);
    $("#mosRanges").html("");
    $("#graph").html("Click any district for Reporting Rate");
    $("#attributeGrid").html("");
    $("#districtRanking").html("");
    $("#mapTitle").html("");
    classesArray.length = 0;
    pieArray.length = 0;
    NoData = '0';
    DataProblem = '0';
    StockOut = '0';
    UnderStock = '0';
    Satisfactory = '0';
    OverStock = '0';
}

function districtCountGraph() {

    var ND = CalculatePercent(NoData, maximum);
    var SO = CalculatePercent(StockOut, maximum);
    var US = CalculatePercent(UnderStock, maximum);
    var SAT = CalculatePercent(Satisfactory, maximum);
    var OS = CalculatePercent(OverStock, maximum);

    pieArray.push({
        label: 'No Data',
        value: ND,
        color: classesArray[0].color_code
    });
    pieArray.push({
        label: 'Stock Out',
        value: SO,
        color: classesArray[1].color_code
    });
    pieArray.push({
        label: 'Under Stock',
        value: US,
        color: classesArray[2].color_code
    });
    pieArray.push({
        label: 'Satisfactory',
        value: SAT,
        color: classesArray[3].color_code
    });
    pieArray.push({
        label: 'Over Stock',
        value: OS,
        color: classesArray[4].color_code
    });

    var revenueChart = new FusionCharts({
        type: 'pie2D',
        renderAt: 'chart-container',
        width: '100%',
        height: '240px',
        dataFormat: 'json',
        dataSource: {
            "chart": {
                "caption": prov_name + " - MOS Status",
                "subcaption": download,
                "showLabels": "0",
                "showlegend": "1",
                "slantLabels": '1',
                "enableLink": '1',
                "showValues": '1',
                "xAxisName": "",
                "numberSuffix": "%",
                "yAxisName": "District Count",
                "exportEnabled": "1",
                "theme": "fint"
            },
            "data": pieArray
        }
    });
    revenueChart.render("pie");
}

function districtCountGraph1() {

    var ND = CalculatePercent(NoData, maximum);
    var SO = CalculatePercent(StockOut, maximum);
    var US = CalculatePercent(UnderStock, maximum);
    var SAT = CalculatePercent(Satisfactory, maximum);
    var OS = CalculatePercent(OverStock, maximum);

    pieArray.push({
        label: 'No Data',
        value: ND,
        color: classesArray[0].color_code
    });
    pieArray.push({
        label: 'Stock Out',
        value: SO,
        color: classesArray[1].color_code
    });
    pieArray.push({
        label: 'Under Stock',
        value: US,
        color: classesArray[2].color_code
    });
    pieArray.push({
        label: 'Satisfactory',
        value: SAT,
        color: classesArray[3].color_code
    });
    pieArray.push({
        label: 'Over Stock',
        value: OS,
        color: classesArray[4].color_code
    });

    var revenueChart = new FusionCharts({
        type: 'pie2D',
        renderAt: 'chart-container',
        width: '100%',
        height: '240px',
        dataFormat: 'json',
        dataSource: {
            "chart": {
                "caption": prov_name + " - MOS Status",
                "subcaption": download,
                "showLabels": "0",
                "showlegend": "1",
                "slantLabels": '1',
                "enableLink": '1',
                "showValues": '1',
                "xAxisName": "",
                "numberSuffix": "%",
                "yAxisName": "District Count",
                "exportEnabled": "1",
                "theme": "fint"
            },
            "data": pieArray
        }
    });
    revenueChart.render("pie");
}
function districtRanking(records, title) {


    records.sort(SortByRankingID);

    if (records.length > 100) {
        width = '480%';
    } else {
        width = '130%';
    }
    var maximum = records[0].value;
    alert(maximum);
    var minMaxPercent = (maximum * 10 / 100);
    maximum = maximum + minMaxPercent;

    var revenueChart = new FusionCharts({
        type: 'column2d',
        renderAt: 'chart-container',
        width: width,
        height: '100%',
        dataFormat: 'json',
        dataSource: {
            "chart": {
                "caption": prov_name + " - District wise Stock Ranking " + title,
                "subcaption": download,
                "yAxisMaxValue": maximum,
                "slantLabels": '1',
                "showValues": '1',
                "rotateValues": '1',
                "placeValuesInside": '1',
                "adjustDiv": '0',
                "numDivLines": '3',
                "xAxisName": "",
                "yAxisName": "No.of Months",
                "exportEnabled": "1",
                "theme": "fint"
            },
            "data": records
        }
    });
    revenueChart.render("districtRanking");
}

function SortByID(x, y) {
    return x.mos - y.mos;
}

function SortByRankingID(x, y) {
    return y.value - x.value;
}

function gridFilter(color) {
    $("#attributeGrid").html("");
    dataDownload.length = 0;
    var features = cLMIS.features;
    table = "<table class='table table-condensed table-hover'>";
    table += "<thead><th>Province</th><th>District</th><th>StakeHolder</th><th>MOS</th><th></th></thead>";
    for (var i = 0; i < features.length; i++) {
        if (features[i].attributes.color == color) {
            table += "<tr><td>" + features[i].attributes.province + "</td><td>" + features[i].attributes.district + "</td><td>" + features[i].attributes.StkHolder + "</td><td align='right'>" + features[i].attributes.value + "</td><td><div style='width:30px;height:18px;background-color:" + features[i].attributes.color + "'></div></td></tr>";
            dataDownload.push({
                province: features[i].attributes.province,
                district_name: features[i].attributes.district,
                Stakeholder: features[i].attributes.StkHolder,
                product: features[i].attributes.product,
                Status: features[i].attributes.status,
                MOS: features[i].attributes.value
            });
        }
    }
    table += "</table>";
    $("#attributeGrid").append(table);
}



function getLegend(value, title) {
    $.ajax({
        url: appPath + "/api/get-color-classes.php",
        type: "GET",
        data: "id=" + value,
        dataType: "json",
        async: false,
        success: callback
    });

    function callback(response) {

        classesArray = response;
        var classes = parseInt(classesArray.length) - 1;
        var legend = document.getElementById('legend');

        var row = legend.insertRow(0);
        var cell = row.insertCell(0);
        cell.colSpan = "3";
        cell.align = "right";
        cell.className = "hide_td";
        cell.innerHTML = "<a class='undo' onclick='getFullColor()'>Reset</a>";

        for (var i = 0; i >= 0; i--) {
            var row = legend.insertRow(0);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            cell1.align = "right";
            cell1.className = "hide_td";
            cell2.align = "right";
            cell2.width = "22px";
            cell3.align = "left";
            cell3.style.paddingLeft = "2px";
            cell1.innerHTML = "<input name='color' class='radio-button' type='radio' onclick='getColorName(\"" + classesArray[i].color_code + "\",\"" + classesArray[i].description + "\")'/>";
            cell2.innerHTML = "<div style='width:22px;height:18px;background-color:" + classesArray[i].color_code + "'></div>";
            cell3.innerHTML = classesArray[i].description;
        }

        var row = legend.insertRow(0);
        var cell = row.insertCell(0);
        cell.colSpan = "3";
        cell.align = "center";
        cell.innerHTML = "<br/>";

        for (var i = classes; i >= 1; i--) {
            var row = legend.insertRow(0);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            cell1.align = "right";
            cell1.className = "hide_td";
            cell2.align = "right";
            cell2.width = "22px";
            cell3.align = "left";
            cell3.style.paddingLeft = "2px";
            cell1.innerHTML = "<input name='color' class='radio-button' type='radio' onclick='getColorName(\"" + classesArray[i].color_code + "\",\"" + classesArray[i].description + "\")'/>";
            cell2.innerHTML = "<div style='cursor:pointer;width:22px;height:18px;background-color:" + classesArray[i].color_code + "'></div>";
            cell3.innerHTML = classesArray[i].description;
        }
        var row = legend.insertRow(0);
        var cell = row.insertCell(0);
        cell.colSpan = "3";
        cell.align = "left";
        cell.innerHTML = "<font size='2' color='green'><b>" + title + "</b></font>";

        var defination = document.getElementById('mosRanges');
        for (var i = classes; i >= 1; i--) {

            var row = defination.insertRow(0);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            cell1.align = "center";
            cell1.width = "250px";
            cell2.align = "center";
            cell2.width = "100px";
            cell1.innerHTML = classesArray[i].description;
            cell2.innerHTML = classesArray[i].interval;

        }
        $("#mosDefination").css("display", "block");
        $("#legendDiv").css("display", "none");
        drawFeature();
    }
}

function getLegendTeh(value, title) {
    $.ajax({
        url: appPath + "/api/get-color-classes.php",
        type: "GET",
        data: "id=" + value,
        dataType: "json",
        async: false,
        success: callback
    });

    function callback(response) {

        classesArray = response;
        var classes = parseInt(classesArray.length) - 1;
        var legend = document.getElementById('legend');

        var row = legend.insertRow(0);
        var cell = row.insertCell(0);
        cell.colSpan = "3";
        cell.align = "right";
        cell.className = "hide_td";
        cell.innerHTML = "<a class='undo' onclick='getFullColor()'>Reset</a>";

        for (var i = 0; i >= 0; i--) {
            var row = legend.insertRow(0);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            cell1.align = "right";
            cell1.className = "hide_td";
            cell2.align = "right";
            cell2.width = "22px";
            cell3.align = "left";
            cell3.style.paddingLeft = "2px";
            cell1.innerHTML = "<input name='color' class='radio-button' type='radio' onclick='getColorName(\"" + classesArray[i].color_code + "\",\"" + classesArray[i].description + "\")'/>";
            cell2.innerHTML = "<div style='width:22px;height:18px;background-color:" + classesArray[i].color_code + "'></div>";
            cell3.innerHTML = classesArray[i].description;
        }

        var row = legend.insertRow(0);
        var cell = row.insertCell(0);
        cell.colSpan = "3";
        cell.align = "center";
        cell.innerHTML = "<br/>";

        for (var i = classes; i >= 1; i--) {
            var row = legend.insertRow(0);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            cell1.align = "right";
            cell1.className = "hide_td";
            cell2.align = "right";
            cell2.width = "22px";
            cell3.align = "left";
            cell3.style.paddingLeft = "2px";
            cell1.innerHTML = "<input name='color' class='radio-button' type='radio' onclick='getColorName(\"" + classesArray[i].color_code + "\",\"" + classesArray[i].description + "\")'/>";
            cell2.innerHTML = "<div style='cursor:pointer;width:22px;height:18px;background-color:" + classesArray[i].color_code + "'></div>";
            cell3.innerHTML = classesArray[i].description;
        }
        var row = legend.insertRow(0);
        var cell = row.insertCell(0);
        cell.colSpan = "3";
        cell.align = "left";
        cell.innerHTML = "<font size='2' color='green'><b>" + title + "</b></font>";

        var defination = document.getElementById('mosRanges');
        for (var i = classes; i >= 1; i--) {

            var row = defination.insertRow(0);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            cell1.align = "center";
            cell1.width = "250px";
            cell2.align = "center";
            cell2.width = "100px";
            cell1.innerHTML = classesArray[i].description;
            cell2.innerHTML = classesArray[i].interval;

        }
        $("#mosDefination").css("display", "block");
        $("#legendDiv").css("display", "block");
        drawFeatureTeh();
    }
}

function drawFeatureTeh() {

    year = '2017';
    month = '01';
    sector = '1';
    stk = '1';
    prov = $("#prov_sel").val();
    dist = $("#dist").val();
    product = '1';


    var multi_members1 = '';
    var multi_members_id1 = '';
    var myarray1 = {};
    $("input[name='dist1[]']").each(function () {

        multi_members1 = $(this).val();
        multi_members_id1 = $(this).attr("id");

        d_id1 = multi_members_id1.split('-', '1');
        myarray1[d_id1[0]] = multi_members1;
    });

    $.ajax({
        url: appPath + "/api/get-c-mos-map-data_1.php",
        type: "POST",
        data: {
            year: year,
            month: month,
            sector: sector,
            stk: stk,
            province: prov,
            product: product,
            level: level,
            district: dist,
            teh: JSON.stringify(myarray1)
        },
        dataType: "json",
        success: callback,
        error: function (response) {
            alert("No Data Available");
            $("#loader").css("display", "none");
            return;
        }
    });

    function callback(response) {

        var data = [];
        data = response;
        if (cLMIS.features.length > 0) {
            cLMIS.removeAllFeatures();
        }
        FilterTehsilData();
        if (data.length <= 0) {
            alert("No Data Available");
            $("#loader").css("display", "none");
            return;
        }
        data.sort(SortByID);
        for (var i = 0; i < data.length; i++) {
            chkeArray1(data[i].district_id, data[i].mapping_id, Number(data[i].mos));
        }
        drawGrid1();
        districtCountGraph1();
    }
}


