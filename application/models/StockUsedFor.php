<?php

/**
 * Model_StockBatch
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Stock Batch
 */
class Model_StockUsedFor extends Model_Base {

    /**
     * $_table
     * @var type 
     */
    protected $_table;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('StockUsedFor');
    }

    /**
     * Add Stock Used For
     */
    public function addStockUsedFor() {
        $data = $this->form_values;

        $obj = new StockUsedFor();
        $itemPairLogic = $this->_em->getRepository("ItemPairsLogic")->findOneBy(array("item" => $data['item_id'], "itemPair" => $data['used_for']));
        $obj->setItemPairLogic($itemPairLogic);
        $quantity = str_replace(",", "", $data['quantity']);
        if ($data['transaction_type_id'] == Model_TransactionTypes::TRANSACTION_ISSUE) {
            $quantity = "-" . $quantity;
        }
        $obj->setQuantity($quantity);
        $stock_detail = $this->_em->getRepository("StockDetail")->find($data['detail_id']);
        $obj->setStockDetail($stock_detail);
        $transaction_type = $this->_em->getRepository("TransactionTypes")->find($data['transaction_type_id']);
        $obj->setTransactionType($transaction_type);
        $user = $this->_em->getRepository("Users")->find($this->_user_id);
        $obj->setCreatedBy($user);
        $obj->setModifiedBy($user);
        $obj->setCreatedDate(App_Tools_Time::now());
        $obj->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($obj);
        $this->_em->flush();
    }

}
