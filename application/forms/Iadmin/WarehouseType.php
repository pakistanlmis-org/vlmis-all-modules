<?php

/**
 * Form_Iadmin_WarehouseType
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Muhammad Imran <muhammad_imran@pk.jsi.com>
 * @version    2.5.1
 */

/**
 *  Form for Iadmin GeoLevels
 */
class Form_Iadmin_WarehouseType extends Form_Base {

    /**
     * Fields for form
     * wh_category_name
     */
    private $_fields = array(
        "warehouse_type_name" => "warehouse_type_name",
        "resupply_interval" => "resupply_interval",
        "reserved_stock" => "reserved_stock",
        "usage_percentage" => "usage_percentage",
        "list_rank" => "list_rank",
        "geo_level" => "geo_level",
        "warehouse_type_category" => "warehouse_type_category"
    );

    /**
     * Hidden fields for form
     * Country Name Hidden
     * Country Id
     */
    private $_hidden = array(
        "warehouse_type_name_hidden" => "warehouse_type_name_hidden",
        "warehouse_type_id" => "warehouse_type_id"
    );
    private $_list = array(
        'geo_level' => array(),
        'warehouse_type_category' => array()
    );
    private $_radio = array(
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        //Populate Geo Level combo
        $warehouse_types = new Model_WarehouseTypes();
        $geo_levels = $warehouse_types->getAllGeoLevels();
        $this->_list["geo_level"][''] = "Select";
        foreach ($geo_levels as $row) {
            $this->_list["geo_level"][$row['pk_id']] = $row['geo_level_name'];
        }
        //Populate Warehouse Type Category combo
        $warehouse_type_categories = new Model_WarehouseTypeCategories ();
        $wh_type_categories = $warehouse_type_categories->getAllWarehouseTypeCategories();
        $this->_list["warehouse_type_category"][''] = "Select";
        foreach ($wh_type_categories as $row2) {
            $this->_list["warehouse_type_category"][$row2['pk_id']] = $row2['category_name'];
        }
        //

        $max_rank = $warehouse_types->getMaxRank();
        $rank = $max_rank[0]['max_rank'] +1;
        
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "warehouse_type_name":
                case "resupply_interval":
                case "reserved_stock":
                case "usage_percentage":
                    parent::createText($col);
                    break;
                case "list_rank":
                    parent::createTextWithValue($col, $rank);
                    break;
                default:
                    break;
            }
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
            if (in_array($col, array_keys($this->_radio))) {
                parent::createRadio($col, $this->_radio[$col]);
            }
        }
        //Creating Hidden Fields
        foreach ($this->_hidden as $col => $name) {
            switch ($col) {

                case "warehouse_type_name_hidden":
                case "warehouse_type_id":
                    parent::createHidden($col);
                    break;
                default:
                    break;
            }
        }
    }

}
