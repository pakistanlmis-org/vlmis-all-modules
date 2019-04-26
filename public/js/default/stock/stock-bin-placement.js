$(function () {

    $('input[type="text"]').keydown(function (e) {
        if (e.shiftKey || e.ctrlKey || e.altKey) { // if shift, ctrl or alt keys held down
            e.preventDefault();         // Prevent character input
        } else {
            var n = e.keyCode;
            if (!((n == 8)              // backspace
                    || (n == 9)                // Tab
                    || (n == 46)                // delete
                    || (n >= 35 && n <= 40)     // arrow keys/home/end
                    || (n >= 48 && n <= 57)     // numbers on keyboard
                    || (n >= 96 && n <= 105))   // number on keypad
                    ) {
                e.preventDefault();     // Prevent character input
            }
        }
    });

    $('#print_stock').click(
            function () {
                var searchby, number, warehouses, product, date_from, date_to, all_arguments;
                searchby = $('#searchby').val();
                number = $('#number').val();
                warehouses = $('#warehouses').val();
                product = $('#product').val();
                date_from = $('#date_from').val();
                date_to = $('#date_to').val();
                all_arguments = "?searchby=" + searchby + "&number=" + number + "&warehouses=" + warehouses + "&product=" + product + "&date_from=" + date_from + "&date_to=" + date_to;
                window.open('stock-receive-list' + all_arguments, '_blank', 'scrollbars=1,width=860,height=595');
            }
    );




//$(".stock-bin-placement").click(function() {
//    $.ajax({
//        type: "POST",
//        url: appName + "/stock/ajax-stock-bin-placement",
//        data: {id: $(this).attr('itemid')},
//        dataType: 'html',
//        success: function(data) {
//            $('#modal-body-contents').html(data);
//            $('#update-button').show();
//        }
//    });
//});

//$(".product-location").click(function() {
//
//        $("#iframeurl").html();
//        var self = $(this);
//        var itemid = self.attr('itemid');
//        //alert('itemid=='+itemid);
//
//        $("#iframeurl").attr('src', appName + '/stock/product-location/?id=' + itemid);
//
//        /*$.ajax({
//         type: "POST",
//         url: appName + "/stock/product-location",
//         data: {pl_id: $(this).attr('id')},
//         dataType: 'html',
//         success: function(data) {
//         $('#modal-body-contents').html(data);
//         $('#update-button').show();
//         }
//         });*/
//    });

//    $("#save").click(function(e) {
//        e.preventDefault();
//        var validator = $("#stockplacement").validate();
//        var unall_qty = $("#unallocated_qty").val();
//        var quantity = $("#quantity").val();
//        if (quantity == 0) {
//            validator.showErrors({
//                "quantity": "Quantity should greater then 0."
//            });
//        } else if (quantity < unall_qty) {
//            validator.showErrors({
//                "quantity": "Quantity should less then unallocated quantity."
//            });
//        } else {
//            qtydata = $('#stockplacement').serialize();
//            //alert(qtydata);
//            $.ajax({
//                type: "POST",
//                url: appName + "/stock/ajax-add-stock-placement?" + qtydata,
//                data: {},
//                dataType: 'html',
//                success: function(data) {
//                    $('#success').show();
//                }
//            });
//        }
//    });



    $("#btn-loading").click(function () {
        //alert("here");
        //added by
        var q = 0;
        var inp = $('.qty');
        for (var i = 0; i < inp.length; i++)
        {
            // alert(inp[i].value);
            if (inp[i].value != '')
            {
                var fldName = inp[i].name;
                var valArr = fldName.split("]");
                var unAllocatedQty = valArr[1];
                q++;
                if (parseInt(inp[i].value) > unAllocatedQty)
                {
                    alert('Quantity can not be greater than ' + unAllocatedQty);
                    inp[i].focus();
                    return false;
                }
            }
            if (isNaN(inp[i].value))
            {
                alert('Quantity should be a number');
                inp[i].focus();
                inp[i].value = "";
                return false;
            }
        }

        if (q == 0)
        {
            alert('Please enter at least one quantity');
            return false;
        } else {

            qtydata = $('#stockplacement').serialize();
            //alert(qtydata);
            $.ajax({
                type: "POST",
                url: appName + "/stock/ajax-add-stock-placement?" + qtydata,
                data: {},
                dataType: 'html',
                success: function (data) {
                    window.location.href = window.location + '&success=1';
                    //$('#success').show();       
                }
            });
        }
    });





//no
//$("#save").click(function(e) {
//    var q = 0;
//    var inp = $('.quantity');
//     var inpv = $('#quantity');
//    var unall_qty = $("#unallocated_qty").val();
//    for (var i = 0; i < inp.length; i++)
//    {
//        if (inp[i].value != '')
//        {
//            q++;
//            if (parseInt(inp[i].value) > parseInt(inp[i].getAttribute('max')))
//            {
//                alert('Quantity can not be greater than ' + inp[i].getAttribute('max'));
//                inp[i].focus();
//                return false;
//            }
//        }
//          else if(inpv > unall_qty)
//    {
//            alert('Quantity should less then unallocated quantity.');
//        return false;
//        
//    }
//     else {
//        qtydata = $('#stockplacement').serialize();
//        //alert(qtydata);
//        $.ajax({
//            type: "POST",
//            url: appName + "/stock/ajax-add-stock-placement?" + qtydata,
//            data: {},
//            dataType: 'html',
//            success: function(data) {
//                $('#success').show();
//            }
//        });
//    }
//    }
//
//    if (q == 0)
//    {
//        alert('Please enter at least one quantity');
//        return false;
//    }
//  
//   
//});



});




