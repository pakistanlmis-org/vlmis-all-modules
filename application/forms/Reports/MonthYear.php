<?php

/**
 * Form_Reports_MonthYear
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Reports
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
*  Form for Reports Month Year
*/

class Form_Reports_MonthYear extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * Form Fields
     * @month: Select Month
     * @year: Select Year
     * 
     * @var type 
     */
    private $_fields = array(
        "month" => "Select Month",
        "year" => "Select Year"
    );
    
    /**
     * $_list
     * @var type 
     */
    private $_list = array(
        'month' => array(),
        'year' => array()
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        $this->_list["month"][''] = "Select Month";
        for ($m = 1; $m <= 12; $m++) {
            $dateObj = DateTime::createFromFormat('!m', $m);
            $monthName = $dateObj->format('F');
            $this->_list["month"][$m] = $monthName;
        }

        $this->_list["year"][''] = "Select Year";
        for ($y = 2014; $y <= date("Y"); $y++) {
            $this->_list["year"][$y] = $y;
        }

        foreach ($this->_fields as $col => $name) {
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelectWithValidator($col, $name, $this->_list[$col]);
            }
        }
    }

}
