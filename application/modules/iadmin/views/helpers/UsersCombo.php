<?php
/**
 * Zend_View_Helper_UsersCombo
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper Users Combo
 */
class Zend_View_Helper_UsersCombo extends Zend_View_Helper_Abstract {

    /**
     * Users Combo
     * Used to load users combo.
     * @param type $office_term
     * @param type $page
     */
    public function usersCombo($office_term, $page, $warehouse_id, $role_id) {

        // Get translater instance.
        $translate = Zend_Registry::get('Zend_Translate');
        // Get base URL.
        $base_url = Zend_Registry::get('baseurl');
        // Check purpose.
        if ($page == "routine") {
            // Init locations levels array.
            $arr_location_level = array(
                '1' => $translate->translate('National'),
                '2' => $translate->translate('Provincial'),
                '3' => $translate->translate('Division'),
                '4' => $translate->translate('District'),
                '5' => $translate->translate('Tehsil'),
                '6' => $translate->translate('EPI Center')
            );
        }
        // Check purpose.
        if ($page == "im") {
            // Init locations levels array.
            $arr_location_level = array(
                '1' => $translate->translate('National'),
                '2' => $translate->translate('Provincial'),
                '3' => $translate->translate('Division'),
                '4' => $translate->translate('District'),
                '5' => $translate->translate('Tehsil')
            );
        }
        // Check purpose.
        if ($page == "pro-admin" && $role_id == 38) {
            // Init locations levels array.
            $arr_location_level = array(
                '3' => $translate->translate('Division'),
                '4' => $translate->translate('District'),
            );
        }

        if ($page == "pro-admin" && $role_id == 39) {
            // Init locations levels array.
            $arr_location_level = array(
                '5' => $translate->translate('Tehsil')
            );
        }

        // Check Purpose.
        if ($page == "campaigns") {
            // Init locations levels array.
            $arr_location_level = array(
                '1' => $translate->translate('National'),
                '2' => $translate->translate('Provincial'),
                '4' => $translate->translate('District')
            );
        }
        ?>
        <div class="col-md-3">
            <label class="control-label" for="office_type" class="col-md-7"><?php
                echo $translate->translate("Office Type");
                ?> </label>
            <div class="controls">
                <select name="office_type" id="office_type" class="form-control">
                    
                    <?php
                    foreach ($arr_location_level as $key => $value) {
                        ?>
                        <option value="<?php echo $key; ?>" <?php
                        if (!empty($office_term['office_type']) && $key == $office_term['office_type']) {
                            echo 'selected';
                        }
                        ?> ><?php echo $value; ?></option>
                            <?php } ?>
                </select>
            </div>
        </div>

        <?php if ($role_id == 38 || $role_id == 39) { ?>
            <input type="hidden" id="combo1" name="combo1" value="">       
        <?php } else { ?>        
            <div class="col-md-3" id="div_combo1" <?php if (empty($translate->prov_id) || isset($translate->office_id) == 1 || empty($translate->office_id)) { ?> style="display:none;" <?php } else { ?> style="display:block;"<?php } ?>>
                <label class="control-label" id="lblcombo1"><?php echo $translate->translate("Province"); ?> <span class="red">*</span></label>
                <div class="controls">
                    <select name="combo1" id="combo1" class="form-control">
                    </select>
                </div>
            </div>
        <?php } ?>

        <?php if ($role_id == 39) { ?>
            <input type="hidden" id="combo2" name="combo2" value="">       
        <?php } else { ?>  
        <div class="col-md-3" id="div_combo2" <?php if (empty($translate->dist_id) || isset($translate->office_id) == 1 || empty($translate->office_id)) { ?> style="display:none;" <?php } ?>>
            <label class="control-label" id="lblcombo2"><?php echo $translate->translate("District"); ?> <span class="red">*</span></label>
            <div class="controls">
                <select name="combo2" id="combo2" class="form-control">
                </select>
            </div>
        </div>
        <?php }?>
        <div class="col-md-3" id="div_combo3" <?php if (empty($translate->tehsil_id)) { ?> style="display:none;" <?php } ?>>
            <label class="control-label" id="lblcombo3"><?php echo $translate->translate("Tehsil"); ?> <span class="red">*</span></label>
            <div class="controls">
                <select name="combo3" id="combo3" class="form-control">
                </select>
            </div>
        </div>

        <div class="col-md-3" id="div_combo4" <?php if (empty($translate->uc_id)) { ?> style="display:none;" <?php } ?>>
            <label class="control-label" id="lblcombo4"><?php echo $translate->translate("UC"); ?> <span class="red">*</span></label>
            <div class="controls">
                <select name="combo4" id="combo4" class="col-md-10">
                </select>
            </div>
        </div>

        <div class="col-md-1" id="loader" style="display:none;"><img src="<?php echo $base_url; ?>/images/loader.gif" style="margin-top:8px; float:left" alt="" /></div>
        <?php
    }

}
?>