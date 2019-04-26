<?php

/**
 * Form_Cadmin_HealthFacility
 *
 *
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Cadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Cadmin Health Facility
 */
class Form_Cadmin_HealthFacility extends Form_Base {

    /**
     * Form Fields
     *
     * @types: Type of Health Facility
     * @types_services_provided: Type of Services Provided
     * @routine_immunization_ice_pack: Routine Immunization Ice Pack Requirements
     * @snid_nid_ice_pack: SNID / NID Ice Pack Requirements
     * @epi_vaccination_staff: EPI Vaccination Staff
     * @grid_electricity_availibility: Grid Electricity Availibility
     * @solar_energy: Solar Energy
     * @old_warehouse: Warehouse
     * @vaccine_supply_mode: vaccine_supply_mode
     * @health_facility_type: Health Facility Type
     * @services_type: Services Type
     * @facility_total_population: Facility Total Population
     * @live_birth_per_year: live Birth Per Year
     * @pregnant_women_per_year: Pregnant Women Per Year
     * @women_of_child_bearing_age: Women of Child Bearing Age
     * @estimation_year: Estimation Year
     *
     * @var type
     *
     */
    private $_fields = array(
        "types" => "Type of Health Facility",
        "types_services_provided" => "Type of Services Provided",
        "routine_immunization_ice_pack" => "Routine Immunization Ice Pack Requirements",
        "snid_nid_ice_pack" => "SNID / NID Ice Pack Requirements",
        "epi_vaccination_staff" => "EPI Vaccination Staff",
        "grid_electricity_availibility" => "Grid Electricity Availibility",
        "solar_energy" => "Solar Energy",
        "old_warehouse" => "Warehouse",
        "vaccine_supply_mode" => "vaccine_supply_mode",
        "health_facility_type" => "health_facility_type",
        "services_type" => "services_type",
        "facility_total_population" => "facility_total_population",
        "live_birth_per_year" => "live_birth_per_year",
        "pregnant_women_per_year" => "pregnant_women_per_year",
        "women_of_child_bearing_age" => "women_of_child_bearing_age",
        "estimation_year" => "estimation_year"
    );

    /**
     * $_hidden
     * @var type
     * Old Warhouses Val
     * Office Level
     */
    private $_hidden = array(
        "id" => "pkId",
        "old_warehouse_val" => "Warehouse",
        "office_level" => "office"
    );

    /**
     * $_list
     * @var type
     * Vaccine Supply Mode
     * Health Facility Type
     */
    private $_list = array(
        "grid_electricity_availibility" => array(),
        "vaccine_supply_mode" => array(),
        "health_facility_type" => array()
    );

    /**
     * $_checkbox
     * @var type
     * EPI Vaccination Staff
     * Services Type
     */
    private $_checkbox = array(
        "epi_vaccination_staff" => array(),
        "solar_energy" => array(),
        "services_type" => array()
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        /**
         * Generate Grid Electricity Availibility
         */
        $list_detail = new Model_ListDetail();
        $list_detail->form_values['master_id'] = Model_ListMaster::GRID_ELECTRICITY_AVAL;
        /**
         * List Detail
         * @param Master Id  default:null
         * @return List Detail By List Master Id
         */
        $result = $list_detail->getListDetailByMasterId();

        foreach ($result as $list) {
            $this->_list["grid_electricity_availibility"][''] = 'Select';
            $this->_list["grid_electricity_availibility"][$list['pkId']] = $list['listValue'];
        }
        /**
         * Health Facility Types
         * @param null
         * @return All Health Facility Types
         */
        $warehouse = new Model_Warehouses();
        $res_warehouse = $warehouse->getHealthFacilityTypes();
        foreach ($res_warehouse as $row) {

            $this->_list["health_facility_type"][''] = 'Select';
            $this->_list["health_facility_type"][$row['pkId']] = $row['warehouseTypeName'];
        }
        $list_detail->form_values['master_id'] = Model_ListMaster::VACCINATION_STAFF;
        /**
         * List Detail
         * @param Master Id  default:null
         * @return List Detail By List Master Id
         */
        $result1 = $list_detail->getListDetailByMasterId();

        foreach ($result1 as $list1) {

            $this->_checkbox["epi_vaccination_staff"][$list1['pkId']] = $list1['listValue'];
        }
        $list_detail->form_values['master_id'] = Model_ListMaster::SERVICE_TYPES;
        $result13 = $list_detail->getListDetailByMasterId();

        foreach ($result13 as $list13) {


            $this->_checkbox["services_type"][$list13['pkId']] = $list13['listValue'];
        }

        $list_detail->form_values['master_id'] = Model_ListMaster::VACCINE_SUPPLY_MODE;
        /**
         * List Detail
         * @param Master Id  default:null
         * @return List Detail By List Master Id
         */
        $result12 = $list_detail->getListDetailByMasterId();

        foreach ($result12 as $list12) {

            $this->_list["vaccine_supply_mode"][''] = 'Select';
            $this->_list["vaccine_supply_mode"][$list12['pkId']] = $list12['listValue'];
        }

        $list_detail->form_values['master_id'] = Model_ListMaster::SOLAR_ENERGY;
        $result2 = $list_detail->getListDetailByMasterId();

        foreach ($result2 as $list2) {

            $this->_checkbox["solar_energy"][$list2['pkId']] = $list2['listValue'];
        }
        /**
         * Generate Text Fields
         */
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                /**
                 * routine_immunization_ice_pack
                 * snid_nid_ice_pack
                 * vaccine_supply_mode
                 * facility_total_population
                 * live_birth_per_year
                 * pregnant_women_per_year
                 * women_of_child_bearing_age
                 */
                case "routine_immunization_ice_pack":
                case "snid_nid_ice_pack":
                case "vaccine_supply_mode":
                case "facility_total_population":
                case "live_birth_per_year":
                case "pregnant_women_per_year":
                case "women_of_child_bearing_age":
                    parent::createText($col);
                    break;
                /**
                 * estimation_year
                 */
                case "estimation_year":
                    parent::createReadOnlyText($col);
                    break;
                default:
                    break;
            }
            /**
             * Generate Select Boxes
             */
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
            /**
             * Generate Check Boxes
             */
            if (in_array($col, array_keys($this->_checkbox))) {
                parent::createCheckbox($col,$this->_checkbox[$col]);
            }
        }
        /**
         * Generate Hidden Fields
         */
        foreach ($this->_hidden as $col => $name) {
            switch ($col) {
                case "old_warehouse_val":
                case "office_level":
                    parent::createHidden($col);
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Add Hidden Fields
     */
    public function addHidden() {
        parent::createHidden("id");
    }

    /**
     * Add Fields
     */
    public function addFields() {

        foreach ($this->_fields as $col => $name) {
            if ($col == "old_warehouse") {
                parent::createText($col);
            }
        }
        $this->getElement('old_warehouse')->setAttrib("disabled", "true");
    }

}
