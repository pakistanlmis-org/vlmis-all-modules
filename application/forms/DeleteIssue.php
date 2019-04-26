<?php

/**
 * Form_DeleteIssue
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Delete Issue
 */
class Form_DeleteIssue extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * Form Fields
     * @asset_id: Location
     * @non_ccm_location_id: Location
     * @quantity: Quantity
     * 
     * @var type 
     */
    private $_fields = array(
        "asset_id" => "Location",
        "non_ccm_location_id" => "Location",
        "quantity" => "Quantity"
    );

    /**
     * $_list
     * 
     * Private Variable
     * 
     * List
     * @non_ccm_location_id
     * @asset_id
     * 
     * @var type 
     */
    private $_list = array(
        'non_ccm_location_id' => array(),
        'asset_id' => array()
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        $cold_chain = new Model_ColdChain();
        $result1 = $cold_chain->getLocationsName();

        $this->_list["asset_id"][''] = "Select Location";
        if ($result1) {
            foreach ($result1 as $row1) {
                $this->_list["asset_id"][$row1['pkId']] = $row1['assetId'];
            }
        }

        $non_ccm_loc = new Model_NonCcmLocations();
        $result2 = $non_ccm_loc->getLocationsName();

        $this->_list["non_ccm_location_id"][''] = "Select Location";
        if ($result2) {
            foreach ($result2 as $row2) {
                $this->_list["non_ccm_location_id"][$row2['pkId']] = $row2['locationName'];
            }
        }

        //foreach loop
        foreach ($this->_fields as $col => $name) {
            if ($col == "quantity") {
                parent::createText($col);
            }

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelectWithValidator($col, $name, $this->_list[$col]);
            }
        }
    }

}
