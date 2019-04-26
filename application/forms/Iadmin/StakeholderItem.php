<?php

/**
 * Form_Iadmin_StakeholderItem
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Iadmin Stakeholder Items
 */
class Form_Iadmin_StakeholderItem extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * Form Fields
     * @stakeholder: Item Name
     * 
     * @var type 
     */
    private $_fields = array(
        "stakeholder" => "item_name"
    );

    /**
     * $_hidden
     * 
     * Private Variable
     * 
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
     * @stakeholder
     * 
     * @var type 
     */
    private $_list = array(
        'stakeholder' => array(),
    );

    /**
     * Initializes Form Fields
     */
    public function init() {


        //Generate Combos
        $stakeholders = new Model_Stakeholders();

        $result1 = $stakeholders->getAllStakeholdersItems();
        $this->_list["stakeholder"][''] = "Select";
        foreach ($result1 as $rs) {
            $this->_list["stakeholder"][$rs['pkId']] = $rs['stakeholderName'];
        }



        foreach ($this->_fields as $col => $name) {
            if ($col == "") {
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
