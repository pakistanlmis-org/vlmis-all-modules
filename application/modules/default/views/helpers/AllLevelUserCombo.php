<?php
/**
 * Zend_View_Helper_AllLevelUserCombo
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper All Level User Combo
 */
class Zend_View_Helper_AllLevelUserCombo extends Zend_View_Helper_Abstract {

    /**
     * All Level User Combo
     * @param type $office_term
     * @param type $postfix
     * @param type $menu_type
     * @return boolean
     */
    public function allLevelUserCombo($office_term = "", $postfix = null, $menu_type = 1) {

        $identity = App_Auth::getInstance();
        $translate = Zend_Registry::get('Zend_Translate');
        $base_url = Zend_Registry::get('baseurl');
        $user_lvl = $identity->getUserLevel($identity->getIdentity());

        if ($menu_type == 1) {
            switch ($user_lvl) {
                case 1:

                    $arr_province = array(
                        '1' => $translate->translate('Federal'),
                        '2' => $translate->translate('Province'),
                    );
                    break;
                case 2:
                case 5:
                    $arr_province = array(
                        '2' => $translate->translate('Province'),
                        '3' => $translate->translate('Division'),
                        '4' => $translate->translate('District')
                    );
                    break;
                case 3:
                    $arr_province = array(
                        '3' => $translate->translate('Division'),
                    );
                    break;
                case 4:
                    $arr_province = array(
                        '4' => $translate->translate('District')
                    );
                    break;
                case 6:
                    $arr_province = array(
                        '2' => $translate->translate('Province'),
                        '3' => $translate->translate('Division'),
                        '5' => $translate->translate('Tehsil-Taluka'),
                        '6' => $translate->translate('Union Council')
                    );
                    break;
                case 7:
                    $arr_province = array(
                        '7' => $translate->translate('District'),
                        '6' => $translate->translate('Union Council')
                    );
                case 8:
                    $arr_province = array(
                        '7' => $translate->translate('District'),
                        '5' => $translate->translate('Tehsil-Taluka'),
                        '6' => $translate->translate('Union Council')
                    );
                    break;
                default:
                    $arr_province = array(
                        '1' => $translate->translate('Federal'),
                        '2' => $translate->translate('Province'),
                        '3' => $translate->translate('Division'),
                        '4' => $translate->translate('District'),
                        '5' => $translate->translate('Tehsil-Taluka'),
                        '6' => $translate->translate('Union Council')
                    );
                    break;
            }
        } else {
            switch ($user_lvl) {
                case 1:
                    $arr_province = array(
                        '2' => 'Province',
                        '3' => 'Division',
                        '4' => 'District',
                        '60' => 'IHR'
                    );
                    break;
                case 2:
                    $arr_province = array(
                        '1' => 'Federal',
                        '2' => 'Province',
                        '3' => 'Division',
                        '4' => 'District',
                        '60' => 'IHR'
                    );
                    break;
                case 3:
                    $arr_province = array(
                        '5' => 'Province',
                        '6' => 'Division',
                        '7' => 'District',
                        '60' => 'IHR'
                    );
                    break;
                case 4:
                    $arr_province = array(
                        '5' => 'Province',
                        '6' => 'Division',
                        '7' => 'District',
                        '8' => 'Tehsil-Taluka',
                        '9' => 'UC',
                        '60' => 'IHR'
                    );
                    break;
                case 5:
                case 6:
                    $arr_province = array(
                        '7' => 'District',
                        '8' => 'Tehsil-Taluka',
                        '9' => 'UC',
                    );
                    break;
                default:
                    $arr_province = array(
                        '5' => 'Province',
                        '6' => 'Division',
                        '7' => 'District',
                        '8' => 'Tehsil-Taluka',
                        '9' => 'UC'
                    );
                    break;
            }
        }
        ?>
        <div class="row">
            <div class="col-md-12" id="all_level_combo">
                <div class="col-md-3">
                    <div class="control-group">
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
        </div>
        <?php
        return true;
    }

}
?>