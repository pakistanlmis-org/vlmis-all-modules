<?php

/**
 * Form_LogBook
 *
 * 
 *
 * Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Log Book
 */
class Form_LogBook extends Form_Base {

    /**
     * 
     * Fields 
     * for Form_LogBook
     * 
     * 
     * 
     * $_fields
     * @name: "name"
     *
     * @father_name: "father_name"
     *
     * @age: "age"
     *
     * @contact: "contact"
     *
     * @address: "address"
     *
     * @contact: "contact"
     *
     * @province: "province"
     *
     * @entry_type: "Entry type"
     *
     * @district: "district"
     *
     * @tehsil: "tehsil"
     *
     * @uc: "uc"
     *
     * @wh: "wh"
     *
     * @vaccination_date_from: "vaccination_date_from"
     *
     * @vaccination_date_to: "vaccination_date_to"
     *
     * @item_1: "item_1"
     *
     * @item_2: "item_2"
     *
     * @item_3: "item_3"
     *
     * @item_4: "item_4"
     *
     * @item_5: "item_5"
     *
     * @reffers_to: "reffers_to"
     *
     * @remarks: "remarks"
     *
     * @var type 
     */
    private $_fields = array(
        "name" => "name",
        "father_name" => "father_name",
        "age" => "age",
        "contact" => "contact",
        "address" => "address",
        "contact" => "contact",
        "province" => "province",
        "entry_type" => "Entry type",
        "district" => "district",
        "tehsil" => "tehsil",
        "uc" => "uc",
        "wh" => "wh",
        "vaccination_date_from" => "vaccination_date_from",
        "vaccination_date_to" => "vaccination_date_to",
        "item_1" => "item_1",
        "item_2" => "item_2",
        "item_3" => "item_3",
        "item_4" => "item_4",
        "item_5" => "item_5",
        "reffers_to" => "reffers_to",
        "remarks" => "remarks"
    );

    /**
     * 
     * Combo boxes 
     * for Form_LogBook
     * 
     * 
     * $_list
     * @province: array()
     *
     * @district: array()
     *
     * @tehsil: array()
     *
     * @uc: array()
     *
     * @wh: array()
     *
     * @var type 
     */
    private $_list = array(
        'province' => array(),
        'district' => array(),
        'tehsil' => array(),
        'uc' => array(),
        'wh' => array()
    );

    /**
     * Radio buttons
     * for Form_LogBook
     * 
     * $_radio
     * 
     * entry_type: array()
     * 
     * @var type 
     */
    private $_radio = array(
        'entry_type' => array(
            "1" => "My Entries",
            "2" => "Referrals"
        )
    );

    /**
     * Child List
     * fo Form_LogBook
     * 
     * 
     * 
     * $_childlist
     * 
     * @var type 
     */
    private $_childlist = array(
    );

    /**
     * Initializes Form Fields
     * for Form_LogBook
     */
    public function init() {

        $locations = new Model_Locations();
        $result = $locations->getAllProvinces();

        if ($result) {
            $this->_list["province"][''] = "Select";
            foreach ($result as $row) {
                $this->_list["province"][$row['pkId']] = $row['locationName'];
            }
        }

        //Generate Model
        $locations = new Model_Locations();
        $result = $locations->getSindhDistricts();
        $this->_list["district"][''] = "Select";
        if ($result) {

            foreach ($result as $row) {

                $this->_list["district"][$row['pkId']] = $row['locationName'];
            }
        }

        $date_from = date('01/m/Y');
        $date_to = date('d/m/Y');

        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                /**
                 * 
                 * Form Text Fields for 
                 * 
                 * name
                 * 
                 * father_name
                 * 
                 * age
                 * 
                 * contact
                 * 
                 * address
                 * 
                 * item_1
                 * 
                 * item_2
                 * 
                 * item_3
                 * 
                 * item_4
                 * 
                 * item_5
                 * 
                 * reffers_to
                 * 
                 * remarks
                 * 
                 */
                case "name":
                case "father_name":
                case "age":
                case "contact":
                case "address":
                case "item_1":
                case "item_2":
                case "item_3":
                case "item_4":
                case "item_5":
                case "reffers_to":
                case "remarks":
                    parent::createText($col);
                    break;
                /**
                 * 
                 * Form Text Fields for 
                 * 
                 * vaccination_date_from
                 * 
                 */
                case "vaccination_date_from":
                    parent::createReadOnlyTextWithValue($col,$date_from);
                    break;
                /**
                 * 
                 * Form Text Fields for 
                 * Form_LogBook
                 * 
                 * vaccination_date_to
                 * 
                 */
                case "vaccination_date_to":
                    parent::createReadOnlyTextWithValue($col,$date_to);
                    break;
                default:
                    break;
            }

            /**
             * 
             * Form Select Fields for
             * 
             * Form_LogBook
             * 
             */
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelectWithValidator($col, $name, $this->_list[$col]);
            }

            if (in_array($col, array_keys($this->_radio))) {
                parent::createRadio($col, $this->_radio[$col]);
            }
        }
    }

    /**
     * Add Hidden Fields
     * for Form_LogBook
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

    /**
     * Adds extra rows to the form
     * Form_LogBook
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
