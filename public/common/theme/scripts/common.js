function changeColor() {
    var div = $.cookie('blink_div_background_color');
    $('#' + div + '').css('backgroundColor', 'White');
    $.cookie('blink_div_background_color', "");
}

$(function() {
    // button state demo
    $('#btn-loading').click(function() {
        var btn = $(this);
        btn.button('loading');
        setTimeout(function() {
            btn.button('reset');
        }, 10000);
    });
});