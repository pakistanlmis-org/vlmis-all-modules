<?php

/**
 * Form_Cadmin_List
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Cadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Cadmin List
 */
class Form_Cadmin_List extends Form_Base {

    /**
     * Fields for 
     * Form_Cadmin_List
     * 
     * 
     * list_master
     * list_value
     * description
     * 
     * 
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "list_master" => "List Master",
        "list_value" => "List Detail Value",
        "description" => "Description"
    );

    /**
     * Combo boxes for Form_Cadmin_List
     * 
     * list_master
     * 
     * 
     * $_list
     * @var type 
     */
    private $_list = array(
        "list_master" => array()
    );

    /**
     * Initializes Form Fields
     * for Form_Cadmin_List
     */
    public function init() {
        //Generate Model
        $list = new Model_ListMaster();
        $result = $list->getMasterList();

        //Popilate combo
        foreach ($result as $lst) {
            $this->_list["list_master"][''] = 'Select';
            $this->_list["list_master"][$lst->getPkId()] = $lst->getListMasterName();
        }

        // Generate fields
        //for Form_Cadmin_List
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "list_value":
                    parent::createText($col);
                    break;
                case "description":
                    /**
                     * $col: Textarea Field Name
                     * $rows: 8
                     */
                    parent::createMultiLineText($col, 8);
                    break;
                default:
                    break;
            }

            //Generate combos
            //for Form_Cadmin_List
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
    }

    /**
     * Add Hidden Fields
     * for Form_Cadmin_List
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

    /**
     * Add Fields
     * for Form_Cadmin_List
     */
    public function addFields() {
        $this->getElement('list_master')->setAttrib("disabled", "true");
    }

}
