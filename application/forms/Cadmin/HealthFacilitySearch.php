<?php

/**
 * Form_Cadmin_HealthFacilitySearch
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Cadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Cadmin Health Facility Search
 */
class Form_Cadmin_HealthFacilitySearch extends Form_Base {

    /**
     * $_hidden
     * @var type 
     */
    private $_hidden = array(
        "office_id" => "",
        "combo1_id" => "",
        "combo2_id" => "",
        "warehouse_id" => "",
    );

    /**
     * Initializes Form Fields
     */
    public function init() {


        foreach ($this->_hidden as $col => $name) {
            switch ($col) {

                case "office_id":
                case "combo1_id":
                case "combo2_id":
                case "warehouse_id" :
                    parent::createHidden($col);
                    break;
                default:
                    break;
            }
        }
    }

}
