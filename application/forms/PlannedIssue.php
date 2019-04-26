<?php

/**
 * Form_PlannedIssue
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Planned Issue
 */
class Form_PlannedIssue extends Form_Base {

    /**
     * Fields
     * for Form_PlannedIssue
     * 
     * Form Fields
     * transaction_date
     * reference_number
     * stakeholder_activity_id
     * description
     * 
     * 
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "transaction_date" => "Issue Date",
        "reference_number" => "Reference No.",
        "stakeholder_activity_id" => "Purpose",
        "description" => "Description"
    );

    /**
     * Combo boxes
     * for Form_PlannedIssue
     * 
     * 
     * stakeholder_activity_id
     * 
     * $_list
     * @var type 
     */
    private $_list = array(
        'stakeholder_activity_id' => array()
    );

    /**
     * Child lists
     * for Form_PlannedIssue
     * number
     * 
     * 
     * 
     * item_id
     * vvm_stage
     * 
     * $_childlist
     * @var type 
     */
    private $_childlist = array(
        'item_id' => array(),
        'vvm_stage' => array(
            '' => 'NA',
            '1' => '1 (Usable)',
            '2' => '2 (Usable)',
            '3' => '3 (Not Usable)',
            '4' => '4 (Not Usable)'
        ),
        'number' => array("Select")
    );

    /**
     * Initializes Form Fields
     * for Form_PlannedIssue
     */
    public function init() {

        //Generate Item Combo
        //for Form_PlannedIssue
        $item_pack_size = new Model_ItemPackSizes();
        $result = $item_pack_size->getItemsAll();
        $this->_childlist["item_id"][''] = "Select";
        if ($result) {
            foreach ($result as $row) {
                $this->_childlist["item_id"][$row->getPkId()] = $row->getItemName();
            }
        }

        //Generate Purpose(activity_id) combo 
        //for Form_PlannedIssue
        $stk_activities = new Model_StakeholderActivities();
        $result4 = $stk_activities->getAllStakeholderActivitiesIssues();
        if ($result4) {
            foreach ($result4 as $stk_activity) {
                $this->_list["stakeholder_activity_id"][$stk_activity['pkId']] = $stk_activity['activity'];
            }
        }

        // Generating Form Fields
        //for Form_PlannedIssue
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "reference_number":
                case "description":
                    parent::createText($col);
                    break;
                case "transaction_date":
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
     * for Form_PlannedIssue
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
    
    // Add additional rows
    // for Form_PlannedIssue
    public function addRows($start, $end) {

        for ($i = $start; $i < $end; $i++) {
            $rows = new Zend_Form_SubForm();
            $rows->setIsArray(true);
            $rows->setOrder($i);

            //Generating Child lists
            //for Form_PlannedIssue
            foreach ($this->_childlist as $col => $name) {
                switch ($col) {
                    case "item_id":
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
                    case "number":
                        $rows->addElement("select", $col, array(
                            "attribs" => array("class" => "form-control batches"),
                            "filters" => array("StringTrim", "StripTags"),
                            "allowEmpty" => true,
                            "required" => false,
                            "registerInArrayValidator" => false,
                            "multiOptions" => $this->_childlist[$col],
                            "validators" => array(),
                            "belongsTo" => "rows$i"
                        ));
                        break;
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

            $rows->addElement("text", "ava_qty", array(
                "attribs" => array("class" => "form-control", "readonly" => "readonly"),
                "allowEmpty" => false,
                "filters" => array("StringTrim", "StripTags"),
                "validators" => array(),
                "belongsTo" => "rows$i"
            ));
            $rows->addElement("text", "expiry_date", array(
                "attribs" => array("class" => "form-control", "readonly" => "readonly"),
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
     * Populate Batches
     * for Form_PlannedIssue
     * 
     * 
     * @param type $item_id
     * @param type $rows
     */
    public function populateBatches($item_id, $rows) {

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
