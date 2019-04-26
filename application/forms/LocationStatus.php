<?php

/**
 * Form_LocationStatus
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Location Status
 * 
 * Inherits: Zend Form
 */
class Form_LocationStatus extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * Fields for Area and Level
     * 
     * Form Fields
     * @area: Area
     * @level: Level
     *  
     * @var type 
     */
    private $_fields = array(
        "area" => "Area",
        "level" => "level",
    );

    /**
     * $_list
     * 
     * Private Variable
     * 
     * List
     * @area
     * @level
     * 
     * @var type 
     */
    private $_list = array(
        'area' => array(),
        'level' => array(),
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        //Generate Area Combo
        $list = new Model_ListDetail();
        $list->form_values = array('listMaster' => Model_ListMaster::AREA);
        $result1 = $list->getListDetail();
        $this->_list["area"][''] = "Select";
        if ($result1) {
            foreach ($result1 as $area) {
                $this->_list["area"][$area->getPkId()] = $area->getListValue();
            }
        }

        //Generate Level Combo
        $list = new Model_ListDetail();
        $list->form_values = array('listMaster' => Model_ListMaster::ROW);
        $result2 = $list->getListDetail();
        $this->_list["level"][''] = "Select";
        if ($result2) {
            foreach ($result2 as $row) {
                $this->_list["level"][$row->getPkId()] = $row->getListValue();
            }
        }

        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "area":
                case "level":
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

}
