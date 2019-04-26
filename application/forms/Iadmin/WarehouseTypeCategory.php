<?php

/**
 * Form_Iadmin_WarehouseTypeCategory
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
class Form_Iadmin_WarehouseTypeCategory extends Form_Base {

    /**
     * Fields for form
     * wh_category_name
     */
    private $_fields = array(
        "wh_category_name" => "wh_category_name",

    );

    /**
     * Hidden fields for form
     * Country Name Hidden
     * Country Id
     */
    private $_hidden = array(
        "wh_category_name_hidden" => "wh_category_name_hidden",
        "wh_category_id" => "wh_category_id"
    );
    private $_list = array(
    );
    private $_radio = array(

    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "wh_category_name":
                    //case "geo_level_description":
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

                case "wh_category_name_hidden":
//                case "geo_level_description_hidden":
                case "wh_category_id":
                    parent::createHidden($col);
                    break;
                default:
                    break;
            }
        }
    }

}
