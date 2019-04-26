<?php

/**
 * Form_Iadmin_GeoLevels
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Muhammad Imran <muhammad_imran@pk.jsi.com>
 * @version    2.5.1
 */

/**
 *  Form for Iadmin GeoLevels
 */
class Form_Iadmin_GeoLevels extends Form_Base {

    /**
     * Fields for form
     * Country Name
     */
    private $_fields = array(
        "geo_level_name" => "geo_level_name",
        "geo_level_description" => "geo_level_description",
        "status" => "Status"
    );

    /**
     * Hidden fields for form
     * Country Name Hidden
     * Country Id
     */
    private $_hidden = array(
        "geo_level_name_hidden" => "geo_level_name_hidden",
        "geo_level_description_hidden" => "geo_level_description_hidden",
        "geo_level_id" => "geo_level_id"
    );
    private $_list = array(
    );
    private $_radio = array(
        'status' => array(
            "1" => "Active",
            "0" => "In Active"
        )
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "geo_level_name":
                case "geo_level_description":
                    parent::createText($col);
                    break;
                default:
                    break;
            }
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
            if (in_array($col, array_keys($this->_radio))) {
                parent::createRadio($col, $this->_radio[$col]);
            }
        }
        //Creating Hidden Fields
        foreach ($this->_hidden as $col => $name) {
            switch ($col) {

                case "geo_level_name_hidden":
                case "geo_level_description_hidden":
                case "geo_level_id":
                    parent::createHidden($col);
                    break;
                default:
                    break;
            }
        }
    }

}
