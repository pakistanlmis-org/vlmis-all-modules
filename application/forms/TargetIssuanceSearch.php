<?php

/**
 * Form_TargetIssuanceSearch
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
*  Form for Target Issuance Search
*/

class Form_TargetIssuanceSearch extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * Form Fields
     * @month: Month
     * @year: Year
     * @province_region: Province / Region
     * @product: Product
     * 
     * @var type 
     */
    private $_fields = array(
        "month" => "Month",
        "year" => "Year",
        "province_region" => "Province/Region",
        "product" => "Product"
    );
    
    /**
     * $_list
     * 
     * Private Variable
     * 
     * List
     * @month
     * @year
     * @province_region
     * @product
     * 
     * @var type 
     */
    private $_list = array(
        'month' => array(),
        'year' => array(),
        'province_region' => array(),
        'product' => array()
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        //Generate month Combo
        $this->_list["month"][''] = 'Select';
        $this->_list["month"]['01'] = 'January';
        $this->_list["month"]['02'] = 'February';
        $this->_list["month"]['03'] = 'March';
        $this->_list["month"]['04'] = 'April';
        $this->_list["month"]['05'] = 'May';
        $this->_list["month"]['06'] = 'June';
        $this->_list["month"]['07'] = 'July';
        $this->_list["month"]['08'] = 'August';
        $this->_list["month"]['09'] = 'September';
        $this->_list["month"]['10'] = 'October';
        $this->_list["month"]['11'] = 'November';
        $this->_list["month"]['12'] = 'December';

        //Generate year Combo
        $this->_list["year"][''] = 'Select';
        $this->_list["year"]['2015'] = '2015';
        $this->_list["year"]['2016'] = '2016';
        
        //Generate Province/Region Combo
        $locations = new Model_Locations();
        $result = $locations->getAllProvinces();
        if ($result) {
            $this->_list["province_region"][''] = "Select";
            foreach ($result as $row) {
                $this->_list["province_region"][$row['pkId']] = $row['locationName'];
            }
        }


        //Generate Antigen(items) Combo
        $item_pack_sizes = new Model_ItemPackSizes();
        $result2 = $item_pack_sizes->getAllVaccines();

        foreach ($result2 as $item) {

            $this->_list["product"][''] = 'Select';
            $this->_list["product"][$item['pkId']] = $item['itemName'];
        }


        foreach ($this->_fields as $col => $name) {


            if (in_array($col, array_keys($this->_list))) {
                parent::createSelectWithValidator($col, $name, $this->_list[$col]);
            }
        }
    }

}
