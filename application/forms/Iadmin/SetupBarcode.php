<?php

/**
 * Form_Iadmin_SetupBarcode
 *
 *
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Iadmin Setup Barcode
 */
class Form_Iadmin_SetupBarcode extends Form_Base {

    /**
     * Form Fields
     *
     * Private Variable
     *
     * @item_pack_size_id: Product
     * @item_pack_size_id_update: Product
     * @stakeholder_id: Manufacturer
     * @stakeholder_id_update: Manufacturer
     * @item_gtin: Item GTIN
     * @pack_size_description: Pack Size
     * @length: Length
     * @width: Width
     * @height: Height
     * @packaging_level: Packaging Level
     * @packaging_level_update: Packaging Level
     * @quantity_per_pack: Vials Pcs
     * @volum_per_vial: Volume
     *
     * @var type
     *
     */
    private $_fields = array(
        "item_pack_size_id" => "Product",
        "item_pack_size_id_update" => "Product",
        "stakeholder_id" => "Manufacturer",
        "stakeholder_id_update" => "Manufacturer",
        "item_gtin" => "Item GTIN",
        "pack_size_description" => "Pack Size",
        "length" => "Length",
        "width" => "Width",
        "height" => "Height",
        "packaging_level" => "Packaging Level",
        "packaging_level_update" => "Packaging Level",
        "quantity_per_pack" => "Vials Pcs",
        "volum_per_vial" => "Volume",
    );

    /**
     * $_list
     * @var type
     * item_pack_size_id
     * stakeholder_id
     * stakeholder_id_update
     * packaging_level
     * packaging_level_update
     */
    private $_list = array(
        'item_pack_size_id' => array(),
        'stakeholder_id' => array('' => "Select Manufacturer"),
        'stakeholder_id_update' => array('' => "Select Manufacturer"),
        'packaging_level' => array(),
        'packaging_level_update' => array()
    );

    /**
     * $_hidden
     * @var type\
     * barcode_id
     * barcode_ty_id
     * item_pack_size_id_hidden
     * stakeholder_id_update_hidden
     */
    private $_hidden = array(
        "barcode_id" => "",
        "barcode_ty_id" => "",
        "item_pack_size_id_hidden" => "",
        "stakeholder_id_update_hidden" => "",
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        //Generate Item Combo
        $item_pack_size = new Model_ItemPackSizes();
        /**
         * Get All Items
         * Parameter: null
         * return All Items
         */
        $result = $item_pack_size->getItemsAll();
        $this->_list["item_pack_size_id"][''] = "Select Product";
        $this->_list["item_pack_size_id_update"][''] = "Select Product";
        if ($result) {
            foreach ($result as $row) {
                $this->_list["item_pack_size_id"][$row->getPkId()] = $row->getItemName();
                $this->_list["item_pack_size_id_update"][$row->getPkId()] = $row->getItemName();
            }
        }
        /**
         * Generate Pack type combo
         * 
         */
        /**
         * Get List Detail
         * Parameter: null
         * return List Detail
         */
        $list = new Model_ListDetail();
        $list->form_values = array('listMaster' => Model_ListMaster::PACKAGING_LEVEL);
        $result2 = $list->getListDetail();
        $this->_list["packaging_level"][''] = "Select Packaging Level";
        $this->_list["packaging_level_update"][''] = "Select Packaging Level";
        if ($result2) {
            foreach ($result2 as $packagingLevel) {
                $this->_list["packaging_level"][$packagingLevel->getPkId()] = $packagingLevel->getListValue();
                $this->_list["packaging_level_update"][$packagingLevel->getPkId()] = $packagingLevel->getListValue();
            }
        }
        /**
         * Generate Hidden Fields
         */
        foreach ($this->_hidden as $col => $name) {
            switch ($col) {
                case "barcode_id":
                case "barcode_ty_id":
                    parent::createHidden($col);
                    break;
                default:
                    break;
            }
        }
        /**
         * Generate Text Fields
         */
        foreach ($this->_fields as $col => $name) {
            /**
             * item_gtin
             * quantity_per_pack
             * volum_per_vial
             * pack_size_description
             */
            switch ($col) {
                case "item_gtin":
                case "quantity_per_pack":
                case "volum_per_vial":
                case "pack_size_description";
                    parent::createText($col);
                    break;
                /**
                 * length
                 */
                case "length";
                    parent::createTextWithPlaceholder($col, "Length");
                    break;
                /**
                 * width
                 */
                case "width";
                    parent::createTextWithPlaceholder($col, "Width");
                    break;
                /**
                 * height
                 */
                case "height";
                    parent::createTextWithPlaceholder($col, "Height");
                    break;
                default:
                    break;
            }
            /**
             * Generate Select Boxes
             */
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelectWithValidator($col, $name, $this->_list[$col]);
            }
        }
        /**
         * Generate Hidden Fields
         */
        foreach ($this->_hidden as $col => $name) {
            switch ($col) {


                case "item_pack_size_id_hidden":
                case "stakeholder_id_update_hidden":
                    parent::createHidden($col);
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Add Hidden Fields
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

    /**
     * Read Fields
     */
    public function readFields() {
        $this->getElement('item_pack_size_id')->setAttrib("disabled", "true");
        $this->getElement('stakeholder_id')->setAttrib("disabled", "true");
        $this->getElement('pack_size_description')->setAttrib("readonly", "true");
        $this->getElement('length')->setAttrib("disabled", "true");
        $this->getElement('width')->setAttrib("disabled", "true");
        $this->getElement('height')->setAttrib("disabled", "true");
        $this->getElement('quantity_per_pack')->setAttrib("disabled", "true");
        $this->getElement('volum_per_vial')->setAttrib("disabled", "true");
    }

}
