<?php

/**
 * Model_StockTicking
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Stock Ticking
 */

class Model_StockTicking extends Model_Base {

    /**
     * $_table
     * @var type 
     */
    protected $_table;


    /**
     * Upload Stock Ticking
     * 
     * @return type
     */
    public function uploadStockTicking() {
        $params = $this->form_values;
        $str_qry = "INSERT INTO stock_ticking(ScanningDate,ScannerID,WarehouseID,ProductID,BatchNo,ExpiryDate,GTIN,SerialNumber,Barcode,LocationID,LocationName,Qty,VVM,InnerLocation) "
                . "VALUES ('" . $params['scanning_date'] . "','" . $params['scanner_id'] . "','" . $params['warehouse_id'] . "','" . $params['product_id'] . "',"
                . "'" . $params['batch_no'] . "','" . $params['expiry_date'] . "','" . $params['gtin'] . "','" . $params['serial_no'] . "','" . $params['barcode'] . "','" . $params['location_id'] . "','" . $params['location_name'] . "','" . $params['qty'] . "','" . $params['vvm'] . "','" . $params['inner_location'] . "')";
        $rec = $this->_em_read->getConnection()->prepare($str_qry);
        $rec->execute();
        return array("success" => "Data has been added successfully");
    }

}
