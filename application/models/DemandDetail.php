<?php

/**
 * Model_StockMaster
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Demand Detail
 */
class Model_DemandDetail extends Model_Base {

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('DemandDetail');
    }

    public function addDemandDetail($array) {
        $action = Zend_Registry::get("action");
        if ($array['rcvedit'] == "Yes") {
            $demand_detail = $this->_em->getRepository("DemandDetail")->find($array['demand_detail_id']);
        } else {
            $demand_detail = new DemandDetail();
        }
        $type = $array['transaction_type_id'];

        if (!empty($array['hdn_stock_id'])) {
            $stock_id = $array['hdn_stock_id'];
        } else {
            $stock_id = $array['stock_master_id'];
        }
        $demand_master_id = $this->_em->getRepository('DemandMaster')->find($stock_id);
        $demand_detail->setDemandMaster($demand_master_id);

        $product = $this->_em->getRepository("ItemPackSizes")->find($array['item_id']);
        $demand_detail->setProduct($product);
        $demand_detail->setPairProductId($array['usage']);
        if (!empty($array['item_unit_id'])) {
            $item_unit_id = $this->_em->getRepository('ItemUnits')->find($array['item_unit_id']);
            $demand_detail->setItemUnit($item_unit_id);
        }
        if ($array['type'] != 's') {
            $demand_detail->setDraft('1');
        }
        $quantity = str_replace(",", "", $array['quantity']);
        $demand_detail->setDemandQuantity($quantity);
        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
        $demand_detail->setModifiedBy($created_by);
        $demand_detail->setModifiedDate(App_Tools_Time::now());
        $demand_detail->setCreatedBy($created_by);
        $demand_detail->setCreatedDate(App_Tools_Time::now());
        $demand_detail->setIsDeleted('0');

        $this->_em->persist($demand_detail);
        $this->_em->flush();

        return $demand_detail->getPkId();
    }

    /*
     * Update demand detail temporary record.
     */
    public function updateDemandDetailTemp($id) {
        $row = $this->_em->getConnection()->prepare("UPDATE demand_detail SET `draft` = 0 WHERE demand_master_id = $id");
        $row->execute();
    }

    
    /**
     * Soft Delete requisition
     */
    public function deleteRequisition($id) {     
        $master_id = $this->deleteDemandDetail($id);      
        $stock_master = new Model_DemandMaster();
        $stock_master->deleteDemandMaster($master_id);      
        return $master_id;
    }
    
     /**
     * Soft Delete Demand Detail.
     */
    public function deleteDemandDetail($id) {
        $stockdetail = $this->_table->find($id);
        $master_id=$stockdetail->getDemandMaster()->getPkId();
        // Set is deleted flag to true.
        $stockdetail->setIsDeleted('1');
        $this->_em->persist($stockdetail);
        $this->_em->flush();
        return $master_id;
    }
}
