<?php

/**
 * Form_Cadmin_ListSearch
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Cadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Cadmin List Search
 */
class Form_Cadmin_ListSearch extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * @var type 
     */
    private $_fields = array(
        "list_master" => "List Master",
        "list_value" => "List Value"
    );

    /**
     * $_list
     * 
     * Private Variable
     * 
     * @var type 
     */
    private $_list = array(
        "list_master" => array()
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        $list = new Model_ListMaster();
        $result = $list->getMasterList();

        foreach ($result as $lst) {
            $this->_list["list_master"][''] = 'Select';
            $this->_list["list_master"][$lst->getPkId()] = $lst->getListMasterName();
        }

        foreach ($this->_fields as $col => $name) {
            if ($col == "list_value") {
                parent::createText($col);
            }

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
    }

}
