<?php
/**
 * Zend_View_Helper_AllLevelAreaCombo
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage reports
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper All Level Area Combo
 */
class Zend_View_Helper_AllLevelAreaCombo extends Zend_View_Helper_Abstract {

    /**
     * All Level Area Combo
     * @param type $data_array
     * @return boolean
     */
    public function allLevelAreaCombo($data_array) {
        $identity = App_Auth::getInstance();
        $translate = Zend_Registry::get('Zend_Translate');
        $base_url = Zend_Registry::get('baseurl');
        $user_lvl = $identity->getRoleId();
        $districts_array = false;

        $office = $data_array["level"];
        $province = $data_array["province"];
        $district = $data_array["district"];
        $facility_type = $data_array["facility_type"];

        if (!empty($province)) {
            $locations = new Model_Locations();
            $locations->form_values = array('parent_id' => 10, 'geo_level_id' => 2);
            $provinces_array = $locations->getLocationsByLevel();
        }
        if (!empty($district)) {
            $location = new Model_Locations();
            $location->form_values = array('province_id' => $province, 'geo_level_id' => 4);
            $districts_array = $location->getLocationsByLevelByProvince();
        }

        switch ($user_lvl) {
            case 1:
            case 2:
            case 3:
            case 4:
            case 6:
                $arr_province = array(
                    '1' => $translate->translate('National'),
                    '2' => $translate->translate('Province'),
                    '6' => $translate->translate('District')
                );
                break;
            case 5:
                $arr_province = array(
                    '2' => $translate->translate('Province')
                );
                break;
            case 7:
                $arr_province = array();
            case 8:
                $arr_province = array(
                    '6' => $translate->translate('District')
                );
                break;
            default:
                $arr_province = array(
                    '1' => $translate->translate('National'),
                    '2' => $translate->translate('Province'),
                    '6' => $translate->translate('District')
                );
                break;
        }

        if ($facility_type == 3) {
            $arr_province = array(
                '1' => $translate->translate('National'),
                '2' => $translate->translate('Province'),
                '6' => $translate->translate('District')
            );
        }
        ?>

        <div class="col-md-2" id="div_office_combo">
            <label class="control-label" for="office"><?php
                if (empty($office_term)) {
                    echo $translate->translate("Office");
                } else {
                    echo $office_term;
                }
                ?> </label>
            <div class="controls">
                <select name="office" id="office" class="form-control input-small">
                    <option value=""><?php echo $translate->translate("Select"); ?></option>
                    <?php
                    foreach ($arr_province as $key => $value) {
                        ?>
                        <option value="<?php echo $key; ?>" <?php if ($key == $office) { ?>selected=""<?php } ?> ><?php echo $value; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="col-md-2" id="div_combo1" <?php if (empty($province)) { ?> style="display:none;" <?php } ?>>
            <label class="control-label" id="lblcombo1">
                <?php echo $translate->translate("Province"); ?> 
                <span class="red">*</span>
            </label>
            <div class="controls">
                <select name="combo1" id="combo1" class="form-control input-small">
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
        <div class="col-md-2" id="div_combo2" <?php if ($district == '') { ?> style="display:none;" <?php } ?>>
            <label class="control-label" id="lblcombo2">
                <?php echo $translate->translate("District"); ?> 
                <span class="red">*</span>
            </label>
            <div class="controls">
                <select name="combo2" id="combo2" class="form-control input-small">
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
        <div class="col-md-1" id="loader" style="display:none;">
            <img src="<?php echo $base_url; ?>/images/loader.gif" style="margin-top:8px; float:left" alt="" />
        </div>
        <?php
        return true;
    }

}
?>