<?php

/**
 * Form_Iadmin_Stores
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Iadmin Stores
 */
class Form_Iadmin_Stores extends Form_Base {

    /**
     * Fields 
     * for Form_Iadmin_Stores
     * 
     * 
     * store_name_add
     * store_name_update
     * ccm_warehouse_id
     * ccm_warehouse_id_update
     * warehouse_type
     * warehouse_type_update
     * 
     * 
     * 
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "store_name_add" => "store/facility Name",
        "store_name_update" => "store/facility Name",
        "ccm_warehouse_id" => "CCEM Code",
        "ccm_warehouse_id_update" => "CCEM Code",
        "warehouse_type" => "warehouse_type",
        "warehouse_type_update" => "warehouse_type_update",
        "starting_on" => "starting_on",
        "from_edit" => "from_edit",
        "starting_on_update" => "starting_on_update",
        "from_edit_update" => "from_edit_update",
        "population_update"=>"population_update",
        "population"=>"Population"
    );

    /**
     * Hidden fields
     * for Form_Iadmin_Stores
     * 
     * 
     * wh_id
     * office_type
     * province_id
     * district_id
     * tehsil_id
     * parent_id
     * office_id_edit
     * province_id_edit
     * district_id_edit
     * tehsil_id_edit
     * parent_id_edit
     * warehouse_type_id_hidden
     * 
     * 
     * $_hidden
     * @var type 
     */
    private $_hidden = array(
        "wh_id" => "pkId",
        "office_type" => "pkId",
        "province_id" => "pkId",
        "district_id" => "pkId",
        "tehsil_id" => "pkId",
        "parent_id" => "pkId",
        "office_id_edit" => "pkId",
        "province_id_edit" => "pkId",
        "district_id_edit" => "pkId",
        "tehsil_id_edit" => "pkId",
        "parent_id_edit" => "pkId",
        "warehouse_type_id_hidden" => "pkId"
    );

    /**
     * Combo boxes for 
     * Form_Iadmin_Stores
     * 
     * 
     * warehouse_type
     * warehouse_type_update
     * 
     * 
     * $_list
     * @var type 
     */
    private $_list = array(
        'warehouse_type' => array(),
        'warehouse_type_update' => array()
    );

    /**
     * Initializes Form Fields
     * for Form_Iadmin_Stores
     */
    public function init() {

        // Generate fields 
        // for Form_Iadmin_Stores
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "store_name_add":
                case "store_name_update":
                case "ccm_warehouse_id":
                case "ccm_warehouse_id_update":
                     case "population":
                case "population_update";
                    parent::createText($col);
                    break;
                case "starting_on":
                case "from_edit":
                case "starting_on_update":
                case "from_edit_update":    
                    parent::createReadOnlyText($col);
                    break;
                default:
                    break;
            }

            // Generate Combo boxes 
            // for Form_Iadmin_Stores
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }

        // Generate hidden fields 
        // for Form_Iadmin_Stores
        foreach ($this->_hidden as $col => $name) {
            switch ($col) {


                case "wh_id":
                case "office_type":
                case "office_id_edit":
                case "province_id":
                case "district_id":
                case "tehsil_id":
                case "parent_id":
                case "province_id_edit":
                case "district_id_edit":
                case "tehsil_id_edit":
                case "parent_id_edit":
                case "warehouse_type_id_hidden":
                    parent::createHidden($col);
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Add Hidden Fields
     * for Form_Iadmin_Stores
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

}
