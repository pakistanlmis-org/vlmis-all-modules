<?php

/**
 * Form_PipelineConsignments
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Pipeline Consignments
 * 
 * Inherits:
 * Form_Base
 */
class Form_PipelineConsignments extends Form_Base {

    /**
     * Field 
     * for Form_PipelineConsignments
     * 
     * Private Variable
     * 
     * Form Fields
     * from_warehouse_id
     * expected_arrival_date
     * reference_number
     * stakeholder_activity_id
     * description
     * status
     * 
     * 
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "from_warehouse_id" => "Source",
        "expected_arrival_date" => "Expected Arrival",
        "reference_number" => "Reference No.",
        "stakeholder_activity_id" => "Purpose",
        "description" => "Description",
        "status" => "Status"
    );

    /**
     * Combo boxes
     * for Form_PipelineConsignments
     * 
     * Private Variable
     * 
     * List
     * from_warehouse_id
     * stakeholder_activity_id
     * status
     * 
     * 
     * $_list
     * @var type 
     */
    private $_list = array(
        'from_warehouse_id' => array(),
        'stakeholder_activity_id' => array(),
        'status' => array('Planned' => 'Planned', 'Receiving' => 'Receiving', 'Received' => 'Received')
    );

    /**
     * Child lists
     * for Form_PipelineConsignments
     * 
     * 
     * item_pack_size_id
     * manufacturer_id
     * vvm_type_id
     * 
     * 
     * $_childlist
     * @var type 
     */
    private $_childlist = array(
        'item_pack_size_id' => array(),
        'manufacturer_id' => array("Select"),
        'vvm_type_id' => array()
    );

