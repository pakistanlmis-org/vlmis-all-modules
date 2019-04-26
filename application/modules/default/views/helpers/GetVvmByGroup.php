<?php

/**
 * Zend_View_Helper_GetVvmByGroup
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */





/**
 *  Zend View Helper Get Vvm By Group
 */
class Zend_View_Helper_GetVvmByGroup extends Zend_View_Helper_Abstract {
    protected $_em;
    protected $_em_read;
    
    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Get Vvm By Group
     * @param type $group_id
     * @param type $current_vvm
     * @param type $key
     */
    public function getVvmByGroup($group_id, $current_vvm, $key) {

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('vvm')
                ->from("VvmGroups", "vvm")
                ->where("vvm.vvmGroupId = $group_id")
                ->andWhere("vvm.vvmStage != $current_vvm");

        $row = $str_sql->getQuery()->getResult();
        if (count($row) > 0) {
            $select = "<select name='newvvm[".$key."]' class='form-control'>";
            foreach ($row as $vvm) {
                $select .= "<option value='" . $vvm->getVvmStage()->getPkId() . "'>" . ($group_id == 1 ? $vvm->getVvmStage()->getPkId() : $vvm->getVvmStage()->getVvmStageValue() ) . "</option>";
            }
            $select .= "</select>";
        } else {
            $select = "<select name='newvvm[".$key."]' class='form-control'>";
            $select .= "<option value=''></option>";
            $select .= "</select>";
        }

        echo $select;
    }

}

?>