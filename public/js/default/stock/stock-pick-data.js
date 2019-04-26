$(function () {

    $(".qty").keydown(function (e) {
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

    $("#btn-loading").click(function () {
        var sum = 0;
        var q = 0;
        var inp = $('.qty');
        for (var i = 0; i < inp.length; i++) {
            if (inp[i].value != '') {
                sum += parseInt(inp[i].value);
                q++;
                if (isNaN(inp[i].value)) {
                    alert('Quantity should be a number.');
                    inp[i].value = '';
                    inp[i].focus();
                    valid = false;
                    return false;
                }
                if (parseInt(inp[i].value) > parseInt(inp[i].getAttribute('max'))) {
                    alert('Quantity can not be greater than ' + inp[i].getAttribute('max'));
                    inp[i].focus();
                    return false;
                }
            }
        }

        var pick_qty = parseInt($('#u_qty').val());

        if (q == 0) {
            alert('Please enter at least one quantity');
            return false;
        } else if (sum > pick_qty) {
            alert('You can\'t pick more then ' + pick_qty + ' quantity!');
            return false;
        } else {
            varform = $('#stockpickdata').serialize();
            $.ajax({
                type: "POST",
                url: appName + "/stock/pick-stock-quantity?" + varform,
                data: {},
                dataType: 'html',
                success: function (data) {
                    window.location.href = appName + '/stock/pick-stock?success=1';
                }
            });
        }
    });
});