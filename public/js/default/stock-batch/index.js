var batchManagement = function () {
    var oTable = $('#batch_management').dataTable({
        "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
            /*
             * Calculate the total batch quantity for all browsers in this table (ie inc. outside
             * the pagination)
             */
            var iTotalVials = 0;
            var iTotalDoses = 0;
            for (var i = 0; i < aaData.length; i++)
            {
                iTotalVials += aaData[i][6].replace(/[^0-9]/gi, '') * 1;
                iTotalDoses += aaData[i][7].replace(/[^0-9]/gi, '') * 1;
            }

            /* Calculate the batch quantity for browsers on this page */
            var iPageVials = 0;
            var iPageDoses = 0;
            for (var i = iStart; i < iEnd; i++)
            {
                iPageVials += aaData[aiDisplay[i]][6].replace(/[^0-9]/gi, '') * 1;
                iPageDoses += aaData[aiDisplay[i]][7].replace(/[^0-9]/gi, '') * 1;
            }

            /* Modify the footer row to match what we want */
            var nCells = nRow.getElementsByTagName('th');
            /*nCells[1].innerHTML = numberWithCommas(parseInt(iPageVials)) + " <br>" + numberWithCommas(parseInt(iTotalVials));
             nCells[2].innerHTML = numberWithCommas(parseInt(iPageDoses)) + " <br>" + numberWithCommas(parseInt(iTotalDoses));*/
            nCells[6].innerHTML = numberWithCommas(parseInt(iPageVials));
            nCells[7].innerHTML = numberWithCommas(parseInt(iPageDoses));
        },
        "aoColumnDefs": [
            {"sType": 'date-uk', "aTargets": [0]},
            {"bSortable": false, "aTargets": [-1]}
            /*{
             "aTargets": [-1],
             "bVisible": false
             }*/
        ],
        "aaSorting": [[0, 'asc']],
        "aLengthMenu": [
            [20, 50, 100, -1],
            [20, 50, 100, "All"] // change per page values here
        ],
        "oSearch": {"bSmart": false, "bRegex": true},
        "sDom": "<'row'<'col-md-4 col-sm-12'l><'col-md-4 col-sm-12'T><'col-md-4 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        //
        //"sDom ": 'T<"clear ">lfrtip',
        // set the initial value
        "bDestroy": true,
        "iDisplayLength": 20,
        "oTableTools": {
            "sSwfPath": appName + "/common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf",
            "aButtons": [{
                    "sExtends": "xls",
                    "sButtonText": "<img src=../images/excel-32.png width=24> Export to Excel",
                    "mColumns": "sortable"
                }, {
                    "sExtends": "copy",
                    "sButtonText": "<img src=../images/copy.png width=24> Copy Data",
                    "mColumns": "sortable"
                }]
        }
    });

    jQuery('#batch_management_wrapper .dataTables_filter input').addClass("form-control input-small input-inline"); // modify table search input
    jQuery('#batch_management_wrapper .dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
    jQuery('#batch_management_wrapper .dataTables_length select').select2(); // initialize select2 dropdown

    $('#batch_management_column_toggler input[type="checkbox"]').change(function () {
        /* Get the DataTables object again - this is not a recreation, just a get of the object */
        var iCol = parseInt($(this).attr("data-column"));
        var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
        oTable.fnSetColumnVis(iCol, (bVis ? false : true));
    });
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

$(function () {
    batchManagement();

    /*$('#item_pack_size_id').change(function() {
     batchDetail();
     });*/
    searchInputFtn($("#searchby").val());

    if ($('#item_pack_size_id').val() != '') {
        batchDetail();
        checkProductCat();
    }

    $('#item_pack_size_id').change(function () {
        checkProductCat();
    });

    $(".detail-batch").click(function () {
        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/detail-batch",
            data: {batch_detail_id: $(this).attr('itemid')},
            dataType: 'html',
            success: function (data) {
                $('#modal-body-contents-detail').html(data);
            }
        });
    });


    //$("button[id$='-makeit']").click(function() {
    $(document).on("click", "button[id$='-makeit']", function () {
        var value = this.id;
        var action = value.replace("-makeit", "");
        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-change-status",
            data: {batch_id: $('#' + action + '_id').val(), status: $('#' + action + '_status').val()},
            dataType: 'json',
            success: function (data) {
                $('#' + action + '-status').html(data.status);
                $('#' + action + '-button').html(data.button);
                $('#' + action + '_status').val(data.status);

                if ($('#' + action + '_status').val() == 'Stacked') {
                    $('#' + action + '-makeit').removeClass("btn-danger");
                    $('#' + action + '-makeit').addClass("btn-success");
                } else {
                    $('#' + action + '-makeit').removeClass("btn-success");
                    $('#' + action + '-makeit').addClass("btn-danger");
                }
            }
        });
    });
});
function checkProductCat() {
    $.ajax({
        type: "POST",
        url: appName + "/stock-batch/ajax-product-category",
        data: {item_id: $('#item_pack_size_id').val()},
        dataType: 'html',
        success: function (data) {
            if (data == 1) {
                $("input[type=radio][value=1]").attr("disabled", false);
                $("input[type=radio][value=2]").attr("disabled", false);
                $("input[type=radio][value=3]").attr("disabled", false);
                $("input[type=radio][value=7]").attr("disabled", false);
                $("#vaccine_div").show();
                $("#non_vaccine_div").hide();
            } else {
                $("#non_vaccine_div").show();
                $("#vaccine_div").hide();
                $("input[type=radio][value=7]").attr("disabled", true);
            }
        }
    });
}
function batchDetail() {
    if ($('#item_pack_size_id').val() != '') {
        $("#printSummary").hide();
        //   $('#priorityBatches').show();
    } else {
        $("#printSummary").show();
        //    $('#priorityBatches').hide();
    }

    $.ajax({
        type: "POST",
        url: appName + "/stock-batch/ajax-batch-detail",
        data: {id: $('#item_pack_size_id').val()},
        dataType: 'html',
        success: function (data) {
            $('#vaccine-detail').show();
            $('#batch_detail_ajax').html(data);
        }
    });
}

