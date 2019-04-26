<?php

/**
 * Form_Cadmin_ModelsSearch
 *
 * 
 *
 * @package    Logistics Management Information System for Vaccines
 * @subpackage Cadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 * @package Form for Cadmin Models Search
 */
class Form_Cadmin_CapacitySearch extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * @var type 
     */
    private $_fields = array(
        'ccm_asset_type_id' => 'Asset Type',
    );

    /**
     * $_list
     * 
     * Private Variable
     * 
     * @var type 
     */
    private $_list = array(
        "ccm_asset_type_id" => array()
    );

    /**
     * $_hidden
     * 
     * Private Variable
     * 
     * @var type 
     */
    private $_hidden = array(
        "office_id" => "",
        "combo1_id" => "",
        "combo2_id" => "",
        "warehouse_id" => "",
    );

    public function init() {
        //Generate Asset Types Combo
        $asset_type = new Model_CcmAssetTypes();
        $result4 = $asset_type->getAssetTypes();

        //foreach loop    
        foreach ($result4 as $rs) {
            $this->_list["ccm_asset_type_id"][$rs['pkId']] = $rs['assetTypeName'];
        }
        //foreach loop
        foreach ($this->_fields as $col => $name) {

            //if loop
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
        
        //foreach loop
        foreach ($this->_hidden as $col => $name) {
            switch ($col) {

                case "office_id":
                case "combo1_id":
                case "combo2_id":
                case "warehouse_id" :
                    parent::createHidden($col);
                    break;
                default:
                    break;
            }
        }
    }

}
