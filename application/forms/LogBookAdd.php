<?php

/**
 * Form_LogBookAdd
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Log Book Add
 */
class Form_LogBookAdd extends Form_Base {

    /**
     * Fields 
     * for Form_LogBookAdd
     * 
     * 
     * name
     * father_name
     * age
     * gender
     * contact
     * address
     * contact
     * province
     * district
     * tehsil
     * uc
     * refer_to
     * vaccination_date_from
     * vaccination_date_to
     * item_1
     * item_2
     * item_3
     * item_4
     * item_5
     * remarks
     * 
     * 
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "name" => "name",
        "father_name" => "father_name",
        "age" => "age",
        "gender" => "gender",
        "contact" => "contact",
        "address" => "address",
        "contact" => "contact",
        "province" => "province",
        "district" => "district",
        "tehsil" => "tehsil",
        "uc" => "uc",
        "refer_to" => "refer_to",
        "vaccination_date_from" => "vaccination_date_from",
        "vaccination_date_to" => "vaccination_date_to",
        "item_1" => "item_1",
        "item_2" => "item_2",
        "item_3" => "item_3",
        "item_4" => "item_4",
        "item_5" => "item_5",
        "remarks" => "remarks"
    );

    /**
     * Combo boxes
     * for Form_LogBookAdd
     * 
     * 
     * province
     * district
     * tehsil
     * refer_to
     * uc
     * gender
     * 
     * 
     * $_list
     * @var type 
     */
    private $_list = array(
        'province' => array(),
        'district' => array(),
        'tehsil' => array(),
        'refer_to' => array(),
        'uc' => array(),
        'gender' => array()
    );

    /**
     * $_childlist
     * @var type 
     */
    private $_childlist = array(
    );

    /**
     * Initializes Form Fields
     */
    public function init() {


        $this->_list["gender"]['male'] = 'Male';
        $this->_list["gender"]['female'] = 'Female';
        //Generate 
        $locations = new Model_Locations();
        $result = $locations->getAllProvinces();

        if ($result) {
            $this->_list["province"][''] = "Select";
            foreach ($result as $row) {
                $this->_list["province"][$row['pkId']] = $row['locationName'];
            }
        }

        $date_from = date('01/m/Y');
        $date_to = date('d/m/Y');

        foreach ($this->_fields as $col => $name) {
            switch ($col) {

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
                case "remarks":
                    parent::createText($col);
                    break;
                case "vaccination_date_from":
                    parent::createReadOnlyTextWithValue($col,$date_from);
                    break;
                case "vaccination_date_to":
                    parent::createReadOnlyTextWithValue($col,$date_to);
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

            //Generating child list
            // for Form_LogBookAdd
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

            //Generating Fields
            // for Form_LogBookAdd
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
