<?php

/**
 * Form_AddManufacturer
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Add Manufacturer
 */
class Form_AddManufacturer extends Form_Base {

    /**
     * Private Variable
     * 
     * $_fields
     * @var type 
     */
    private $_fields = array(
        'manufacturer_name' => 'Manufacturer Name',
        'quantity_per_pack' => 'Quantity Per Pack'
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "manufacturer_name":
                case "quantity_per_pack":
                    parent::createText($col);

                    break;
                default:
                    break;
            }
        }
    }

}
