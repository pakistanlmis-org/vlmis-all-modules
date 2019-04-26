<?php

/**
 * Form_Iadmin_MessagesSearch
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Iadmin Messages Search
 * 
 * Function:
 * To Search Messages
 */
class Form_Iadmin_MessagesSearch extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * Form Fields
     * @page_name: Page Name
     * @search_text: Page Name Description
     * @status: Status
     * @deleted: Deleted
     * 
     * @var type 
     */
    private $_fields = array(
        "page_name" => "Page Name",
        "search_text" => "Page Name Description",
        "status" => "Status",
        "deleted" => "Deleted"
    );

    /**
     * $_list
     * 
     * Private Variable
     * 
     * @var type 
     */
    private $_list = array(
        'page_name' => array(),
        'status' => array(
            '0' => 'Disable',
            '1' => 'Enable',
            '2' => 'Deleted'
        )
    );

    /**
     * $_checkbox
     * 
     * Private Variable
     * 
     * @var type 
     */
    private $_checkbox = array(
        "deleted" => array(
            '1' => 'deleted'
        ),
    );

    /**
     * Initializes Form Fields
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
        foreach ($this->_fields as $col => $name) {
            if ($col == "search_text") {
                parent::createText($col);
            }

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }

            if (in_array($col, array_keys($this->_checkbox))) {
                parent::createCheckbox($col, $this->_checkbox[$col]);
            }
        }
    }

}
