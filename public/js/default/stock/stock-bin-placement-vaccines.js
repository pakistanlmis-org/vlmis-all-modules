
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
    $("#btn-loading").click(function (e) {
        e.preventDefault();
        var unvalid = 0;
        var valid = 0;
        $("input[id$='-quantity']").each(function () {
            if ($(this).val() != "")
                valid += 1;
        });
        if (valid == 0) {
            alert('Please enter at least one quantity');
            return false;
        }

        $("input[id$='-quantity']").each(function () {
            var value = $(this).attr("id");
            var id = value.replace("-quantity", "");
            var unall_qty = $('#' + id + '-unallocated_qty').val();
            var unall_qty = parseInt(unall_qty.replace(/,/g, ""));
            var quantity = parseInt($('#' + id + '-quantity').val());
            if (quantity <= 0) {
                alert("Quantity should greater then 0.");
                unvalid += 1;
            }
            if (quantity > unall_qty) {
                //alert("Quantity should less then unallocated quantity.");
                alert('Quantity can not be greater than ' + unall_qty);
                unvalid += 1;
                $(this).focus();
                return false;
            }
            if (isNaN($('#' + id + '-quantity').val()))
            {
                alert('Quantity should be a number');
                $('#' + id + '-quantity').val("");
                $('#' + id + '-quantity').focus();
                unvalid += 1;
            }
        });
        if (unvalid === 0) {
            $('#stockplacementvaccines').submit();
        }
    });
});



