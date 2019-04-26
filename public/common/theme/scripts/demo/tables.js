/* ==========================================================
 * AdminKIT v1.5
 * tables.js
 * 
 * http://www.mosaicpro.biz
 * Copyright MosaicPro
 *
 * Built exclusively for sale @Envato Marketplaces
 * ========================================================== */

$(function ()
{

    /* DataTables */
    if ($('.dynamicTable').size() > 0)
    {

        var datatable = $('.dynamicTable').dataTable({
            "sPaginationType": "bootstrap",
            //"sDom": 'W<"clear">lfrtip',
            // "sDom": '<"clear">lfrtipT',
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page"
            }

        });
    }

    if ($('.dynamicTable1').size() > 0)
    {

        var datatable = $('.dynamicTable1').dataTable({
            "sPaginationType": "bootstrap",
            //"sDom": 'W<"clear">lfrtip',
            //"sDom": '<"clear">l',
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page"
            }

        });
    }

    if ($('.monthlyConsumption').size() > 0)
    {

        var datatable = $('.monthlyConsumption').dataTable({
            // "sPaginationType": "bootstrap",
            //"sDom": 'W<"clear">lfrtip',
            "sDom": '<"clear">T',
            //  "sDom": "<'row'<'col-md-11'>T<'clear'>><'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
            // "sDom": '<"clear">lfrtipT',

            "oTableTools": {
                "aButtons": [
                    {
                        "sExtends": "xls",
                        "sButtonText": "<img src=../images/excel-32.png>"
                    }

                ],
                "sSwfPath": appName + "/common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf"
            }

        });

    }
});