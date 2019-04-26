<?php

/**
 * Form_Iadmin_Stakeholders
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
*  Form for Iadmin Stakeholders
*/

class Form_Iadmin_Stakeholders extends Form_Base {

    /**
     * Fields 
     * for Form_Iadmin_Stakeholders
     * 
     * 
     * 
     * stakeholder_name
     * geo_level
     * sector
     * activity
     * stakeholder_activity
     * stakeholder_type
     * stakeholder_sector
     * 
     * 
     * 
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "stakeholder_name" => "stakeholder",
        "geo_level" => "geo_level",
        "sector" => "sector",
        "activity" => "activity",
        "stakeholder_activity" => "stakeholder_activity",
        "stakeholder_type" => "stakeholder_type",
        "stakeholder_sector" => "stakeholder_sector"
    );
    
    /**
     * Hidden fields
     * for Form_Iadmin_Stakeholders
     * 
     * 
     * 
     * stakeholder_id
     * stakeholder_activity_id
     * stakeholder_type_id
     * stakeholder_sector_id
     * 
     * 
     * 
     * $_hidden
     * @var type 
     */
    private $_hidden = array(
        "stakeholder_id" => "stakeholder_id",
        "stakeholder_activity_id" => "stakeholder_activity_id",
        "stakeholder_type_id" => "stakeholder_type_id",
        "stakeholder_sector_id" => "stakeholder_sector_id"
    );
    
    /**
     * Combo boxes
     * for Form_Iadmin_Stakeholders
     * 
     * 
     * 
     * geo_level
     * sector
     * activity
     * 
     * 
     * 
     * $_list
     * @var type 
     */
    private $_list = array(
        'geo_level' => array(),
        'sector' => array(),
        'activity' => array()
    );

    /**
     * Initializes Form Fields
     * for Form_Iadmin_Stakeholders
     */
    public function init() {
        //Generate Asset Type Combo
        $geo_level = new Model_Locations();

        $result1 = $geo_level->getStakeholderGeoLevel();
        $this->_list["geo_level"][''] = "Select";
        foreach ($result1 as $rs) {
            $this->_list["geo_level"][$rs['pkId']] = $rs['geoLevelName'];
        }
        $stakeholder_sectors = new Model_Stakeholders();

        $result2 = $stakeholder_sectors->getAllSectors();
        $this->_list["sector"][''] = "Select";
        foreach ($result2 as $rs) {
            $this->_list["sector"][$rs['pkId']] = $rs['stakeholderSectorName'];
        }

        $stakeholder_activities = new Model_Stakeholders();

        $result3 = $stakeholder_activities->getAllActivities();
        $this->_list["activity"][''] = "Select";
        foreach ($result3 as $rs) {
            $this->_list["activity"][$rs['pkId']] = $rs['activity'];
        }

        
        //Generate fields 
        // for Form_Iadmin_Stakeholders
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "stakeholder_name":
                case "stakeholder_activity":
                case "stakeholder_type":
                case "stakeholder_sector":
                    parent::createText($col);
                    break;
                default:
                    break;
            }
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }

        // Generate Hidden fields 
        // for Form_Iadmin_Stakeholders
        foreach ($this->_hidden as $col => $name) {
            switch ($col) {

                case "stakeholder_id":
                case "stakeholder_activity_id":
                case "stakeholder_type_id" :
                case "stakeholder_sector_id":
                    parent::createHidden($col);
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Add Hidden Fields
     * for Form_Iadmin_Stakeholders
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

}
