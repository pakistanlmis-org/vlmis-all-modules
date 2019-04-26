<?php

/**
 * Form_Cadmin_ModelsAdd
 *
 * 
 *
 * @package    Logistics Management Information System for Vaccines
 * @subpackage Cadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 * @package Form for Cadmin Models Add
 */
class Form_Cadmin_CapacityAdd extends Form_Base {

    /**
     * $_fields
     * 
     * Form Fields
     * @asset: Asset
     * @ccm_asset_type_id: CCM Asset Type Id
     * @ccm_asset_type_id_add: CCM Asset Type Id Add
     * @ccm_asset_sub_type: Asset Sub Type
     * @gross: Gross
     * @net: Net
     * 
     * @var type 
     */
    private $_fields = array(
        'asset' => 'asset',
        'ccm_asset_type_id' => 'ccm_asset_type_id',
        'ccm_asset_type_id_add' => 'ccm_asset_type_id_add',
        'ccm_asset_sub_type' => 'asset sub type',
        'gross' => 'gross',
        'net' => 'net'
    );
    
    /**
     * $_list
     * 
     * List
     * @ccm_asset_type_id
     * @ccm_asset_sub_type
     * @ccm_asset_type_id_add
     * 
     * @var type 
     */
    private $_list = array(
        "ccm_asset_type_id" => array(),
        "ccm_asset_sub_type" => array('' => 'Select Asset Type First'),
        "ccm_asset_type_id_add" => array()
    );

    /**
     * Initializes Form Fields
     */
    public function init() {



        //Generate Asset Types Combo
        $asset_type = new Model_CcmAssetTypes();
        $result3 = $asset_type->getAssetTypes();



        foreach ($result3 as $rs) {
            $this->_list["ccm_asset_type_id"][$rs['pkId']] = $rs['assetTypeName'];
            $this->_list["ccm_asset_type_id"][$rs['pkId']] = $rs['assetTypeName'];
        }


        foreach ($result3 as $rs) {
            $this->_list["ccm_asset_type_id_add"][$rs['pkId']] = $rs['assetTypeName'];
            $this->_list["ccm_asset_type_id_add"][$rs['pkId']] = $rs['assetTypeName'];
        }
        
        $this->_list["ccm_asset_sub_type"][''] = "Select";

        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "asset":
                case "gross":
                case "net":
                    parent::createText($col);
                    break;
                default:
                    break;
            }

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
    }

    /**
     * Add Hidden
     */
    public function addHidden() {
               parent::createHiddenWithValidator("id");
    }

}