    /**
     * Initializes Form Fields
     * for Form_PipelineConsignments
     */
    public function init() {
        //Generate WareHouses Combo
        $warehouse = new Model_Warehouses();
        $result1 = $warehouse->getSupplierWarehouses();
        foreach ($result1 as $wh) {
            $this->_list["from_warehouse_id"][$wh['pk_id']] = $wh['warehouse_name'];
        }

        //Generate Item Combo
        $item_pack_size = new Model_ItemPackSizes();
        $result = $item_pack_size->getItemsAll();
        $this->_childlist["item_pack_size_id"][''] = "Select";
        if ($result) {
            foreach ($result as $row) {
                $this->_childlist["item_pack_size_id"][$row->getPkId()] = $row->getItemName();
            }
        }

        //Generate VVM Type Combo
        $vvmtypes = new Model_VvmTypes();
        $result3 = $vvmtypes->getAll();
        $this->_childlist["vvm_type_id"][''] = 'Select';
        foreach ($result3 as $vvmtype) {
            $this->_childlist["vvm_type_id"][$vvmtype['pk_id']] = $vvmtype['vvm_type_name'];
        }

        //Generate Purpose(activity_id) combo 
        $stk_activities = new Model_StakeholderActivities();
        $result4 = $stk_activities->getAllStakeholderActivitiesIssues();
        if ($result4) {
            foreach ($result4 as $stk_activity) {
                $this->_list["stakeholder_activity_id"][$stk_activity['pkId']] = $stk_activity['activity'];
            }
        }

        // Generating fields
        // for Form_PipelineConsignments
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "reference_number":
                case "description":
                    parent::createText($col);
                    break;
                case "expected_arrival_date":
                case "transaction_number":
                    parent::createReadOnlyText($col);
                    break;
                default:
                    break;
            }

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelectWithValidator($col, $name, $this->_list[$col]);
            }
        }
    }

    /**
     * Add Hidden Fields
     * for Form_PipelineConsignments
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

    /**
     * Adds extra rows to the form
     *
     * @access public
     * @param mixed $data. (default: null)
     * @return void
     */
    public function addRows($start, $end) {

        for ($i = $start; $i < $end; $i++) {
            $rows = new Zend_Form_SubForm();
            $rows->setIsArray(true);
            $rows->setOrder($i);

            foreach ($this->_childlist as $col => $name) {
                switch ($col) {
                    //if item_pack_size_id:
                    //adds elect element
                    //with form-control products class
                    case "item_pack_size_id":
                        $rows->addElement("select", $col, array(
                            "attribs" => array("class" => "form-control products"),
                            "filters" => array("StringTrim", "StripTags"),
                            "allowEmpty" => true,
                            "required" => false,
                            "registerInArrayValidator" => false,
                            "multiOptions" => $this->_childlist[$col],
                            "validators" => array(),
                            "belongsTo" => "rows$i"
                        ));
                        break;
                    //if manufacturer_id:
                    //add select element
                    //with form-control manufaturers class
                    case "manufacturer_id":
                        $rows->addElement("select", $col, array(
                            "attribs" => array("class" => "form-control manufaturers"),
                            "filters" => array("StringTrim", "StripTags"),
                            "allowEmpty" => true,
                            "required" => false,
                            "registerInArrayValidator" => false,
                            "multiOptions" => $this->_childlist[$col],
                            "validators" => array(),
                            "belongsTo" => "rows$i"
                        ));
                        break;
                    //default case:
                    //add select element
                    //with form-control class
                    default:
                        $rows->addElement("select", $col, array(
                            "attribs" => array("class" => "form-control"),
                            "filters" => array("StringTrim", "StripTags"),
                            "allowEmpty" => true,
                            "required" => false,
                            "registerInArrayValidator" => false,
                            "multiOptions" => $this->_childlist[$col],
                            "validators" => array(),
                            "belongsTo" => "rows$i"
                        ));
                        break;
                }
            }

            $rows->addElement("text", "batch_number", array(
                "attribs" => array("class" => "form-control number"),
                "allowEmpty" => false,
                "filters" => array("StringTrim", "StripTags"),
                "validators" => array(),
                "belongsTo" => "rows$i"
            ));

            $rows->addElement("text", "production_date", array(
                "attribs" => array("class" => "form-control", 'readonly' => 'true'),
                "allowEmpty" => false,
                "filters" => array("StringTrim", "StripTags"),
                "validators" => array(),
                "belongsTo" => "rows$i"
            ));

            $rows->addElement("text", "expiry_date", array(
                "attribs" => array("class" => "form-control", 'readonly' => 'true'),
                "allowEmpty" => false,
                "filters" => array("StringTrim", "StripTags"),
                "validators" => array(),
                "belongsTo" => "rows$i"
            ));

            $rows->addElement("text", "unit_price", array(
                "attribs" => array("class" => "form-control"),
                "allowEmpty" => false,
                "filters" => array("StringTrim", "StripTags"),
                "validators" => array(),
                "belongsTo" => "rows$i"
            ));

            $rows->addElement("text", "quantity", array(
                "attribs" => array("class" => "form-control"),
                "allowEmpty" => false,
                "filters" => array("StringTrim", "StripTags"),
                "validators" => array(),
                "belongsTo" => "rows$i"
            ));

            foreach ($rows->getElements() as $element) {
                $element->removeDecorator("Label");
                $element->removeDecorator("HtmlTag");
            }

            $this->addSubForm($rows, "rows$i");
        }
    }

    /**
     * Populate Manufacturer
     * 
     * for Form_PipelineConsignments
     * 
     * @param type $item_id
     * @param type $rows
     */
    public function populateManufacturer($item_id, $rows) {

        $manufacturer = array();
        $stakeholder_items = new Model_Stakeholders();
        $stakeholder_items->form_values['item_id'] = $item_id;
        $associated = $stakeholder_items->getManufacturerByProduct();
        if ($associated) {
            foreach ($associated as $row) {
                $manufacturer[$row['pkId']] = $row['stakeholderName'];
            }
        }

        $this->$rows->manufacturer_id->setMultiOptions($manufacturer);
    }

}
