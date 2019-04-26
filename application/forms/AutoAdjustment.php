<?php

/**
 *  php for Auto Adjustment
 */
?>

<?php

/**
 * Form_AutoAdjustment
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */
class Form_AutoAdjustment extends Form_Base {

    /**
     * Form Fields
     * 
     * @adjustment_date: Adjustment Date
     * @adjustment_type: Adjustment Type
     * @ref_no: Reference Number
     * @comments: Comments
     * @transaction_date: Issue Date
     * @reference_number: Reference No.
     * @stakeholder_activity_id: Purpose
     * @description: Description
     * @catalogue_id_popup: Catalogue id
     * 
     * @var type
     *  
     */
    private $_fields = array(
        "adjustment_date" => "Adjustment Date",
        "adjustment_type" => "Adjustment Type",
        "ref_no" => "Reference Number",
        "comments" => "Comments",
        "transaction_date" => "Issue Date",
        "reference_number" => "Reference No.",
        "stakeholder_activity_id" => "Purpose",
        "description" => "Description"
    );

    /**
     * $_list
     * @var type 
     * stakeholder_activity_id
     */
    private $_list = array(
        'stakeholder_activity_id' => array()
    );

    /**
     * $_childlist
     * @var type 
     * item_id
     * vvm_stage
     * number
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
     */
    public function init() {

        /**
         * Generate Adjustment Type Combo
         */
        $transaction_types = new Model_TransactionTypes();
        /**
         * Adjustment Types
         * @param null
         * @return Adjustment Types
         */
        $result = $transaction_types->getAdjusmentTypes();

        foreach ($result as $trans) {
            $this->_list["adjustment_type"][''] = 'Select';
            $this->_list["adjustment_type"][$trans['pkId']] = $trans['transactionTypeName'];
        }
        /**
         * Generate Item Combo
         */
        $item_pack_size = new Model_ItemPackSizes();
        /**
         * ALL Items
         * @param null
         * @return All Items
         */
        $result = $item_pack_size->getItemsAll();
        $this->_childlist["item_id"][''] = "Select";
        if ($result) {
            foreach ($result as $row) {
                $this->_childlist["item_id"][$row->getPkId()] = $row->getItemName();
            }
        }
        /**
         * Generate Purpose(activity_id) combo 
         */
        $stk_activities = new Model_StakeholderActivities();
        /**
         * ALL Stakeholder Activities
         * @param null
         * @return All Items
         */
        $result4 = $stk_activities->getAllStakeholderActivitiesIssues();
        if ($result4) {
            foreach ($result4 as $stk_activity) {
                $this->_list["stakeholder_activity_id"][$stk_activity['pkId']] = $stk_activity['activity'];
            }
        }
        /**
         * Generate Text Boxes
         */
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                /**
                 * adjustment_date
                 * transaction_date
                 */
                case "adjustment_date":
                case "transaction_date":
                    parent::createReadOnlyText($col);
                    break;
                /**
                 * ref_no
                 * transaction_reference
                 * comments
                 * reference_number
                 * description
                 */
                case "ref_no":
                case "transaction_reference":
                case "comments":
                case "reference_number":
                case "description":
                    parent::createText($col);
                    break;
                default:
                    break;
            }
            /**
             * Generate Select Boxes
             */
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelectWithValidator($col, $name, $this->_list[$col]);
            }
        }
    }

    /**
     * Add Hidden Fields
     * @access public
     * @default: null
     * @return void
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
                    /**
                     * item_id
                     */
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
                    /**
                     * number
                     */
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
                    /**
                     * Default
                     */
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
            /**
             * Readonly Fields
             * @Average Qty
             */
            $rows->addElement("text", "ava_qty", array(
                "attribs" => array("class" => "form-control", "readonly" => "readonly"),
                "allowEmpty" => false,
                "filters" => array("StringTrim", "StripTags"),
                "validators" => array(),
                "belongsTo" => "rows$i"
            ));
            /**
             * @Expiry Date
             */
            $rows->addElement("text", "expiry_date", array(
                "attribs" => array("class" => "form-control", "readonly" => "readonly"),
                "allowEmpty" => false,
                "filters" => array("StringTrim", "StripTags"),
                "validators" => array(),
                "belongsTo" => "rows$i"
            ));
            /**
             * @Quantity
             */
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
