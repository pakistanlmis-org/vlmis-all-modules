<?php

/**
 * Form_Iadmin_MessagesAdd
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Iadmin Messages Add
 */
class Form_Iadmin_MessagesAdd extends Form_Base {

    /**
     * Fields for Form_Iadmin_MessagesAdd
     * 
     * 
     * 
     * page_name
     * description
     * status
     * 
     * 
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "page_name" => "Page Name",
        "description" => "Description",
        "status" => "status"
    );

    /**
     * Combo boxes for 
     * Form_Iadmin_MessagesAdd
     * 
     * 
     * 
     * page_name
     * 
     * $_list
     * @var type 
     */
    private $_list = array(
        'page_name' => array(),
    );

    /**
     * Radio buttons 
     * for Form_Iadmin_MessagesAdd
     * 
     * 
     * status
     * 
     * 
     * $_radio
     * @var type 
     */
    private $_radio = array(
        'status' => array(
            '0' => 'Disable',
            '1' => 'Enable'
        )
    );

    /**
     * Initializes Form Fields
     * for Form_Iadmin_MessagesAdd
     */
    public function init() {
        $resources = new Model_Resources();
        $resources->form_values['only_childs'] = 1;
        $result2 = $resources->getResources('resource_name', 'ASC');
        $this->_list["page_name"][''] = "Select";
        if ($result2) {
            foreach ($result2 as $row2) {
                $resource = $row2->getResourceName();
                $arr_resources = explode("/", $resource);
                $second_name = (!empty($arr_resources[1])) ? ucfirst($arr_resources[1]) . " - " : "";
                $this->_list["page_name"][$row2->getPkId()] = ucfirst($arr_resources[0]) . " - " . $second_name . $row2->getDescription();
            }
        }

        //Generate fields 
        // for Form_Iadmin_MessagesAdd
        foreach ($this->_fields as $col => $name) {
            if ($col == "description") {
                parent::createMultiLineText($col, "4");
            }

            // Generate combo boxes 
            // for Form_Iadmin_MessagesAdd
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }

            // Generate Radio buttons
            // for Form_Iadmin_MessagesAdd
            if (in_array($col, array_keys($this->_radio))) {
                parent::createRadio($col, $this->_radio[$col]);
            }
        }
    }

    /**
     * Add Hidden Fields
     * 
     * for Form_Iadmin_MessagesAdd
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

}
