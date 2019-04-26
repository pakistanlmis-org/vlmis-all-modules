$(function ()
{
//    $("input[id$='amc']").focusout(function (e) {
//        if ($(this).val() == '' || isNaN($(this).val())) {
//            $(this).val('0');
//        }
//        if ($(this).val() < 0) {
//            $(this).val(Math.abs($(this).val()));
//        }
//    });

    $("input[id$='amc']").priceFormat({
        prefix: '',
        thousandsSeparator: ',',
        suffix: '',
        centsLimit: 0,
        limit: 10
    });

    
});