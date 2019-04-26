<?php

/**
 * Model_VvmGroups
 */

/**
 *  Model for VVm Types
 */
class Model_VvmGroups extends Model_Base {

    /**
     * $_table
     * @var type 
     */
    protected $_table;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('VvmGroups');
    }

    /**
     * Get All Vvm Types
     * 
     * @param type $order
     * @param type $sort
     * @return boolean
     */
    public function getAllVvmGroups($order = null, $sort = null) {
        $str_sql = "SELECT
vvm_groups.pk_id,
vvm_groups.vvm_group_id as vvmGroupId,
GROUP_CONCAT(DISTINCT vvm_groups.vvm_stage_id) AS vvmstages,
item_pack_sizes.item_name
FROM
vvm_groups
LEFT JOIN item_pack_sizes ON item_pack_sizes.vvm_group_id = vvm_groups.pk_id
GROUP BY
	vvm_groups.vvm_group_id";

        $sql = $this->_em_read->getConnection()->prepare($str_sql);
        $sql->execute();
        $row = $sql->fetchAll();
//        print_r($row);
//        exit;
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return FALSE;
        }

//        $str_sql = $this->_em_read->createQueryBuilder()
//                ->select("vg.pkId,vg.vvmGroupId,GROUP_CONCAT(vvm.pkId) AS vvmstages")
//                ->from('VvmGroups', 'vg')
//                ->join('vg.vvmStage', 'vvm')
//                ->groupBy('vg.vvmGroupId');
//
//        $row = $str_sql->getQuery()->getResult();
//        if (!empty($row) && count($row) > 0) {
//            return $row;
//        } else {
//            return false;
//        }
    }

    /**
     * Check Vvm Groups
     * 
     * @return type
     */
    public function checkVvmGroups() {
        $form_values = $this->form_values;
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('vg.pkId')
                ->from("VvmGroups", "vg")
                ->where("vg.vvmGroupId= '" . $form_values['vvm_group_id'] . "' ");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Add Vvm Group
     */
    public function addVvmGroup() {
        $form_values = $this->form_values;
        $vvm_stage = $this->_em->find('VvmStages', $form_values['vvm_stage_id']);

        $vvm_group = new VvmGroups();
        $vvm_group->setVvmGroupId($form_values['vvm_group_id']);
        $vvm_group->setVvmStage($vvm_stage);
        $user_id = $this->_em->find('Users', $this->_user_id);
        $vvm_group->setCreatedBy($user_id);
        $vvm_group->setModifiedBy($user_id);
        $vvm_group->setCreatedDate(App_Tools_Time::now());
        $vvm_group->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($vvm_group);
        $this->_em->flush();
    }
    
    /**
     * Delete Vvm Group
     */
    public function deleteVvmGroup() {
        $form_values = $this->form_values;
       
         $str_sql = "DELETE FROM vvm_groups WHERE vvm_groups.vvm_group_id = ".$form_values['vvm_group_id'];

        $sql = $this->_em_read->getConnection()->prepare($str_sql);
        $sql->execute();
       
       

    }

}
