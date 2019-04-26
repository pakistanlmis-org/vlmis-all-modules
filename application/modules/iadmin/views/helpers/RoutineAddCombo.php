<?php
/**
 * Zend_View_Helper_RoutineAddCombo
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper Routine Add Combo
 */
class Zend_View_Helper_RoutineAddCombo extends Zend_View_Helper_Abstract {

    /**
     * Routine Add Combo
     * Used to load routine combo.
     * @param type $page
     */
    public function routineAddCombo($page, $warehouse_id = null, $role_id = null) {
        // Get translater.
        $translate = Zend_Registry::get('Zend_Translate');
        // Get base URL.
        $base_url = Zend_Registry::get('baseurl');
        $em = Zend_Registry::get('doctrine');
        // Prepare query.
        if ($role_id == 38) {
            $str_sql = $em->createQueryBuilder()
                    ->select("l.pkId,l.locationName")
                    ->from('Warehouses', 'w')
                    ->Join('w.location', 'l')
                    ->where("l.parent = 10")
                    ->andWhere("w.pkId = $warehouse_id")
                    ->andWhere("l.pkId <= 8");
        }else if ($role_id == 39) {
            $str_sql = $em->createQueryBuilder()
                    ->select("l.pkId,l.locationName")
                    ->from('Warehouses', 'w')
                    ->Join('w.province', 'l')
                    ->andWhere("w.pkId = $warehouse_id");
        } else {
            $str_sql = $em->createQueryBuilder()
                    ->select("l.pkId,l.locationName")
                    ->from('Locations', 'l')
                    ->where("l.parent = 10")
                    ->andWhere("l.pkId <= 8");
        }
        // Execute and get result.
        $result = $str_sql->getQuery()->getResult();
        
        if ($role_id == 39) {
              $str_sql = $em->createQueryBuilder()
                    ->select("l.pkId,l.locationName")
                    ->from('Warehouses', 'w')
                    ->Join('w.district', 'l')
                    ->andWhere("w.pkId = $warehouse_id");
             $result_district = $str_sql->getQuery()->getResult();
        }
        ?>
        <?php if ($role_id == 38 || $role_id == 39) { ?>
            <input type="hidden" id="combo1_add" name="combo1_add" value="<?php echo $result[0]['pkId']; ?>">       
        <?php } else { ?>  
            <div class="col-md-4">
                <label class="control-label" for="province" class="col-md-7"><?php
            echo $translate->translate("Province");
            ?> <span class="red">*</span></label>
                <div class="controls">
                    <select name="combo1_add" id="combo1_add" class="form-control">
                        <option value=""><?php echo $translate->translate("Select"); ?></option>
                        <?php
                        foreach ($result as $row) {
                            ?>
                            <option value="<?php echo $row['pkId']; ?>" <?php
                if (!empty($office_term['combo1']) && $row['pkId'] == $office_term['combo1']) {
                    echo 'selected';
                }
                            ?> ><?php echo $row['locationName']; ?></option>";
                                <?php } ?>

                    </select>
                </div>
            </div>
        <?php } ?>
              <?php if ($role_id == 39) { ?>
             <input type="hidden" id="combo2_add" name="combo2_add" value="<?php echo $result_district[0]['pkId']; ?>">      
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

        <div class="col-md-4" id="div_combo4_add" <?php if (empty($translate->uc_id)) { ?> style="display:none;" <?php } ?>>
            <label class="control-label" id="lblcombo4_add"><?php echo $translate->translate("UC"); ?> <span class="red">*</span></label>
            <div class="controls">
                <select name="combo4_add" id="combo4_add" class="form-control">
                </select>
            </div>
        </div>

        <div class="col-md-1" id="loader" style="display:none;"><img src="<?php echo $base_url; ?>/images/loader.gif" style="margin-top:8px; float:left" alt="" /></div>
        <?php
    }

}
?>