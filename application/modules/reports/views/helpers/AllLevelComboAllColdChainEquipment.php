<?php
/**
 * Zend_View_Helper_AllLevelComboAllColdChainEquipment
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage reports
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper All Level Combo All Cold Chain Equipment
 */
class Zend_View_Helper_AllLevelComboAllColdChainEquipment extends Zend_View_Helper_Abstract {

    /**
     * All Level Combo All Cold Chain Equipment
     * @param type $data_array
     * @param type $office_term
     * @param type $postfix  
     * @return boolean
     */
    public function allLevelComboAllColdChainEquipment($data_array, $office_term = "", $postfix = null) {

        $identity = App_Auth::getInstance();
        $translate = Zend_Registry::get('Zend_Translate');
        $base_url = Zend_Registry::get('baseurl');
        $user_lvl = $identity->getUserLevel($identity->getIdentity());
        $stakeholder_id = $identity->getStakeholderId();

        $warehouse = new Model_Warehouses();
        $office = $data_array["level"];
        $province = $data_array["province"];
        $district = $data_array["district"];
        $warehouse_id = $data_array["warehouse"];
        if ($office == 1) {
            $warehouse->form_values = array('stakeholder_id' => $stakeholder_id);
            if ($office == 1) {
                $warehouse_array = $warehouse->getFederalWarehouses();
            }
        }

        if (!empty($province)) {
            $locations = new Model_Locations();
            $locations->form_values = array('parent_id' => 10, 'geo_level_id' => 2);
            $provinces_array = $locations->getLocationsByLevel();

            if (empty($district)) {
                $warehouse->form_values = array('province_id' => $province, 'stakeholder_id' => $stakeholder_id);
                switch ($office) {
                    case 1:
                        $warehouse_array = $warehouse->getFederalWarehouses();
                        break;
                    case 2:
                        $warehouse_array = $warehouse->getProvincialWarehouses();
                        break;
                    case 4:
                        $warehouse_array = $warehouse->getDistrictWarehousesofProvince();
                        break;
                    default:
                        break;
                }
            }
        }


        if (!empty($district)) {
            $location = new Model_Locations();
            $location->form_values = array('province_id' => $province, 'geo_level_id' => 4);
            $districts_array = $location->getLocationsByLevelByProvince();
            $warehouse->form_values = array('district_id' => $district, 'stakeholder_id' => $stakeholder_id);
            switch ($office) {
                case 5:
                case 8:
                    $warehouse_array = $warehouse->getTehsilWarehousesofDistrict();
                    break;
                case 6:
                case 9:
                    $warehouse_array = $warehouse->getUCWarehousesofDistrict();
                    break;
                default:
                    break;
            }
        }

        switch ($user_lvl) {
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
                $arr_levels = array(
                    '1' => $translate->translate('National'),
                    '2' => $translate->translate('Province'),
                    //'3' => $translate->translate('Division'),
                    '4' => $translate->translate('District'),
                    '8' => $translate->translate('Tehsil-Taluka'),
                    '9' => $translate->translate('UC')
                );
                break;


            case 6:
                $arr_levels = array(
                    '2' => $translate->translate('Province'),
                    //'3' => $translate->translate('Division'),
                    '5' => $translate->translate('Tehsil-Taluka'),
                    '6' => $translate->translate('Union Council')
                );
                break;
            case 7:
                $arr_levels = array(
                    '7' => $translate->translate('District'),
                    '6' => $translate->translate('Union Council')
                );
            case 8:
                $arr_levels = array(
                    '7' => $translate->translate('District'),
                    '5' => $translate->translate('Tehsil-Taluka'),
                    '6' => $translate->translate('Union Council')
                );
                break;
            default:
                $arr_levels = array(
                    '1' => $translate->translate('National'),
                    '2' => $translate->translate('Province'),
                    '4' => $translate->translate('District'),
                    '5' => $translate->translate('Tehsil-Taluka'),
                    '6' => $translate->translate('Union Council')
                );
                break;
        }
        ?>
        <div class="row">
            <div class="col-md-12" id="all_level_combo">
                <div class="col-md-3">
                    <div class="control-group">
                        <label class="control-label" for="office" class="col-md-7"><?php echo $translate->translate("Level"); ?></label>
                        <div class="controls">
                            <select name="office" id="office<?php echo $postfix; ?>" class="form-control">
                                <option value=""><?php echo $translate->translate("Select"); ?></option>
                                <?php
                                foreach ($arr_levels as $key => $value) {
                                    ?>
                                    <option value="<?php echo $key; ?>" <?php
                                    if (!empty($office) && $key == $office) {
                                        echo 'selected';
                                    }
                                    ?>><?php echo $value ?></option>
                                        <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3" id="div_combo1<?php echo $postfix; ?>" <?php if (($office == 2) || !empty($province)) { ?> style="display:block;" <?php } else { ?> style="display:none;"<?php } ?>>
                    <label class="control-label" id="lblcombo1"><?php echo $translate->translate("Province"); ?> </label>
                    <div class="controls">
                        <select name="combo1" id="combo1<?php echo $postfix; ?>" class="form-control">
                            <?php if ($provinces_array) { ?>
                                <option value=""><?php echo $translate->translate("Select"); ?></option>
                                <?php foreach ($provinces_array as $row) {
                                    ?>
                                    <option value="<?php echo $row['key']; ?>" <?php
                                    if (!empty($province) && $row['key'] == $province) {
                                        echo 'selected';
                                    }
                                    ?>>
                                        <?php echo $row['value']; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3" id="div_combo2<?php echo $postfix; ?>" <?php if (($office == 5) || !empty($district)) { ?> style="display:block;" <?php } else { ?> style="display:none;"<?php } ?>>
                    <label class="control-label" id="lblcombo2"><?php echo $translate->translate("District"); ?> </label>
                    <div class="controls">
                        <select name="combo2" id="combo2<?php echo $postfix; ?>" class="form-control">
                            <?php if ($districts_array) { ?>
                                <option value=""><?php echo $translate->translate("Select"); ?></option>
                                <?php foreach ($districts_array as $row) {
                                    ?>
                                    <option value="<?php echo $row['key']; ?>" <?php
                                    if (!empty($district) && $row['key'] == $district) {
                                        echo 'selected';
                                    } 
                                    ?>>
                                        <?php echo $row['value']; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3" id="wh_combo<?php echo $postfix; ?>">
                    <label class="control-label" id="wh_l"><?php echo $translate->translate("Warehouse"); ?> <span id="compulsory" class="red">*</span></label>
                    <div class="controls">
                        <select name="warehouse<?php echo $postfix; ?>" id="warehouse<?php echo $postfix; ?>" class="form-control">
                            <?php if ($warehouse_array) { ?>
                                <option value=""><?php echo $translate->translate("Select"); ?></option>
                                <?php foreach ($warehouse_array as $row) {
                                    ?>
                                    <option value="<?php echo $row['key']; ?>" <?php
                                    if (!empty($warehouse_id) && $row['key'] == $warehouse_id) {
                                        echo 'selected';
                                    }
                                    ?>>
                                        <?php echo $row['value']; ?></option>
                                    <?php
                                }
                            }
                            ?>

                        </select>
                    </div>
                </div>
                <div class="col-md-1" id="loader<?php echo $postfix; ?>" style="display:none;"><img src="<?php echo $base_url; ?>/images/loader.gif" style="margin-top:8px; float:left" alt="" /></div>
            </div>
        </div>
        <?php
        return true;
    }

}
?>