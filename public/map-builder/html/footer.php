<!-- BEGIN FOOTER -->
<div class="clearfix"></div>

<!-- END FOOTER -->
<script type="text/javascript">
    var basePath = "  ";
    var appPath = "/map-builder";
</script>

<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="  assets/global/plugins/respond.min.js"></script>
<script src="  assets/global/plugins/excanvas.min.js"></script>
<![endif]-->
<script src="  assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
<script src="  assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="  assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<script src="  assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="  assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="  assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="  assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="  assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<?php /* ?><script src="<?php echo PUBLIC_URL;?>assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script><?php */ ?>
<script src="  assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->

<script src="  assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
<script src="  assets/global/plugins/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="  assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
<!-- IMPORTANT! fullcalendar depends on jquery-ui-1.10.3.custom.min.js for drag & drop support -->
<script src="  assets/global/plugins/fullcalendar/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
<script src="  assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js" type="text/javascript"></script>
<script src="  assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="  js/jquery.notyfy.js"></script>
<script src="  assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="  assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="  assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="  assets/admin/pages/scripts/index.js" type="text/javascript"></script>
<script src="  assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>

<!-- END PAGE LEVEL SCRIPTS -->
<script>
    jQuery(document).ready(function () {
        Metronic.init(); // init metronic core componets
        Layout.init(); // init layout
        QuickSidebar.init() // init quick sidebar
        // Index.init();
        //Index.initDashboardDaterange();
        //Index.initJQVMAP(); // init index page's custom scripts
        //Index.initCalendar(); // init index page's custom scripts
        //Index.initCharts(); // init index page's custom scripts
        //Index.initChat();
        //Index.initMiniCharts();
        //Index.initIntro();
        Tasks.initDashboardWidget();
    });
</script>

<!-- END JAVASCRIPTS -->

<!-- JAVA Script files that were  in old code but are not present in current code -->
<!-- small hack that enables the use of touch events on sites using the jQuery UI user interface library -->
<script src="  common/theme/scripts/plugins/system/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
<!-- Modernizr -->
<script src="  common/theme/scripts/plugins/system/modernizr.js"></script>
<script src="  common/theme/scripts/demo/common.js?1369414385"></script>
<!-- PrettyPhoto -->
<script src="  common/theme/scripts/plugins/gallery/prettyphoto/js/jquery.prettyPhoto.js"></script>

<!-- DataTables Tables Plugin -->
<script src="  common/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.js"></script>
<script src="  common/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
<script src="  common/theme/scripts/demo/date-uk.js" type="text/javascript"></script>
<script src="  common/theme/scripts/demo/tables.js" type="text/javascript"></script>


<script src="  js/jquery.price_format.js"></script>
<script src="  common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/js/TableTools.js"></script>
<!-- Column Table Tools min -->
<script src="  common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/js/TableTools.min.js"></script>
<!-- Column Table Tools zero Clipboard -->
<script src="  common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/js/ZeroClipboard.js"></script>
<script type="text/javascript" src="  js/admin/jquery.validate.js"></script>
<script type="text/javascript" src="  js/admin/custom.js"></script>

<script src="  js/jquery.notyfy.js"></script>

<script type='text/javascript' src='  js/jquery.cookie.js'></script>
<script type='text/javascript' src='  js/jquery.dcjqaccordion.2.7.js'></script>
