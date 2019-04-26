<?php

/**
 * Model_StockMaster
 *
 * Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 *  Model for Stock Master
 */
class Model_Alerts extends Model_Base {

    public $from_wh;
    public $to_wh;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->from_wh = '';
        $this->to_wh = '';
    }

    /**
     * sendEmail
     *
     * @param type $array
     * @return type
     */
    public function sendEmail($id, $type) {
        switch ($type) {
            case 1:
                $this->sendReceiveEmail($id);
                break;
            case 2:
                $this->sendIssueEmail($id);
                break;
            case 3:
                $this->sendAdjustmentEmail($id);
                break;
            case 4:
                $this->sendShipmentEmail($id);
                break;
        }
    }

    /**
     * sendSMS
     *
     * @param type $array
     * @return type
     */
    public function sendSMS($id, $type) {
        switch ($type) {
            case 1:
                $this->sendReceiveSMS($id);
                break;
            case 2:
                $this->sendIssueSMS($id);
                break;
            case 3:
                $this->sendAdjustmentSMS($id);
                break;
            case 4:
                $this->sendShipmentSMS($id);
                break;
        }
    }

    public function sendReceiveEmail($id) {
        $stock_master = new Model_StockMaster();
        $stock_master->form_values['pk_id'] = $id;
        $result = $stock_master->getStocksReceiveList();
        //$result = $stock_master->getStocksIssueList();
        $username = $this->_identity->getUserName();
        $warehousename = $this->_identity->getWarehouseName();
        $this->to_wh = $this->_identity->getWarehouseId();
        $print_title = "Stock Receive Voucher";
        $print_serial = strtotime(date("Y-m-d H:i:s"));

        ob_start();
        ?>
        <!-- Content -->
        <style>
            #content_print {
                margin-left: 50px;
                width: 624px;
            }
            table.mytable {
                border: 1px solid #444;
                font-size: 9pt;
                width: 100%;
            }
            table.mytable tr td {
                border: 1px solid #444;
            }
            table.mytable tr th {
                border: 1px solid #444;
            }
        </style>
        <!-- Content -->

        <style type="text/css" media="print">
            .page{
                -webkit-transform: rotate(-90deg); -moz-transform:rotate(-90deg);
                filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
            }
            @media print{
                #printButt{
                    display: none !important;
                }
            }
        </style>
        <?php
        foreach ($result as $row) {
            $transaction_number = $row['transactionNumber'];
            $transaction_date = $row['transactionDate'];
            $comments = $row['comments'];
            $purpose = $row['activity'];
            $this->from_wh = $row['fromWhId'];
            
        }
        ?>

        <div class="container-fluid fluid menu-left hide">
            <div id="content_print">
                <table width="100%" center>
                    <tr>
                        <td><div id="logoLeft" style="float:left; width:107px; margin-left: -8px;">
                                <img src="http://v.lmis.gov.pk/images/EPI_logo.png" width="48" />
                            </div></td>
                        <td><div id="report_type" style="float:left; width:350px; text-align:center; font-size: 16px;">
                                <b>EXPANDED PROGRAM ON IMMUNIZATION</b><br />
                                <span style="line-height:20px">Government of Pakistan</span><br/>
                                <span style="line-height:15px"><b>Store: </b><?php echo $warehousename; ?></span>
                                <div style="margin-top:30px;"> </div>
                                <!-- <p><b style="font-size:16px;"><?php //echo $this->print_title;                ?></b></p> -->
                            </div></td>
                        <td><div id="logoRight" style="float: right;">
                                <img src="http://v.lmis.gov.pk/images/gop.png" width="48" />
                            </div></td>
                    </tr>
                </table>                <div style="clear:both"></div>

                <div class="row">
                    <div class="col-md-12">
                        <table style="width:100%;">
                            <tr>
                                <td class="center" colspan="4"><b style="font-size: 16px;"><?php echo $print_title; ?></b></td>
                            </tr>
                            <tr>
                                <td class="right" colspan="4"><b style="font-size: 16px;">Receive Voucher # <span style="font-family: Verdana; "><?php echo $transaction_number; ?></span> </b></td>
                            </tr>
                            <tr>
                                <td><b>Received Date:</b> <?php echo date("d/m/Y", strtotime($transaction_date)); ?></td>
                                <?php if (!empty($purpose)) { ?>
                                    <td><b>Purpose:</b> <?php echo $purpose; ?></td>
                                <?php } ?>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="mytable" cellpadding='2'>
                            <tr >
                                <th rowspan="2" width="5%"><?php echo ("Sr No"); ?></th>
                                <th rowspan="2" align="left"><?php echo ("Product"); ?></th>
                                <th rowspan="2" align="left"><?php echo ("Manufacturer"); ?></th>
                                <th rowspan="2" align="left"><?php echo ("Batch No"); ?></th>

                                <th rowspan="2"><?php echo ("Expiry Date"); ?></th>
                                <th colspan="3" align="center"><?php echo ("Quantity"); ?></th>
                                <th rowspan="2"><?php echo ("VVM Stage"); ?></th>
                                <th rowspan="2"><?php echo ("Cost"); ?></th>
                            </tr>
                            <tr style="background-color: #F8F8F8;">
                                <th><?php echo ("Vials/Pcs"); ?></th>
                                <th><?php echo ("Doses Per Vial"); ?></th>
                                <th><?php echo ("Total Doses"); ?></th>
                            </tr>
                            <tbody>
                                <?php
                                $i = 0;
                                if (!empty($result)) {
                                    foreach ($result as $val) {
                                        //$batch[] = $val['number'];
                                        $i++;
                                        ?>
                                        <tr>
                                            <td align="center"><?php echo $i; ?></td>
                                            <td align="left" nowrap><?php echo $val['itemName']; ?></td>	
                                            <td align="left" nowrap><?php echo $val['manufacturer']; ?></td>	
                                            <td align="left"><?php echo $val['number']; ?></td>
                                            <td align="center"><?php echo date("d/m/Y", strtotime($val['expiryDate'])); ?></td>
                                            <td align="right"><?php echo number_format($val['quantity']); ?></td>
                                            <td align="center"><?php echo $val['description']; ?></td>
                                            <td align="right"><?php echo number_format($val['quantity'] * $val['description']); ?></td>
                                            <td align="center"><?php echo (!empty($val['vvmStage']) ? $val['vvmStage'] : 'NA'); ?></td>
                                            <td align="right">PKR <?php echo (!empty($val['unitCost']) ? number_format(round($val['unitCost'],2)) : '0'); ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>

                        </table>
                    </div>
                </div><br/>
                <?php if (!empty($comments)) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <b>Notes:</b><?php echo $comments; ?>
                        </div>
                    </div>
                <?php } ?>

                <div class="row" style="clear: both; padding-top: 50px;">
                </div>
                <div class="row" style="clear: both;">
                    <table width="100%">
                        <tr>
                            <td width="33%"><b>Email Date:</b> <?php echo date("d/m/Y"); ?></td>            
                            <td width="33%"><b>Serial Number:</b> <?php echo $print_serial; ?></td>
                        </tr>
                    </table>
                    <div class="col-md-12 left">
                        <i>This is an auto generated email</i>
                    </div>
                </div>
            </div>            
        </div>
        <?php
        $body = ob_get_contents();

        $to = $this->getToMails();
        $cc = $this->getCcMails();

        ob_end_flush();
        //App_Mail::smtpStart();
        $options = array(
            'to' => explode(",", $to),
            'cc' => explode(",", $cc),
            'subject' => 'Stock Receive Alert : ' . $warehousename,
            'body' => $body
        );
        App_Mail::send($options);
        //App_Mail::clear();
        //App_Mail::smtpClose();
    }

    public function sendIssueEmail($id) {

        $stock_master = new Model_StockMaster();
        $stock_master->form_values['pk_id'] = $id;
        $result = $stock_master->getStocksIssueList();
        $username = $this->_identity->getUserName();
        $warehousename = $this->_identity->getWarehouseName();
        $this->from_wh = $this->_identity->getWarehouseId();
        $print_title = "Stock Issue/Dispatch Voucher";
        $print_serial = strtotime(date("Y-m-d H:i:s"));

        ob_start();
        ?>
        <!-- Content -->
        <style>
            #content_print {
                margin-left: 55px;
                width: 624px;
            }
            table#mytable {
                border: 1px solid #444;
                font-size: 9pt;
                width: 100%;
            }
            table#mytable tr td {
                border: 1px solid #444;
            }
            table#mytable tr th {
                border: 1px solid #444;
            }
        </style>

        <style type="text/css" media="print">
            .page{
                -webkit-transform: rotate(-90deg); -moz-transform:rotate(-90deg);
                filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
            }
            @media print{    
                #printButt{
                    display: none !important;
                }
            }
        </style>
        <?php
        $row = $result[0];
        $transaction_number = $row['transactionNumber'];
        $transaction_date = $row['transactionDate'];
        $warehouse_name = $row['warehouseName'];
        $this->to_wh = $row['toWhId'];
        $comments = $row['comments'];
        $reference = $row['transactionReference'];
        $dispatch_by = $row['dispatchBy'];
        $master_id = $row['pkId'];
        $purpose = $row['activity'];
        ?>
        <div class="container-fluid fluid menu-left hide">
            <div id="content_print">
                <table width="100%" center>
                    <tr>
                        <td><div id="logoLeft" style="float:left; width:107px; margin-left: -8px;">
                                <img src="http://v.lmis.gov.pk/images/EPI_logo.png" width="48" />
                            </div></td>
                        <td><div id="report_type" style="float:left; width:350px; text-align:center; font-size: 16px;">
                                <b>EXPANDED PROGRAM ON IMMUNIZATION</b><br />
                                <span style="line-height:20px">Government of Pakistan</span><br/>
                                <span style="line-height:15px"><b>Store: </b><?php echo $warehousename; ?></span>
                                <div style="margin-top:30px;"> </div>
                                <!-- <p><b style="font-size:16px;"><?php //echo $this->print_title;                ?></b></p> -->
                            </div></td>
                        <td><div id="logoRight" style="float: right;">
                                <img src="http://v.lmis.gov.pk/images/gop.png" width="48" />
                            </div></td>
                    </tr>
                </table>

                <div style="clear:both"></div>

                <div class="row">
                    <div class="col-md-12">
                        <table width="100%">
                            <tr>
                                <td class="center" colspan="4"><b style="font-size: 16px;"><?php echo $print_title; ?></b></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="right"><b style="font-size: 16px;">Dispatch Voucher # <span style="font-family: Verdana; "><?php echo $transaction_number; ?></span> </b></td>
                            </tr>
                            <tr>
                                <td colspan="4"><b>Date of Dispatch:</b> <?php echo date("d/m/Y", strtotime($transaction_date)); ?></td>
                            </tr>
                            <tr>
                                <?php if (!empty($purpose)) { ?>
                                    <td><b>Purpose:</b> <?php echo $purpose; ?></td>
                                <?php } ?>
                                <td><b>Ref. Number:</b> <?php echo (!empty($reference)) ? $reference : 'N/A'; ?></td>
                                <td><b>Recipient:</b> <?php echo $warehouse_name; ?></td>
                                <td class="right"><b>Transport Mode:</b> <?php echo (!empty($dispatch_by)) ? $dispatch_by : 'N/A'; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10">
                        <table id="mytable" cellpadding="2">
                            <tr >
                                <th rowspan="2" width="5%"><?php echo ("Sr No"); ?></th>
                                <th rowspan="2"><?php echo ("Item"); ?></th>
                                <th rowspan="2"><?php echo ("Batch Number"); ?></th>
                                <th rowspan="2"><?php echo ("Manufacturer"); ?></th>
                                <th rowspan="2"><?php echo ("Expiry Date"); ?></th>

                                <th colspan="3" align="center"><?php echo ("Quantity"); ?></th>
                                <th rowspan="2"><?php echo ("VVM Stage"); ?></th>
                                <th rowspan="2"><?php echo ("Cost"); ?></th>
                            </tr>
                            <tr style="background-color: #F8F8F8;">
                                <th><?php echo ("Vials/Pcs"); ?></th>
                                <th><?php echo ("Doses Per Vials"); ?></th>
                                <th><?php echo ("Total Doses"); ?></th>
                            </tr>
                            <tbody>
                                <?php
                                $i = 0;
                                if (!empty($result)) {
                                    foreach ($result as $val) {
                                        $i++;
                                        ?>
                                        <tr>
                                            <td align="center"><?php echo $i; ?></td>
                                            <td align="left" nowrap><?php echo $val['itemName']; ?></td>	    
                                            <td align="left"><?php echo $val['number']; ?></td>
                                            <td align="center" nowrap><?php echo $val['stakeholderName']; ?></td>
                                            <td align="center"> <?php echo date("d/m/y", strtotime($val['expiryDate'])); ?></td>
                                            <td align="right"><?php echo number_format($val['quantity']); ?></td>
                                            <td align="center"><?php echo $val['numberOfDoses']; ?></td>
                                            <td align="right"><?php echo number_format($val['quantity'] * $val['numberOfDoses']); ?></td>
                                            <td align="center"><?php echo (!empty($val['vvmStage'])) ? $val['vvmStage'] : 'NA'; ?></td>
                                            <td align="center">PKR <?php echo (!empty($val['unitCost'])) ? number_format(round($val['unitCost'],2)) : '0'; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        <br />
                    </div>
                </div>
                <?php if (!empty($result[0]['issue_from'])) { ?>
                    <div class="row">
                        <div class="col-md-6">
                            <b>Issuance for the period of: </b>From <?php echo $result[0]['issue_from']; ?> To <?php echo $result[0]['issue_to']; ?>
                        </div>
                    </div> <?php } ?>
                <?php if (!empty($comments)) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <b>Notes:</b> <?php echo $comments; ?>
                        </div>
                    </div>
                <?php } ?>

                <div class="row" style="clear: both;">
                    <table width="100%">
                        <tr>
                            <td width="33%"><b>Print Date:</b> <?php echo date("d/m/Y"); ?></td>            
                            <td width="33%"><b>Serial Number:</b> <?php echo $print_serial; ?></td>
                        </tr>
                    </table>
                </div>
                <div class="row" style="clear: both;">
                    <div class="col-md-12 left">
                        <i>This is an auto generated email</i>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $body = ob_get_contents();
        $to = $this->getToMails();
        $cc = $this->getCcMails();
        
        ob_end_flush();
        //App_Mail::smtpStart();
        $options = array(
            'to' => explode(",", $to),
            'cc' => explode(",", $cc),
            'subject' => 'Stock Issue Alert : from ' . $warehousename . ' to ' . $warehouse_name,
            'body' => $body
        );

        App_Mail::send($options);
        //App_Mail::clear();
        //App_Mail::smtpClose();

        return true;
    }

    public function sendAdjustmentEmail($id) {
        
    }

    public function sendShipmentEmail($id) {
//        ob_start();
//        
        ?>

        //<?php
//        $body = ob_get_contents();
//        ob_end_flush();
//        App_Mail::smtpStart();
//        $options = array(
//            'to' => array('ahussain@ghsc-psm.org'),
//            'cc' => array('ajmaleyetii@gmail.com'),
//            'subject' => 'Add Shipment Alert : Federal EPI sotre',
//            'body' => $body
//        );
//        App_Mail::send($options);
//        App_Mail::clear();
//        App_Mail::smtpClose();
    }

    public function sendReceiveSMS($id) {
        $stock_master = new Model_StockMaster();
        $stock_master->form_values['pk_id'] = $id;
        $result = $stock_master->getStocksReceiveList();
        $this->to_wh = $this->_identity->getWarehouseId();
        $warehousename = $this->_identity->getWarehouseName();
        
        $message = "Following products are received in $warehousename \r\n";
        
        foreach ($result as $row){
            $array[$row['itemShortName']] += $row['quantity'];
            $units[$row['itemShortName']] = $row['itemUnitName'];
        }
        
        foreach($array as $prod=>$qty){
           $message .= "$prod:$qty ".$units[$prod].", ";
        }
        
        $message .= "\r\n[auto generated SMS]";
        
        $allnos = $this->getToSms();
        $array = explode(",", $allnos);
        foreach ($array as $to) {
            if (!empty($to)) {
                $options = array(
                    'to' => $to,
                    'message' => $message
                );
                App_Sms::send($options);
            }
        }
    }

    public function sendIssueSMS($id) {
        $stock_master = new Model_StockMaster();
        $stock_master->form_values['pk_id'] = $id;
        $result = $stock_master->getStocksIssueList();
        $username = $this->_identity->getUserName();
        $warehousename = $this->_identity->getWarehouseName();
        $this->from_wh = $this->_identity->getWarehouseId();
        $print_title = "Stock Issue/Dispatch Voucher";
        $print_serial = strtotime(date("Y-m-d H:i:s"));
        $warehousenameto = $result[0]['warehouseName'];
        
        $message = "Following products are issued from $warehousename to $warehousenameto \r\n";
        
        foreach ($result as $row){
            $array[$row['itemName']] += $row['quantity'];
            $units[$row['itemName']] = $row['itemUnitName'];
        }
        
        foreach($array as $prod=>$qty){
           $message .= "$prod:$qty ".$units[$prod].", ";
        }
        
        $message .= "\r\n[auto generated SMS]";
        
        $allnos = $this->getToSms();
        $array = explode(",", $allnos);
        foreach ($array as $to) {
            if (!empty($to)) {
                $options = array(
                    'to' => $to,
                    'message' => $message
                );
                App_Sms::send($options);
            }
        }
    }

    public function sendAdjustmentSMS($id) {
        
    }

    public function sendShipmentSMS($id) {
        
    }

    public function getToMails() {
        if (!empty($this->from_wh) && isset($this->from_wh)) {
            $whids[] = $this->from_wh;
        }
        if (!empty($this->to_wh) && isset($this->to_wh)) {
            $whids[] = $this->to_wh;
        }

        $str_qry = "SELECT
	GROUP_CONCAT(DISTINCT users.email) allemails
FROM
	warehouse_users
INNER JOIN users ON warehouse_users.user_id = users.pk_id
WHERE
	warehouse_users.warehouse_id IN (" . implode(",", $whids) . ")
AND users.role_id IN (3, 4, 5, 6, 7, 8)";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        $data = $row->fetchAll();
        if (count($data) > 0) {
            return $data[0]['allemails'];
        } else {
            return '';
        }
    }

    public function getCcMails() {
        $prov_id = $this->_identity->getProvinceId();

        $prov_arr = array();
        if(!empty($prov_id)){
            $prov_arr[] = $prov_id;
        }
        $prov_arr[] = 100;
        
        $arr_pro = implode(",", $prov_arr);
        
        $str_qry = "SELECT
	GROUP_CONCAT(DISTINCT alerts.email_address) allemail
FROM
	alerts
WHERE
	alerts.prov_id IN ($arr_pro)";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        $data = $row->fetchAll();
        if (count($data) > 0) {
            return $data[0]['allemail'];
        } else {
            return '';
        }
    }

    public function getToSms() {
        if (!empty($this->from_wh) && isset($this->from_wh)) {
            $whids[] = $this->from_wh;
        }
        if (!empty($this->to_wh) && isset($this->to_wh)) {
            $whids[] = $this->to_wh;
        }
        $prov_id = $this->_identity->getProvinceId();

        $str_qry = "SELECT
	CONCAT(
		A.allnumbers,
		',',
		B.allnumber
	) allnos
FROM
	(
		SELECT
			GROUP_CONCAT(DISTINCT users.cell_number) allnumbers
		FROM
			warehouse_users
		INNER JOIN users ON warehouse_users.user_id = users.pk_id
		WHERE
			warehouse_users.warehouse_id IN (" . implode(",", $whids) . ")
		AND users.role_id IN (3, 4, 5, 6, 7, 8)
	) A
JOIN (
	SELECT
		GROUP_CONCAT(DISTINCT alerts.cell_number) allnumber
	FROM
		alerts
	WHERE
		alerts.prov_id IN ($prov_id, 100)
) B";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        $data = $row->fetchAll();
        if (count($data) > 0) {
            return $data[0]['allnos'];
        } else {
            return '';
        }
    }

}
