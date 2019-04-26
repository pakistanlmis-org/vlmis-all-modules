<?php

/**
 * Form_Iadmin_Manufacturer
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Iadmin Manufacturer
 */
class Form_Iadmin_Manufacturer extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * Form Fields
     * @manufacturer: Maufacturer
     * @sector: Sector
     * 
     * @var type 
     */
    private $_fields = array(
        "manufacturer" => "manufacturer",
        "sector" => "sector"
    );

    /**
     * $_hidden
     * 
     * Private Variable
     * 
     * Hidden
     * @stakeholder_id: Stakeholder Id
     * 
     * @var type 
     */
    private $_hidden = array(
        "stakeholder_id" => "stakeholder_id"
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
        'sector' => array()
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        $stakeholder_sectors = new Model_Stakeholders();
        $result2 = $stakeholder_sectors->getAllSectors();
        $this->_list["sector"][''] = "Select";
        foreach ($result2 as $rs) {
            $this->_list["sector"][$rs['pkId']] = $rs['stakeholderSectorName'];
        }


        foreach ($this->_fields as $col => $name) {
            if ($col == "manufacturer") {
                parent::createText($col);
            }
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }

        foreach ($this->_hidden as $col => $name) {
            if ($col == "stakeholder_id") {
                parent::createHidden($col);
            }
        }
    }

    /**
     * Add Hidden Fields
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

}
