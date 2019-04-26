<?php
/**
 * Zend_View_Helper_UcLevelCombo
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage cadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper Uc Level Combo
 * 
 * Inherits: Zend View Helper Abstract
 */
class Zend_View_Helper_UcLevelCombo extends Zend_View_Helper_Abstract {

    /**
     * UC Level Combo
     * 
     * Public Function
     * 
     * @param type $office_term
     * @param type $postfix
     * @return boolean
     */
    public function ucLevelCombo($office_term = "", $postfix = null) {

        $identity = App_Auth::getInstance();
        $translate = Zend_Registry::get('Zend_Translate');
        $base_url = Zend_Registry::get('baseurl');
        $user_lvl = $identity->getRoleId();

        if ($user_lvl) {
            $arr_province = array(
                '6' => $translate->translate('Union Council')
            );
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
                    <select name="office" id="office<?php echo $postfix; ?>" class="form-control">
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
                    <select name="combo1" id="combo1<?php echo $postfix; ?>" class="form-control">
                    </select>
                </div>
            </div>
            <div class="col-md-3" id="div_combo2<?php echo $postfix; ?>" <?php if (empty($translate->dist_id) || isset($translate->office_id) == 1 || empty($translate->office_id)) { ?> style="display:none;" <?php } ?>>
                <label class="control-label" id="lblcombo2"><?php echo $translate->translate("District"); ?> <span class="red">*</span></label>
                <div class="controls">
                    <select name="combo2" id="combo2<?php echo $postfix; ?>" class="form-control">
                    </select>
                </div>
            </div>
            <div class="col-md-3" id="wh_combo<?php echo $postfix; ?>" <?php if (empty($translate->warehouse)) { ?> style="display:none;" <?php } ?>>
                <label class="control-label" id="wh_l"><?php echo $translate->translate("Warehouse"); ?> <span class="red">*</span></label>
                <div class="controls">
                    <select name="warehouse<?php echo $postfix; ?>" id="warehouse<?php echo $postfix; ?>" class="form-control">
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