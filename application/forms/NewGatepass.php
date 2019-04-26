<?php

/**
 * Form_NewGatepass
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for New Gatepass
 */
class Form_NewGatepass extends Form_Base {

    /**
     * 
     * $_fields
     * 
     * Form Fields
     * for Form_NewGatepass
     * 
     * 
     * @date_from: Date From
     * @date_to: Date To
     * @vehicle_type_id: Vehicle Type
     * @gatepass_vehicle_id: Vehicle
     * @vehicle_other: Vehicle Number
     * @other: Other
     * @stock_master_id: Issue No
     * @transaction_date: Date
     * @quantity: Quantity
     * 
     * @var type 
     */
    private $_fields = array(
        "date_from" => "Date From",
        "date_to" => "Date To",
        "vehicle_type_id" => "Vehicle Type",
        "gatepass_vehicle_id" => "Vehicle",
        "vehicle_other" => "Vehicle Number",
        "other" => "Other",
        "stock_master_id" => "Issue No",
        "transaction_date" => "Date",
        "quantity" => "Quantity",
    );

    /**
     * Combo boxes
     * for Form_NewGatepass
     * 
     * 
     * vehicle_type_id
     * gatepass_vehicle_id
     * stock_master_id
     * 
     * 
     * $_list
     * @var type 
     */
    private $_list = array(
        'vehicle_type_id' => array(),
        'gatepass_vehicle_id' => array(),
        'stock_master_id' => array(),
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        //Generate Vehicle Type Combo
        $list = new Model_ListDetail();
        $list->form_values = array('listMaster' => Model_ListMaster::VEHICLE_TYPE);
        $result1 = $list->getListDetail();
        $this->_list["vehicle_type_id"][''] = "Select Vehicle Type";
        if ($result1) {
            foreach ($result1 as $row) {
                $this->_list["vehicle_type_id"][$row->getPkId()] = $row->getListValue();
            }
        }

        $this->_list["number"][''] = "Select Vehicle Type First";

        // Generate Form fields
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "vehicle_type_id":
                case "gatepass_vehicle_id":
                case "vehicle_other":
                case "quantity":
                case "transaction_number":
                    parent::createText($col);
                    break;
                case "stock_master_id":
                    parent::createTextWitMultiple($col);
                    break;
                case "date_from":
                case "date_to":
                case "transaction_date":
                    parent::createReadOnlyText($col);
                    break;
                case "other":
                    parent::createCheckbox1($col);
                    break;
                default:
                    break;
            }

            // Generate combo boxes
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelectWithValidator($col, $name, $this->_list[$col]);
            }
        }
    }

    /**
     * Read Fields
     */
    public function readFields() {
        $this->getElement('vehicle_type_id')->setAttrib("disabled", "true");
        $this->getElement('gatepass_vehicle_id')->setAttrib("disabled", "true");
        $this->getElement('transaction_date')->setAttrib("disabled", "true");
        $this->getElement('vehicle_other')->setAttrib("disabled", "true");
    }

}
