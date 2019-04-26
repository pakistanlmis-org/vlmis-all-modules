<?php

/**
 * Form_AddStockIssue
 *
 * Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Add Stock Issue
 */
class Form_AddStockIssue extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * Form Fields
     * 
     * @status: "Status"
     * 
     * @transaction_date: "Transaction Date"
     * 
     * @comments: "Comments"
     * 
     * @transaction_reference: "Transaction Reference"
     * 
     * @hdn_available_quantity: "hdn_available_quantity"
     * 
     * @hdn_vvm_stage: "hdn_vvm_stage"
     * 
     * @var type 
     */
    private $_fields = array(
        "status" => "Status",
        "transaction_date" => "Transaction Date",
        "comments" => "Comments",
        "transaction_reference" => "Transaction Reference",
        "hdn_available_quantity" => "hdn_available_quantity",
        "hdn_vvm_stage" => "hdn_vvm_stage"
    );

    /**
     * $_hidden
     * 
     * Private Variable
     * 
     * Hidden
     * 
     * @id: "ID"
     * 
     * @hdn_activity_id: ""
     * 
     * @hdn_receive_warehouse_id: ""
     * 
     * @hdn_stock_master_id: ""
     * 
     * @var type 
     */
    private $_hidden = array(
        "id" => "ID",
        "hdn_activity_id" => "",
        "hdn_receive_warehouse_id" => "",
        "hdn_stock_master_id" => "",
    );

    /**
     * $_list
     * 
     * Private Variable
     * 
     * List
     * 
     * @var type 
     */
    private $_list = array(
    );

    /**
     * $_childlist
     * 
     * item_pack_size_id: array()
     * 
     * number: array()
     * 
     * @var type 
     */
    private $_childlist = array(
        'item_pack_size_id' => array(),
        'number' => array("Select")
    );

    /**
     * init
     * 
     * Initializes Form Fields
     */
    public function init() {
        //Generate Item Combo
        $sips = new Model_StakeholderItemPackSizes();
        $sips->form_values['stakeholder_id'] = '1';

        $result = $sips->getAllProductsByStakeholderType();

        $this->_childlist["item_pack_size_id"][''] = "Select";

        foreach ($result as $row) {
            $this->_childlist["item_pack_size_id"][$row['item_pack_size_id']] = $row['item_name'];
        }

        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                /**
                 * 
                 * Form Text Fields for 
                 * 
                 * transaction_reference
                 * 
                 */
                case "transaction_reference":
                    parent::createText($col);
                    break;
                /**
                 * 
                 * Form Text Fields for 
                 * 
                 * transaction_number
                 * 
                 */
                case "transaction_number":
                /**
                 * 
                 * Form Text Fields for 
                 * 
                 * transaction_date
                 * 
                 */
                case "transaction_date":
                    parent::createReadOnlyText($col);
                    break;
                /**
                 * 
                 * Form Text Fields for 
                 * 
                 * comments
                 * 
                 */
                case "comments":
                    parent::createMultiLineText($col, 2);
                default:
                    break;
            }

            /**
             * 
             * Form Select Fields for
             * 
             */
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelectWithValidator($col, $name, $this->_list[$col]);
            }
        }
        /**
         * 
         * Form Hidden Fields for loop
         * 
         */
        foreach ($this->_hidden as $col => $name) {
            switch ($col) {
                /**
                 * 
                 * Form Hidden Fields for 
                 * 
                 * hdn_activity_id
                 * 
                 * hdn_receive_warehouse_id
                 * 
                 * hdn_stock_master_id
                 * 
                 */
                case "hdn_activity_id":
                case "hdn_receive_warehouse_id":
                case "hdn_stock_master_id":
                    parent::createHidden($col);
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * addHidden
     * 
     * Add Hidden Fields
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
                    case "number":
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

            $rows->addElement("text", "expiry_date", array(
                "attribs" => array("class" => "form-control", 'readonly' => 'true'),
                "allowEmpty" => false,
                "filters" => array("StringTrim", "StripTags"),
                "validators" => array(),
                "belongsTo" => "rows$i"
            ));
            $rows->addElement("hidden", "hdn_vvm_stage", array(
                "attribs" => array("class" => "form-control", 'readonly' => 'true'),
                "allowEmpty" => false,
                "filters" => array("StringTrim", "StripTags"),
                "validators" => array(),
                "belongsTo" => "rows$i"
            ));
            $rows->addElement("hidden", "hdn_available_quantity", array(
                "attribs" => array("class" => "form-control", 'readonly' => 'true'),
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
     * @param type $item_id
     * @param type $rows
     */
    public function populateManufacturer($item_id, $rows) {

        $batch = array();
        $stock_batch = new Model_StockBatch();
        $stock_batch->form_values['item_id'] = $item_id;
        $stock_batch->form_values['item_pack_size_id'] = $item_id;
        $stock_batch->form_values['transaction_date'] = $this->_request->transaction_date;

        $itm = $this->_em->getRepository("ItemPackSizes")->find($this->_request->item_id);

        if ($itm->getItemCategory()->getPkId() == 1 || $itm->getItemCategory()->getPkId() == 4) {
            $associated = $stock_batch->getAllPriorityBatches();
        } else {
            $associated = $stock_batch->getAllRunningBatches();
        }

        if ($associated) {
            foreach ($associated as $row) {
                $batch[$row['pkId']] = $row['stakeholderName'];
            }
        }

        $this->$rows->number->setMultiOptions($batch);
    }

}
