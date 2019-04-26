<?php
// Include configuration file
require_once("../../application/includes/classes/Configuration.inc.php");
//include header
include(PUBLIC_PATH . "html/header.php");
?>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content" onLoad="doInitGrid()">
<!-- BEGIN HEADER -->
<div class="page-container">
    <?php
        //include top_im
        include PUBLIC_PATH . "html/top_im.php";
        ?>
    <div class="page-content-wrapper">
        <div class="page-content" style="margin-left:0px !important;">
            <div class="row">
                <div class="col-md-12"> 
                    <!-- BEGIN ALERTS PORTLET-->
                    <div class="portlet yellow box">
                        <div class="portlet-title">
                            <div class="caption"> <i class="fa fa-cogs"></i>Indicator Definitions </div>
                        </div>
                        <div class="portlet-body">
                            <div class="note note-success">
                                <h4 class="block">Consumption</h4>
                                <p> It is the number (quantity) of contraceptives dispensed / issued to the clients/users at the facility level. However, in case facility level issuance data is not available issuance of contraceptives to facilities by district store can be considered as proxy for consumption </p>
                            </div>
                            <div class="note note-warning">
                                <h4 class="block">Average Monthly Consumption</h4>
                                <p> It is the average aggregated consumption (of a contraceptive) of the last three non-zero consumption months </p>
                            </div>
                            <div class="note note-success">
                                <h4 class="block">Stock on Hand - National Level</h4>
                                <p> It is the sum of the quantity of usable stock available in facilities, district stores, provincial stores and national store at a given time</p>
                            </div>
                            <div class="note note-warning">
                                <h4 class="block">Stock on Hand - Provincial Level</h4>
                                <p> It is the sum of the quantity of usable stock available in facilities, district stores and provincial store at a given time</p>
                            </div>
                            <div class="note note-success">
                                <h4 class="block">Stock on Hand - District Level</h4>
                                <p> It is the sum of the quantity of usable stock available in facilities and district store at a given time</p>
                            </div>
                            <div class="note note-warning">
                                <h4 class="block">Stock on Hand - Field Level</h4>
                                <p> It is the quantity of usable stock available at facility level in a district at a given time</p>
                            </div>
                            <div class="note note-success">
                                <h4 class="block">Stock on Hand - Store</h4>
                                <p> It is the quantity of usable stock available in a store at a given time</p>
                            </div>
                            <div class="note note-warning">
                                <h4 class="block">Months of Stock - National Level</h4>
                                <p> It is the number of months, the available stock (stock on hand) at a given time at national level will last or will be sufficient for. It can be calculated by dividing the available quantity (stock on hand) at national level by AMC at national level.<br />
									MOS =  SOH (Stock on Hand) / AMC (Average Monthly Consumption)</p>
                            </div>
                            <div class="note note-success">
                                <h4 class="block">Months of Stock - Provincial Level</h4>
                                <p> It is the number of months, the available stock (stock on hand) at a given time at provincial level will last or will be sufficient for. It can be calculated by dividing the available quantity (stock on hand) at a provincial level by AMC at provincial level.<br />
									MOS =  SOH (Stock on Hand) / AMC (Average Monthly Consumption)</p>
                            </div>
                            <div class="note note-warning">
                                <h4 class="block">Months of Stock - District Level</h4>
                                <p> It is the number of months, the available stock (stock on hand) at a given time at district level will last or will be sufficient for. It can be calculated by dividing the available quantity (stock on hand) at a district level by AMC at district level.<br />
									MOS =  SOH (Stock on Hand) / AMC (Average Monthly Consumption)</p>
                            </div>
                            <div class="note note-success">
                                <h4 class="block">Months of Stock - Field Level</h4>
                                <p> It is the number of months, the available stock (stock on hand) at a given time in facility level stores will last or will be sufficient for. It can be calculated by dividing the available quantity (stock on hand) in facility level stores by AMC of that store.<br />
									MOS =  SOH (Stock on Hand) / AMC (Average Monthly Consumption)</p>
                            </div>
                            <div class="note note-warning">
                                <h4 class="block">Months of Stock – Store</h4>
                                <p> It is the number of months, the available stock (stock on hand) at a given time in a store will last or will be sufficient for. It can be calculated by dividing the available quantity (stock on hand) in a store by AMC of that store.<br />
									MOS =  SOH (Stock on Hand) / AMC (Average Monthly Consumption)</p>
                            </div>
                            <div class="note note-success">
                                <h4 class="block">Couple Year Protection</h4>
                                <p> The term Couple Year Protection (CYP) is used to estimate the quantity or the number of a specific type of contraceptive required to protect a couple from contraception / pregnancy for one year</p>
                            </div>
                            <div class="note note-warning">
                                <h4 class="block">Reporting Rate (in percentage)</h4>
                                <p> It is the percentage of stores / SDPs reported in a given time period</p>
                            </div>
                            <div class="note note-success">
                                <h4 class="block">Stock Issued</h4>
                                <p> It is the number (quantity) of contraceptives given to a store / health facility</p>
                            </div>
                            <div class="note note-warning">
                                <h4 class="block">Stock Received</h4>
                                <p> It is the number (quantity) of contraceptives received from a store</p>
                            </div>
                        </div>
                    </div>
                    <!-- END ALERTS PORTLET--> 
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<!-- END BODY -->
</html>