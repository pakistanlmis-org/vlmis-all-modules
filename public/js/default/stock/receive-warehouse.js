$(function () {
    /*$("#rec_date").datepicker({
     minDate: $("#issue_date").val(),
     maxDate: 0,
     dateFormat: 'dd/mm/yy'
     });*/

    $("input[id$='-missing']").bind("paste", function (e) {
        e.preventDefault();
    });

    $("input[id$='-missing']").keydown(function (e) {
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

    $('#rec_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        format: 'd/m/Y H:i A',
        minDate: new Date($("#issue_year").val(), $("#issue_month").val() - 1, $("#issue_day").val()),
        maxDate: 0
    });

    $("input[id$='-stage1']").change(function () {
        var value = $(this).attr("id");
        var id = value.replace("-stage1", "");

        var stage1 = parseInt($("#" + id + "-stage1").val());
        var stage2 = parseInt($("#" + id + "-stage2").val());
        var stage3 = parseInt($("#" + id + "-stage3").val());
        var quantity = parseInt($("#" + id + "-qty").val());
        if (isNaN(stage1)) {
            stage1 = 0;
        }
        if (isNaN(stage2)) {
            stage2 = 0;
        }
        if (isNaN(stage3)) {
            stage3 = 0;
        }
        var total = stage1 + stage2 + stage3;
        $("#" + id + "-received").val(total);

        if (total > quantity) {
            alert("Quantity should not be greater than " + quantity);
        }
    });

    $("input[id$='-stage2']").change(function () {
        var value = $(this).attr("id");
        var id = value.replace("-stage2", "");

        var stage1 = parseInt($("#" + id + "-stage1").val());
        var stage2 = parseInt($("#" + id + "-stage2").val());
        var stage3 = parseInt($("#" + id + "-stage3").val());
        var quantity = parseInt($("#" + id + "-qty").val());
        if (isNaN(stage1)) {
            stage1 = 0;
        }
        if (isNaN(stage2)) {
            stage2 = 0;
        }
        if (isNaN(stage3)) {
            stage3 = 0;
        }
        var total = stage1 + stage2 + stage3;
        $("#" + id + "-received").val(total);

        if (total > quantity) {
            alert("Quantity should not be greater than " + quantity);
        }
    });

    $("input[id$='-stage3']").change(function () {
        var value = $(this).attr("id");
        var id = value.replace("-stage3", "");

        var stage1 = parseInt($("#" + id + "-stage1").val());
        var stage2 = parseInt($("#" + id + "-stage2").val());
        var stage3 = parseInt($("#" + id + "-stage3").val());
        var quantity = parseInt($("#" + id + "-qty").val());
        if (isNaN(stage1)) {
            stage1 = 0;
        }
        if (isNaN(stage2)) {
            stage2 = 0;
        }
        if (isNaN(stage3)) {
            stage3 = 0;
        }
        var total = stage1 + stage2 + stage3;
        $("#" + id + "-received").val(total);

        if (total > quantity) {
            alert("Quantity should not be greater than " + quantity);
        }
    });

    $('#checkall').attr('checked', false);

    //$("select[id$='-types']").attr("disabled", true);

    $('#estimated_life').priceFormat({
        prefix: '',
        thousandsSeparator: '',
        suffix: '',
        centsLimit: 0,
        limit: 2
    });

    $('#save').click(function(e) {
        
        var btn = $(this);
        btn.button('loading');
        setTimeout(function() {
            btn.button('reset');
        }, 10000);
        
        e.preventDefault();
        var flag = 'true';

        if ($('#receive_stock').find('input[type=checkbox]:checked').length == 0) {
            alert('Please select atleast one checkbox');
            flag = 'false';
        }
        var locations = $('#locations').val();
        if (locations == null) {
            alert("Location must be selected.");
            flag = 'false';
        }

        $("input[id$='-received']").each(function () {
            var value = $(this).attr("id");
            var id = value.replace("-received", "");
            var qty = $('#' + id + '-qty').val();
            var received = $('#' + id + '-received').val();

            if (parseInt(received) > parseInt(qty)) {
                alert("Received quantity should not be greater than actual quantity.");
                $(this).focus();
                flag = 'false';
            }
        });

        $("input[id$='-missing']").each(function () {
            var value = $(this).attr("id");
            var id = value.replace("-missing", "");
            $('#' + id + '-types').attr("required", true);
            var adjqty = $(this).val();
            var qty = $('#' + id + '-qty').val();
            var qtydoses = $('#' + id + '-missingdoses').val();
            adjqty = parseInt(adjqty.replace(",", ""));
            qty = parseInt(qty.replace(",", ""));
            var nod = $('#' + id + '-nod').val();

            var result = qtydoses % nod;

            if (result != 0) {
                flag = 'false';
                $('#' + id + '-missing').css({
                    'background-color': 'red',
                    color: 'yellow'
                });
            } else {
                $('#' + id + '-missing').css({
                    'background-color': 'white',
                    color: 'black'
                });
            }

            if (adjqty > 0 && $('#' + id + '-types').val() == '') {
                alert("Reason must be selected.");
                $($('#' + id + '-types')).focus();
                flag = 'false';
            }

            if (adjqty > qty) {
                alert("Adjustment quantity should not be greater than available quantity.");
                $(this).focus();
                flag = 'false';
            }

            if (qty != '' && !$.isNumeric(qty)) {
                alert("Adjustment quantity should be number");
                $(this).focus();
                flag = 'false';
            }
            if (adjqty == 'NaN' && adjqty != '' && isNaN(adjqty)) {
                alert("Adjustment quantity should be number");
                $(this).focus();
                flag = 'false';
            }
        });

        if (flag == 'true') {
            if (confirm('Are you sure you received all the items?')) {
                var checkedAtLeastOne = false;
                $('input[type="checkbox"]').each(function () {
                    if ($(this).is(":checked")) {
                        $('#save').attr('disabled', 'disabled');
                        $('#receive_stock').submit();
                        return false;
                    }
                });
            }
        }

    });

    $("input[id$='-missing']").keyup(function (e) {
        var value = $(this).attr("id");
        var id = value.replace("-missing", "");

        var adjqty = $(this).val();
        var qty = $('#' + id + '-qty').val();
        var nod = $('#' + id + '-nod').val();

        adjqty = parseInt(adjqty.replace(",", ""));
        qty = parseInt(qty.replace(",", ""));

        var doses = adjqty * nod;
        $('#' + id + '-missingdoses').val(doses);

        var result = doses % nod;

        if (result != 0) {
            $('#' + id + '-missing').css({
                'background-color': 'red',
                color: 'yellow'
            });
            $('#' + id + '-missingdoses').css({
                'background-color': 'red',
                color: 'yellow'
            });
        } else {
            $('#' + id + '-missing').css({
                'background-color': 'white',
                color: 'black'
            });
            $('#' + id + '-missingdoses').css({
                'background-color': 'white',
                color: 'black'
            });
        }

        if (adjqty > qty) {
            alert("Adjustment quantity should not be greater than available quantity.");
            $(this).focus();
        }
        if (qty != '' && !$.isNumeric(qty)) {
            alert("Adjustment quantity should be number");
            $('#' + id + '-types').attr("disabled", true);
            $(this).focus();
        }
    });

    $("input[id$='-missingdoses']").keyup(function (e) {
        var value = $(this).attr("id");
        var id = value.replace("-missingdoses", "");

        var adjqty = $(this).val();
        var qty = $('#' + id + '-doses').val();
        var nod = $('#' + id + '-nod').val();

        adjqty = parseInt(adjqty.replace(",", ""));
        qty = parseInt(qty.replace(",", ""));

        var vials = adjqty / nod;
        $('#' + id + '-missing').val(vials);

        var result = adjqty % nod;

        if (result != 0) {
            $('#' + id + '-missingdoses').css({
                'background-color': 'red',
                color: 'yellow'
            });
            $('#' + id + '-missing').css({
                'background-color': 'red',
                color: 'yellow'
            });
        } else {
            $('#' + id + '-missingdoses').css({
                'background-color': 'white',
                color: 'black'
            });
            $('#' + id + '-missing').css({
                'background-color': 'white',
                color: 'black'
            });
        }

        if (adjqty > qty) {
            alert("Adjustment quantity should not be greater than available quantity.");
            $(this).focus();
            flag = 'false';
        }
        if (qty != '' && !$.isNumeric(qty)) {
            alert("Adjustment quantity should be number");
            $('#' + id + '-types').attr("disabled", true);
            $(this).focus();
        }
    });


    $("#checkall").change(function () {
        if (this.checked) {
            $("input[type=checkbox]").attr("checked", true);
        }
        if (!this.checked) {
            $("input[type=checkbox]").attr("checked", false);
        }
    });


});