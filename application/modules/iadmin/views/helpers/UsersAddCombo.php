<?php
/**
 * Zend_View_Helper_UsersAddCombo
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper Users Add Combo
 */
class Zend_View_Helper_UsersAddCombo extends Zend_View_Helper_Abstract {

    /**
     * Users Add Combo
     * Used to load users combo
     * to add
     * @param type $office_term
     */
    public function usersAddCombo($office_term, $role_id) {
        // Get translater instance.
        $translate = Zend_Registry::get('Zend_Translate');
        // Get base URL.
        $base_url = Zend_Registry::get('baseurl');
        // If routine purpose.
        if ($office_term == "routine") {
            // Init locations array
            $arr_location_level = array(
                '1' => $translate->translate('National'),
                '2' => $translate->translate('Provincial'),
                '3' => $translate->translate('Division'),
                '4' => $translate->translate('District'),
                '5' => $translate->translate('Tehsil'),
                '6' => $translate->translate('EPI Center')
            );
        }

        // Check for Immunization.
        if ($office_term == "im") {
            // Init locations array
            $arr_location_level = array(
                '1' => $translate->translate('National'),
                '2' => $translate->translate('Provincial'),
                '3' => $translate->translate('Division'),
                '4' => $translate->translate('District'),
                '5' => $translate->translate('Tehsil'),
            );
        }
        // Check purpose.
        if ($office_term == "pro-admin" && $role_id == 38) {
            // Init locations levels array.
            $arr_location_level = array(
                '3' => $translate->translate('Division'),
                '4' => $translate->translate('District'),
            );
        }

        if ($office_term == "pro-admin" && $role_id == 39) {
            // Init locations levels array.
            $arr_location_level = array(
                '5' => $translate->translate('Tehsil')
            );
        }
        // Check for campaigns.
        if ($office_term == "campaigns") {
            // Init locations array
            $arr_location_level = array(
                '1' => $translate->translate('National'),
                '2' => $translate->translate('Provincial'),
                '4' => $translate->translate('District')
            );
        }
        ?>

        <div class="col-md-4">
            <label class="control-label" for="office_type" class="col-md-7"><?php
                echo $translate->translate("Office Type");
                ?> <span class="red">*</span></label>
            <div class="controls">
                <select name="office_type_add" id="office_type_add" class="form-control">
                    <option value=""><?php echo $translate->translate("Select"); ?></option>
                    <?php
                    foreach ($arr_location_level as $key => $value) {
                        ?>
                        <option value="<?php echo $key; ?>" <?php
                        if (!empty($office_term['office_type_add']) && $key == $office_term['office_type_add']) {
                            echo 'selected';
                        }
                        ?> ><?php echo $value; ?></option>
                            <?php } ?>
                </select>
            </div>
        </div>
        <?php
        if ($role_id == 38 || $role_id == 39) {
            $auth = App_Auth::getInstance();
            ?>
            <input type="hidden" id="combo1_add" name="combo1_add" value="<?php echo $auth->getProvinceId(); ?>">       
        <?php } else { ?>        
            <div class="col-md-4" id="div_combo1_add" <?php if (empty($translate->prov_id) || isset($translate->office_id) == 1 || empty($translate->office_id)) { ?> style="display:none;" <?php } else { ?> style="display:block;"<?php } ?>>
                <label class="control-label" id="lblcombo1_add"><?php echo $translate->translate("Province"); ?> <span class="red">*</span></label>
                <div class="controls">
                    <select name="combo1_add" id="combo1_add" class="form-control">
                    </select>
                </div>
            </div>
        <?php } ?>
        <?php
        if ($role_id == 39) {
            $auth = App_Auth::getInstance();
            ?>
            <input type="hidden" id="combo2_add" name="combo2_add" value="<?php echo $auth->getDistrictId(); ?>">       
        <?php } else { ?>   
            <div class="col-md-4" id="div_combo2_add" <?php if (empty($translate->dist_id) || isset($translate->office_id) == 1 || empty($translate->office_id)) { ?> style="display:none;" <?php } ?>>
                <label class="control-label" id="lblcombo2_add"><?php echo $translate->translate("Division/District"); ?> <span class="red">*</span></label>
                <div class="controls">
                    <select name="combo2_add" id="combo2_add" class="form-control">
                    </select>
                </div>
            </div>
        <?php } ?>
        <div class="col-md-4" id="div_combo3_add" <?php if (empty($translate->tehsil_id)) { ?> style="display:none;" <?php } ?>>
            <label class="control-label" id="lblcombo3_add"><?php echo $translate->translate("Tehsil"); ?> <span class="red">*</span></label>
            <div class="controls">
                <select name="combo3_add" id="combo3_add" class="form-control">
                </select>
            </div>
        </div>

        <div class="col-md-4" id="div_combo4_add" <?php if (empty($translate->uc_id)) { ?> style="display:none;" <?php } ?>>
            <label class="control-label" id="lblcombo4_add"><?php echo $translate->translate("UC"); ?> <span class="red">*</span></label>
            <div class="controls">
                <select name="combo4_add" id="combo4_add" class="col-md-10">
                </select>
            </div>
        </div>

        <div class="col-md-1" id="loader" style="display:none;"><img src="<?php echo $base_url; ?>/images/loader.gif" style="margin-top:8px; float:left" alt="" /></div>
        <?php
    }

}
?>