<?php

/**
 * Form_Iadmin_LocationTypeAdd
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Iadmin Location Type Add
 */
class Form_Iadmin_LocationTypeAdd extends Form_Base {

    /**
     * Fields for Form_Iadmin_LocationTypeAdd
     * 
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "location_type_name" => "Location Type Name",
        "geo_level_id" => "Geo Level",
        "status" => "Status"
    );

    /**
     * List boxes
     * for Form_Iadmin_LocationTypeAdd
     * $_list
     * @var type 
     */
    private $_list = array(
        'geo_level_id' => array(),
    );

    /**
     * Radio buttons 
     * for Form_Iadmin_LocationTypeAdd
     * $_radio
     * @var type 
     */
    private $_radio = array(
        'status' => array(
            "1" => "Active",
            "0" => "In Active"
        )
    );

    /**
     * Hidden fields
     * for Form_Iadmin_LocationTypeAdd
     * $_hidden
     * @var type 
     */
    private $_hidden = array(
        "location_type_id" => "pkId"
    );

    /**
     * Initializes Form Fields
     * for Form_Iadmin_LocationTypeAdd
     */
    public function init() {
        //Generate Item Combo
        // for Form_Iadmin_LocationTypeAdd
        $geo_level = new Model_GeoLevels();
        $result = $geo_level->getGeosAll();
        $this->_list["geo_level_id"][''] = "Select Geo Levels";
        if ($result) {
            foreach ($result as $row) {
                $this->_list["geo_level_id"][$row->getPkId()] = $row->getGeoLevelName();
            }
        }

        //Generate hidden fields
        // for Form_Iadmin_LocationTypeAdd
        foreach ($this->_hidden as $col => $name) {
            if ($col == "location_type_id") {
                parent::createHidden($col);
            }
        }

        // Generate fields
        // for Form_Iadmin_LocationTypeAdd
        foreach ($this->_fields as $col => $name) {
            if ($col == "location_type_name") {
                parent::createText($col);
            }

            // Generate Combo boxes 
            // for Form_Iadmin_LocationTypeAdd
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelectWithValidator($col, $name, $this->_list[$col]);
            }

            // Generate radio buttons for 
            // Form_Iadmin_LocationTypeAdd
            if (in_array($col, array_keys($this->_radio))) {
                parent::createRadio($col, $this->_radio[$col]);
            }
        }
    }

    /**
     * Add Hidden Fields
     * for Form_Iadmin_LocationTypeAdd
     */
    public function addHidden() {
        parent::createHidden("id");
    }

}
