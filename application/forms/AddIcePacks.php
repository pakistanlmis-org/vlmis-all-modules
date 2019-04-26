<?php

/**
 * Form_AddIcePacks
 *
 *
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Add Ice Packs
 */
class Form_AddIcePacks extends Form_Base {

    /**
     * Fileds for form Form_AddIcePacks
     * $_fields
     * @var type
     * placed_at
     * quantity
     */
    private $_fields = array(
        "placed_at" => "Placed At",
        "quantity" => "quantity"
    );

    /**
     *  combo for Form_AddIcePacks
     * 
     * $_list
     * @var type
     */
    private $_list = array(
    );

    /**
     * Radio for Form_AddIcePacks
     * 
     * $_radio
     * @var type
     * 
     * placed_at
     */
    private $_radio = array(
        'placed_at' => array(
            "0" => "Unallocated",
            "1" => "Select Store",
        )
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        /**
         * Generate Text Fields
         * for form
         */
        foreach ($this->_fields as $col => $name) {

            if ($col == "quantity") {
                parent::createText($col);
            }
            /**
             * Generate Select Boxes
             * for form
             */
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelectWithValidator($col, $name, $this->_list[$col]);                
            }
            /**
             * Generate Radio Buttons
             * for form 
             */
            if (in_array($col, array_keys($this->_radio))) {
                parent::createRadio($col, $this->_radio[$col]);
            }
        }
    }

    /**
     * Add Hidden Fields
     * validate
     * apply filter
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }
}