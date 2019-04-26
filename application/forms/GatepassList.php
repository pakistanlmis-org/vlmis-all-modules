<?php

/**
 * Form_GatepassList
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Gatepass List
 * used for gatepasses
 */
class Form_GatepassList extends Form_Base {

    /**
     * Fields for Form_GatepassList
     * 
     * 
     * 
     * date_from
     * date_to
     * vehicle_type_id
     * item_pack_size_id
     * stock_batch_id
     * 
     * 
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "date_from" => "Date From",
        "date_to" => "Date To",
        "vehicle_type_id" => "Vehicle Type",
        "item_pack_size_id" => "Item",
        "stock_batch_id" => "Batch",
    );

    /**
     * Combo boxes for 
     * Form_GatepassList
     * 
     * 
     * vehicle_type_id
     * item_pack_size_id
     * stock_batch_id
     * 
     * 
     * $_list
     * @var type 
     */
    private $_list = array(
        'vehicle_type_id' => array(),
        'item_pack_size_id' => array(),
        'stock_batch_id' => array(),
    );

    /**
     * Initializes Form Fields
     * for Form_GatepassList
     */
    public function init() {
        //Generate Vehicle Type Combo
        // for Form_GatepassList
        $list = new Model_ListDetail();
        $list->form_values = array('listMaster' => Model_ListMaster::VEHICLE_TYPE);
        $result1 = $list->getListDetail();
        $this->_list["vehicle_type_id"][''] = "Select Vehicle Type";
        if ($result1) {
            foreach ($result1 as $row) {
                $this->_list["vehicle_type_id"][$row->getPkId()] = $row->getListValue();
            }
        }
        //Generate Item Combo
        // for Form_GatepassList
        $item_pack_size = new Model_ItemPackSizes();
        $result = $item_pack_size->getItemsAll();
        $this->_list["item_pack_size_id"][''] = "Select Product";
        if ($result) {
            foreach ($result as $row) {
                $this->_list["item_pack_size_id"][$row->getPkId()] = $row->getItemName();
            }
        }

        //Generate Batch Number Combo  
        // for Form_GatepassList
        $this->_list["stock_batch_id"][''] = "Select Item First";

        // Generate fields 
        // for Form_GatepassList
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "vehicle_type_id":
                case "item_pack_size_id":
                case "stock_batch_id":
                    parent::createText($col);
                    break;
                case "date_from":
                case "date_to":
                    parent::createReadOnlyText($col);
                    break;
                default:
                    break;
            }

            // Generate combo boxes 
            // for Form_GatepassList
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelectWithValidator($col, $name, $this->_list[$col]);
            }
        }
    }

}
