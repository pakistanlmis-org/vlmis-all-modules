<?php
/**
 * Zend_View_Helper_TableHeading
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper Table Heading
 */
class Zend_View_Helper_TableHeading extends Zend_View_Helper_Abstract {

    /**
     * Table Heading
     * @param type $order
     * @param type $sort
     * @param type $fields
     */
    public function tableHeading($order, $sort, $fields = array()) {
        $translate = Zend_Registry::get('Zend_Translate');
        ?>
        <thead>
            <tr>
        <?php
        foreach ($fields as $key => $value) {
            if ($value == 'VVM Stage') {
                ?> 

                        <th  data-order="<?php echo $key; ?>" data-sort="<?php if ($order == $key && $sort == 'asc') { ?>desc<?php } else { ?>asc<?php } ?>"><?php echo $translate->translate($value) ?> </th>

            <?php } else { ?>

                        <th class="<?php if ($order == $key) { ?>sorting_<?php echo $sort; ?> <?php } else { ?>sorting<?php } ?>" data-order="<?php echo $key; ?>" data-sort="<?php if ($order == $key && $sort == 'asc') { ?>desc<?php } else { ?>asc<?php } ?>"><?php echo $translate->translate($value) ?> </th>
                    <?php }
                } ?>
                <th><?php echo $translate->translate("Action") ?></th>
            </tr>
        </thead>
        <?php
    }

}
