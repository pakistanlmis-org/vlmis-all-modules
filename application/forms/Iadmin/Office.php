<?php

/**
 * Form_Iadmin_Office
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Iadmin Office
 */
class Form_Iadmin_Office extends Form_Base {

    /**
     * Fields for Form_Iadmin_Office
     * 
     * 
     * office
     * geo_level
     * stakeholder
     * 
     * 
     * 
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "office" => "office",
        "geo_level" => "geo_level",
        "stakeholder" => "stakeholder"
    );

    /**
     * Hidden fields for Form_Iadmin_Office
     * 
     * 
     * stakeholder_id
     * 
     * 
     * 
     * $_hidden
     * @var type 
     */
    private $_hidden = array(
        "stakeholder_id" => "stakeholder_id"
    );

    /**
     * Combo boxes for Form_Iadmin_Office
     * 
     * 
     * geo_level
     * stakeholder
     * 
     * 
     * 
     * $_list
     * @var type 
     */
    private $_list = array(
        'geo_level' => array(),
        'stakeholder' => array()
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        //Generate Asset Type Combo
        // for Form_Iadmin_Office
        $geo_level = new Model_Locations();

        $result1 = $geo_level->getOfficeGeoLevels();
        $this->_list["geo_level"][''] = "Select";
        foreach ($result1 as $rs) {
            $this->_list["geo_level"][$rs['pkId']] = $rs['geoLevelName'];
        }
        $stakeholders = new Model_Stakeholders();

        $result2 = $stakeholders->getAllStakeholders();
        $this->_list["stakeholder"][''] = "Select";
        foreach ($result2 as $rs) {
            $this->_list["stakeholder"][$rs['pkId']] = $rs['stakeholderName'];
        }

        // Generate fields
        // for Form_Iadmin_Office
        foreach ($this->_fields as $col => $name) {
            if ($col == "office") {
                parent::createText($col);
            }
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }

        // Generate hidden fields 
        // for Form_Iadmin_Office
        foreach ($this->_hidden as $col => $name) {
            if ($col == "stakeholder_id") {
                parent::createHidden($col);
            }
        }
    }

    /**
     * Add Hidden Fields
     * for Form_Iadmin_Office
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

}
