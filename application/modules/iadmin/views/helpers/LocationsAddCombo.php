<?php
/**
 * Zend_View_Helper_LocationsAddCombo
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper Locations Add Combo
 */
class Zend_View_Helper_LocationsAddCombo extends Zend_View_Helper_Abstract {

    /**
     * Locations Add Combo
     * @param type $office_term
     * @param type $postfix
     */
    public function locationsAddCombo($office_term = "", $postfix = null, $warehouse_id = null, $role_id = null) {

        $translate = Zend_Registry::get('Zend_Translate');
        $base_url = Zend_Registry::get('baseurl');
        // Set array key and values.
        if ($role_id == '39') {
            $arr_location_level_add = array(
                '5' => $translate->translate('Tehsil'),
                '6' => $translate->translate('UC')
            );
        } else {
            $arr_location_level_add = array(
                '3' => $translate->translate('Division'),
                '4' => $translate->translate('District'),
                '5' => $translate->translate('Tehsil'),
                '6' => $translate->translate('UC')
            );
        }
        ?>
        <div class="col-md-4">
            <label class="control-label" for="location_level_add" class="col-md-7"><?php
                if (empty($office_term)) {
                    echo $translate->translate("Location Level");
                } else {
                    echo $office_term;
                }
                ?> <span class="red">*</span></label>
            <div class="controls">
                <select name="location_level_add" id="location_level_add" class="form-control">
                    <option value=""><?php echo $translate->translate("Select"); ?></option>
                    <?php
                    foreach ($arr_location_level_add as $key => $value) {
                        ?>
                        <option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <?php if ($role_id == 38 || $role_id == 39) {
            ?>
            <input type="hidden" id="combo1_add" name="combo1_add" value="">       

        <?php } else { ?>
            <div class="col-md-4" id="div_combo1_add" <?php if (empty($translate->prov_id) || isset($translate->office_id) == 1 || empty($translate->office_id)) { ?> style="display:none;" <?php } else { ?> style="display:block;"<?php } ?>>
                <label class="control-label" id="lblcombo1_add"><?php echo $translate->translate("Province"); ?> <span class="red">*</span></label>
                <div class="controls">
                    <select name="combo1_add" id="combo1_add" class="form-control">
                    </select>
                </div>
            </div>
        <?php } ?>

        <?php if ($role_id == 39) {
            ?>
            <input type="hidden" id="combo2_add" name="combo2_add" value="">

        <?php } else { ?>

            <div class="col-md-4" id="div_combo2_add" <?php if (empty($translate->dist_id) || isset($translate->office_id) == 1 || empty($translate->office_id)) { ?> style="display:none;" <?php } ?>>
                <label class="control-label" id="lblcombo2_add"><?php echo $translate->translate("District"); ?> <span class="red">*</span></label>
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



        <div class="col-md-1" id="loader_add" style="display:none;"><img src="<?php echo $base_url; ?>/images/loader.gif" style="margin-top:8px; float:left" alt="" /></div>
        <?php
    }

}
?>