<?php

/**
 * Form_UcWiseTarget
 *
 * 
 *
 * Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Log Book
 */
class Form_UcWiseTarget extends Form_Base {

    /**

     */
    private $_fields = array(
        "province" => "province",
        "district" => "district",
        "year" => "Year",
    );

    /**
     * 
     * Combo boxes 

     * @var type 
     */
    private $_list = array(
        'province' => array(),
        'district' => array(),
        'year' => array(),
    );

    /**
     * Radio buttons
     * for Form_LogBook
     * 
     * $_radio
     * 
     * entry_type: array()
     * 
     * @var type 
     */
    private $_radio = array(
    );

    /**
     * Child List
     * fo Form_LogBook
     * 
     * 
     * 
     * $_childlist
     * 
     * @var type 
     */
    private $_childlist = array(
    );

    /**
     * Initializes Form Fields
     * for Form_LogBook
     */
    public function init() {

        $locations = new Model_Locations();
        $result = $locations->getAllProvinces();

        if ($result) {
            $this->_list["province"][''] = "Select";
            foreach ($result as $row) {
                $this->_list["province"][$row['pkId']] = $row['locationName'];
            }
        }

        //Generate year Combo
        $this->_list["year"]['2016'] = '2016';
        $this->_list["year"]['2015'] = '2015';
        $this->_list["year"]['2014'] = '2014';
        
        //Generate Model
//        $locations = new Model_Locations();
//        $result = $locations->getSindhDistricts();
        $this->_list["district"][''] = "All";
//        if ($result) {
//            foreach ($result as $row) {
//
//                $this->_list["district"][$row['pkId']] = $row['locationName'];
//            }
//        }

        
        foreach ($this->_fields as $col => $name) {

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelectWithValidator($col, $name, $this->_list[$col]);
            }
        }
    }

    /**
     * Add Hidden Fields
     * for Form_LogBook
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

}