function searchInputFtn(val) {
    switch (val) {
        case 'expired_before':
        case 'expired_after':
            $("#searchinput").attr('readonly', 'readonly');
            $("#searchinput").datepicker({
                minDate: 0,
                maxDate: "+10Y",
                dateFormat: 'dd/mm/yy',
                changeMonth: true,
                changeYear: true
            });
            break;
        default:
            $("#searchinput").removeProp('readonly');
            $("#searchinput").datepicker("destroy");
            break;
    }
}

$('#print_vaccine_placement').click(function () {
    var form_name, status, number, transaction_refernece, item_id, all_arguments;
    form_name = $('#batch_search');
    status = form_name.find('input[name=status]:checked').val();
    searchby = $('#searchby').val();
    item_id = $('#item_pack_size_id').val();
    searchinput = $('#searchinput').val();
    all_arguments = "?type=1&item_pack_size_id=" + item_id + "&status=" + status + "&searchby=" + searchby + "&searchinput=" + searchinput;
    window.open(appName + '/stock-batch/batch-summary' + all_arguments, '_blank', 'scrollbars=1,width=860,height=595');
});

$('#priorityBatches').click(function () {

    Metronic.startPageLoading('Please wait...');
    $.ajax({
        type: "POST",
        url: appName + "/stock-batch/ajax-run-priority-batches",
        data: {product_id: $('#item_pack_size_id').val()},
        dataType: 'html',
        success: function (data) {
            Metronic.stopPageLoading();
            location.href = appName + '/stock-batch/index?item_pack_size_id=' + $('#item_pack_size_id').val() + '&status=' + $('input[name=status]:checked').val();
        }
    });
});

$.inlineEdit({
    expiry: appName + '/stock-batch/ajax-expiry-edit/type/expiry/id/'
}, {
    animate: false,
    filterElementValue: function ($o) {
        return $o.html().trim();
    },
    afterSave: function () {
    }
});

$(".placement-history").click(function () {
    $('#modal-body-contents').html("<div style='text-align: center; '><img src='" + appName + "/images/loader.gif' style='margin:30px;'  /></div>");
    $.ajax({
        type: "POST",
        url: appName + "/stock-batch/ajax-get-placement-history",
        data: {id: $(this).attr('pkid')},
        dataType: 'html',
        success: function (data) {
            $('#modal-body-contents').html(data);
        }
    });
});
//$('input:radio[id=status-1]').prop('checked', true);

$("#searchby").change(function (e) {
    var val = $(this).val();

    searchInputFtn(val);

    if (val == 'expired_before' || val == 'expired_after') {
        $("input[type=radio][value=5]").attr("disabled", true);

        $("#searchinput").val("");
    } else {
        $("input[type=radio][value=5]").attr("disabled", false);
    }
    if (val == 'number') {
        $("#searchinput").val("");
    }
});

//var search_by = $("#searchby").val();
//if (search_by != '') {
//    $("#searchby").trigger("change");
//}

/*var validator = $("#batch_search").validate();
 validator.showErrors({
 "searchinput": "Date should be selected."
 });
 
 $("#batch_search").validate({
 rules: {
 item_pack_size_id: {
 required: true
 }
 },
 messages: {
 item_pack_size_id: {
 required: "Please select issue from"
 }
 }
 });*/