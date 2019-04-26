<?php

/**
 * Form_Placement
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Placement
 * 
 * Inherits:
 * Form_Base
 */
class Form_Placement extends Form_Base {

    /**
     * $_fields
     * 
     * 
     * Form Fields
     * Form_Placement
     * 
     * 
     * @area: Area
     * @row: Row
     * @rack: Rack
     * @pallet: Pallet
     * @level: Level
     * 
     * @var type 
     */
    private $_fields = array(
        "area" => "Area",
        "row" => "Row",
        "rack" => "Rack",
        "pallet" => "Pallet",
        "level" => "Level",
    );

    /**
     * $_list
     * 
     * Combo boxes
     * for Form_Placement
     * 
     * 
     * @area
     * @row
     * @rack
     * @pallet
     * @level
     * 
     * @var type 
     */
    private $_list = array(
        'area' => array(),
        'row' => array(),
        'rack' => array(),
        'pallet' => array(),
        'level' => array(),
    );

    /**
     * $_hidden
     * 
     * Hidden
     * @placement_id
     * 
     * @var type 
     */
    private $_hidden = array(
        "placement_id" => ""
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        //Generate Area Combo
        $list = new Model_ListDetail();
        $list->form_values = array('listMaster' => Model_ListMaster::AREA);
        $result1 = $list->getListDetail();
        $this->_list["area"][''] = "Select Store";
        if ($result1) {
            foreach ($result1 as $area) {
                $this->_list["area"][$area->getPkId()] = $area->getListValue();
            }
        }

        //Generate Row Combo
        $list = new Model_ListDetail();
        $list->form_values = array('listMaster' => Model_ListMaster::ROW);
        $result2 = $list->getListDetail();
        $this->_list["row"][''] = "Select Row";
        if ($result2) {
            foreach ($result2 as $row) {
                $this->_list["row"][$row->getPkId()] = $row->getListValue();
            }
        }

        //Generate Rack Combo
        $list = new Model_ListDetail();
        $list->form_values = array('listMaster' => Model_ListMaster::RACK);
        $result3 = $list->getListDetail();
        $this->_list["rack"][''] = "Select Rack";
        if ($result3) {
            foreach ($result3 as $rack) {
                $this->_list["rack"][$rack->getPkId()] = $rack->getListValue();
            }
        }

        //Generate Rack Type Combo
        $rack_information = new Model_RackInformation();
        $result4 = $rack_information->getRackInformation();
        $this->_list["rack_information_id"][''] = "Select Rack Type";
        if ($result4) {
            foreach ($result4 as $rackinformation) {
                $this->_list["rack_information_id"][$rackinformation['pkId']] = $rackinformation['rackType'];
            }
        }

        //Generate Pallet Combo
        $list = new Model_ListDetail();
        $list->form_values = array('listMaster' => Model_ListMaster::PALLET);
        $result5 = $list->getListDetail();
        $this->_list["pallet"][''] = "Select Bin";
        if ($result5) {
            foreach ($result5 as $pallet) {
                $this->_list["pallet"][$pallet->getPkId()] = $pallet->getListValue();
            }
        }

        //Generate Level Combo
        $list = new Model_ListDetail();
        $list->form_values = array('listMaster' => Model_ListMaster::LEVEL);
        $result6 = $list->getListDetail();
        $this->_list["level"][''] = "Select Shelf";
        if ($result6) {
            foreach ($result6 as $level) {
                $this->_list["level"][$level->getPkId()] = $level->getListValue();
            }
        }

        //Generate Hidden fields
        foreach ($this->_hidden as $col => $name) {
            if ($col == "placement_id") {
                $this->addElement("hidden", $col);
                $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
            }
        }

        //Generate Form fields
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "area":
                case "row":
                case "rack":
                case "rack_information_id":
                case "pallet":
                case "level":
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
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

}
