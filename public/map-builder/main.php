<?php
/**
 * mos
 * @package maps
 * 
 * @author     Ajmal Hussain
 * @email <ahussain@ghsc-psm.org>
 * 
 * @version    2.2
 * 
 */
//include AllClasses
include("includes/classes/AllClasses.php");
include("includes/classes/config.php");
//include header
include("html/header.php");
?>
<SCRIPT LANGUAGE="Javascript" SRC="FusionCharts/Charts/FusionCharts.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="FusionCharts/themes/fusioncharts.theme.fint.js"></SCRIPT>
<link rel="stylesheet" type="text/css" href="css/map.css" />
<SCRIPT LANGUAGE="Javascript" SRC="js/maps/html2canvas.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="js/maps/download.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="js/maps/symbology.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="js/maps/mos.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="js/maps/Filter.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="js/maps/refineLegend.js"></SCRIPT>
</head><!-- END HEAD -->

<body class="">
    <!-- BEGIN HEADER -->
    <div class="page-container">
        <?php include "html/top.php"; ?>
        <?php include "html/top_im.php"; ?>
        <div class="container">


            <div class="row">

            </div>
            <div class="col-md-12">
                <table width="100%"style="visibility: hidden">
                    <tr>
                        <td style="width:100%" align="right"> <img id="image" src="  images/map-icon.png" style="cursor:pointer;width:35px;height:35px" /> </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-12">

                <div class="col-md-8">

                    <div class="tab-content">





                        <div id="tab-2" class="tab-pane fade" style="display:none;">
                            <table id='mosRanges' border='1' >
                            </table>
                        </div>
                    </div>




                    <div style="width:auto;height:auto">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td width="100%"><div id="map">
                                        <div id="customZoom"> <a href="#customZoomIn" id="customZoomIn">in</a> <a href="#customZoomOut" id="customZoomOut">out</a> </div>
                                        <div id="legendDiv" style="display:none;">
                                            <div>
                                                <table id='legend' style="display:none;">
                                                </table>
                                            </div>
                                        </div>
                                        <img id="loader" src="  images/ajax-loader.gif" />

                                        <div id="printedDate"></div>
                                    </div></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="widget" data-toggle="collapse-widget">
                        <div class="widget-head">
                            <h3 class="heading">District Info</h3>
                        </div>
                        <div class="widget-body">
                            <table border='1' class="infoTable">
                                <tr>
                                    <td class='bold'>Province</td>
                                    <td id='prov'></td>
                                </tr>
                                <tr>
                                    <td class='bold'>District</td>
                                    <td id='district'></td>
                                </tr>
                                <tr>
                                    <td class='bold'>Population (Male)</td>
                                    <td id='pop_m'></td>
                                </tr>

                                <tr>
                                    <td class='bold'>Population (Female)</td>
                                    <td id='pop_f'></td>
                                </tr>
                                <tr>
                                    <td class='bold'>Total Population</td>
                                    <td id='pop_t'></td>
                                </tr>
                                <tr>
                                    <td class='bold'>Area (KM)</td>
                                    <td id='area'></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><span class="bold">DHQ:</span> <span id='dhq'></span>

                                        <span class="bold">THQ:</span> <span id='thq'></span>
                                        <span class="bold">RHC:</span> <span id='rhc'></span>
                                        <span class="bold">BHU:</span> <span id='bhu'></span>
                                        <span class="bold">GD:</span> <span id='gd'></span>
                                        <span class="bold">Epi Center:</span> <span id='epi_center'></span></td>

                                </tr>
                                <tr>
                                    <td colspan="2"><span class="bold">LHW House:</span> <span id='lhw_house'></span>

                                        <span class="bold">RHS-A:</span> <span id='rhs_a'></span>
                                        <span class="bold">MSU:</span> <span id='msu'></span>
                                        <span class="bold">FWC:</span> <span id='fwc'></span>
                                        <span class="bold">MCH:</span> <span id='mch_centers'></span>
                                        <span class="bold">CMWs:</span> <span id='cmws'></span></td>

                                </tr>

                            </table>
                        </div>
                    </div>

                    <div class="widget" data-toggle="collapse-widget">
                        <div class="widget-head">
                            <h3 class="heading">Reporting Rate</h3>
                        </div>
                        <div class="widget-body" >
                            <div id='graph'>


                             Click any district for Reporting Rate

                            </div>
                         
                        </div>
                    </div>


                </div>

            </div>
            <hr/>

        </div>

    </div>
    <?php include "html/footer.php"; ?>
</body>
<!-- END BODY -->
<?php
$_SESSION['user_id'] = 1;


    $_SESSION[user_id] = 2054;
    $_SESSION[user_role] = 16;
    $_SESSION[user_name] = Guest;
    $_SESSION[user_warehouse] = 123;
    $_SESSION[user_stakeholder] = 1;
    $_SESSION[user_stakeholder_office] = 1;
   
    $_SESSION[user_province] = 10;
    $_SESSION[user_district] = 15;
    $_SESSION[is_allowed_im] = 0;
    
    $_SESSION[user_stakeholder_type] = 0;
    $_SESSION[user_province1] = 10;
    $_SESSION[user_stakeholder1] = 1;
    $_SESSION[landing_page] = "application/dashboard/dashboard.php";
    $_SESSION[menu] = "C:/xampp/htdocs/clmis/public/html/top.php";
   

    $_SESSION[im_open] = 0;




?>
</html>