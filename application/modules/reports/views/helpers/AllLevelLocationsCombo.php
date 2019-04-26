<?php
/**
 * Zend_View_Helper_AllLevelLocationsCombo
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage reports
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zenz View Helper All Level Locations Combo
 */
class Zend_View_Helper_AllLevelLocationsCombo extends Zend_View_Helper_Abstract {

    /**
     * All Level Locations Combo
     * Used to get all levels locations
     * combo
     * @param type $office_term
     * @param type $postfix
     * @return boolean
     */
    public function allLevelLocationsCombo($office_term = "", $postfix = null) {

        // Get login user session
        $identity = App_Auth::getInstance();
        // Get translater instance.
        $translate = Zend_Registry::get('Zend_Translate');
        // Get base URL.
        $base_url = Zend_Registry::get('baseurl');
        // Get role id.
        $user_lvl = $identity->getRoleId();

        // Check user level.
        switch ($user_lvl) {
            // Check if level is 1 to 4
            case 1:
            case 2:
            case 3:
            case 4:
                $arr_province = array(
                    '1' => $translate->translate('Federal'),
                    '2' => $translate->translate('Province')
                );
                break;
            // Check if level is 5 to 6
            case 5:
            case 6:
                $arr_province = array(
                    '2' => $translate->translate('Province')
                );
                break;
            // Check if level is 7
            case 7:
                $arr_province = array(
                );
            // Check if level is 8
            case 8:
                $arr_province = array(
                    '7' => $translate->translate('District')
                );
                break;
            // Default case.
            default:
                $arr_province = array(
                    '1' => $translate->translate('Federal'),
                    '2' => $translate->translate('Province')
                );
                break;
        }
        ?>
        <div class="control-group span12" id="all_level_combo">
            <div class="col-md-3">
                <label class="control-label" for="office" class="col-md-7"><?php
                    if (empty($office_term)) {
                        echo $translate->translate("Office");
                    } else {
                        echo $office_term;
                    }
                    ?> <span class="red">*</span></label>
                <div class="controls">
                    <select name="office" id="office<?php echo $postfix; ?>" class="col-md-10">
                        <option value=""><?php echo $translate->translate("Select"); ?></option>
                        <?php
                        foreach ($arr_province as $key => $value) {
                            ?>
                            <option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3" id="div_combo1<?php echo $postfix; ?>" <?php if (empty($translate->prov_id) || isset($translate->office_id) == 1 || empty($translate->office_id)) { ?> style="display:none;" <?php } else { ?> style="display:block;"<?php } ?>>
                <label class="control-label" id="lblcombo1"><?php echo $translate->translate("Province"); ?> <span class="red">*</span></label>
                <div class="controls">
                    <select name="combo1" id="combo1<?php echo $postfix; ?>" class="col-md-10">
                    </select>
                </div>
            </div>
            <div class="col-md-3" id="div_combo2<?php echo $postfix; ?>" <?php if (empty($translate->dist_id) || isset($translate->office_id) == 1 || empty($translate->office_id)) { ?> style="display:none;" <?php } ?>>
                <label class="control-label" id="lblcombo2"><?php echo $translate->translate("District"); ?> <span class="red">*</span></label>
                <div class="controls">
                    <select name="combo2" id="combo2<?php echo $postfix; ?>" class="col-md-10">
                    </select>
                </div>
            </div>
            <div class="col-md-1" id="loader<?php echo $postfix; ?>" style="display:none;"><img src="<?php echo $base_url; ?>/images/loader.gif" style="margin-top:8px; float:left" alt="" /></div>
        </div>
        <?php
        return true;
    }

}
?>