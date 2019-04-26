<?php

/**
 * Model_Stakeholders
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Stakeholder Activities
 */

class Model_StakeholderActivities extends Model_Base {

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
        $this->_table = $this->_em->getRepository('StakeholderActivities');
    }

    /**
     * Get All Stakeholder Activities
     * 
     * @return boolean
     */
    public function getAllStakeholderActivities() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sa.pkId, sa.activity")
                ->from('StakeholderActivities', 'sa')
                ->where('sa.pkId <> 5');
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get All Stakeholder Activities Issues
     * 
     * @return boolean
     */
    public function getAllStakeholderActivitiesIssues() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sa.pkId, sa.activity")
                ->from('StakeholderActivities', 'sa')
                ->where('sa.pkId <> 5');
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

}
