var province_style = new OpenLayers.StyleMap();
var lookup = {
    "0": {fillColor: "#E6E7E9",strokeColor: "red",strokeWidth: 0.8,label : "${province_name}",fontSize:"12px",cursor: "pointer",fontWeight: "bold",fillOpacity: 0,fontFamily: "Courier New, monospace"},
    "1": {fillColor: "grey",strokeWidth: 1.5,strokeColor: "black",strokeOpacity: 1,fillOpacity:0, label : "${province_name}",fontColor:"black",fontSize:"12px",fontWeight: "bold", fontFamily: "Courier New, monospace"}
}
province_style.addUniqueValueRules("default", "class",lookup);

var district_style =  new OpenLayers.StyleMap({'default':{
    strokeColor: "#777777",
    strokeOpacity: 1,
    strokeWidth:0.2,
    fillColor: "grey",
    fillOpacity: 0
}});


var style =  OpenLayers.Util.applyDefaults({
    fillColor: "${color}",
    strokeColor: "white",
    strokeWidth: 0.5,
    cursor: "pointer",
    pointerEvents: "visiblePainted",
    fillOpacity: 1 },OpenLayers.Feature.Vector.style['default']);

var style_select = OpenLayers.Util.applyDefaults({
    fillColor: "${color}",
    fillOpacity: 0.5,
    cursor: "pointer",
    strokeColor: "white",
    strokeWidth:2
    });


var vlMIS_style = new OpenLayers.StyleMap({
    "default":style,
    "select" :style_select
});



var style_label = new OpenLayers.Style({
    fillColor: "${color}",
    strokeColor: "white",
    strokeWidth: 0.5,
    cursor: "pointer",
    label : "${district}\n\${value}",
    fontColor:"black",fontSize:"9px",fontWeight: "bold",
    pointerEvents: "visiblePainted",
    fillOpacity: 1 },OpenLayers.Feature.Vector.style['default']);
   

var style_label_select = new OpenLayers.Style({
    fillColor: "${color}",
    fillOpacity: 0.5,
    cursor: "pointer",
    strokeColor: "white",
    strokeWidth:2,
    label : "${district}\n\${value}",
    fontColor:"black",fontSize:"9px",fontWeight: "bold",
    });

var vlMIS_label_style = new OpenLayers.StyleMap({
    "default":style_label,
    "select" :style_label_select
});