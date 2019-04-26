<?php

/**
 * Form_PlacementArea
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Placement Area
 */
class Form_PlacementArea extends Form_Base {

    /**
     * Fields 
     * for Form_PlacementArea
     * 
     * 
     * area
     * row
     * row_to
     * rack
     * rack_to
     * rack_information_id
     * pallet
     * pallet_to
     * level
     * level_to
     * location_name
     * 
     * 
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "area" => "Area",
        "row" => "Row From",
        "row_to" => "Row To",
        "rack" => "Rack From",
        "rack_to" => "Rack To",
        "rack_information_id" => "Rack Type",
        "pallet" => "Pallet From",
        "pallet_to" => "Pallet To",
        "level" => "Level From",
        "level_to" => "Level To",
        "location_name" => "Label",
    );

    /**
     * Combo boxes
     * for Form_PlacementArea
     * 
     * 
     * area
     * row
     * rack
     * rack_to
     * rack_information_id
     * pallet
     * pallet_to
     * level
     * level
     * 
     * 
     * $_list
     * @var type 
     */
    private $_list = array(
        'area' => array(),
        'row' => array(),
        'row_to' => array(),
        'rack' => array(),
        'rack_to' => array(),
        'rack_information_id' => array(),
        'pallet' => array(),
        'pallet_to' => array(),
        'level' => array(),
        'level_to' => array(),
    );

    /**
     * Hidden fields 
     * for Form_PlacementArea
     * 
     * 
     * placement_area_id
     * 
     * $_hidden
     * @var type 
     */
    private $_hidden = array(
        "placement_area_id" => ""
    );

    /**
     * Initializes Form Fields
     * for Form_PlacementArea
     */
    public function init() {

        //Generate Area Combo
        //for Form_PlacementArea
        $list = new Model_ListDetail();
        $list->form_values = array('listMaster' => Model_ListMaster::AREA);
        $result1 = $list->getListDetail();
        $this->_list["area"][''] = "Select Area";
        if ($result1) {
            foreach ($result1 as $area) {
                $this->_list["area"][$area->getListValue()] = $area->getListValue();
            }
        }

        //Generate Row From Combo
        //for Form_PlacementArea
        $list = new Model_ListMaster();
        $list->form_values = array('pk_id' => Model_ListMaster::ROW);
        $result2 = $list->getListDetailByType();
        $this->_list["row"][''] = "Select Row";
        if ($result2) {
            foreach ($result2 as $row) {
                $this->_list["row"][$row['pkId']] = $row['listValue'];
            }
        }

        //Generate Row To Combo
        //for Form_PlacementArea
        $list = new Model_ListMaster();
        $list->form_values = array('pk_id' => Model_ListMaster::ROW);
        $list->form_values['order'] = "desc";
        $result3 = $list->getListDetailByType();
        $this->_list["row_to"][''] = "Select Row To";
        if ($result3) {
            foreach ($result3 as $row) {
                $this->_list["row_to"][$row['pkId']] = $row['listValue'];
            }
        }

        //Generate Rack Combo
        //for Form_PlacementArea
        $list = new Model_ListMaster();
        $list->form_values = array('pk_id' => Model_ListMaster::RACK);
        $result7 = $list->getListDetailByType();
        $this->_list["rack"][''] = "Select Rack";
        if ($result7) {
            foreach ($result7 as $rack) {
                $this->_list["rack"][$rack['pkId']] = $rack['listValue'];
            }
        }

        //Generate Rack To Combo
        //for Form_PlacementArea
        $list = new Model_ListDetail();
        $list->form_values = array('listMaster' => Model_ListMaster::RACK);
        $result3 = $list->getListDetail();
        $this->_list["rack_to"][''] = "Select Rack To";
        if ($result3) {
            foreach ($result3 as $rack) {
                $this->_list["rack_to"][$rack->getListValue()] = $rack->getListValue();
            }
        }

        //Generate Rack Type Combo
        //for Form_PlacementArea
        $rack_information = new Model_RackInformation();
        $result4 = $rack_information->getRackInformation();
        $this->_list["rack_information_id"][''] = "Select Rack Type";
        if ($result4) {
            foreach ($result4 as $rackinformation) {
                $this->_list["rack_information_id"][$rackinformation['pkId']] = $rackinformation['dimType'];
            }
        }

        //Generate Pallet Combo
        //for Form_PlacementArea
        $list = new Model_ListDetail();
        $list->form_values = array('listMaster' => Model_ListMaster::PALLET);
        $result5 = $list->getListDetail();
        $this->_list["pallet"][''] = "Select Pallet";
        if ($result5) {
            foreach ($result5 as $pallet) {
                $this->_list["pallet"][$pallet->getListValue()] = $pallet->getListValue();
            }
        }

        //Generate Pallet To Combo
        //for Form_PlacementArea
        $list = new Model_ListDetail();
        $list->form_values = array('listMaster' => Model_ListMaster::PALLET);
        $result5 = $list->getListDetail();
        $this->_list["pallet_to"][''] = "Select Pallet To";
        if ($result5) {
            foreach ($result5 as $pallet) {
                $this->_list["pallet_to"][$pallet->getListValue()] = $pallet->getListValue();
            }
        }

        //Generate Level Combo
        //for Form_PlacementArea
        $list = new Model_ListDetail();
        $list->form_values = array('listMaster' => Model_ListMaster::LEVEL);
        $result6 = $list->getListDetail();
        $this->_list["level"][''] = "Select Level";
        if ($result6) {
            foreach ($result6 as $level) {
                $this->_list["level"][$level->getListValue()] = $level->getListValue();
            }
        }

        //Generate Level To Combo
        //for Form_PlacementArea
        $list = new Model_ListDetail();
        $list->form_values = array('listMaster' => Model_ListMaster::LEVEL);
        $result6 = $list->getListDetail();
        $this->_list["level_to"][''] = "Select Level To";
        if ($result6) {
            foreach ($result6 as $level) {
                $this->_list["level_to"][$level->getListValue()] = $level->getListValue();
            }
        }

        //Generating hidden fields
        //for Form_PlacementArea
        foreach ($this->_hidden as $col => $name) {
            if ($col == "placement_area_id") {
                $this->addElement("hidden", $col);
                $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
            }
        }

        //Generating fields
        //for Form_PlacementArea
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "area":
                case "row":
                case "row_to":
                case "rack":
                case "rack_to":
                case "rack_information_id":
                case "pallet":
                case "pallet_to":
                case "level":
                case "level_to":
                case "location_name":
                    parent::createText($col);
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
     * for Form_PlacementArea
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

}
