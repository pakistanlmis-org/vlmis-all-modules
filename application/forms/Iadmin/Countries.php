<?php

/**
 * Form_Iadmin_Countries
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
*  Form for Iadmin Countries
*/

class Form_Iadmin_Countries extends Form_Base {

    /**
     * Fields for form
     * Country Name
     */
    private $_fields = array(
        "country_name" => "country_name"
    );
    
    /**
     * Hidden fields for form
     * Country Name Hidden
     * Country Id
     */
    private $_hidden = array(
        "country_name_hidden" => "country_name_hidden",
        "country_id" => "country_id"
    );
    
    private $_list = array(
       
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "country_name":
                    parent::createText($col);
                    break;
                default:
                    break;
            }
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
        //Creating Hidden Fields
        foreach ($this->_hidden as $col => $name) {
            switch ($col) {
                
                case "country_name_hidden";
                case "country_id":
                    parent::createHidden($col);
                    break;
                default:
                    break;
            }
        }
    }

}
