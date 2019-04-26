<?php

/**
 * Form_ReportsSearch
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Reports Search
 */
class Form_ReportsSearch extends Form_Base {

    /**
     * $_fields
     * 
     * Form Fields
     * combo1: Province
     * combo1_add: Province
     * combo2_add: District
     * combo2: District
     * year: Year
     * campaign_id: Campaigns
     * facility_type: Facility Type
     * 
     * @var type 
     */
    private $_fields = array(
        "combo1" => "Province",
        "combo1_add" => "Province",
        "combo2_add" => "District",
        "combo2" => "District",
        "year" => "Year",
        "campaign_id" => "Campaigns",
        "facility_type" => "Facility Type"
    );

    /**
     * $_list
     * 
     * List
     * @combo1
     * @combo1_add
     * @combo2_add
     * @combo2
     * @year
     * @campaign_id
     * @facility_type
     * 
     * @var type 
     */
    private $_list = array(
        'combo1' => array(),
        'combo1_add' => array(),
        'combo2_add' => array(),
        'combo2' => array(),
        'year' => array(),
        'campaign_id' => array(),
        'facility_type' => array()
    );

    /**
     * $_hidden
     * @var type 
     */
    private $_hidden = array(
        "district_id_hidden" => "pkId"
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        //Generate Provinces Combo
        $locations = new Model_Locations();
        $provinces = $locations->getAllProvinces();
        $this->_list["combo1"][''] = "Select";
        $this->_list["combo1_add"][''] = "Select";
        if ($provinces) {
            foreach ($provinces as $row) {
                $this->_list["combo1"][$row['pkId']] = $row['locationName'];
                $this->_list["combo1_add"][$row['pkId']] = $row['locationName'];
            }
        }

        $this->_list["combo2_add"][''] = "Select Province";
        $this->_list["combo2"][''] = "Select Province";
        //Generate Facility Type Combo
        $warehouse = new Model_Warehouses();
        $warehouse->form_values['stakeholder_id'] = 1;
        $wh_types = $warehouse->getAllHealthFacilityTypesByStakeholder();
        $this->_list["facility_type"][''] = "Select";
        if ($wh_types) {
            foreach ($wh_types as $row) {
                $this->_list["facility_type"][$row['pkId']] = $row['warehouseTypeName'];
            }
        }

        $this->_list["year"][''] = "Select";
        $yy = date('Y');
        $end_year = 2014;
        for ($i = $yy; $i >= $end_year; $i = $i - 1) {
            $this->_list["year"][$i] = $i;
        }

        $campaign = new Model_Campaigns();
        $result1 = $campaign->allCampaigns();
        $this->_list["campaign_id"][''] = 'Select';
        foreach ($result1 as $row) {
            $this->_list["campaign_id"][$row['pkId']] = $row['campaignName'];
        }

        foreach ($this->_fields as $col => $name) {
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelectWithValidator($col, $name, $this->_list[$col]);
            }
        }

        foreach ($this->_hidden as $col => $name) {
            if ($col == "district_id_hidden") {
                $this->addElement("hidden", $col);
                $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
            }
        }
    }

}
