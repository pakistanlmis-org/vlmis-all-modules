<?php

/**
 * Form_StatusWorkingUpdate
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
*  Form for Status Working Update
*/

class Form_StatusWorkingUpdate extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * Form Fields
     * @working_status: Working Status
     * @reason_utilization: Reason Utilization
     * @quantity: Quantity
     * @comments: Comments
     * @temperature: Temperature
     * 
     * @var type 
     */
    private $_fields = array(
        "working_status" => "Working Status",
        "reason_utilization" => "Reason Utilization",
        "quantity" => "Quantity",
        "comments" => "Comments",
        "temperature" => "Temperature"
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
        'working_status' => array()
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        $status_list = new Model_CcmStatusList();

        $result3 = $status_list->getStatusLists();
        foreach ($result3 as $rs) {

            $this->_list["working_status"][''] = "Select";
            $this->_list["working_status"][$rs['pkId']] = $rs['ccmStatusListName'];
        }

        $result4 = $status_list->getAllReasons();
        foreach ($result4 as $rs1) {

            $this->_list["reason_utilization"][''] = "Select";
            $this->_list["reason_utilization"][$rs1['pkId']] = $rs1['ccmStatusListName'];
        }
        $i = 1;
        foreach ($this->_fields as $col => $name) {

            switch ($col) {
                case "quantity":
                case "comments":
                case "temperature":
                    parent::createText($col);
                    break;
                default:
                    break;
            }

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
            $i++;
        }
    }

}
