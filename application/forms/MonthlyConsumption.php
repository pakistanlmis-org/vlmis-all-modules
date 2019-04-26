<?php

/**
 * Form_MonthlyConsumption
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Monthly Consumption
 */
class Form_MonthlyConsumption extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * Form Fields
     * @uc: UC
     * @uc_center: UC Center
     * @monthly_report: Monthly Report
     * 
     * @var type 
     */
    private $_fields = array(
        "uc" => "Uc",
        "uc_center" => "uc_center",
        "monthly_report" => "monthly_report"
    );

    /**
     * $_list
     * 
     * Private Variable
     * 
     * List
     * 
     * @var type 
     */
    private $_list = array(
        'uc' => array(),
        'uc_center' => array(),
        'monthly_report' => array(
            '' => 'Month - Year'
        )
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        $locations = new Model_Locations();
        $uc_warehouses = $locations->getAllUCByUserId();
        if (is_array($uc_warehouses)) {
            foreach ($uc_warehouses as $locations) {

                $this->_list["uc"][''] = 'Select';
                $this->_list["uc"][$locations['pk_id']] = $locations['location_name'];
            }
        }
        foreach ($this->_fields as $col) {

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
    }

}
